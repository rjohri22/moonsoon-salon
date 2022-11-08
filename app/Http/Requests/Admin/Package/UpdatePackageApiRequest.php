<?php

namespace App\Http\Requests\Admin\Package;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePackageApiRequest extends FormRequest
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
            'name'     => 'required|string',
            'price' => 'required',
            'discount' => 'nullable',
            'discount_type' => 'nullable|string',
            'packages_type' => 'required|string',
            'description'     => 'nullable|string',
            /* 'description'     => 'required|string', */
        ];
    }

    public function fillable($key)
    {
        $attributes = [
            'packages' => [
                'name',
                'price',
                'discount',
                'status',
                'discount_type',
                'packages_type',
                'description',
            ],
            'packagesDetails' => [
                'table_id',
                'package_id',
                'table_type',
                'status'
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
