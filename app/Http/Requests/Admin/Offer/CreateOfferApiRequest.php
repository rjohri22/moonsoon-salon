<?php

namespace App\Http\Requests\Admin\Offer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateOfferApiRequest extends FormRequest
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
            'title'     => 'required',
            'code'   => 'required',
            'date_valid_from'     => 'required',
            'date_valid_to' => 'required',
            'time_valid_from' => 'required',
            'time_valid_to' => 'required',
            /* 'description'     => 'required|string', */
        ];
    }

    public function fillable($key)
    {
        $attributes = [
            'Offers' => [
                'title',
                'code',
                'date_valid_from',
                'date_valid_to',
                'time_valid_from',
                'time_valid_to',
                'days',
                'table_type',
                'table_id',
                'is_slider',
                'amount',
                'amount_type',
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
