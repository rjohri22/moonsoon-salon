<?php

namespace App\Http\Controllers\Admin\Brand;

use Illuminate\Http\Request;
use App\Repositories\BrandApiRepository;
use App\Http\Controllers\AppBaseController;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Resources\Admin\Brand\BrandApiCollection;
use App\Http\Requests\Admin\Brand\UpdateBrandApiRequest;
use App\Http\Requests\Admin\Brand\CreateBrandApiRequest;

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
        $items = $this->brandApiRepository->paginate($request->limit);

        // return $this->sendResponse(['item' => BrandApiCollection::collection($items), 'total' => $items->total()], '');
        $datas = BrandApiCollection::collection($items);
        return view('admin.brand.brand', compact('datas'))/* ->with('datas',$datas) */;
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
        if ($request->id) {
            $this->brandApiRepository->updateBrand($request->id, $request);
            $message = "Brand Updated Successfully..";
        } else {
            $this->brandApiRepository->createBrand($request);
            $message = "Brand Added Successfully..";
        }

        // $this->sendResponse(new BrandApiCollection($brand), 'Brand created successfully');
        return redirect('admin/brands')->with('success', $message);
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
        return $this->sendResponse($brand, 'Brand retrived successfully');
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

        $this->sendResponse(new BrandApiCollection($brand), 'Brand updated successfully');
        return redirect('admin/brands');
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
        $this->sendResponse([], 'Brand deleted successfully');
        BrandApiCollection::collection($brand);
        return redirect()->to('/admin/brands')->with('delete', 'Brand Deleted Successfully..');
    }
}
