<?php

namespace App\Http\Criteria\Customer\Item;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

class ItemCriteria implements CriteriaInterface {

    /**
     * @var array
     */
    private $request;

    /**
     * ItemCriteria Constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request; 
    }
    
    public function apply($model, RepositoryInterface $repository)
    {
            $model =    $model->where('status', 'active')->whereNull('deleted_at');
            $sql = $model;

            $itemCategoryId  = $this->request->get('item_category_id') ?? null;
            $itemSubCategoryId  = $this->request->get('item_sub_category_id') ?? null;
            $productCategoryId  = $this->request->get('product_category_id') ?? null;
            $query  = $this->request->get('search') ?? null;
            $brandId    = $this->request->get('brand_id') ?? null;
            $price      = $this->request->get('price') ?? null;
            // $discountId      = $this->request->get('discount_id') ?? null;
            $rating      = $this->request->get('rating') ?? null;
            
            // Applying Start & End date Filter.
            if($itemCategoryId){
                $sql   =  $model->where('item_category_id', $itemCategoryId);
            }
            if($productCategoryId){
                $sql   =  $model->where('product_category_id', $productCategoryId);
            }
            if($itemSubCategoryId){
                $sql   =  $model->where('item_sub_category_id', $itemSubCategoryId);
            }
            
            if($rating){
                $sql   =  $model->where('avg_rating', '>=', $rating);
            }
            
            
            if($brandId){
                $sql   =  $model->where('brand_id', $brandId);
            }
            
            if($price)
            {
                $sql   =  $model->where('price', $price);
            }

            // if($discountId)
            // {
            //     $sql   =  $model->whereHas('itemVariants', function($q) use ($discountId) {
            //         return $q->where('discount_id', $discountId);
            //     });
            // }
            // Applying Name & Phone No Filter.
            if($query)
            {
                $sql   =  isset($query) ? $sql->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                }) : $sql; 
            }
           
            return $sql;
    }
}