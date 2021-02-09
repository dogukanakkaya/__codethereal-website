@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <x-breadcrumb :nav="$navigations"/>
    </div>
    <div id="list" class="list-area p-4">
        <div class="row">
            @foreach($commands as $command)
            <div class="col-md-2 text-center">
                <button type="button" class="btn btn-dark" onclick="__cli('{{ $command->command }}')" data-command="{{ $command->command }}">{{ $command->title }}</button>
            </div>
            @endforeach
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        const __cli = command => {
            request.post('{{ route('cli.run') }}', {command})
                .then(response => makeToast(response.data))
        }
    </script>
@endpush
