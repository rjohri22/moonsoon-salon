<?php

namespace App\Http\Criteria\Customer\Service;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;

class ServiceCriteria implements CriteriaInterface {

    /**
     * @var array
     */
    private $request;

    /**
     * ServiceCriteria Constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request; 
    }
    
    public function apply($model, RepositoryInterface $repository)
    {
            $model =    $model->where('status', 'active')->whereNull('deleted_at');
            $sql = $model;

            $serviceCategoryId  = $this->request->get('item_category_id') ?? null;
            $serviceSubCategoryId  = $this->request->get('item_sub_category_id') ?? null;
            $query  = $this->request->get('search') ?? null;
            // $brandId    = $this->request->get('brand_id') ?? null;
            $price      = $this->request->get('price') ?? null;
            // $discountId      = $this->request->get('discount_id') ?? null;
            $rating      = $this->request->get('rating') ?? null;
            
            // Applying Start & End date Filter.
            if($serviceCategoryId){
                $sql   =  $model->where('item_category_id', $serviceCategoryId);
            }
            
            if($serviceSubCategoryId){
                $sql   =  $model->where('item_sub_category_id', $serviceSubCategoryId);
            }
            
            if($rating){
                $sql   =  $model->where('avg_rating', '>=', $rating);
            }
            
            
            // if($brandId){
            //     $sql   =  $model->where('brand_id', $brandId);
            // }
            
            if($price)
            {
                $sql   =  $model->where('price', $price);
            }

            // if($discountId)
            // {
            //     $sql   =  $model->whereHas('ServiceVariants', function($q) use ($discountId) {
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