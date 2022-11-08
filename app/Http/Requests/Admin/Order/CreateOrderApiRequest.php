<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateOrderApiRequest extends FormRequest
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
            'txn_id'     => 'nullable',
            'txn_status'     => 'nullable',
            'payment_mode'     => 'nullable|string',
            'delivery_address_id'     => 'required|numeric'
        ];
    }

    public function fillable($key)
    {
        $attributes = [
            'orders' => [
                'user_id',
                'order_no',
                'txn_id',
                'payment_mode',
                'delivery_address',
                'sub_total',
                'status',
                'discount_amount',
                'delivery_charge',
                'cgst_amount',
                'sgst_amount',
                'igst_amount',
                'total_amount',
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
            'status' => false, 'message' => (string) json_encode($errors), 'extra' => 'validation errors'
        ], 200));
    }
}
