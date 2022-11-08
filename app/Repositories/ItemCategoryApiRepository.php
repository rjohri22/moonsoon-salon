<?php

namespace App\Repositories;

use App\Models\ItemCategory;
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
class ItemCategoryApiRepository extends BaseRepository
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
        return ItemCategory::class;
    }

    /**
     * Create a  ItemCategory
     *
     * @param Request $request
     *
     * @return ItemCategory
     */
    public function createItemCategory($request)
    {
        $input = collect($request->all());
        $itemCategory = ItemCategory::create($input->only($request->fillable('itemCategorys'))->all());
        $this->uploadFile($request,$itemCategory);

        return $itemCategory;
    }

    /**
     * Update the ItemCategory
     *
     * @param Request $request
     *
     * @return ItemCategory
     */

    public function updateItemCategory($id, $request)
    {

        $input = collect($request->all());
        $itemCategory = ItemCategory::findOrFail($id);
        $itemCategory->update($input->only($request->fillable('itemCategorys'))->all());
        if (isset($itemCategory->media)) {
            $storageName  = $itemCategory->media->file_name;
            $this->deleteFile('itemCategory/' . $storageName);
            // remove from the database
            $itemCategory->media->delete();
        }
        $this->uploadFile($request,$itemCategory);
        return $itemCategory;
    }

    public function uploadFile($request, $item)
    {
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];
        if ($request->has('file')) {
            if (!empty($request->file)) {
                    $extension = strtolower($request->file->getClientOriginalExtension());
                    $check = in_array($extension, $allowedfileExtension);
                    if ($check) {
                        $photo = $this->storeFileMultipart($request->file, 'itemCategory');
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
