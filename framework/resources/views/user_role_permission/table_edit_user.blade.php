 <div class="form-group col-md-12">
                                    <div class="form-check mb-2">
                                        <label for="permissions[]"> @lang('equicare.permissions') </label>
                                        <span class="ml-2  ">
                                            <input type="checkbox" class="no-check"  checked ="checked"   /> @lang('equicare.role_permissions')
                                            <input type="checkbox" class="color-red no-check" checked ="checked"   /> @lang('equicare.user_permissions')
                                        </span>
                                    </div>
                                    <div class="form-check">
                                        <div class="display-flex">
                                            <div class="display-flex-check-all">
                                                <label for="check_all"> @lang('equicare.check_all')</label>
                                                <input type="checkbox" name="check_all" id="check_all" />
                                            </div>  
                                            <span class="badge old_permission" style="cursor:pointer;">@lang('equicare.old_permission')</span>
                                        </div>
                                        {{-- <br /> --}}
                                        <br />
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('equicare.module_name')</th>
                                                            <th>@lang('equicare.view')</th>
                                                            <th>@lang('equicare.create')</th>
                                                            <th>@lang('equicare.edit')</th>
                                                            <th>@lang('equicare.delete')</th>
                                                            <th>@lang('equicare.select_all')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($module_names as $module)
                                                            <tr class="moduel_checkbox">
                                                                <td>{{ $module }}</td>
                                                                <td>
                                                                    @if (in_array('View ' . $module, $permission_array))
                                                                        <input type="checkbox" name="permissions[]"
                                                                            data-type ="{{ $module }}"
                                                                            id="role_permissions_view_{{'View_'. str_replace(' ','_',$module) }}"
                                                                            value="View {{ $module }}"
                                                                            @if (in_array('View ' . $module,$data->getDirectPermissions()->pluck('name')->toArray())) checked
                                                                            @if (!in_array('View ' . $module, $data->getPermissionsViaRoles()->pluck('name')->toArray()))
                                                                                class="color-red" @endif
                                                                            @endif>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if (in_array('Create ' . $module, $permission_array))
                                                                        <input type="checkbox" name="permissions[]"
                                                                            data-type ="{{ $module }}"
                                                                            id="role_permissions_create_{{'Create_'. str_replace(' ','_',$module) }}"
                                                                            value="Create {{ $module }}"
                                                                            @if (in_array('Create ' . $module,$data->getDirectPermissions()->pluck('name')->toArray())) checked
                                                                            @if (!in_array('Create ' . $module, $data->getPermissionsViaRoles()->pluck('name')->toArray()))
                                                                                class="color-red" @endif
                                                                            @endif>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if (in_array('Edit ' . $module, $permission_array))
                                                                        <input type="checkbox" name="permissions[]"
                                                                            data-type ="{{ $module }}"
                                                                            id="role_permissions_edit_{{'Edit_'. str_replace(' ','_',$module) }}"
                                                                            value="Edit {{ $module }}"
                                                                            @if (in_array('Edit ' . $module,$data->getDirectPermissions()->pluck('name')->toArray())) checked
                                                                            @if (!in_array('Edit ' . $module, $data->getPermissionsViaRoles()->pluck('name')->toArray()))
                                                                                class="color-red" @endif
                                                                            @endif>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if (in_array('Delete ' . $module, $permission_array))
                                                                        <input type="checkbox" name="permissions[]"
                                                                            data-type ="{{ $module }}"
                                                                            id="role_permissions_delete_{{'Delete_'. str_replace(' ','_',$module) }}"
                                                                            value="Delete {{ $module }}"
                                                                            @if (in_array('Delete ' . $module,$data->getDirectPermissions()->pluck('name')->toArray())) checked
                                                                            @if (!in_array('Delete ' . $module, $data->getPermissionsViaRoles()->pluck('name')->toArray()))
                                                                                class="color-red" @endif
                                                                            @endif>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <input class="select_module"
                                                                        data-type ="{{ $module }}" type="checkbox"
                                                                        name="">
                                                                </td>
                                                            </tr>
                                                        @endforeach


                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>