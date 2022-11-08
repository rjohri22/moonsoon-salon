<?php

namespace App\Http\Controllers\Admin\Package;

use Illuminate\Http\Request;
use App\Repositories\PackageApiRepository;
use App\Repositories\DiscountTypeApiRepository;
use App\Repositories\ServiceApiRepository;
use App\Repositories\ItemApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Admin\Package\PackageApiCollection;
use App\Http\Requests\Admin\Package\UpdatePackageApiRequest;
use App\Http\Requests\Admin\Package\CreatePackageApiRequest;
use App\Models\Brand;
use App\Traits\ActivityLogTrait;
use App\Traits\UploaderTrait;
use App\Http\Criteria\Customer\Package\PackageCriteria;

class PackageApiController extends AppBaseController
{

    protected $packageApiRepository;
    protected $serviceApiRepository;
    protected $itemApiRepository;
    protected $discountTypeApiRepository;
 

    use UploaderTrait, ActivityLogTrait;
    public function __construct(PackageApiRepository $package,ServiceApiRepository $service,ItemApiRepository $item,DiscountTypeApiRepository  $discountType)
    {
        $this->packageApiRepository = $package;
        $this->serviceApiRepository = $service;
        $this->itemApiRepository = $item;
        $this->discountTypeApiRepository = $discountType;
       
    }



    public function index(Request $request)
    {
        $this->packageApiRepository->pushCriteria(new RequestCriteria($request));
        // $this->packageApiRepository->pushCriteria(new PackageCriteria($request));

        // $packages = $this->packageApiRepository->where('status', 'active')->get();
        $packages = $this->packageApiRepository->paginate($request->limit);
        $datas = PackageApiCollection::collection($packages);

        $serviceItems = $this->serviceApiRepository->where('status','active')->get();
        $services = PackageApiCollection::collection($serviceItems);

        $itemsDatas = $this->itemApiRepository->where('status','active')->get();
        $items = PackageApiCollection::collection($itemsDatas);
        // return $datas;
        $discountTypes = $this->discountTypeApiRepository->whereStatus('active')->select('id', 'discount_type as name')->get();

        return view('admin.package.package', compact('datas', 'services', 'items','discountTypes'));
 
        // return $this->sendResponse(PackageApiCollection::collection($packages), '');
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

   
    public function store(CreatePackageApiRequest $request)
    {
        // return $request->all();
        if(!empty($request->id))
        {
            $package =  $this->packageApiRepository->updatePackage($request->id,$request);

        }else
        {
            $package =  $this->packageApiRepository->createPackage($request);
        }
        // return $package;
        $this->packageApiRepository->uploadFile($request, $package);
        $this->saveActivity($request, "Package", "list", $package);
        return redirect('admin/packages')->with('success', "Package created successfully");

        return $this->sendResponse(new PackageApiCollection($package), 'Package created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $package = $this->packageApiRepository->findWithoutFail($id);
        if (empty($package)) {
            return $this->sendError($this->getLangMessages('Sorry! Package not found', 'Package'));
        }
        $this->saveActivity($request, "package", "show", $package);
        return $this->sendResponse(new PackageApiCollection($package), 'Package retrived successfully');
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
    public function update($id, UpdatePackageApiRequest $request)
    {
        // return $id;
        $package = $this->packageApiRepository->findWithoutFail($id);
        if (empty($package)) {
            return $this->sendError($this->getLangMessages('Sorry! Package not found', 'Package'));
        }
        $package = $this->packageApiRepository->updatePackage($id, $request);
        if (isset($package->media)) {
            $storageName  = $package->media->file_name;
            $this->deleteFile('package/' . $storageName);
            // remove from the database
            $package->media->delete();
        }
        $this->saveActivity($request, "package", "update", $package);
        $this->packageApiRepository->uploadFile($request, $package);
        return $this->sendResponse(new PackageApiCollection($package), 'Package updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $package = $this->packageApiRepository->findWithoutFail($id);
        if (empty($package)) {
            return $this->sendError($this->getLangMessages('Sorry! Package not found', 'Package'));
        }
        $package->delete();
        $this->saveActivity($request, "package", "delete", $package);
        return $this->sendResponse([], 'Package deleted successfully');
    }

    // public function searchBy($section = null, $id = null)
    // {
    //     $section = $section === null ? "" : $section;
    //     $id = $id === null ? "" : $id;
    //     $data = null;
    //     /*if($section == 'brand'){
    //         $data=$this->PackageApiRepository->findWithoutFail()
    //     }*/
    //     return $section . $id;
    // }
}
