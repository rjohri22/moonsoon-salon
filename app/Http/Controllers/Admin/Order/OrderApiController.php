<?php

namespace App\Http\Controllers\Admin\Order;

use Illuminate\Http\Request;
use App\Repositories\OrderApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Admin\Order\OrderApiCollection;
use App\Http\Requests\Admin\Order\UpdateOrderApiRequest;
use App\Http\Requests\Admin\Order\CreateOrderApiRequest;
use App\Models\Order;
use PDF;
use File;
class OrderApiController extends AppBaseController
{
    protected $orderApiRepository;

    public function __construct(OrderApiRepository $order)
    {
        $this->orderApiRepository = $order;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     *   @OA\get(
     *     path="/api/orders",
     *      tags={"View all Placed Orders"},
     *
     *
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
    public function index(Request $request)
    {
        $this->orderApiRepository->pushCriteria(new RequestCriteria($request));


        $this->orderApiRepository->pushCriteria(new RequestCriteria($request));
        $order = $this->orderApiRepository->get();
        $datas = OrderApiCollection::collection($order);
        // return $this->sendResponse(OrderApiCollection::collection($order), '');
        return view('admin.order.order', compact('datas'))/* ->with('datas',$datas) */;
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
	* @OA\Post(
	*     path="/api/orders",
	*     tags={"Place Order"},
    *
    *      @OA\Parameter(
    *           name="delivery_address_id",
    *           in="query",
    *           required=true,
    *           @OA\Schema(
    *               type="Numeric"
    *           )
	*       ),
    *     @OA\Parameter(
    *           name="txn_id",
    *           in="query",
    *           required=false,
    *           @OA\Schema(
    *               type="Numeric"
    *           )
	*       ),
    *     @OA\Parameter(
    *           name="txn_status",
    *           in="query",
    *           required=false,
    *           @OA\Schema(
    *               type="Numeric"
    *           )
	*       ),
    *     @OA\Parameter(
    *           name="delivery_notes",
    *           in="query",
    *           required=false,
    *           @OA\Schema(
    *               type="String"
    *           )
	*       ),
    *
    *     @OA\Parameter(
    *           name="payment_mode",
    *           in="query",
    *           required=true,
    *           @OA\Schema(
    *               type="string"
    *           )
	*       ),
	*     @OA\Response(
    *           response=200,
    *           description="Success",
    *            @OA\MediaType(
    *               mediaType="application/json",
    *           )
    *       )
    * )
    */
    public function store(CreateOrderApiRequest $request)
    {
        // $order =  $this->orderApiRepository->createOrder($request);
            $userId = \Helper::getUserId();

            $items = \Helper::viewCart($userId);
        // return  $userId;
            // $request->merge(['is_online' => 1]);

            $total = \Helper::getTotal($userId);

            if(count($items) == 0){
                return $this->sendError("Your cart is empty.");
            }
            // return $items;
            // comment for testing
            // Zipcode availability
            // $address = $this->zipRepo->checkAddressAvailability($request->delivery_address_id, $request->shop_id);
            // if(count($address) == 0){
            //     return response()->json(['success' => false, 'message' => "Sorry, delivery is not available in this zipcode", "cart_total"=> "0.00"], 200);
            // }

            // Setting availability
            /*$setting = $this->settingRepo->checkShopSettingStatus($request);
            if(!isset($setting['status'])){
                return $this->sendError($this->getLangMessages('Setting name not found', 'Order'), 200);
            }

            if($setting['status']){
                return $this->sendError($this->getLangMessages('Sorry, '.$setting['name'].' not available now', 'Order'), 200);
            }*/
            $data = $this->orderApiRepository->placeOrder($request);
            // return $data;

        return $this->sendResponse(["item"=>OrderApiCollection::collection($data)], $this->getLangMessages('Order has been placed successfully.', 'Order'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     *   @OA\get(
     *     path="/api/orders/{id}",
     *      tags={"View Order By  Id"},
     *
     *
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
    public function show($id, Request $request)
    {
        $order = $this->orderApiRepository->findWithoutFail($id);
        if (empty($order)) {
            return $this->sendError($this->getLangMessages('Sorry! Order not found', 'Order'));
        }
        return $this->sendResponse(new OrderApiCollection($order), 'Order retrived successfully');
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
     /**
     *   @OA\PUT(
     *     path="/api/orders/{id}",
     *      tags={"Update Payment Details By Id"},
     *   @OA\Parameter(
    *           name="txn_id",
    *           in="query",
    *           required=false,
    *           @OA\Schema(
    *               type="Numeric"
    *           )
	*       ),
    *     @OA\Parameter(
    *           name="txn_status",
    *           in="query",
    *           required=false,
    *           @OA\Schema(
    *               type="Numeric"
    *           )
	*       ),
    *
    *
    *     @OA\Parameter(
    *           name="payment_mode",
    *           in="query",
    *           required=true,
    *           @OA\Schema(
    *               type="string"
    *           )
	*       ),
     *
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
    public function update($id, UpdateOrderApiRequest $request)
    {
        // return $id;
        $order = $this->orderApiRepository->findWithoutFail($id);
        if (empty($order)) {
            return $this->sendError($this->getLangMessages('Sorry! Order not found', 'Order'));
        }
        $order = $this->orderApiRepository->updateOrder($id, $request);

        return $this->sendResponse(new OrderApiCollection($order), 'Order updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $order = $this->orderApiRepository->findWithoutFail($id);
        if (empty($order)) {
            return $this->sendError($this->getLangMessages('Sorry! Order not found', 'Order'));
        }

        $order->delete();
        return $this->sendResponse([], 'Order deleted successfully');
    }


    public function genrateOrderInvoice(Request $request)
    {
        $orderId = $request->order_id;
        if (empty($orderId)) { // validate
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
        // $order = $this->orderRepo->findWithoutFail($orderId);
        $order = Order::where('id',$orderId)->first();
        // return $order;
        if (empty($order)) { // validations
            return $this->sendError("Something went wrong, please try again !");
        }
        // data through collection
        $orderDetatils = ["order" => collect(new OrderApiCollection($order))];
        // return $orderDetatils;
        $orderItems = collect($orderDetatils['order']['order_items']);
        $orderDetatils['order']['orderItems'] = $orderItems;
        // return $orderDetatils['order']['barcode_invoice'];

        // business and location details
        // $locationDetails = $this->paymentRepository->folioGetLocationDetails();
        // if ($locationDetails) {
        //     $orderDetatils["location_details"] = $locationDetails;
        // } else { // if no user found
        //     return $this->sendError("Something went wrong, please try again !");
        // }

        $barcode = "";
            // if (!empty($orderDetatils)) {
            //   $image = base64_encode(file_get_contents("http://localhost:8000/storage/barcodes/3pdf417.png"));
            // //   $image = base64_encode(file_get_contents(public_path('storage/' . $business->businessImage->path)));

            //   $barcode = "data:image/png;base64," . $image; // \Helper::mediaUrl($business->businessImage);
            //   $orderDetatils['order']['barcode_invoice'] = $barcode;
            // }

        // return $orderItems;
        /**
         * step 1: generate pdf
         * step 2: generate link
         */
        // step 1
        // return $orderDetatils;
        $path = "pdf/orders";
        if (!File::exists(\Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix() . 'pdf/orders')) {
            File::makeDirectory(\Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix() . 'pdf/orders', 0755, true);
        }

        $pdf = PDF::loadView('pdf.orders-invoice', $orderDetatils); // make pdf
        $fileNamePath = $path . '/' . $orderDetatils['order']['order_id_no'] . ".pdf"; // file name
        $savePath  = \Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix() . $fileNamePath; // complete
        $pdf->setOptions(['dpi' => 96])->save($savePath); // saving file

        // step 2: generating link
        if (\File::exists(\Storage::disk('public')->getDriver()->getAdapter()->getPathPrefix() . $fileNamePath)) {
            $data["link"] = \Storage::disk('public')->url($fileNamePath);
            $data["email"] = $orderDetatils['order']['user_email'];
            $data["mobile_no"] = $orderDetatils['order']['contact_no'];
            $data["name"] = $orderDetatils['order']['user_name'];
            // $data["barcode"] = $barcode;
        }

        return $this->sendResponse($data, "Generated succesfully");
    }
}
