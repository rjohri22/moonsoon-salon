<?php

namespace App\Repositories;

use App\Models\Branch;
use App\Repositories\BaseRepository;
use App\Traits\UploaderTrait;
use App\Models\Media;

/**
 * Class BranchRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method BranchRepository findWithoutFail($id, $columns = ['*'])
 * @method BranchRepository find($id, $columns = ['*'])
 * @method BranchRepository first($columns = ['*'])
 */
class BranchApiRepository extends BaseRepository
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
        return Branch::class;
    }

    /**
     * Create a  Branch
     *
     * @param Request $request
     *
     * @return Branch
     */
    public function createBranch($request)
    {
        $input = collect($request->all());
        $branch = Branch::create($input->only($request->fillable('branchs'))->all());
        // $image = '';
        // if (!empty($input['Branch_banner'])) {
        //     $image = $branch->id . '.' . $request->Branch_banner->getClientOriginalExtension();
        //     request()->Branch_banner->move('web_assets/images/Branchs', $image);
        // }
        // $branch->update(['Branch_banner' => $image]);
        $this->uploadFile($request,$branch);

        return $branch;
    }

    /**
     * Update the Branch
     *
     * @param Request $request
     *
     * @return Branch
     */

    public function updateBranch($id, $request)
    {

        $input = collect($request->all());
        $branch = Branch::findOrFail($id);
        $branch->update($input->only($request->fillable('branchs'))->all());
        if ($request->has('file')) {
            if (isset($branch->media)) {
                echo "sdsd";
                $storageName  = $branch->media->file_name;
                $this->deleteFile('branch/' . $storageName);
                // remove from the database
                $branch->media->delete();
            }
        }
        $this->uploadFile($request,$branch);
        return $branch;
    }

    public function uploadFile($request, $item)
    {
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];
        if ($request->has('file')) {
            if (!empty($request->file)) {
                    $extension = strtolower($request->file->getClientOriginalExtension());
                    $check = in_array($extension, $allowedfileExtension);
                    if ($check) {
                        $photo = $this->storeFileMultipart($request->file, 'branch');
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
