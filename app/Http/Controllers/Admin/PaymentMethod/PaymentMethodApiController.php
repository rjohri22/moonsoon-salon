<?php

namespace App\Http\Controllers\Admin\PaymentMethod;

use Illuminate\Http\Request;
use App\Repositories\PaymentMethodApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Admin\PaymentMethod\PaymentMethodApiCollection;
use App\Http\Requests\Admin\PaymentMethod\UpdatePaymentMethodApiRequest;
use App\Http\Requests\Admin\PaymentMethod\CreatePaymentMethodApiRequest;

class PaymentMethodApiController extends AppBaseController
{
    protected $paymentMethodApiRepository;

    public function __construct(PaymentMethodApiRepository $paymentMethod)
    {
        $this->paymentMethodApiRepository = $paymentMethod;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->paymentMethodApiRepository->pushCriteria(new RequestCriteria($request));
        $paymentMethod = $this->paymentMethodApiRepository->paginate($request->limit);

        // return $this->sendResponse(['item' => PaymentMethodApiCollection::collection($paymentMethod), 'total' => $paymentMethod->total()], '');
        $datas = PaymentMethodApiCollection::collection($paymentMethod);
        // return $datas;
        return view('admin.paymentMethod.paymentMethod')->with('datas', $datas);
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
    public function store(CreatePaymentMethodApiRequest $request)
    {
        if ($request->id) {
            $this->paymentMethodApiRepository->updatePaymentMethod($request->id, $request);
            $message = "Payment Method Updated Successfully..";
        } else {
            $this->paymentMethodApiRepository->createPaymentMethod($request);
            $message = "Payment Method Added Successfully..";
        }

        // $this->sendResponse(new PaymentMethodApiCollection($paymentMethod), 'PaymentMethod created successfully');
        return redirect('admin/payment-methods')->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $paymentMethod = $this->paymentMethodApiRepository->findWithoutFail($id);
        if (empty($paymentMethod)) {
            return $this->sendError($this->getLangMessages('Sorry! Payment Method not found', 'PaymentMethod'));
        }
        return $this->sendResponse($paymentMethod, 'Payment Method retrived successfully');
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
    public function update($id, UpdatePaymentMethodApiRequest $request)
    {
        // return $id;
        $paymentMethod = $this->paymentMethodApiRepository->findWithoutFail($id);
        if (empty($paymentMethod)) {
            return $this->sendError($this->getLangMessages('Sorry! Payment Method not found', 'PaymentMethod'));
        }
        $paymentMethod = $this->paymentMethodApiRepository->updatePaymentMethod($id, $request);

        $this->sendResponse(new PaymentMethodApiCollection($paymentMethod), 'Payment Method updated successfully');
        return redirect('admin/payment-methods');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $paymentMethod = $this->paymentMethodApiRepository->findWithoutFail($id);
        if (empty($paymentMethod)) {
            return $this->sendError($this->getLangMessages('Sorry! Payment Method not found', 'PaymentMethod'));
        }

        $paymentMethod->delete();
        $this->sendResponse([], 'PaymentMethod deleted successfully');
        PaymentMethodApiCollection::collection($paymentMethod);
        return redirect('admin//payment-methods')->with('delete', 'Payment Method Deleted Successfully..');
    }
}
