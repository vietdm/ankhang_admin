<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'regex:/^[A-Za-z][A-Za-z0-9]{5,31}$/'],
            'email' => ['required', 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/'],
            'cccd' => ['required', 'regex:/^\d{9}(\d{3})?$/'],
            'phone' => ['required', 'regex:/^(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})$/', 'min:10'],
            'fullname' => 'required',
            'password' => 'required',
            'present_phone' => ['required', 'regex:/^(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})$/', 'min:10'],
        ];
    }

    public function messages()
    {
        return [
            'username.regex' => 'Username phải viết liền và chỉ bao gồm số, chữ thường, chữ hoa!',
            'phone.regex' => 'Số điện thoại không đúng định dạng!',
            'phone.min' => 'Số điện thoại không đúng định dạng!',
            'present_phone.regex' => 'Số điện thoại người giới thiệu không đúng định dạng!',
            'present_phone.min' => 'Số điện thoại người giới thiệu không đúng định dạng!',
            'email.regex' => 'Email không hợp lệ!',
            'cccd.regex' => 'Số CCCD phải là số có 9 hoặc 12 ký tự!',
        ];
    }
}
