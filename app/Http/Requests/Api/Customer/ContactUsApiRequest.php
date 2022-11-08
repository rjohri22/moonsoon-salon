<?php

namespace App\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;
use Str;

class ContactUsApiRequest extends FormRequest
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
            "title" => "required",
            "description" => "required",
        ];
    }
    public function fillable($key)
    {
        $attributes = [
            'contact_us' => [
                'title',
                'description',
                'email',
                'mobile',
                'name',
                /* 'user_type',
                'age',
                'registered_from',
                'username',
                'password'*/
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
        $input = $this->all();
        // $input['name'] = $input['name'];
        //$input['age'] = $input['age'] ?? '';
        // $input['registered_from'] = $input['registered_from'] ?? 'app';
        //$input['password'] = Hash::make($input['password']);
        /* if ($input['user_type'] != 'user') {
            $input['username'] = Str::slug($input['username'], "_");
        } else {
            $input['username'] = '';
        } */

        $this->getInputSource()->replace($input);

        return parent::getValidatorInstance();
    }
    public function messages()
    {
        return [];
    }
    public function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $keys = array_keys($errors);
        $message = $errors[$keys[0]];
        throw new HttpResponseException(response()->json(['status' => 201, 'message' => /* (string)json_encode */ implode(" ", $errors[$keys[0]]), 'extra' => 'validation errors'], 201));
    }
}
