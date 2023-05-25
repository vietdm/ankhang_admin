@extends('layout')
@php
    $userRole = array_reduce($users->toArray(), function($result, $u) {
        $result[$u['id']] = $u['role'];
        return $result;
    }, []);
@endphp
@section('content')
    <div class="block">
        <div class="block-header alert-primary">
            <h3 class="block-title font-weight-bold">Cài đặt quyền người dùng</h3>
        </div>
        <div class="block-content pb-3">
            <div class="form-group">
                <label for="user_id_change_role" class="font-weight-bold">Người cần cập nhật quyền</label>
                <select name="user_id_change_role" id="user_id_change_role" class="form-control">
                    <option value="">Chọn người cần cập nhật quyền</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->fullname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="dropdown-divider"></div>
            <p class="mb-2 font-weight-bold">Chọn quyền cho người dùng</p>
            @foreach ($roles as $role)
                <label class="css-control css-control-sm css-control-primary css-switch w-100 ml-0 py-2">
                    <input type="checkbox" class="css-control-input input-select-role" value="{{ $role->code }}" disabled>
                    <span class="css-control-indicator"></span>
                    <span class="ml-1 user-select-none">{{ $role->name }}</span>
                </label>
            @endforeach
            <div class="dropdown-divider"></div>
            <div class="mt-3">
                <button class="btn btn-primary btn-update-role">Cập nhật quyền</button>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>window.UserRoleList = {!! json_encode($userRole) !!}</script>
    <script src='{{ asset('assets/js/settings.js?i=' . time()) }}' type="module"></script>
@endsection
