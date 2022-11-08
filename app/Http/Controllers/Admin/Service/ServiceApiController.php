<?php

namespace App\Http\Controllers\Admin\Service;

use Illuminate\Http\Request;
use App\Repositories\ServiceApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Admin\Service\ServiceApiCollection;
use App\Http\Requests\Admin\Service\UpdateServiceApiRequest;
use App\Http\Requests\Admin\Service\CreateServiceApiRequest;
use App\Models\Brand;
use App\Traits\ActivityLogTrait;
use App\Traits\UploaderTrait;
use App\Http\Criteria\Customer\Service\ServiceCriteria;

use App\Repositories\BrandApiRepository;
use App\Repositories\DiscountTypeApiRepository;
use App\Repositories\ItemCategoryApiRepository;
use App\Repositories\ItemSubCategoryApiRepository;
use App\Repositories\ProductCategoryApiRepository;
use App\Repositories\UnitApiRepository;
use App\Repositories\BranchApiRepository;

class ServiceApiController extends AppBaseController
{

    protected $serviceApiRepository;
    protected $brandApiRepository;
    protected $itemCategoryApiRepository;
    protected $itemSubCategoryApiRepository;
    protected $productCategoryApiRepository;
    protected $unitApiRepository;
    protected $branchApiRepository;
    protected $discountTypeApiRepository;

    use UploaderTrait, ActivityLogTrait;
    public function __construct(ServiceApiRepository $service, BrandApiRepository $brands, ItemCategoryApiRepository $categories, ItemSubCategoryApiRepository $subCategories, ProductCategoryApiRepository $productCategories, UnitApiRepository $units, DiscountTypeApiRepository $discountTypes, BranchApiRepository $branchApi)
    {
        $this->serviceApiRepository = $service;
        $this->brandApiRepository = $brands;
        $this->itemCategoryApiRepository = $categories;
        $this->itemSubCategoryApiRepository = $subCategories;
        $this->productCategoryApiRepository = $productCategories;
        $this->unitApiRepository = $units;
        $this->branchApiRepository = $branchApi;
        $this->discountTypeApiRepository = $discountTypes;
    }



    public function index(Request $request)
    {
        $this->serviceApiRepository->pushCriteria(new RequestCriteria($request));
        // $this->serviceApiRepository->pushCriteria(new ServiceCriteria($request));

        // $services = $this->serviceApiRepository->where('status', 'active')->get();
        $items = $this->serviceApiRepository->paginate($request->limit);

        $datas = ServiceApiCollection::collection($items);
        // return $datas;
        $brands = $this->brandApiRepository->whereStatus('active')->select('id', 'name')->get();
        $branches = $this->branchApiRepository->select('id', 'name')->get();
        $categories = $this->itemCategoryApiRepository->whereStatus('active')->select('id', 'name')->get();
        $subSategories = $this->itemSubCategoryApiRepository->whereStatus('active')->where('category_type','Service')->select('id', 'name', 'category_type')->get();
        $productCategories = $this->productCategoryApiRepository->whereStatus('active')->select('id', 'name')->get();
        $units = $this->unitApiRepository->whereStatus('active')->select('id', 'name')->get();
        $discountTypes = $this->discountTypeApiRepository->whereStatus('active')->select('id', 'discount_type as name')->get();
        return view('admin.service.service', compact('datas', 'brands', 'categories', 'subSategories', 'productCategories', 'units', 'discountTypes','branches'));
 
        // return $this->sendResponse(ServiceApiCollection::collection($services), '');
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
     *     path="/api/services",
     *      tags={"Service Store"},
     *     
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
     *     
     *      @OA\Parameter(
     *           name="name",
     *           in="query",
     *           required=true,
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="service_time",
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
     *      
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
    public function store(CreateServiceApiRequest $request)
    {
        // return $request;
        if ($request->id) {
            $item = $this->serviceApiRepository->updateService($request->id, $request);
            if (isset($item->medias)) {
                foreach($item->medias as $key => $media)
                {
                    $storageName  = $media->file_name;
                    $this->deleteFile('service/' . $storageName);
                    // remove from the database
                    $media->delete();
                }
            }
             $this->serviceApiRepository->uploadFile($request, $item);
            $this->saveActivity($request, "service", "list", $item);
            $message = "Service Updated Successfully..";
        } else {
            $item = $this->serviceApiRepository->createService($request);
            $this->serviceApiRepository->uploadFile($request, $item);
            $this->saveActivity($request, "item", "list", $item);
            $message = "Service Added Successfully..";
        }


        return redirect('admin/services')->with('success', "Service created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $service = $this->serviceApiRepository->findWithoutFail($id);
        if (empty($service)) {
            return $this->sendError($this->getLangMessages('Sorry! Service not found', 'Service'));
        }
        $this->saveActivity($request, "service", "show", $service);
        $related_services = $this->serviceApiRepository->where('item_sub_category_id', $service->item_sub_category_id)->where('id','!=',$service->id)->get();
        return $this->sendResponse(new ServiceApiCollection($service), 'Service retrived successfully');
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
    public function update($id, UpdateServiceApiRequest $request)
    {
        // return $id;
        $service = $this->serviceApiRepository->findWithoutFail($id);
        if (empty($service)) {
            return $this->sendError($this->getLangMessages('Sorry! Service not found', 'Service'));
        }
        $service = $this->serviceApiRepository->updateService($id, $request);
        if (isset($service->media)) {
            $storageName  = $service->media->file_name;
            $this->deleteFile('service/' . $storageName);
            // remove from the database
            $service->media->delete();
        }
        $this->saveActivity($request, "service", "update", $service);
        $this->serviceApiRepository->uploadFile($request, $service);
        return $this->sendResponse(new ServiceApiCollection($service), 'Service updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $service = $this->serviceApiRepository->findWithoutFail($id);
        if (empty($service)) {
            return $this->sendError($this->getLangMessages('Sorry! Service not found', 'Service'));
        }
        $service->delete();
        $this->saveActivity($request, "service", "delete", $service);
        return $this->sendResponse([], 'Service deleted successfully');
    }

    // public function searchBy($section = null, $id = null)
    // {
    //     $section = $section === null ? "" : $section;
    //     $id = $id === null ? "" : $id;
    //     $data = null;
    //     /*if($section == 'brand'){
    //         $data=$this->serviceApiRepository->findWithoutFail()
    //     }*/
    //     return $section . $id;
    // }
}
