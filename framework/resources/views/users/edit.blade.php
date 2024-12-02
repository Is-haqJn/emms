@extends('layouts.admin')
@section('body-title')
    @lang('equicare.users')
@endsection
@section('title')
    | @lang('equicare.users')
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('admin/users') }}">@lang('equicare.users')</a></li>
    <li class="breadcrumb-item active">@lang('equicare.edit')</li>
@endsection
@section('styles')
    <style>
        .ml-2 {
            margin-left: 10px;
        }

        .mb-2 {
            margin-bottom: 10px;
        }
        .display-flex{
            display:flex;
            justify-content:space-between;
            align-item:center;
        }
        .display-flex-check-all{
            display:flex;
            gap:10px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-title">@lang('equicare.edit_user')</h4>
                </div>
                <div class="box-body ">
                    @include ('errors.list')
                    <form class="form" method="post" action="{{ route('users.update', $user->id) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PATCH" />
                        <input type="hidden" id="select_all" name="select_all" value="{{ $user->select_all }}" />
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name"> @lang('equicare.name') </label>
                                <input type="text" name="name" value="{{ $user->name }}" class="form-control" />
                                <input type="hidden" name="id" value="{{ $user->id }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email"> @lang('equicare.email') </label>
                                <input type="email" name="email" value="{{ $user->email }}" class="form-control" />
                            </div>


                            <div class="form-group col-md-6">
                                <label for="role"> @lang('equicare.role') </label>
                                <select name="role" class="form-control select2_role">
                                    <option value=""> </option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}</option>
                                        }
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password"> @lang('equicare.password') </label>
                                <input type="password" value="" name="password" class="form-control col-md-6" />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="hospitals"> @lang('equicare.hospitals')
                                    @if ($user->select_all == 1)
                                        <span class="select_all_msg">(Select All option automatically assigns a new hospital
                                            to a user.)</span>
                                    @else
                                        <span class="select_all_msg"></span>
                                    @endif
                                </label>

                                <select class="select2_hospital form-control" multiple name="hospitals[]">
                                    <option value="selectAll">
                                        Select All
                                    </option>
                                    @foreach ($hospitals as $hospital)
                                        <option value="{{ $hospital->id }}"
                                            @if (in_array((int) $hospital->id, $selectedHospitalIds)) selected @endif>
                                            {{ $hospital->name ?? '' }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            @if (isset($permissions) && $permissions->count() > 0)
                               <div class="old_permission_table">
                                        @include('user_role_permission.table_edit_user',['data'=>$user])
                                </div>
                            @endif  
                            <br />
                            <div class="form-group col-md-12">
                                <input type="submit" value="@lang('equicare.submit')" class="btn btn-primary btn-flat" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.select2_hospital').select2({
            allowClear: true,
            placeholder: "@lang('equicare.select_hospital')"
        });
        $('.select2_role').select2({
            allowClear: true,
            placeholder: "@lang('equicare.select_role')"
        });
        let select2_role_ajax = true;
       $(document).on('click','.old_permission',function(){
        console.log('test');
            $.ajax({
                type: 'POST',
                url: "{{ route('user_permissions') }}",
                data: {
                    user_id: '{{$user->id}}',
                    _token: "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('.color-red').iCheck('destroy');
                    select2_role_ajax = false;
                    setTimeout(function() {
                        $('input[type="checkbox"]').iCheck({
                          checkboxClass: 'icheckbox_square-blue',
                                 radioClass: 'iradio_minimal-blue',
                        });
                        $('.select2_role').val("{{$user->role_id}}").trigger('change');
                        $('.color-red').iCheck({
                            checkboxClass: 'icheckbox_square-red',
                            radioClass: 'iradio_minimal-red',
                            // increaseArea: '20%' /* optional */
                        });
                    }, 200);
                    $('.old_permission_table').html(data.view);
                    
                },
                error: function() {
                    alert('Error Something went wrong');
                },
            })
       });
        $('.select2_role').on('change', function() {
            console.log(select2_role_ajax);
            if(select2_role_ajax){
            $.ajax({
                type: 'POST',
                url: "{{ route('role_permissions') }}",
                data: {
                    role_id: $('.select2_role').val(),
                    _token: "{{ csrf_token() }}",
                },
                success: function(data) {
                    // Uncheck all checkboxes first
                    $('.moduel_checkbox input[type="checkbox"]').iCheck('uncheck');
                    // Check only the checkboxes specified in data.role_permissions
                    data.forEach(function(permission) {
                        permission = permission.replace(/ /g, '_');
                        $('#role_permissions_view_' + permission).iCheck('check');
                        $('#role_permissions_create_' + permission).iCheck('check');
                        $('#role_permissions_edit_' + permission).iCheck('check');
                        $('#role_permissions_delete_' + permission).iCheck('check');
                    });
                    // Destroy and recreate iCheck for checkboxes with class .color-red
                    $('.moduel_checkbox input[type="checkbox"]').iCheck('destroy');
                    $('.moduel_checkbox input[type="checkbox"]').iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_minimal-blue',
                        // increaseArea: '20%' /* optional */
                    });
                },
                error: function() {
                    alert('Error Something went wrong');
                },
            })
        }
            select2_role_ajax = true;
       
        });

        $('.select2_hospital').on('select2:select', function() {
            if ($(this).val() !== null && $(this).val().includes('selectAll')) {
                // Select all options
                $('#select_all').val(1);
                $('.select_all_msg').text('(Select All option automatically assigns a new hospital to a user.)');
                $(this).val($(this).find('option').not(':first').map(function() {
                    return this.value;
                })).trigger('change');
            }
        });
        $('.select2_hospital').on('select2:unselect', function() {
            $('#select_all').val(0);
            $('.select_all_msg').text('');
        });

       
    </script>
@endsection
