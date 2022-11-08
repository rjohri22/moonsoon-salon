<?php

namespace App\Http\Requests\Admin\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateServiceApiRequest extends FormRequest
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
            'item_category_id'   => 'required|numeric',
            'item_sub_category_id'     => 'required|numeric',
            'name'     => 'required|string',
            'price'     => 'required',
            'service_time'     => 'required',
            'price' => 'required|numeric',
            'discount_amount' => 'required|numeric',
            /* 'description'     => 'required|string', */
        ];
    }

    public function fillable($key)
    {
        $attributes = [
            'services' => [
                'item_category_id',
                'item_sub_category_id',
                'name',
                'price',
                'service_time',
                'discount_amount',
                'discount_type',
                'description',
                'how_to_use',
                'benefits',
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
        // $input['slug'] = \Str::slug($input['name'], '_');
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
