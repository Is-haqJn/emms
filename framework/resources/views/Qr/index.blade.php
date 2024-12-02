@extends('layouts.admin')
@section('body-title')
    @lang('equicare.qr_generate')
@endsection
@section('title')
    | @lang('equicare.qr_generate')
@endsection
@section('breadcrumb')
    <li class=" active">@lang('equicare.qr_generate')</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">@lang('equicare.manage_qr_generate')
                        @if(
                            Auth::user()->hasDirectPermission('Create QR'))
                        <a href="{{ route('qr.create') }}" class="btn btn-primary btn-flat">@lang('equicare.generate_qr')</a>
                        @endif
                    </h4>
                </div>
                <div class="box-body table-responsive">
                    <div style="display: flex;justify-content:space-between">
                        <div>
                            <ul class="nav nav-tabs" style="margin-bottom: 10px">
                                <li class="active"><a href="#tab1" data-toggle="tab" class="active">@lang('equicare.qr_not_assign')</a></li>
                                <li><a href="{{ route('qr-assigned') }}">@lang('equicare.qr_assign')</a></li>
                            </ul>
                        </div>
                        <div>
                            <button type="button" class="btn btn-flat btn-primary" data-toggle="modal" data-target="#exampleModal">@lang('equicare.print_sticker')</button>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <table class="table table-bordered table-hover dataTable bottom-padding" id="data_table">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th> # </th>
                                        <th> @lang('equicare.qr') </th>
                                        <th> @lang('equicare.created_on') </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($qr))
                                        @php
                                            $count = 0;
                                        @endphp
                                        @foreach ($qr as $q)
                                            <tr>
                                                <td>{{ ++$count }}</td>
                                                <td><img src="{{ asset('/uploads/qrcodes/qr_assign/' . $q->uid . '.png') }}"
                                                        width="100" loading="lazy" /></td>

                                                <td>{{ date_change($q->created_at) ?? '-' }}</td>
                                               
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th> # </th>
                                        <th> @lang('equicare.qr') </th>
                                        <th> @lang('equicare.created_on') </th>
                                        
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane" id="tab2">

                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap Modal --}}
    <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header" >
              <h5 class="modal-title" style="display: inline-block;">@lang('equicare.qr_generate_settings')</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">@lang('equicare.select_number_of_qr_in_one_row')</label> <br>
                    <select class="select2_qr" style="width: 100%;">
                        <option value="">--select--</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="6">6</option>
                    </select>
               </div>
            <div class="form-group">
                <label class="form-label">@lang('equicare.enter_the_text_below_the_line_you_want_to_show')</label> 
                <input type="text" name="" class="form-control qr_line"/>
            </div>
           
            </div>
            <div class="modal-footer">
            <form action="{{route('qr_sticker','not-assigned')}}" method="get">
              <button type="submit" class="btn btn-primary btn-qr" disabled>@lang('equicare.save')</button>
              <input type="hidden" name="qr_size" class="form-control qr-size"/>
              <input type="hidden" name="qr_line" class="form-control qr_line_value"/>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('equicare.close')</button>
            </form>
            </div>
          </div>
        </div>
      </div>
@endsection
@section('scripts')
<script src="{{ asset('assets/js/qr-modal.js') }}"></script>
@endsection
