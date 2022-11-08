<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\Offer;
use App\Repositories\BaseRepository;
use App\Traits\UploaderTrait;

/**
 * Class OfferRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method OfferRepository findWithoutFail($id, $columns = ['*'])
 * @method OfferRepository find($id, $columns = ['*'])
 * @method OfferRepository first($columns = ['*'])
 */
class OfferApiRepository extends BaseRepository
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
        return Offer::class;
    }

    /**
     * Create a  Offer
     *
     * @param Request $request
     *
     * @return Offer
     */
    public function createOffer($request)
    {
        $input = collect($request->all());
        //return $input;
        $input['is_slider'] = !empty($input['is_slider'] ? 1  : 0);
        $input['days'] = json_encode($input['days']);
        $offer = Offer::create($input->only($request->fillable('Offers'))->all());
        /* $image = '';
        if (!empty($input['Offer_banner'])) {
            $image = $offer->id . '.' . $request->Offer_banner->getClientOriginalExtension();
            request()->Offer_banner->move('web_assets/images/Offers', $image);
        }
        $offer->update(['Offer_banner' => $image]); */
        $this->uploadFile($request, $offer);
        return $offer;
    }

    /**
     * Update the Offer
     *
     * @param Request $request
     *
     * @return Offer
     */

    public function updateOffer($id, $request)
    {
        $input = collect($request->all());
        $input['is_slider'] = !empty($input['is_slider'] ? 1  : 0);
        $offer = Offer::findOrFail($id);
        $offer->update($input->only($request->fillable('Offers'))->all());
        if (isset($offer->media)) {
            $storageName  = $offer->media->file_name;
            $this->deleteFile('offer/' . $storageName);
            // remove from the database
            $offer->media->delete();
        }
        $this->uploadFile($request, $offer);
        return $offer;
    }

    public function uploadFile($request, $item)
    {
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];
        if ($request->has('file')) {
            if (!empty($request->file)) {
                $extension = strtolower($request->file->getClientOriginalExtension());
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $photo = $this->storeFileMultipart($request->file, 'offer');
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
