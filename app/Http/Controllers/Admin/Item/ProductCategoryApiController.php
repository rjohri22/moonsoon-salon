<?php

namespace App\Http\Controllers\Admin\Item;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\Item\CreateProductCategoryApiRequest;
use App\Http\Requests\Admin\Item\UpdateProductCategoryApiRequest;
use App\Http\Resources\Admin\Item\ProductCategoryApiCollection;
use App\Repositories\ProductCategoryApiRepository;
use App\Repositories\ItemSubCategoryApiRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;

class ProductCategoryApiController extends AppBaseController
{
    protected $productCategoryApiRepository;
    protected $itemSubCategoryApiRepository;

    public function __construct(ProductCategoryApiRepository $productCategory, ItemSubCategoryApiRepository $itemSubCategory)
    {
        $this->productCategoryApiRepository = $productCategory;
        $this->itemSubCategoryApiRepository = $itemSubCategory;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->productCategoryApiRepository->pushCriteria(new RequestCriteria($request));
        $items = $this->productCategoryApiRepository->paginate($request->limit);
        $datas = ProductCategoryApiCollection::collection($items);
        $subCategories = $this->itemSubCategoryApiRepository->whereStatus('active')->select('id', 'name','category_type')->get();
        return view('admin.item.product-category', compact('datas', 'subCategories'));
        return $this->sendResponse(['products' => ProductCategoryApiCollection::collection($items), 'total' => $items->total()], '');
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
        if ($request->id) {
            $this->productCategoryApiRepository->updateproductCategory($request->id, $request);
            $message = "Product-Category Updated Successfully..";
        } else {
            $this->productCategoryApiRepository->createProductCategory($request);
            $message = "Product-Category Added Successfully..";
        }
        // $this->sendResponse(new ProductCategoryApiCollection($productCategory), 'Product Category created successfully');
        return redirect('admin/product-categories')->with('success', $message);
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
            return $this->sendError($this->getLangMessages('Sorry! Product Category not found', 'productCategory'));
        }
        return $this->sendResponse($productCategory, 'Product Sub Category retrived successfully');
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
            return $this->sendError($this->getLangMessages('Sorry! Product Category not found', 'productCategory'));
        }
        $productCategory = $this->productCategoryApiRepository->updateproductCategory($id, $request);

        $this->sendResponse(new ProductCategoryApiCollection($productCategory), 'Product Category updated successfully');
        return redirect('admin/product-category');
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
            return $this->sendError($this->getLangMessages('Sorry! Product Sub Category not found', 'productCategory'));
        }
        Session::put('delete','Product Sub Category deleted successfully');

        $productCategory->delete();
       return $this->sendResponse([], 'Product Sub Category deleted successfully');
    }
}
