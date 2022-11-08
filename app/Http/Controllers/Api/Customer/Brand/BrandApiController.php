<?php

namespace App\Http\Controllers\Api\Customer\Brand;

use Illuminate\Http\Request;
use App\Repositories\BrandApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Api\Customer\Brand\BrandApiCollection;
use App\Http\Requests\Api\Customer\Brand\UpdateBrandApiRequest;
use App\Http\Requests\Api\Customer\Brand\CreateBrandApiRequest;

class BrandApiController extends AppBaseController
{
    protected $brandApiRepository;

    public function __construct(BrandApiRepository $brand)
    {
        $this->brandApiRepository = $brand;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->brandApiRepository->pushCriteria(new RequestCriteria($request));
        $items = $this->brandApiRepository->whereStatus('active');
        if(!empty($request->branch_id))
        {
            $items->where('branch_id',$request->branch_id);
        }
        $items = $items->get();

        return $this->sendResponse(['item' => BrandApiCollection::collection($items)], '');
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
    public function store(CreateBrandApiRequest $request)
    {
        $brand =  $this->brandApiRepository->createBrand($request);

        return $this->sendResponse(new BrandApiCollection($brand), 'Brand created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $brand = $this->brandApiRepository->findWithoutFail($id);
        if (empty($brand)) {
            return $this->sendError($this->getLangMessages('Sorry! Brand not found', 'Brand'));
        }
        return $this->sendResponse(new BrandApiCollection($brand), 'Brand retrived successfully');
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
    public function update($id, UpdateBrandApiRequest $request)
    {
        // return $id;
        $brand = $this->brandApiRepository->findWithoutFail($id);
        if (empty($brand)) {
            return $this->sendError($this->getLangMessages('Sorry! Brand not found', 'Brand'));
        }
        $brand = $this->brandApiRepository->updateBrand($id, $request);

        return $this->sendResponse(new BrandApiCollection($brand), 'Brand updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $brand = $this->brandApiRepository->findWithoutFail($id);
        if (empty($brand)) {
            return $this->sendError($this->getLangMessages('Sorry! Brand not found', 'Brand'));
        }

        $brand->delete();
        return $this->sendResponse([], 'Brand deleted successfully');
    }
}
