<?php

namespace App\Http\Controllers\Admin\Item;

use Illuminate\Http\Request;
use App\Repositories\ItemApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Admin\Item\ItemApiCollection;
use App\Http\Requests\Admin\Item\UpdateItemApiRequest;
use App\Http\Requests\Admin\Item\CreateItemApiRequest;
use App\Models\ItemCategory;
use App\Models\ItemSubCategory;
use App\Traits\ActivityLogTrait;
use App\Traits\UploaderTrait;
use App\Repositories\BrandApiRepository;
use App\Repositories\DiscountTypeApiRepository;
use App\Repositories\ItemCategoryApiRepository;
use App\Repositories\ItemSubCategoryApiRepository;
use App\Repositories\ProductCategoryApiRepository;
use App\Repositories\UnitApiRepository;
use Session;
class ItemApiController extends AppBaseController
{
    protected $itemApiRepository;
    protected $brandApiRepository;
    protected $itemCategoryApiRepository;
    protected $itemSubCategoryApiRepository;
    protected $productCategoryApiRepository;
    protected $unitApiRepository;
    protected $discountTypeApiRepository;

    use UploaderTrait, ActivityLogTrait;
    public function __construct(ItemApiRepository $item, BrandApiRepository $brands, ItemCategoryApiRepository $categories, ItemSubCategoryApiRepository $subCategories, ProductCategoryApiRepository $productCategories, UnitApiRepository $units, DiscountTypeApiRepository $discountTypes)
    {
        $this->itemApiRepository = $item;
        $this->brandApiRepository = $brands;
        $this->itemCategoryApiRepository = $categories;
        $this->itemSubCategoryApiRepository = $subCategories;
        $this->productCategoryApiRepository = $productCategories;
        $this->unitApiRepository = $units;
        $this->discountTypeApiRepository = $discountTypes;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    




    public function index(Request $request)
    {
        $this->itemApiRepository->pushCriteria(new RequestCriteria($request));
        $items = $this->itemApiRepository->paginate($request->limit);

        $datas = ItemApiCollection::collection($items);
        // return $datas;
        $brands = $this->brandApiRepository->whereStatus('active')->select('id', 'name')->get();
        $categories = $this->itemCategoryApiRepository->whereStatus('active')->select('id', 'name')->get();
        $subSategories = $this->itemSubCategoryApiRepository->whereStatus('active')->select('id', 'name', 'category_type')->get();
        $productCategories = $this->productCategoryApiRepository->whereStatus('active')->select('id', 'name')->get();
        $units = $this->unitApiRepository->whereStatus('active')->select('id', 'name')->get();
        $discountTypes = $this->discountTypeApiRepository->whereStatus('active')->select('id', 'discount_type as name')->get();
        return view('admin.item.item', compact('datas', 'brands', 'categories', 'subSategories', 'productCategories', 'units', 'discountTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    
    public function store(CreateItemApiRequest $request)
    {

        if ($request->id) {
            $item = $this->itemApiRepository->updateItem($request->id, $request);
            if (isset($item->medias)) {
                foreach($item->medias as $key => $media)
                {
                    $storageName  = $media->file_name;
                    $this->deleteFile('item/' . $storageName);
                    // remove from the database
                    $media->delete();
                }
            }
        //    return $item->medias;
             $this->itemApiRepository->uploadFile($request, $item);
            $this->saveActivity($request, "item", "list", $item);
            $message = "Item Updated Successfully..";
        } else {
            $item = $this->itemApiRepository->createItem($request);
            $this->itemApiRepository->uploadFile($request, $item);
            $this->saveActivity($request, "item", "list", $item);
            $message = "Item Added Successfully..";
        }

        // return $request->all();
        // return $item;
        // $this->itemApiRepository->uploadFile($request, $item);
        // $this->saveActivity($request, "item", "list", $item);
    //    return $this->sendResponse(new ItemApiCollection($item), 'Item created successfully');
        return redirect('admin/items')->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $item = $this->itemApiRepository->findWithoutFail($id);
        if (empty($item)) {
            return $this->sendError($this->getLangMessages('Sorry! Item not found', 'Item'));
        }
        $this->saveActivity($request, "item", "add", $item);

        return $this->sendResponse(new ItemApiCollection($item), 'Item retrived successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateItemApiRequest $request)
    {
        // return $request->all();
        $item = $this->itemApiRepository->findWithoutFail($id);
        if (empty($item)) {
            return $this->sendError($this->getLangMessages('Sorry! Item not found', 'Item'));
        }
        $item = $this->itemApiRepository->updateItem($id, $request);
        if (isset($item->media)) {
            $storageName  = $item->media->file_name;
            $this->deleteFile('item/' . $storageName);
            // remove from the database
            $item->media->delete();
        }
        $this->saveActivity($request, "item", "update", $item);
        return $this->itemApiRepository->uploadFile($request, $item);

        $this->sendResponse(new ItemApiCollection($item), 'Item updated successfully');
        return redirect('admin/items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $item = $this->itemApiRepository->findWithoutFail($id);
        if (empty($item)) {
            return $this->sendError($this->getLangMessages('Sorry! Item not found', 'Item'));
        }
        $item->delete();
        Session::put('delete','Item deleted successfully');

        $this->saveActivity($request, "item", "delete", $item);
        return $this->sendResponse([], 'Item deleted successfully');
    }

    public function getSubCategoryDD($id = null,Request $request)
    {
        $data = [];
        if ($id != null) {
            // $data = ItemCategory::find($id)->getSubCategory;
            $data = $this->itemSubCategoryApiRepository->where('item_category_id',$id)->whereStatus('active')->select('id', 'name', 'category_type');
            if(!empty($request->category_type))
            {
                $data = $data->where('category_type',$request->category_type);
            }
            $data = $data->get();
        } else {
            $data = $this->itemSubCategoryApiRepository->whereStatus('active')->select('id', 'name', 'category_type');
            if(!empty($request->category_type))
            {
                $data = $data->where('category_type',$request->category_type);
            }
            $data = $data->get();
            // $data = $this->itemSubCategoryApiRepository->whereStatus('active')->select('id', 'name', 'category_type')->get();
        }
        return $data;
    }

    public function getProductCategoryDD($id = null)
    {
        $data = null;
        if ($id != null) {
            $data = ItemSubCategory::find($id)->getProductCategory;
        } else {
            $data = $this->productCategoryApiRepository->whereStatus('active')->select('id', 'name')->get();
        }
        return $data;
    }
}
