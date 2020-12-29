@extends('admin.layouts.base')

@section('content')
    <x-breadcrumb :nav="$navigations"/>
    <div class="profile">
        <div class="row">
            <div class="col-md-2 col-xs-12">
                <div class="nav flex-column nav-pills border-active-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link right active" data-toggle="pill" href="#profile" role="tab"
                       aria-selected="true">{{ __('users.profile') }}</a>
                    <a class="nav-link right" data-toggle="pill" href="#notes" role="tab"
                       aria-selected="false">{{ __('users.notes') }}</a>
                    <a class="nav-link right" data-toggle="pill" href="#calendar" role="tab"
                       aria-selected="false">{{ __('users.calendar') }}</a>
                </div>
            </div>
            <div class="col-md-10 col-xs-12">
                <div class="list-area p-4">
                    {{ Form::open(['id' => 'profile-form']) }}
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                            @include('admin.partials.description', ['text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, itaque!'])
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        {{ Form::label('name', __('users.fullname'), ['class' => 'required']) }}
                                        {{ Form::text('name', $user->name, ['class' => 'form-control', 'required' => true]) }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        {{ Form::label('email', __('users.email'), ['class' => 'required']) }}
                                        {{ Form::email('email', $user->email, ['class' => 'form-control', 'required' => true, 'readonly' => true]) }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        {{ Form::label('position', __('users.position')) }}
                                        {{ Form::text('position', $user->position, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        {{ Form::label('password', __('users.password')) }}
                                        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => '********', 'readonly' => true]) }}
                                        <a href="{{ route('profile.reset_password') }}"><small>{{ __('passwords.reset_password') }}</small></a>

                                        @if($errors->any())
                                            <div class="alert alert-danger mt-2 mb-2">{{ $errors->first() }}</div>
                                        @elseif(Session::has('status'))
                                            <div
                                                class="alert alert-success mt-2 mb-2">{{ Session::get('status') }}</div>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        {{ Form::label('about', __('users.about')) }}
                                        {{ Form::textarea('about', $user->about, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        {{ Form::label(__('users.photo')) }}
                                        <x-dropzone :file-id="$user->image" folder="user-profiles" input-name="image"
                                                    max-files="1"/>
                                    </div>
                                </div>
                                <div class="col-12 text-right">
                                    {{ Form::save() }}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="notes" role="tabpanel">
                            <p>{{ __('global.coming_soon', ['name' => __('global.notes')]) }}</p>
                        </div>
                        <div class="tab-pane fade" id="calendar" role="tabpanel">
                            <p>{{ __('global.coming_soon', ['name' => __('global.calendar')]) }}</p>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const form = document.getElementById('profile-form')
        form.addEventListener('submit', (e) => {
            e.preventDefault()
            toggleBtnLoading()
            const formData = serialize(form, {hash: true})
            request.put('{{ route('profile.update') }}', formData)
                .then(response => {
                    toggleBtnLoading()
                    makeToast(response.data)
                })
        })
    </script>
@endpush
