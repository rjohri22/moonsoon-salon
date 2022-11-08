<?php

namespace App\Http\Controllers\Admin\Item;

use Illuminate\Http\Request;
use App\Repositories\ItemCategoryApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Admin\Item\ItemCategoryApiCollection;
use App\Http\Requests\Admin\Item\UpdateItemCategoryApiRequest;
use App\Http\Requests\Admin\Item\CreateItemCategoryApiRequest;

class ItemCategoryApiController extends AppBaseController
{
    protected $itemCategoryApiRepository;

    public function __construct(ItemCategoryApiRepository $itemCategory)
    {
        $this->itemCategoryApiRepository = $itemCategory;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->itemCategoryApiRepository->pushCriteria(new RequestCriteria($request));
        $items = $this->itemCategoryApiRepository->paginate($request->limit);
        $datas = ItemCategoryApiCollection::collection($items);
        return view('admin.item.category')->with('datas', $datas);
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
    public function store(CreateItemCategoryApiRequest $request)
    {
        if($request->id)
        {
            $this->itemCategoryApiRepository->updateItemCategory($request->id, $request);
            $message = "Category Updated Successfully..";
        }
        else
        {
            $this->itemCategoryApiRepository->createItemCategory($request);
            $message = "Category Added Successfully..";
        }

        // $this->sendResponse(new ItemCategoryApiCollection($itemCategory), 'Item Category created successfully');
        return redirect('admin/categories')->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $itemCategory = $this->itemCategoryApiRepository->findWithoutFail($id);
        if (empty($itemCategory)) {
            return $this->sendError($this->getLangMessages('Sorry! Item Category not found', 'ItemCategory'));
        }
        return $this->sendResponse($itemCategory, 'Item Category retrived successfully');
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
    public function update($id, UpdateItemCategoryApiRequest $request)
    {
        // return $id;
        $itemCategory = $this->itemCategoryApiRepository->findWithoutFail($id);
        if (empty($itemCategory)) {
            return $this->sendError($this->getLangMessages('Sorry! Item Category not found', 'ItemCategory'));
        }
        $itemCategory = $this->itemCategoryApiRepository->updateItemCategory($id, $request);

        // $this->sendResponse(new ItemCategoryApiCollection($itemCategory), 'Item Category updated successfully');
        return redirect('admin/item-categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $itemCategory = $this->itemCategoryApiRepository->findWithoutFail($id);
        if (empty($itemCategory)) {
            return $this->sendError($this->getLangMessages('Sorry! Item Category not found', 'ItemCategory'));
        }

        $itemCategory->delete();
        Session::put('delete','Item Category deleted successfully');

        return $this->sendResponse([], 'Item Category deleted successfully');
        // ItemCategoryApiCollection::collection($itemCategory);
        return redirect('admin/item-categories')->with('delete', 'Item Category Deleted Successfully..');
    }
}
