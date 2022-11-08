<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\User;
use App\Models\UserDetail;
use App\Repositories\BaseRepository;
use App\Traits\UploaderTrait;

/**
 * Class UserApiRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method UserApiRepository findWithoutFail($id, $columns = ['*'])
 * @method UserApiRepository find($id, $columns = ['*'])
 * @method UserApiRepository first($columns = ['*'])
 */
class UserDetailApiRepository extends BaseRepository
{
    use UploaderTrait;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
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
        return UserDetail::class;
    }

    /**
     * Create a  User
     *
     * @param Request $request
     *
     * @return UserDetail
     */
    public function saveUserDetail($request)
    {
        $input = collect($request->all());
        $input['user_id'] = \Helper::getUserId();
        $UserDetail = UserDetail::whereUserId(\Helper::getUserId())->first();
        if (empty($UserDetail)) {
            $UserDetail = UserDetail::create($input->only($request->fillable('profile'))->all());
        } else {
            $UserDetail->update($input->only($request->fillable('profile'))->all());
        }

        $User = User::find(\Helper::getUserId());
        if (isset($User->media)) {
            $storageName  = $User->media->file_name;
            $this->deleteFile('user/' . $storageName);
            // remove from the database
            $User->media->delete();
        }
        $User->update($input->only($request->fillable('user'))->all());
        $this->uploadFile($request, $User);
        return $UserDetail;
    }
    public function saveUserDetailApi($request)
    {
        $input = collect($request->all());
        $input['user_id'] = \Helper::getUserId();
        $UserDetail = UserDetail::whereUserId(\Helper::getUserId())->first();
        if (empty($UserDetail)) {
            $UserDetail = UserDetail::create($input->only($request->fillable('profile'))->all());
        } else {
            $UserDetail->update($input->only($request->fillable('profile'))->all());
        }

        $User = User::find(\Helper::getUserId());
        if (isset($User->media)) {
            $storageName  = $User->media->file_name;
            $this->deleteFile('user/' . $storageName);
            // remove from the database
            $User->media->delete();
        }
        $User->update($input->only($request->fillable('user'))->all());
        $this->uploadFileApi($request, $User);
        return $UserDetail;
    }

    /**
     * Update the User
     *
     * @param Request $request
     *
     * @return UserDetail
     */

    public function updateUserDetail($id, $request)
    {

        $input = collect($request->all());
        $input['user_id'] = \Helper::getUserId();
        $User = UserDetail::findOrFail($id);
        $User->update($input->only($request->fillable('user'))->all());

        return $User;
    }

    public function uploadFile($request, $item)
    {
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];
        if ($request->has('file')) {
            if (!empty($request->file)) {
                $extension = strtolower($request->file->getClientOriginalExtension());
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $photo = $this->storeFileMultipart($request->file, 'user');
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

    public function uploadFileApi($request, $item)
    {
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];
        if ($request->has('file')) {
            if (!empty($request->file)) {
                $extension = strtolower($request->file->getClientOriginalExtension());
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $photo = $this->base64FileUpload($request->file, 'user');
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
