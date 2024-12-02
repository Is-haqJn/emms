 <div class="form-group col-md-12">
     <div class="form-check mb-2">
         <label for="permissions[]"> @lang('equicare.permissions') </label>

     </div>
     <div class="form-check">
         <input type="checkbox" name="check_all" id="check_all" />
         <label for="check_all"> @lang('equicare.check_all')</label>
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
                                         <input type="checkbox" name="permissions[]" data-type ="{{ $module }}"
                                             id="role_permissions_view_{{ 'View_' . $module }}"
                                             value="View {{ $module }}"
                                             @if (in_array('View ' . $module, $data->permissions->pluck('name')->toArray())) checked @endif>
                                     @else
                                         -
                                     @endif
                                 </td>
                                 <td>
                                     @if (in_array('Create ' . $module, $permission_array))
                                         <input type="checkbox" name="permissions[]" data-type ="{{ $module }}"
                                             id="role_permissions_create_{{ 'Create_' . $module }}"
                                             value="Create {{ $module }}"
                                             @if (in_array('Create ' . $module, $data->permissions->pluck('name')->toArray())) checked @endif>
                                     @else
                                         -
                                     @endif
                                 </td>
                                 <td>
                                     @if (in_array('Edit ' . $module, $permission_array))
                                         <input type="checkbox" name="permissions[]" data-type ="{{ $module }}"
                                             id="role_permissions_edit_{{ 'Edit_' . $module }}"
                                             value="Edit {{ $module }}"
                                             @if (in_array('Edit ' . $module, $data->permissions->pluck('name')->toArray())) checked @endif>
                                     @else
                                         -
                                     @endif
                                 </td>
                                 <td>
                                     @if (in_array('Delete ' . $module, $permission_array))
                                         <input type="checkbox" name="permissions[]" data-type ="{{ $module }}"
                                             id="role_permissions_delete_{{ 'Delete_' . $module }}"
                                             value="Delete {{ $module }}"
                                             @if (in_array('Delete ' . $module, $data->permissions->pluck('name')->toArray())) checked @endif>
                                     @else
                                         -
                                     @endif
                                 </td>
                                 <td>
                                     <input class="select_module" data-type ="{{ $module }}" type="checkbox"
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
