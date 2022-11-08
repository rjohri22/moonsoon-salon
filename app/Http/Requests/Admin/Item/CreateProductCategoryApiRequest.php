<?php

namespace App\Http\Requests\Admin\Item;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateProductCategoryApiRequest extends FormRequest
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
            /* 'user_id'     => 'required|numeric',
            'shop_id'     => 'required|numeric', */
            'item_sub_category_id'   => 'required|string',
            'name'   => 'required|string',
            /* 'description' => 'required|string', */
        ];
    }

    public function fillable($key)
    {
        $attributes = [
            'ProductCategorys' => [
                /* 'user_id',
                'shop_id', */
                'item_sub_category_id',
                'name',
                'description',
            ]
        ];
        return $attributes[$key];
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
            'status' => 201, 'message' => (string) json_encode($errors), 'extra' => 'validation errors'
        ], 201));
    }
}
