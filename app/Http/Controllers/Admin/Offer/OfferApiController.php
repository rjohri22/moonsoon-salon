<?php

namespace App\Http\Controllers\Admin\Offer;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Offer\CreateOfferApiRequest;
use App\Http\Requests\Admin\Offer\UpdateOfferApiRequest;
use App\Http\Resources\Admin\Offer\OfferApiCollection;
use App\Repositories\BrandApiRepository;
use App\Repositories\DiscountTypeApiRepository;
use App\Repositories\ItemApiRepository;
use App\Repositories\ItemCategoryApiRepository;
use App\Repositories\ItemSubCategoryApiRepository;
use App\Repositories\OfferApiRepository;
use App\Repositories\ProductCategoryApiRepository;
use App\Traits\ActivityLogTrait;
use App\Traits\UploaderTrait;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;

class OfferApiController extends AppBaseController
{
    use UploaderTrait, ActivityLogTrait;
    protected $offerApiRepository;
    protected $discountTypeApiRepository;
    protected $brandApiRepository;
    protected $itemCategoryApiRepository;
    protected $itemSubCategoryApiRepository;
    protected $productCategoryApiRepository;
    protected $itemApiRepository;
    public function __construct(OfferApiRepository $offer, DiscountTypeApiRepository $discountType, BrandApiRepository $brand, ItemCategoryApiRepository $category, ItemSubCategoryApiRepository $subCategory, ProductCategoryApiRepository $productCategory, ItemApiRepository $item)
    {
        $this->offerApiRepository = $offer;
        $this->discountTypeApiRepository = $discountType;
        $this->brandApiRepository = $brand;
        $this->itemCategoryApiRepository = $category;
        $this->itemSubCategoryApiRepository = $subCategory;
        $this->productCategoryApiRepository = $productCategory;
        $this->itemApiRepository = $item;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->offerApiRepository->pushCriteria(new RequestCriteria($request));
        $offers = $this->offerApiRepository->paginate($request->limit);
        $datas = OfferApiCollection::collection($offers);
        //return $datas[0]->media->getUrl();
        $discountTypes = $this->discountTypeApiRepository->whereStatus('active')->select('id', 'discount_type as name')->get();
        return view('admin.offer.offer', compact('datas', 'discountTypes'))/* ->with('datas', $datas) */;
        return $this->sendResponse(['offer' => OfferApiCollection::collection($offers), 'total' => $offers->total()], 'fetched successfully');
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
    public function store(CreateOfferApiRequest $request)
    {
        /* return $request->table_type = match ($request->table_type) {
            'brand' => get_class($this->brandApiRepository),
            'category' => get_class($this->itemCategoryApiRepository),
            'sub-category' => get_class($this->itemSubCategoryApiRepository),
            'product-category' => get_class($this->productCategoryApiRepository),
            'item' => get_class($this->itemApiRepository),
            default => '',
        }; */
        if ($request->id) {
            $this->offerApiRepository->updateOffer($request->id, $request);
            $message = "Offer Updated Successfully..";
        } else {
            $this->offerApiRepository->createOffer($request);
            $message = "Offer Added Successfully..";
        }
        return redirect('admin/offers')->with('success', $message);
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
        $this->saveActivity($request, "offer", "show", $offer);

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
        $offer = $this->offerApiRepository->findWithoutFail($id);
        if (empty($offer)) {
            return $this->sendError($this->getLangMessages('Sorry! Item Sub Category not found', 'Offer'));
        }

        $offer->delete();
        $this->sendResponse([], 'Offer deleted successfully');
        return OfferApiCollection::collection($offer);
    }
    public function addToSlider($id = null)
    {
        if ($id == null) {
            return 'No ID';
        } else {
            $offer = $this->offerApiRepository->findWithoutFail($id);
            $offer->update(['is_slider' => !$offer->is_slider]);
            return redirect('admin/offers');
        }
    }
    public function offerPrefrence($id = null)
    {
        $datas =
            match ($id) {
                'brands' => $this->brandApiRepository->get(),
                'item_categories' => $this->itemCategoryApiRepository->get(),
                'item_sub_categories' => $this->itemSubCategoryApiRepository->get(),
                'product_categories' => $this->productCategoryApiRepository->get(),
                'items' => $this->itemApiRepository->get(),
                default => 'Invalid Input',
            };
        return response($datas);
    }
}
