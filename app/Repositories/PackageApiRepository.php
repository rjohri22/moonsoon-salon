<?php

namespace App\Repositories;

use App\Models\Package;
use App\Models\PackageDetail;
use App\Models\Media;
use App\Repositories\BaseRepository;
use App\Traits\UploaderTrait;

/**
 * Class PackageRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method PackageRepository findWithoutFail($id, $columns = ['*'])
 * @method PackageRepository find($id, $columns = ['*'])
 * @method PackageRepository first($columns = ['*'])
 */
class PackageApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    use UploaderTrait;
    protected $fieldSearchable = [
        /* 'module_id',
        'user_id',
        'shop_id', */
        // 'brand_id',
        // 'item_category_id',
        // 'item_sub_category_id',
        'name',
        'description',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Package::class;
    }

    /**
     * Create a  Package
     *
     * @param Request $request
     *
     * @return Package
     */
    public function createPackage($request)
    {
        $input = collect($request->all());
        $package = Package::create($input->only($request->fillable('packages'))->all());
        
        if($request->packages_type == "service")
        {
            if(!empty($request->service_table_id))
            {
                foreach($request->service_table_id as $key => $value)
                {
                    $data = collect(['table_id'=>$value,'table_type'=>$request->packages_type,'status'=>$request->status[$key]]);
                    $package->packageDetail()->updateOrCreate($data->only($request->fillable('packagesDetails'))->all());
                }
            }
        }else{
            if(!empty($request->item_table_id))
            {
                foreach($request->item_table_id as $key => $valueItem)
                {
                    $data = collect(['table_id'=>$valueItem,'table_type'=>$request->packages_type,'status'=>$request->status[$key]]);
                    $package->packageDetail()->updateOrCreate($data->only($request->fillable('packagesDetails'))->all());
                }
            }
        }

        /* $variants= json_decode($input['variants']);
        // return $variants[0]->qty;
        if(!empty($variants))
        {
            foreach($variants as $key => $variant){
               $packageDetailData = [
                    'Package_id' => $package->id,
                    'qty' => $variant->qty,
                    'unit_value' => $variant->unit_value,
                    'price' => $variant->price,
                    'sale_price' => $variant->sale_price,
                    'unit_id' => $variant->unit_id,
                    'discount_type' => $variant->discount_type,
                    'discount_amount' => $variant->discount_amount,
                    'default' => $variant->default ? 1 : 0,
                ];
                $packageDetails = $package->PackageDetails()->updateOrCreate($packageDetailData);

            }
        } */
        return $package;
    }

    /**
     * Update the Package
     *
     * @param Request $request
     *
     * @return Package
     */

    public function updatePackage($id, $request)
    {

        $input = collect($request->all());
        $package = Package::findOrFail($id);
        $package->update($input->only($request->fillable('packages'))->all());
        if($request->packages_type == "service")
        {
            if(!empty($request->service_table_id))
            {
                foreach($request->service_table_id as $key => $value)
                {
                    $data = collect(['table_id'=>$value,'table_type'=>$request->packages_type,'status'=>$request->status[$key]]);
                    $package->packageDetail()->updateOrCreate(['id'=>$request->package_id[$key]],$data->only($request->fillable('packagesDetails'))->all());
                }
            }
        }else{
            if(!empty($request->item_table_id))
            {
                foreach($request->item_table_id as $key => $valueItem)
                {
                    $data = collect(['table_id'=>$valueItem,'table_type'=>$request->packages_type,'status'=>$request->status[$key]]);
                    $package->packageDetail()->updateOrCreate(['id'=>$request->package_id[$key]],$data->only($request->fillable('packagesDetails'))->all());
                }
            }
        }
        /* $packageDetailId=[];
        $variants= json_decode($input['variants']);
        if(!empty($variants))
        {
            foreach($variants as $key => $variant){
                $packageDetails=[
                    'Package_id' => $package->id,
                    'qty' => $variant->qty,
                    'price' => $variant->price,
                    'unit_value' => $variant->unit_value,
                    'sale_price' => $variant->sale_price,
                    'unit_id' => $variant->unit_id,
                    'discount_type' => $variant->discount_type,
                    'discount_amount' => $variant->discount_amount,
                    'default' => $variant->default ? 1 : 0,
                ];
                if(!empty($variant->id))
                {
                    $packageDetailId[]=$variant->id;
                    $package->PackageDetails()->updateOrCreate(['id'=>!empty($variant->id) ? $variant->id : null],$packageDetails);
                }else
                {
                    $packageDetails = $package->PackageDetails()->updateOrCreate($packageDetails);
                    $packageDetailId[]=$packageDetails->id;
                }
            }

        }*/
        return $package;
    }

    public function uploadFile($request, $package)
    {
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];

        //return $request->file;
        if ($request->has('file')) {
            if (!empty($request->file)) {
                // foreach ($request->file as $file) {
                    $extension = strtolower($request->file->getClientOriginalExtension());
                    $check = in_array($extension, $allowedfileExtension);
                    if ($check) {
                        $photo = $this->storeFileMultipart($request->file, 'package');
                        $input['file_name'] = $photo['name'];
                        $input['status'] = 1;
                        $input['file_type'] = \File::extension($this->getFile($photo['path']));
                        $media = Media::create([
                            'table_type' => get_class($package),
                            'table_id' => $package->id,
                            'file_name' => $input['file_name'],
                            'status' => $input['status'],
                            'default' => null,
                            'file_type' => $input['file_type']
                        ]);
                        // $resizeImage = $this->imageResize($media->file_name, 800, 800);
                        // $photo = $this->moveFileDirectory('temp/'.$media->file_name , 'Package/'.$resizeImage);
                    }
                // }
            }
        }
        // return true;
    }
}
