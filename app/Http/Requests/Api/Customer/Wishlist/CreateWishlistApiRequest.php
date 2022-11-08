<?php

namespace App\Http\Requests\Api\Customer\Wishlist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateWishlistApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            /* 
            'item_id'   => 'required|numeric',
            'user_id'     => 'required|numeric',
            'discount'     => 'required|numeric',
            'discount_type'     => 'required|string',
            'rate'     => 'required|numeric',*/
        ];
    }

    public function fillable($key)
    {
        /* $attributes = [
            'wishlists' => [
                'shop_id',
                'item_id',
                'user_id',
                'discount',
                'discount_type',
                'rate',
            ]
        ];
        return $attributes[$key]; */
    }

    public function validationData()
    {
        return $this->all();
    }

    protected function getValidatorInstance()
    {
        $input  = $this->all();
        if (empty($input)) {
            $input = (array) (json_decode($this->getContent()));
        }
        $this->getInputSource()->replace($input);
        return parent::getValidatorInstance();
    }

    public function messages()
    {
        return [];
    }

    protected function failedValidation(Validator $validator)
    {

        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json([
            'status' => false, 'message' => (string) json_encode($errors), 'extra' => 'validation errors'
        ], 200));
    }
}
