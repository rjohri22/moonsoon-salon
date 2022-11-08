<?php

namespace App\Http\Controllers\Api\Customer\Offer;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\Offer\CreateOfferApiRequest;
use App\Http\Requests\Api\Customer\Offer\UpdateOfferApiRequest;
use App\Http\Resources\Api\Customer\Offer\OfferApiCollection;
use App\Models\Offer;
use App\Repositories\OfferApiRepository;
use App\Traits\ActivityLogTrait;
use App\Traits\UploaderTrait;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;

class OfferApiController extends AppBaseController
{
    use UploaderTrait, ActivityLogTrait;
    protected $offerApiRepository;
    public function __construct(OfferApiRepository $offer)
    {
        $this->offerApiRepository = $offer;
    }


    /**
     *   @OA\get(
     *     path="/api/coupans/",
     *      tags={"View all coupans"}, 
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       )
     *      )
     */

    /**
     *   @OA\get(
     *     path="/api/offers/",
     *      tags={"View all offers"}, 
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       )
     *    )
     */
    public function index(Request $request)
    {
        $this->offerApiRepository->pushCriteria(new RequestCriteria($request));
        $offers = $this->offerApiRepository->where('status','active')->get();

        return $this->sendResponse(OfferApiCollection::collection($offers), 'fetched successfully');
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

   
   
    public function store(CreateOfferApiRequest $request)
    {
        //return $request->all();
        if (isset($request->id)) {
            $offer = $this->offerApiRepository->findWithoutFail($request->id);
            if (empty($offer)) {
                return $this->sendError($this->getLangMessages('Sorry! Offer not found', 'Offer'));
            }
            $offer = $this->offerApiRepository->updateOffer($request->id, $request);
            /* if (isset($offer->media)) {
            $storageName  = $offer->media->file_name;
            $this->deleteFile('item/' . $storageName);
            // remove from the database
            $offer->media->delete();
        } */
            // $this->saveActivity($request, "offer", "update", $offer);
        }
        $offer =  $this->offerApiRepository->createOffer($request);
        // return $offer;
        /* $this->saveActivity($request, "offer", "list", $offer); */
        return $this->sendResponse(new OfferApiCollection($offer), 'Offer created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $offer = $this->offerApiRepository->findWithoutFail($id);
        if (empty($offer)) {
            return $this->sendError($this->getLangMessages('Sorry! Offer not found', 'Offer'));
        }
        $this->saveActivity($request, "offer", "add", $offer);

        return $this->sendResponse(new OfferApiCollection($offer), 'Offer retrived successfully');
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
    public function update(UpdateOfferApiRequest $request, $id)
    {
        $offer = $this->offerApiRepository->findWithoutFail($id);
        if (empty($offer)) {
            return $this->sendError($this->getLangMessages('Sorry! Offer not found', 'Offer'));
        }
        $offer = $this->offerApiRepository->updateOffer($id, $request);
        /* if (isset($offer->media)) {
            $storageName  = $offer->media->file_name;
            $this->deleteFile('item/' . $storageName);
            // remove from the database
            $offer->media->delete();
        } */
        $this->saveActivity($request, "offer", "update", $offer);
        // $this->offerApiRepository->uploadFile($request, $offer);
        return $this->sendResponse(new OfferApiCollection($offer), 'Offer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *   @OA\Get(
     *     path="/api/validate-coupan",
     *      tags={"Salon App: Validate Coupan"},
     *          @OA\Parameter(
     *           name="coupan_code",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *           )
     *       ),
     *  )
     */
    public function validateCoupan(Request $request)
    {
        $coupanCode = $request->coupan_code;
        $offer = Offer::where('code',$coupanCode)->where('status','active')->first();
        if(!empty($offer))
        {
            return $this->sendResponse(new OfferApiCollection($offer), 'Offer updated successfully');
        }
        if (empty($offer)) {
            return $this->sendError($this->getLangMessages('Sorry! Offer not found', 'Offer'));
        }

    }

    public function getSliders()
    {
        $slider = $this->offerApiRepository->where('is_slider', '0')->get();
        return $this->sendResponse(OfferApiCollection::collection($slider), 'slider retrived');
    }
}
