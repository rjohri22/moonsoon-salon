<?php

namespace App\Repositories;

use App\Models\ItemSubCategory;
use App\Repositories\BaseRepository;
use App\Traits\UploaderTrait;
use App\Models\Media;
/**
 * Class ItemCategoryRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method ItemCategoryRepository findWithoutFail($id, $columns = ['*'])
 * @method ItemCategoryRepository find($id, $columns = ['*'])
 * @method ItemCategoryRepository first($columns = ['*'])
 */
class ItemSubCategoryApiRepository extends BaseRepository
{
    use UploaderTrait;
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
        return ItemSubCategory::class;
    }

    /**
     * Create a  ItemCategory
     *
     * @param Request $request
     *
     * @return ItemSubCategory
     */
    public function createItemSubCategory($request)
    {
        $input = collect($request->all());
        $itemSubCategory = ItemSubCategory::create($input->only($request->fillable('itemSubCategorys'))->all());
        $this->uploadFile($request,$itemSubCategory);

        return $itemSubCategory;
    }

    /**
     * Update the ItemCategory
     *
     * @param Request $request
     *
     * @return ItemSubCategory
     */

    public function updateItemSubCategory($id, $request)
    {
        $input = collect($request->all());
        $itemSubCategory = ItemSubCategory::findOrFail($id);
        $itemSubCategory->update($input->only($request->fillable('itemSubCategorys'))->all());
        if (isset($itemSubCategory->media)) {
            $storageName  = $itemSubCategory->media->file_name;
            $this->deleteFile('itemSubCategory/' . $storageName);
            // remove from the database
            $itemSubCategory->media->delete();
        }
        $this->uploadFile($request,$itemSubCategory);
        return $itemSubCategory;
    }

    public function uploadFile($request, $itemSubCategory)
    {
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];
        if ($request->has('file')) {
            if (!empty($request->file)) {
                    $extension = strtolower($request->file->getClientOriginalExtension());
                    $check = in_array($extension, $allowedfileExtension);
                    if ($check) {
                        $photo = $this->storeFileMultipart($request->file, 'itemSubCategory');
                        $input['file_name'] = $photo['name'];
                        $input['status'] = 1;
                        $input['file_type'] = \File::extension($this->getFile($photo['path']));
                        $media = Media::create([
                            'table_type' => get_class($itemSubCategory),
                            'table_id' => $itemSubCategory->id,
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
