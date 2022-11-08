<?php

namespace App\Http\Controllers\Api\Customer\Item;

use Illuminate\Http\Request;
use App\Repositories\ItemApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Item\ItemApiCollection;
use App\Http\Requests\Api\Customer\Item\UpdateItemApiRequest;
use App\Http\Requests\Api\Customer\Item\CreateItemApiRequest;
use App\Models\Brand;
use App\Traits\ActivityLogTrait;
use App\Traits\UploaderTrait;
use App\Http\Criteria\Customer\Item\ItemCriteria;

class ItemApiController extends AppBaseController
{
    protected $itemApiRepository;

    use UploaderTrait, ActivityLogTrait;
    public function __construct(ItemApiRepository $item)
    {
        $this->itemApiRepository = $item;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     *   @OA\Get(
     *     path="/api/items",
     *      tags={"Salon App: Item Listing"},
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     *  )
     */

    /**
     *   @OA\Get(
     *     path="/api/offer-items",
     *      tags={"Salon App: List Offer Items"},
    *           @OA\Parameter(
    *               name="is_offer_item",
    *               in="query",
    *               required=true,
    *               @OA\Schema(
    *                   type="boolean"
    *               )
     *          ),
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     *  )
     */




    public function index(Request $request)
    {
        $this->itemApiRepository->pushCriteria(new RequestCriteria($request));
        $this->itemApiRepository->pushCriteria(new ItemCriteria($request));

        $items = $this->itemApiRepository->where('status', 'active')->get();

        return $this->sendResponse(ItemApiCollection::collection($items), '');
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

    /**
     *   @OA\Post(
     *     path="/api/items",
     *      tags={"Item Store"},
     *      @OA\Parameter(
     *           name="brand_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="Numeric"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="item_category_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="Numeric"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="item_sub_category_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="numeric"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="product_category_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="Numeric"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="description",
     *           in="query",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="how_to_use",
     *           in="query",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="benefits",
     *           in="query",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="qty",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="numeric"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="unit_id",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="numeric"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="price",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="numeric"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="discount_type",
     *           in="query",
     *          description="amount,percentage",
     *           required=true,
     *           @OA\Schema(
     *               type="numeric"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="discount_amount",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="numeric"
     *           )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     *
     * )
     */
    public function store(CreateItemApiRequest $request)
    {
        // return $request->all();
        $item =  $this->itemApiRepository->createItem($request);
        // return $item;
        $this->itemApiRepository->uploadFile($request, $item);
        $this->saveActivity($request, "item", "list", $item);
        return $this->sendResponse(new ItemApiCollection($item), 'Item created successfully');
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
        $this->saveActivity($request, "item", "show", $item);
        $related_items = $this->itemApiRepository->where('product_category_id', $item->product_category_id)->where('id','!=',$item->id)->get();
        return $this->sendResponse(['item_detail' => new ItemApiCollection($item), 'related_items' => ItemApiCollection::collection($related_items)], 'Item retrived successfully');
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
        // return $id;
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
        $this->itemApiRepository->uploadFile($request, $item);
        return $this->sendResponse(new ItemApiCollection($item), 'Item updated successfully');
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
        $this->saveActivity($request, "item", "delete", $item);
        return $this->sendResponse([], 'Item deleted successfully');
    }

    // public function searchBy($section = null, $id = null)
    // {
    //     $section = $section === null ? "" : $section;
    //     $id = $id === null ? "" : $id;
    //     $data = null;
    //     /*if($section == 'brand'){
    //         $data=$this->itemApiRepository->findWithoutFail()
    //     }*/
    //     return $section . $id;
    // }
}
