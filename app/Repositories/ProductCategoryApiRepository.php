<?php

namespace App\Repositories;

use App\Models\ProductCategory;
use App\Repositories\BaseRepository;

/**
 * Class ItemCategoryRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method ItemCategoryRepository findWithoutFail($id, $columns = ['*'])
 * @method ItemCategoryRepository find($id, $columns = ['*'])
 * @method ItemCategoryRepository first($columns = ['*'])
 */
class ProductCategoryApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        /* 'module_id',
        'user_id',
        'shop_id', */
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
        return ProductCategory::class;
    }

    /**
     * Create a  ItemCategory
     *
     * @param Request $request
     *
     * @return ProductCategory
     */
    public function createProductCategory($request)
    {
        $input = collect($request->all());
        $productCategory = ProductCategory::create($input->only($request->fillable('ProductCategorys'))->all());
        return $productCategory;
    }

    /**
     * Update the ItemCategory
     *
     * @param Request $request
     *
     * @return ProductCategory
     */

    public function updateProductCategory($id, $request)
    {
        $input = collect($request->all());
        $productCategory = ProductCategory::findOrFail($id);
        $productCategory->update($input->only($request->fillable('ProductCategorys'))->all());

        return $productCategory;
    }
    
    public function uploadFile($request, $item)
    {
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];
        if ($request->has('file')) {
            if (!empty($request->file)) {
                    $extension = strtolower($request->file->getClientOriginalExtension());
                    $check = in_array($extension, $allowedfileExtension);
                    if ($check) {
                        $photo = $this->storeFileMultipart($request->file, 'brand');
                        $input['file_name'] = $photo['name'];
                        $input['status'] = 1;
                        $input['file_type'] = \File::extension($this->getFile($photo['path']));
                        $media = Media::create([
                            'table_type' => get_class($item),
                            'table_id' => $item->id,
                            'file_name' => $input['file_name'],
                            'status' => $input['status'],
                            'default' => null,
                            'file_type' => $input['file_type']
                        ]);
                    }
            }
        }
    }
}
