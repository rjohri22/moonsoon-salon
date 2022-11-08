<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Repositories\BaseRepository;
use App\Traits\UploaderTrait;
use App\Models\Media;
/**
 * Class BrandRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method BrandRepository findWithoutFail($id, $columns = ['*'])
 * @method BrandRepository find($id, $columns = ['*'])
 * @method BrandRepository first($columns = ['*'])
 */
class BrandApiRepository extends BaseRepository
{
    use UploaderTrait;
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        return Brand::class;
    }

    /**
     * Create a  Brand
     *
     * @param Request $request
     *
     * @return Brand
     */
    public function createBrand($request)
    {
        $input = collect($request->all());
        $brand = Brand::create($input->only($request->fillable('brands'))->all());
        // $image = '';
        // if (!empty($input['brand_banner'])) {
        //     $image = $brand->id . '.' . $request->brand_banner->getClientOriginalExtension();
        //     request()->brand_banner->move('web_assets/images/brands', $image);
        // }
        // $brand->update(['brand_banner' => $image]);
        $this->uploadFile($request,$brand);

        return $brand;
    }

    /**
     * Update the Brand
     *
     * @param Request $request
     *
     * @return Brand
     */

    public function updateBrand($id, $request)
    {

        $input = collect($request->all());
        $brand = Brand::findOrFail($id);
        $brand->update($input->only($request->fillable('brands'))->all());

        if (isset($brand->media)) {
            $storageName  = $brand->media->file_name;
            $this->deleteFile('brand/' . $storageName);
            // remove from the database
            $brand->media->delete();
        }
        $this->uploadFile($request,$brand);
        return $brand;
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
