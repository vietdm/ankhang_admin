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
            'address' => 'required',
            'note' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Số điện thoại không đúng định dạng!',
            'phone.min' => 'Số điện thoại không đúng định dạng!'
        ];
    }
}
