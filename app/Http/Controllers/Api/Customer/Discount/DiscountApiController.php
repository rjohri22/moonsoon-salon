<?php

namespace App\Http\Controllers\Api\Customer\Discount;

use Illuminate\Http\Request;
use App\Repositories\DiscountTypeApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Discount\DiscountApiCollection;
use App\Http\Requests\Api\Customer\Discount\UpdateDiscountApiRequest;
use App\Http\Requests\Api\Customer\Discount\CreateDiscountApiRequest;

class DiscountApiController extends AppBaseController
{
    protected $discountApiRepository;

    public function __construct(DiscountTypeApiRepository $discount)
    {
        $this->discountApiRepository = $discount;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->discountApiRepository->pushCriteria(new RequestCriteria($request));
        $items = $this->discountApiRepository->paginate($request->limit);

        return $this->sendResponse(['item' => DiscountApiCollection::collection($items), 'total' => $items->total()], '');
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
    public function store(CreateDiscountApiRequest $request)
    {
        $discount =  $this->discountApiRepository->createDiscount($request);

        return $this->sendResponse(new DiscountApiCollection($discount), 'Discount created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $discount = $this->discountApiRepository->findWithoutFail($id);
        if (empty($discount)) {
            return $this->sendError($this->getLangMessages('Sorry! Discount not found', 'Discount'));
        }
        return $this->sendResponse(new DiscountApiCollection($discount), 'Discount retrived successfully');
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
    public function update($id, UpdateDiscountApiRequest $request)
    {
        // return $id;
        $discount = $this->discountApiRepository->findWithoutFail($id);
        if (empty($discount)) {
            return $this->sendError($this->getLangMessages('Sorry! Discount not found', 'Discount'));
        }
        $discount = $this->discountApiRepository->updateDiscount($id, $request);

        return $this->sendResponse(new DiscountApiCollection($discount), 'Discount updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $discount = $this->discountApiRepository->findWithoutFail($id);
        if (empty($discount)) {
            return $this->sendError($this->getLangMessages('Sorry! Discount not found', 'Discount'));
        }

        $discount->delete();
        return $this->sendResponse([], 'Discount deleted successfully');
    }
}
