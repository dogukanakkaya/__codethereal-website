@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ce/datatable.css') }}">
@endpush

<table class="table table-striped table-hover" id="ce-datatable"></table>

@push('scripts')
    <script>
        let dt;
        $(function () {
            dt = $('#ce-datatable').DataTable({
                dom: '<"dt-top"lf><t><"dt-bottom"ip>',
                processing: true,
                serverSide: true,
                ajax: '{{ $url }}',
                columns: @json($columns)
            });
        })
        function __refresh(){
            dt.ajax.reload()
        }
    </script>
@endpush
