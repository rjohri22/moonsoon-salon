<?php

namespace App\Http\Controllers\Admin\ServiceOrder;

use Illuminate\Http\Request;
use App\Repositories\ServiceOrderApiRepository;
use App\Repositories\ServiceCartApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\ServiceOrder\ServiceOrderApiCollection;
use App\Http\Requests\Api\Customer\ServiceOrder\UpdateServiceOrderApiRequest;
use App\Http\Requests\Api\Customer\ServiceOrder\CreateServiceOrderApiRequest;
use App\Models\ServiceOrder;
use PDF;
use File;
use App\Utils\Helper;
class ServiceOrderApiController extends AppBaseController
{
    protected $serviceOrderApiRepository;
    protected $serviceCartApiRepository;

    public function __construct(ServiceOrderApiRepository $serviceOrder,ServiceCartApiRepository $cartApi)
    {
        $this->serviceOrderApiRepository = $serviceOrder;
        $this->serviceCartApiRepository = $cartApi;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

  
    public function index(Request $request)
    {
        $this->serviceOrderApiRepository->pushCriteria(new RequestCriteria($request));
        $datas = $this->serviceOrderApiRepository->get();

        return view('admin.order.service-order', compact('datas'))/* ->with('datas',$datas) */;

        // return $this->sendResponse(ServiceOrderApiCollection::collection($items), '');
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


    
    public function store(CreateServiceOrderApiRequest $request)
    {
        // $serviceOrder =  $this->serviceOrderApiRepository->createServiceOrder($request);
            $userId = Helper::getUserId();
            $items = $this->serviceCartApiRepository->addToServiceCart($request);

            // $items = Helper::viewServiceCart($userId);
        // return  $userId;
            // $request->merge(['is_online' => 1]);

            $total = Helper::getServiceTotal($userId);

            if(!empty($items) == 0){
                return $this->sendError("Your cart is empty.");
            }
            
            $data = $this->serviceOrderApiRepository->placeServiceOrder($request);
            // return $data;
        
        return $this->sendResponse(["item"=>ServiceOrderApiCollection::collection($data)], $this->getLangMessages('ServiceOrder has been placed successfully.', 'ServiceOrder'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

   
    public function show($id, Request $request)
    {
        // return $id;
        $serviceOrder = $this->serviceOrderApiRepository->findWithoutFail($id);
        if (empty($serviceOrder)) {
            return $this->sendError($this->getLangMessages('Sorry! ServiceOrder not found', 'ServiceOrder'));
        }
        return $this->sendResponse(new ServiceOrderApiCollection($serviceOrder), 'ServiceOrder retrived successfully');
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

  
    public function update($id, UpdateServiceOrderApiRequest $request)
    {
        // return $id;
        $serviceOrder = $this->serviceOrderApiRepository->findWithoutFail($id);
        if (empty($serviceOrder)) {
            return $this->sendError($this->getLangMessages('Sorry! Service Order not found', 'ServiceOrder'));
        }
        $serviceOrder = $this->serviceOrderApiRepository->updateServiceOrder($id, $request);

        return $this->sendResponse(new ServiceOrderApiCollection($serviceOrder), 'Service Order updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $serviceOrder = $this->serviceOrderApiRepository->findWithoutFail($id);
        if (empty($serviceOrder)) {
            return $this->sendError($this->getLangMessages('Sorry! Service Order not found', 'Service Order'));
        }

        $serviceOrder->delete();
        return $this->sendResponse([], 'ServiceOrder deleted successfully');
    }

    public function genrateServiceOrderInvoice(Request $request)
    {
        $serviceOrderId = $request->service_orders_id;
        if (empty($serviceOrderId)) { // validate
            return $this->sendError("Something went wrong, please try again !");
        }
        // return data
        $data = [
            "link" => "",
            "email" => "",
            "mobile_no" => "",
            "name" => "",
        ];
        // all data list i.e. according to selected payments id
        // $serviceOrder = $this->ServiceOrderRepo->findWithoutFail($serviceOrderId);
        $serviceOrder = ServiceOrder::where('id',$serviceOrderId)->first();
        // return $serviceOrder;
        if (empty($serviceOrder)) { // validations
            return $this->sendError("Something went wrong, please try again !");
        }
        // data through collection
        $serviceOrderDetatils = ["serviceOrder" => collect(new ServiceOrderApiCollection($serviceOrder))];
        // return $serviceOrderDetatils;
        $serviceOrderItems = collect($serviceOrderDetatils['serviceOrder']['service_order_items']);
        $serviceOrderDetatils['serviceOrder']['serviceOrderItems'] = $serviceOrderItems;
        // return $serviceOrderDetatils['ServiceOrder']['barcode_invoice'];

        // business and location details
        // $locationDetails = $this->paymentRepository->folioGetLocationDetails();
        // if ($locationDetails) {
        //     $serviceOrderDetatils["location_details"] = $locationDetails;
        // } else { // if no user found
        //     return $this->sendError("Something went wrong, please try again !");
        // }

        $barcode = "";
            // if (!empty($serviceOrderDetatils)) {
            //   $image = base64_encode(file_get_contents("http://localhost:8000/storage/barcodes/3pdf417.png"));
            // //   $image = base64_encode(file_get_contents(public_path('storage/' . $business->businessImage->path)));

            //   $barcode = "data:image/png;base64," . $image; // \Helper::mediaUrl($business->businessImage);
            //   $serviceOrderDetatils['ServiceOrder']['barcode_invoice'] = $barcode;
            // }

        // return $serviceOrderItems;
        /**
         * step 1: generate pdf
         * step 2: generate link
         */
        // step 1
        // return $serviceOrderDetatils;
        $path = "pdf/service-orders";
        if (!File::exists(\Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix() . 'pdf/service-orders')) {
            File::makeDirectory(\Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix() . 'pdf/service-orders', 0755, true);
        }

        $pdf = PDF::loadView('pdf.service-orders-invoice', $serviceOrderDetatils); // make pdf
        $fileNamePath = $path . '/' . $serviceOrderDetatils['serviceOrder']['service_order_id_no'] . ".pdf"; // file name
        $savePath  = \Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix() . $fileNamePath; // complete
        $pdf->setOptions(['dpi' => 96])->save($savePath); // saving file

        // step 2: generating link
        if (\File::exists(\Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix() . $fileNamePath)) {
            $data["link"] = \Storage::disk('public')->url($fileNamePath);
            $data["email"] = $serviceOrderDetatils['serviceOrder']['user_email'];
            $data["mobile_no"] = $serviceOrderDetatils['serviceOrder']['contact_no'];
            $data["name"] = $serviceOrderDetatils['serviceOrder']['user_name'];
            // $data["barcode"] = $barcode;
        }

        return $this->sendResponse($data, "Generated succesfully");
    }
}
