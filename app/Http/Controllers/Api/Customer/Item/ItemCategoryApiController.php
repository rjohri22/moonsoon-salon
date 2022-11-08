<?php

namespace App\Http\Controllers\Api\Customer\Item;

use Illuminate\Http\Request;
use App\Repositories\ItemCategoryApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Item\ItemCategoryApiCollection;
use App\Http\Resources\Api\Customer\CategoryGroup\ItemCategoryGroupApiCollection;
use App\Http\Requests\Api\Customer\Item\UpdateItemCategoryApiRequest;
use App\Http\Requests\Api\Customer\Item\CreateItemCategoryApiRequest;

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
        $items = $this->itemCategoryApiRepository->where('status', 'active')->get();

        return $this->sendResponse(ItemCategoryApiCollection::collection($items), '');
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
        $itemCategory =  $this->itemCategoryApiRepository->createItemCategory($request);

        return $this->sendResponse(new ItemCategoryApiCollection($itemCategory), 'Item Category created successfully');
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
        return $this->sendResponse(new ItemCategoryApiCollection($itemCategory), 'Item Category retrived successfully');
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

        return $this->sendResponse(new ItemCategoryApiCollection($itemCategory), 'Item Category updated successfully');
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
        return $this->sendResponse([], 'Item Category deleted successfully');
    }

    /**
     *   @OA\Get(
     *     path="/api/items-categories-group",
     *      tags={"Salon App: items-categories-group"},
     * 
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     *  )
     */
    public function itemsCategoriesGroup(Request $request)
    {
        // return $itemCategory;
        $this->itemCategoryApiRepository->pushCriteria(new RequestCriteria($request));
        $items = $this->itemCategoryApiRepository->where('status', 'active')->get();

        return $this->sendResponse(ItemCategoryGroupApiCollection::collection($items), 'Data retrived successfully');
    }
}
