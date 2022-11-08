<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use InfyOm\Generator\Request\APIRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserProfileRequest extends FormRequest
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
            'first_name'     => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'     => 'required|string|max:255',
            'username'   => 'required|string|max:255'
        ];
    }
    /** * Get fillable key for the attributes.
     *
     * @return array
     */
    public function fillable($key)
    {   // dd($key);
        $attributes = [
            'users' => [
                'name',
                'username',
                'email'
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
