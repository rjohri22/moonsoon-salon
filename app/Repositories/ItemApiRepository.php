<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\Media;
use App\Repositories\BaseRepository;
use App\Traits\UploaderTrait;

/**
 * Class ItemRepository
 * @package App\Repositories
 * @version November 6, 2018, 9:09 am UTC
 *
 * @method ItemRepository findWithoutFail($id, $columns = ['*'])
 * @method ItemRepository find($id, $columns = ['*'])
 * @method ItemRepository first($columns = ['*'])
 */
class ItemApiRepository extends BaseRepository
{
    /**
     * @var array
     */
    use UploaderTrait;
    protected $fieldSearchable = [
        /* 'module_id',
        'user_id',
        'shop_id', */
        'brand_id',
        'item_category_id',
        'item_sub_category_id',
        'product_category_id',
        'name'=>"like",
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
        return Item::class;
    }

    /**
     * Create a  Item
     *
     * @param Request $request
     *
     * @return Item
     */
    public function createItem($request)
    {
        $input = collect($request->all());
        $item = Item::create($input->only($request->fillable('items'))->all());
        /* $variants= json_decode($input['variants']);
        // return $variants[0]->qty;
        if(!empty($variants))
        {
            foreach($variants as $key => $variant){
               $itemVariantData = [
                    'item_id' => $item->id,
                    'qty' => $variant->qty,
                    'unit_value' => $variant->unit_value,
                    'price' => $variant->price,
                    'sale_price' => $variant->sale_price,
                    'unit_id' => $variant->unit_id,
                    'discount_type' => $variant->discount_type,
                    'discount_amount' => $variant->discount_amount,
                    'default' => $variant->default ? 1 : 0,
                ];
                $itemVariants = $item->itemVariants()->updateOrCreate($itemVariantData);

            }
        } */
        return $item;
    }

    /**
     * Update the Item
     *
     * @param Request $request
     *
     * @return Item
     */

    public function updateItem($id, $request)
    {

        $input = collect($request->all());
        $item = Item::findOrFail($id);
        $item->update($input->only($request->fillable('items'))->all());
        /* $itemVariantId=[];
        $variants= json_decode($input['variants']);
        if(!empty($variants))
        {
            foreach($variants as $key => $variant){
                $itemVariants=[
                    'item_id' => $item->id,
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
                    $itemVariantId[]=$variant->id;
                    $item->itemVariants()->updateOrCreate(['id'=>!empty($variant->id) ? $variant->id : null],$itemVariants);
                }else
                {
                    $itemVariants = $item->itemVariants()->updateOrCreate($itemVariants);
                    $itemVariantId[]=$itemVariants->id;
                }
            }

        }*/
        return $item;
    }

    public function uploadFile($request, $item)
    {
        $allowedfileExtension = ['pdf', 'jpg', 'png', 'jpeg'];

        //return $request->file;
        if ($request->has('file')) {
            if (!empty($request->file)) {
                foreach ($request->file as $file) {
                    $extension = strtolower($file->getClientOriginalExtension());
                    $check = in_array($extension, $allowedfileExtension);
                    if ($check) {
                        $photo = $this->storeFileMultipart($file, 'item');
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
                        // $resizeImage = $this->imageResize($media->file_name, 800, 800);
                        // $photo = $this->moveFileDirectory('temp/'.$media->file_name , 'item/'.$resizeImage);
                    }
                }
            }
        }
        // return true;
    }
}
