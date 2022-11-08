<?php

namespace App\Http\Requests\Api\Customer\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserDetaiApiRequest extends FormRequest
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
        return [];
    }

    public function fillable($key)
    {
        $attributes = [
            'profile' => [
                'user_id',
                'marital_status',
                'dob',
                'anniversary',
                'hair_length',
                'hair_type',
                'skin_type',
                'allergies'
            ],
            'user' => [
                'first_name',
                'last_name',
                'email',
                'mobile',
                'gender',
                'profile_photo_path'
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
        $input['dob'] = !empty($input['dob']) ? date($input['dob']) : NULL;
        $input['anniversary'] = !empty($input['anniversary']) ? date($input['anniversary']) : NULL;
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
