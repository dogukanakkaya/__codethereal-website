@foreach($permissionGroups as $permissionGroup)
    <div class="permission-group-checks">
        @foreach($permissionGroup as $permission)
            <div class="form-check form-check-inline">
                {{ Form::checkbox('role_permissions[]', $permission->name, false, ['class' => 'form-check-input', 'id' => 'role_permission_' . $permission->id]) }}
                {{ Form::label('role_permission_' . $permission->id, $permission->title, ['class' => 'form-check-label']) }}
            </div>
        @endforeach
    </div>
@endforeach
