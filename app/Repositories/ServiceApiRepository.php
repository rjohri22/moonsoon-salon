<?php

namespace App\Repositories;

use App\Models\Service;
use App\Models\ServiceVariant;
use App\Models\Media;
use App\Repositories\BaseRepository;
use App\Traits\UploaderTrait;

/**
 * Class ServiceRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method ServiceRepository findWithoutFail($id, $columns = ['*'])
 * @method ServiceRepository find($id, $columns = ['*'])
 * @method ServiceRepository first($columns = ['*'])
 */
class ServiceApiRepository extends BaseRepository
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
        'item_category_id',
        'item_sub_category_id',
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
        return Service::class;
    }

    /**
     * Create a  Service
     *
     * @param Request $request
     *
     * @return Service
     */
    public function createService($request)
    {
        $input = collect($request->all());
        $service = Service::create($input->only($request->fillable('services'))->all());
        
        return $service;
    }

    /**
     * Update the Service
     *
     * @param Request $request
     *
     * @return Service
     */

    public function updateService($id, $request)
    {

        $input = collect($request->all());
        $service = Service::findOrFail($id);
        $service->update($input->only($request->fillable('services'))->all());
        
        return $service;
    }

    public function uploadFile($request, $service)
    {
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];

        //return $request->file;
        if ($request->has('file')) {
            if (!empty($request->file)) {
                foreach ($request->file as $file) {
                    $extension = strtolower($file->getClientOriginalExtension());
                    $check = in_array($extension, $allowedfileExtension);
                    if ($check) {
                        $photo = $this->storeFileMultipart($file, 'service');
                        $input['file_name'] = $photo['name'];
                        $input['status'] = 1;
                        $input['file_type'] = \File::extension($this->getFile($photo['path']));
                        $media = Media::create([
                            'table_type' => get_class($service),
                            'table_id' => $service->id,
                            'file_name' => $input['file_name'],
                            'status' => $input['status'],
                            'default' => null,
                            'file_type' => $input['file_type']
                        ]);
                        // $resizeImage = $this->imageResize($media->file_name, 800, 800);
                        // $photo = $this->moveFileDirectory('temp/'.$media->file_name , 'Service/'.$resizeImage);
                    }
                }
            }
        }
        // return true;
    }
}
