<?php

namespace App\Http\Controllers\Api\Customer\Item;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\Item\CreateProductCategoryApiRequest;
use App\Http\Requests\Api\Customer\Item\UpdateProductCategoryApiRequest;
use App\Http\Resources\Api\Customer\Item\ProductCategoryApiCollection;
use App\Repositories\ProductCategoryApiRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;

class ProductCategoryApiController extends AppBaseController
{
    protected $productCategoryApiRepository;
    public function __construct(ProductCategoryApiRepository $productCategory)
    {
        $this->productCategoryApiRepository = $productCategory;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->productCategoryApiRepository->pushCriteria(new RequestCriteria($request));
        $items = $this-> productCategoryApiRepository->where('status', 'active')->get();

        return $this->sendResponse(['products' => ProductCategoryApiCollection::collection($items)], '');
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
    public function store(CreateProductCategoryApiRequest $request)
    {
        $productCategory = $this->productCategoryApiRepository->createProductCategory($request);
        return $this->sendResponse(new ProductCategoryApiCollection($productCategory), 'Product Category created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productCategory = $this->productCategoryApiRepository->findWithoutFail($id);
        if (empty($productCategory)) {
            return $this->sendError($this->getLangMessages('Sorry! Item Category not found', 'productCategory'));
        }
        return $this->sendResponse(new ProductCategoryApiCollection($productCategory), 'Item Sub Category retrived successfully');
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
    public function update(UpdateProductCategoryApiRequest $request, $id)
    {
        $productCategory = $this->productCategoryApiRepository->findWithoutFail($id);
        if (empty($productCategory)) {
            return $this->sendError($this->getLangMessages('Sorry! Item Category not found', 'productCategory'));
        }
        $productCategory = $this->productCategoryApiRepository->updateproductCategory($id, $request);

        return $this->sendResponse(new ProductCategoryApiCollection($productCategory), 'Item Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productCategory = $this->productCategoryApiRepository->findWithoutFail($id);
        if (empty($productCategory)) {
            return $this->sendError($this->getLangMessages('Sorry! Item Sub Category not found', 'productCategory'));
        }

        $productCategory->delete();
        return $this->sendResponse([], 'Item Sub Category deleted successfully');
    }
}
