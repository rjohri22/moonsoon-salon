<?php

namespace App\Http\Controllers\Admin\DeliveryRegion;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeliveryRegion\ZipCodeWebRequest;
use App\Http\Resources\Admin\DeliveryRegion\ZipCodeWebCollection;
use App\Repositories\ZipCodeApiRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Imports\ZipCodeImport;
use Maatwebsite\Excel\Facades\Excel;
class ZipCodeController extends AppBaseController
{
    protected $zipCodeApiRepository;

    public function __construct(ZipCodeApiRepository $zipCode)
    {
        $this->zipCodeApiRepository = $zipCode;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->zipCodeApiRepository->pushCriteria(new RequestCriteria($request));
        $zipCode = $this->zipCodeApiRepository->paginate($request->limit);

        $datas = ZipCodeWebCollection::collection($zipCode);
        return view('admin.zipcode.zipcode', compact('datas'));
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
    public function store(ZipCodeWebRequest $request)
    {
        if ($request->id) {
            $this->zipCodeApiRepository->updateZipCode($request->id, $request);
            $message = "Area Updated Successfully..";
        } else {
            $this->zipCodeApiRepository->createZipCode($request);
            $message = "Area Added Successfully..";
        }

        // $this->sendResponse(new Area DetailsApiCollection($zipCode), 'Area Details created successfully');
        return redirect('admin/delivery-regions')->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zipCode = $this->zipCodeApiRepository->findWithoutFail($id);
        if (empty($zipCode)) {
            return $this->sendError($this->getLangMessages('Sorry! Area Details not found', 'Area Details'));
        }
        return $this->sendResponse($zipCode, 'Area Details retrived successfully');
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $zipCode = $this->zipCodeApiRepository->findWithoutFail($id);
        if (empty($zipCode)) {
            return $this->sendError($this->getLangMessages('Sorry! Area not found', 'Area'));
        }

        $zipCode->delete();
        $this->sendResponse([], 'Area deleted successfully');
        ZipCodeWebCollection::collection($zipCode);
        return redirect()->to('/admin/delivery-regions')->with('delete', 'Area Deleted Successfully..');
    }
    
    public function importFile(Request $request) 
    {

        // return $request->all();
        // Excel::import(new ZipCodeImport, 'users.xlsx');
        // $request->file

        // $array = Excel::toArray(new ZipCodeImport, $request->file('upload_excel'));
        // $array = Excel::toArray(new ZipCodeImport, $request->file('upload_excel'));
        $array = Excel::import(new ZipCodeImport, request()->file('upload_excel'));


        return $array;
        return redirect('/')->with('success', 'All good!');
    }

}
