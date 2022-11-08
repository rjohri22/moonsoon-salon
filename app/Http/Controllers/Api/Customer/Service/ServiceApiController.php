<?php

namespace App\Http\Controllers\Api\Customer\Service;

use Illuminate\Http\Request;
use App\Repositories\ServiceApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Service\ServiceApiCollection;
use App\Http\Requests\Api\Customer\Service\UpdateServiceApiRequest;
use App\Http\Requests\Api\Customer\Service\CreateServiceApiRequest;
use App\Models\Brand;
use App\Traits\ActivityLogTrait;
use App\Traits\UploaderTrait;
use App\Http\Criteria\Customer\Service\ServiceCriteria;

class ServiceApiController extends AppBaseController
{
    protected $serviceApiRepository;

    use UploaderTrait, ActivityLogTrait;
    public function __construct(ServiceApiRepository $service)
    {
        $this->serviceApiRepository = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     *   @OA\Get(
     *     path="/api/services",
     *      tags={"Salon App: Service Listing"},
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
        $this->serviceApiRepository->pushCriteria(new RequestCriteria($request));
        $this->serviceApiRepository->pushCriteria(new ServiceCriteria($request));

        $services = $this->serviceApiRepository->where('status', 'active')->get();

        return $this->sendResponse(ServiceApiCollection::collection($services), '');
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
    public function store(CreateServiceApiRequest $request)
    {
        // return $request->all();
        $service =  $this->serviceApiRepository->createService($request);
        // return $service;
        $this->serviceApiRepository->uploadFile($request, $service);
        $this->saveActivity($request, "Service", "list", $service);
        return $this->sendResponse(new ServiceApiCollection($service), 'Service created successfully');
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
        $related_services = $this->serviceApiRepository->where('product_category_id', $service->product_category_id)->where('id','!=',$service->id)->get();
        return $this->sendResponse(['service_detail' => new ServiceApiCollection($service), 'related_Services' => ServiceApiCollection::collection($related_services)], 'Service retrived successfully');
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
