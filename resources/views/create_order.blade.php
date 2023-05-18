@extends('layout')
@section('content')
    <div class="row gutters-tiny invisible" data-toggle="appear">
        <div class="col-12">
            <div class="alert alert-secondary">
                <h3 class="mb-0 text-primary">Tạo đơn thủ công</h3>
            </div>
        </div>
        <div class="col-md-6 col-12 m-auto">
            <div class="form-group">
                <div class="form-material floating">
                    <input type="text" class="form-control" id="username" name="username" autocomplete="off">
                    <label for="username">Mã khách hàng</label>
                </div>
            </div>
            <div class="form-group">
                <div class="form-material floating">
                    <select class="form-control" id="product_id" name="product_id">
                        <option></option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->title }}</option>
                        @endforeach
                    </select>
                    <label for="product_id">Chọn sản phẩm</label>
                </div>
            </div>
            <div class="form-group">
                <div class="form-material floating">
                    <input type="number" class="form-control" id="quantity" name="quantity" autocomplete="off">
                    <label for="quantity">Số lượng</label>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-alt-primary btn-create-order">Submit</button>
            </div>
        </div>
    </div>
@endsection
@section('script')
   <script src="{{ asset('assets/js/create_order.js') }}" type="module"></script>
@endsection
