<?php

namespace App\Http\Controllers\Admin\Item;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Item\CreateItemSubCategoryApiRequest;
use App\Http\Requests\Admin\Item\UpdateItemSubCategoryApiRequest;
use App\Http\Resources\Admin\Item\ItemSubCategoryApiCollection;
use App\Repositories\ItemSubCategoryApiRepository;
use App\Repositories\ItemCategoryApiRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Session;
class ItemSubCategoryApiController extends AppBaseController
{
    protected $itemSubCategoryApiRepository;
    protected $itemCategoryApiRepository;

    public function __construct(ItemSubCategoryApiRepository $itemSubCategory, ItemCategoryApiRepository $itemCategory)
    {
        $this->itemSubCategoryApiRepository = $itemSubCategory;
        $this->itemCategoryApiRepository = $itemCategory;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = $this->itemSubCategoryApiRepository->paginate($request->limit);
        $datas = ItemSubCategoryApiCollection::collection($items);
        $categories = $this->itemCategoryApiRepository->whereStatus('active')->select('id', 'name')->get();
        return view('admin.item.sub-category', compact('datas', 'categories'));
        // return view('admin.item.sub-category')->with('datas', $datas);
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
    public function store(CreateItemSubCategoryApiRequest $request)
    {
        if ($request->id) {
            $this->itemSubCategoryApiRepository->updateItemSubCategory($request->id, $request);
            $message = "Sub-Category Updated Successfully..";
        } else {
            $this->itemSubCategoryApiRepository->createItemSubCategory($request);
            $message = "Sub-Category Added Successfully..";
        }       
        // $this->sendResponse(new ItemSubCategoryApiCollection($itemSubCategory), 'Item Sub Category created successfully');
        return redirect('admin/sub-categories')->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $itemSubCategory = $this->itemSubCategoryApiRepository->findWithoutFail($id);
        if (empty($itemSubCategory)) {
            return $this->sendError($this->getLangMessages('Sorry! Item Category not found', 'ItemSubCategory'));
        }
        return $this->sendResponse($itemSubCategory, 'Item Sub Category retrived successfully');
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
    public function update(UpdateItemSubCategoryApiRequest $request, $id)
    {
        $itemSubCategory = $this->itemSubCategoryApiRepository->findWithoutFail($id);
        if (empty($itemSubCategory)) {
            return $this->sendError($this->getLangMessages('Sorry! Item Category not found', 'ItemSubCategory'));
        }
        $itemSubCategory = $this->itemSubCategoryApiRepository->updateItemSubCategory($id, $request);

        $this->sendResponse(new ItemSubCategoryApiCollection($itemSubCategory), 'Item Category updated successfully');
        return redirect('admin/sub-categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $itemSubCategory = $this->itemSubCategoryApiRepository->findWithoutFail($id);
        if (empty($itemSubCategory)) {
            return $this->sendError($this->getLangMessages('Sorry! Item Sub Category not found', 'ItemSubCategory'));
        }

        $itemSubCategory->delete();
        Session::put('delete','Item Sub Category deleted successfully');
       return  $this->sendResponse([], 'Item Sub Category deleted successfully');
        return redirect('admin/sub-categories')->with('delete', 'Sub Category Deleted Successfully..');
    }
}
