<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'order' => 'required',
            'user_id' => 'required',
            'name' => 'required',
            'phone' => ['required', 'regex:/^(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})$/', 'min:10'],
            'address' => 'nullable',
            'note' => 'nullable',
            'delivery_address_type' => 'nullable',
            'delivery_address' => 'nullable',
            'total_price_pay' => 'nullable|integer'
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Số điện thoại không đúng định dạng!',
            'phone.min' => 'Số điện thoại không đúng định dạng!',
            'total_price_pay.integer' => 'Định dạng dữ liệu của tổng tiền thanh toán không hợp lệ'
        ];
    }
}
