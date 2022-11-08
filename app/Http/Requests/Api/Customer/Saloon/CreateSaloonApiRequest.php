<?php

namespace App\Http\Requests\Api\Customer\Saloon;

use Illuminate\Foundation\Http\FormRequest;
use InfyOm\Generator\Request\APIRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CreateSaloonApiRequest extends FormRequest
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
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|string|max:255',
            'mobile'        => 'required|string|max:255',
            'whatsapp_no'   => 'string|max:20',
            'address'       => 'max:255',
            'city'          => 'required|string|max:100',
            'state'         => 'required|string|max:100',
            'zipcode'       => 'required|numeric',
            'shop_name'     => 'required|max:60'
        ];
    }
    /** * Get fillable key for the attributes.
     *
     * @return array
     */
    public function fillable($key)
    {   // dd($key);
        $attributes = [
            'saloon' => [
                'user_id',
                'shop_name',
                'whatsapp_no',
                'address',
                'description',
                'city',
                'state',
                'country',
                'zipcode',
                'lat',
                'lng',
                'status',
            ],
            'user' => [
                'first_name',
                'last_name',
                'email',
                'mobile',
                'gender',
                'password'
            ]
        ];
        return $attributes[$key];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function validationData()
    {
        return $this->all();
    }

    /**
     * Get the validator instance for the request.
     *
     * @return Validator
     */
    protected function getValidatorInstance()
    {
        /*$input  = $this->all();
        $this->getInputSource()->replace($input);
        return parent::getValidatorInstance();*/
        $input  = $this->all();
        $input['user_id'] = \Helper::getUserId();
        if (empty($input)) {
            $input = (array) (json_decode($this->getContent()));
        }
        $this->getInputSource()->replace($input);
        return parent::getValidatorInstance();
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Throw exception from
     *
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {

        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json([
            'status' => 201, 'message' => (string) json_encode($errors), 'extra' => 'validation errors'
        ], 201));
    }
}
