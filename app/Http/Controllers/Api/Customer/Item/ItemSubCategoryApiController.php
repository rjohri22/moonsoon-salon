<?php

namespace App\Http\Controllers\Api\Customer\Item;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\Item\CreateItemSubCategoryApiRequest;
use App\Http\Requests\Api\Customer\Item\UpdateItemSubCategoryApiRequest;
use App\Http\Resources\Api\Customer\Item\ItemSubCategoryApiCollection;
use App\Repositories\ItemSubCategoryApiRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;

class ItemSubCategoryApiController extends AppBaseController
{
    protected $itemSubCategoryApiRepository;

    public function __construct(ItemSubCategoryApiRepository $itemSubCategory)
    {
        $this->itemSubCategoryApiRepository = $itemSubCategory;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->itemSubCategoryApiRepository->pushCriteria(new RequestCriteria($request));
        $items = $this-> itemSubCategoryApiRepository->where('status', 'active')->get();

        return $this->sendResponse(['item' => ItemSubCategoryApiCollection::collection($items)], '');
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
        $itemSubCategory = $this->itemSubCategoryApiRepository->createItemSubCategory($request);
        return $this->sendResponse(new ItemSubCategoryApiCollection($itemSubCategory), 'Item Sub Category created successfully');
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
        return $this->sendResponse(new ItemSubCategoryApiCollection($itemSubCategory), 'Item Sub Category retrived successfully');
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

        return $this->sendResponse(new ItemSubCategoryApiCollection($itemSubCategory), 'Item Category updated successfully');
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
        return $this->sendResponse([], 'Item Sub Category deleted successfully');
    }
}
