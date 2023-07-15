@extends('layout')
@section('content')
    @yield('content.event')
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        jQuery(".js-dataTable-full").dataTable({
            columnDefs: [{
                orderable: false,
                targets: 'no-sort'
            }],
            pageLength: 15,
            lengthMenu: [
                [5, 15, 30, 50],
                [5, 15, 30, 50]
            ],
            autoWidth: !1,
            responsive: true
        })
    </script>
    @yield('script.event')
@endsection
