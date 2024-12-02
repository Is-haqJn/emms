@extends('layouts.admin')
@section('body-title')
@lang('equicare.equipments')
@endsection
@section('title')
| @lang('equicare.equipments')
@endsection
@section('breadcrumb')
<li><a href="{{ url('admin/equipments') }}">@lang('equicare.equipments') </a></li>
<li class="active">@lang('equicare.edit')</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary ">
            <div class="box-header with-border">
                <h4 class="box-title">@lang('equicare.edit_equipments')</h4>
            </div>
            <div class="nav-tabs-custom" style="padding-left:10px;">
                <ul class="nav nav-pills custom">
                    <li class="nav-item"><a class="nav-link active" href="#info-tab" data-toggle="tab">
                            General Information <i class="fa"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="#document" data-toggle="tab"> Document <i
                                class="fa"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="#images" data-toggle="tab"> Images <i
                                class="fa"></i></a></li>
                </ul>
            </div>

            <div class="box-body ">
                <div class="tab-content">
                    <div class="tab-pane active" id="info-tab">
                        @include ('errors.list')
                        <form class="form" method="post" action="{{ route('equipments.update',$equipment->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name"> @lang('equicare.name') </label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ $equipment->name }}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="short_name"> @lang('equicare.short_name_eq') </label>
                                    <input type="text" name="short_name" class="form-control"
                                        value="{{ $equipment->short_name }}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="company"> @lang('equicare.company') </label>
                                    <input type="text" name="company" class="form-control"
                                        value="{{ $equipment->company }}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="model"> @lang('equicare.model') </label>
                                    <input type="text" name="model" class="form-control"
                                        value="{{ $equipment->model }}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="sr_no"> @lang('equicare.serial_number') </label>
                                    <input type="text" name="sr_no" class="form-control"
                                        value="{{ old('sr_no')??$equipment->sr_no }}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="hospital_id"> @lang('equicare.hospital') </label>
                                    <select name="hospital_id" class="form-control">
                                        <option value="">---select---</option>
                                        @if(isset($hospitals))
                                        @foreach ($hospitals as $hospital)
                                        <option value="{{ $hospital->id }}"
                                            {{ $hospital->id==$equipment->hospital_id?'selected':''}}>
                                            {{ $hospital->name }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="department"> @lang('equicare.department') </label>
                                    {!!
                                    Form::select('department',$departments??[],$equipment->department??null,['class'=>'form-control','placeholder'=>'--select--'])
                                    !!}
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_of_purchase"> @lang('equicare.purchase_date') </label>

                                    <div class="input-group">
                                        {{-- @dd($equipment->date_of_purchase,env('date_settings')) --}}
                                        <input type="text" id="date_of_purchase" name="date_of_purchase"
                                            class="form-control"
                                            value="{{date_change($equipment->date_of_purchase)}}" />
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="order_date"> @lang('equicare.order_date') </label>
                                    <div class="input-group">

                                        <input type="text" id="order_date" name="order_date" class="form-control"
                                            value="{{ date_change($equipment->order_date) }}" />
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_of_installation"> @lang('equicare.installation_date') </label>
                                    <div class="input-group">

                                        <input type="text" id="date_of_installation" name="date_of_installation"
                                            class="form-control"
                                            value="{{ date_change($equipment->date_of_installation) }}" />
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="warranty_due_date"> @lang('equicare.warranty_due_date') </label>
                                    <div class="input-group">

                                        <input type="text" id="warranty_due_date" name="warranty_due_date"
                                            class="form-control"
                                            value="{{ date_change($equipment->warranty_due_date) }}" />
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="service_engineer_no"> @lang('equicare.service_engineer_number') </label>
                                    <input type="text" name="service_engineer_no" class="form-control phone"
                                        value="{{ $equipment->service_engineer_no }}" />
                                </div>

                                <div class="form-group col-md-6 ">
                                    <label>@lang('equicare.working_status')</label>
                                    <select class="form-control" name="working_status">
                                        <option value="">Select Status</option>
                                        <option value="working"
                                            <?php if ($equipment->working_status === "working") echo "selected"; ?>>
                                            @lang('equicare.working')
                                        </option>
                                        <option value="not working"
                                            <?php if($equipment->working_status == "not working") echo "selected";?>>Not
                                            working</option>
                                        <option value="pending"
                                            <?php if($equipment->working_status == "pending") echo "selected";?>>Pending
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="notes"> @lang('equicare.notes') </label>
                                    <textarea rows="2" name="notes"
                                        class="form-control">{{ $equipment->notes }}</textarea>
                                </div>

                                <div class="form-group col-md-6">
                                    <label> @lang('equicare.critical') </label><br />
                                    <label>
                                        <input type="radio" value="1" name="is_critical"
                                            {{ $equipment->is_critical==1? 'checked':'' }}>
                                        @lang('equicare.yes') </label> &nbsp;
                                    <label>
                                        <input type="radio" value="0" name="is_critical"
                                            {{ $equipment->is_critical==0? 'checked':''  }}>
                                        @lang('equicare.no')
                                    </label>
                                </div>

                                <div class="form-group col-md-12">
                                    <input type="submit" value="@lang('equicare.submit')"
                                        class="btn btn-primary btn-flat" />
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="tab-pane" id="document">
                        @php
                        $docs = \App\EquipDocs::where('equip_id', $equipment->id)->get();
                        @endphp

                        <div id="documentTableContainer" class="table-responsive"
                            style="{{ $docs->isEmpty() ? 'display: none;' : '' }}">
                            @if($docs->isNotEmpty())
                            <table class="table table-bordered" id="documentTable">
                                <thead>
                                    <tr>
                                        <th>@lang('equicare.document_name')</th>
                                        <th>@lang('equicare.uploaded_document')</th>
                                        <th>@lang('equicare.uploaded_by')</th>
                                        <th>@lang('equicare.uplaoded_time')</th>
                                        <th>@lang('equicare.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($docs as $document)
                                    <tr id="document-{{ $document->id }}">
                                        <td>{{ $document->document_name }}</td>
                                        <td>
                                            <a href="{{ asset('uploads/documents/' . $document->document_file) }}"
                                                target="_blank">
                                                {{ $document->document_file }}
                                            </a>
                                        </td>
                                        <td>@php
                                            $user = \App\User::find($document->user_id);
                                            $user_name = $user->name;
                                            @endphp
                                            {{ucfirst($user_name) ?? '-'}}
                                        </td>
                                        <td>{{ $document->created_at }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger delete-button"
                                                data-toggle="modal" data-target="#deleteModal"
                                                data-id="{{ $document->id }}">@lang('equicare.delete')</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>

                        <div id="documentFormContainer" style="{{ $docs->isNotEmpty() ? 'display: none;' : '' }}">
                            <form class="form" method="post" action="{{ route('upload.document', $equipment->id) }}"
                                enctype="multipart/form-data" id="documentForm">
                                @csrf
                                <div class="form-group">
                                    <label for="document_name">@lang('equicare.document_name')</label>
                                    <input type="text" class="form-control" id="document_name" name="document_name"
                                        placeholder="Enter document name" required>
                                </div>
                                <div class="form-group">
                                    <label for="document_file">@lang('equicare.upload_document')</label>
                                    <input type="file" class="form-control file" id="document_file" name="document_file"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                                    <small class="form-text text-danger">Upload PDF, DOC, or Excel files only.</small>

                                </div>
                                <button type="submit" class="btn btn-primary">@lang('equicare.upload')</button>
                            </form>
                        </div>

                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#documentModal"
                            style="{{ $docs->isEmpty() ? 'display: none;' : '' }}">
                            @lang('equicare.add_more_document')
                        </button>

                        <!-- Modal for adding documents -->
                        <div class="modal fade" id="documentModal" tabindex="-1" role="dialog"
                            aria-labelledby="documentModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            &times;
                                        </button>
                                        <h5 class="modal-title" id="documentModalLabel">
                                            @lang('equicare.upload_document')</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form class="form" method="post"
                                            action="{{ route('upload.document', $equipment->id) }}"
                                            enctype="multipart/form-data" id="modalDocumentForm">
                                            @csrf
                                            <div class="form-group">
                                                <label for="modal_document_name">@lang('equicare.document_name')</label>
                                                <input type="text" class="form-control" id="modal_document_name"
                                                    name="document_name" placeholder="Enter document name" required>
                                            </div>
                                            <div class="form-group">
                                                <label
                                                    for="modal_document_file">@lang('equicare.upload_document')</label>
                                                <input type="file" class="form-control file" id="modal_document_file"
                                                    name="document_file" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                                                <small class="form-text text-danger">Upload PDF, DOC, or Excel files
                                                    only.</small>

                                            </div>
                                            <button type="submit"
                                                class="btn btn-primary">@lang('equicare.upload')</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane" id="images">
                        @php
                        $image = \App\EquipmentImages::where('equip_id', $equipment->id)->first();
                        @endphp

                        <form method="post" action="{{ route('uplaod.images', $equipment->id) }}"
                            enctype="multipart/form-data" id="imagesForm">
                            @csrf
                            @if(!$image)
                            <div class="form-group">
                                <label for="thumbnail_image" class="demo">@lang('equicare.thumbnail_image')</label>
                                <input type="file" class="form-control file input-width-300" id="thumbnail_image"
                                    name="thumbnail_image" accept=".jpg,.jpeg,.png,.gif" required>
                            </div>
                            @endif
                            @if($image)
                            @if($image->thumbnail_image === null)
                            <div class="col-md-12" id="Thumbnail" style="margin-left:-10px;">
                                <div class="form-group">
                                    <label for="thumbnail_image"
                                        style="margin-left:5px;margin-top:15px;">@lang('equicare.thumbnail_image')</label>
                                    <input type="file" class="form-control file input-width-300" id="thumbnail_image"
                                        name="thumbnail_image" accept=".jpg,.jpeg,.png,.gif" required>
                                </div>
                            </div>
                            @else

                            <div class="col-md-12" id="imageThumbnail">
                                <h4 class="demo">@lang('equicare.thumbnail_image')</h4>
                                <div class="image-container-thumbnail position-relative">
                                    <img src="{{ asset('/uploads/EquipImages/' . $image->thumbnail_image) }}"
                                        alt="Thumbnail Image" class="img-thumbnail-container" height="100px"
                                        width="100px">
                                    <div class="overlay-thumbnail">
                                        <a class="delete-image1" data-target="#confirmDeleteModal"
                                            data-toggle="modal"><i class="fa fa-trash" aria-hidden="true"
                                                style="color:red;"></i></a>
                                        <a href="#" data-toggle="modal" data-target="#imageModal">
                                            <i class="fa fa-eye" aria-hidden="true" style="color:black;"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>

                            <div class="modal fade" id="imageModal" tabindex="-1" role="dialog"
                                aria-labelledby="imageModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset('/uploads/EquipImages/' . $image->thumbnail_image) }}"
                                                alt="Enlarged Image" class="img-fluid"
                                                style="height:300px;width:200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @php
                            $multipleImages = json_decode($image->multiple_images, true);
                            if (is_array($multipleImages)) {
                            $multipleImages = array_values($multipleImages);
                            }
                            @endphp
                            @if(is_array($multipleImages) && count($multipleImages) > 0)
                            <div class="main-container">
                                <div class="container">
                                    <div class="row" style="width:100%;margin-left:-5px;" id="imageGallery">
                                        <h4 id="heading" style="margin-top:20px;">@lang('equicare.equipment_images')
                                        </h4>
                                        @foreach(json_decode($image->multiple_images) as $key => $img)
                                        <div class="col-md-3 container-img">
                                            <div class="image-container position-relative">
                                                <img src="{{ asset('/uploads/EquipImages/' . $img) }}" alt="Image"
                                                    class="equipment-image">
                                                <div class="overlay">
                                                    <a href="#" class="delete-image" data-image-type="multiple"
                                                        data-image-id="{{ $image->id }}" data-image-index="{{ $key }}"
                                                        data-target="#deleteImageModal" data-toggle="modal">
                                                        <i class="fa fa-trash" aria-hidden="true"
                                                            style="color:red;"></i></a>&nbsp;
                                                    <a href="#" class="view-image" data-toggle="modal"
                                                        data-target="#viewImageModal"
                                                        data-image-src="{{ asset('/uploads/EquipImages/' . $img) }}">
                                                        <i class="fa fa-eye" aria-hidden="true"
                                                            style="color:black;height:50%;"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>


                                <div class="modal fade" id="viewImageModal" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="" alt="Large Image" id="largeImage" class="img-fluid">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                            @if(!(is_array(json_decode($image->multiple_images??'')) &&
                            count(json_decode($image->multiple_images??'')) > 0))
                            <div class="row" id="imageGallery" style="margin-left:0px;">
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="multiple_images"
                                    style="margin-left:5px;">@lang('equicare.equipment_images')</label>
                                <input type="file" class="form-control input-width-300 ml-5" id="multiple_images"
                                    name="multiple_images[]" accept=".jpg,.jpeg,.png,.gif" style="margin-left:5px;"
                                    multiple />
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>

                        <form method="post" action="{{ route('uplaod.images', $equipment->id) }}"
                            enctype="multipart/form-data" id="imagesFormHidden" style="display: none;">
                            @csrf
                            <input type="file" id="image-input-hidden" name="multiple_images[]"
                                accept=".jpg,.jpeg,.png,.gif" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- 
<div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">Upload Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" method="post" action="{{ route('upload.document', $equipment->id) }}"
                    enctype="multipart/form-data" id="documentFormModal">
                    @csrf
                    <div class="form-group">
                        <label for="document_name">Document Name</label>
                        <input type="text" class="form-control" id="document_name" name="document_name"
                            placeholder="Enter document name" required>
                    </div>
                    <div class="form-group">
                        <label for="document_file">Upload Document</label>
                        <input type="file" class="form-control file" id="document_file" name="document_file"
                            accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                        <small class="form-text text-danger">Upload PDF, DOC, or Excel files only.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div> -->

<!-- @if($image && $image->thumbnail_image) -->

<!-- @endif -->

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    &times;
                </button>
                <h5 class="modal-title" id="deleteModalLabel">@lang('equicare.delete_document')</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this document?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger confirm-delete">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
    aria-labelledby="confirmdeletethumbnailLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    &times;
                </button>
                <h5 clas="modal-title" id="confirmdeletethumbnailLabel">Delete Image </h5>
            </div>

            <div class="modal-body">
                Are you sure you want to delete this image?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDelete" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteImageModal" tabindex="-1" role="dialog" aria-labelledby="deleteimageLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    &times;
                </button>
                <h5 clas="modal-title" id="deleteimageLabel">Delete Image</h5>
            </div>

            <div class="modal-body">
                Are you sure you want to delete this image?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteImageButton" class="btn btn-danger">Delete</button>
            </div>

        </div>
    </div>
</div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" alt="Enlarged Image" class="img-fluid" style="height:300px;width:200px;">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewImageModal" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="" alt="Large Image" id="largeImage" class="img-fluid">
                                            </div>

                                        </div>
                                    </div>
                                </div>
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.view-image').on('click', function() {
        var imageSrc = $(this).data('image-src');
        $('#largeImage').attr('src', imageSrc);
    });

    // $('#imagesForm').on('submit', function(event) {

    // })
    var assetBaseUrl = "{{ asset('') }}";
    $('.nav-pills a[href="#info-tab"]').tab('show');
    $('#add-image-button').on('click', function() {
        console.log("ffff");
        $('#image-input').trigger('click');
    });

    $('#image-input').on('change', function() {
        if (this.files && this.files.length > 0) {
            var fileInput = $('#image-input-hidden');
            fileInput.prop('files', this.files);
            $('#imagesForm').submit();
        }
    });
    $('.image-container').hover(
        function() {
            $(this).find('.overlay').fadeIn();
        },
        function() {
            $(this).find('.overlay').fadeOut();
        }
    );
    $('#date_of_purchase').datepicker({
        format: "{{env('date_settings')=='' ? 'yyyy-mm-dd' : env('date_settings')}}",
        'todayHighlight': true,
    });
    $('#order_date').datepicker({
        format: "{{env('date_settings')=='' ? 'yyyy-mm-dd' : env('date_settings')}}",
        'todayHighlight': true,
    });
    $('#date_of_installation').datepicker({
        format: "{{env('date_settings')=='' ? 'yyyy-mm-dd' : env('date_settings')}}",
        'todayHighlight': true,
    });
    $('#warranty_due_date').datepicker({
        format: "{{env('date_settings')=='' ? 'yyyy-mm-dd' : env('date_settings')}}",
        'todayHighlight': true,
    });
    @if(isset($_GET['tab']) && $_GET['tab'] != "")
    $('.nav-pills a[href="#{{$_GET['
        tab ']}}"]').tab('show')
    @endif

    handleFormSubmission('#documentForm');
    handleFormSubmission('#modalDocumentForm');

    function handleFormSubmission(formId) {
        $(document).on('submit', formId, function(e) {
            e.preventDefault();

            var form = $(this)[0];
            var formData = new FormData(form);
            var valid = true;
            var errorMessages = '';


            $(form).find('input[type="file"]').each(function() {
                var input = $(this)[0];
                if (input.files.length > 0) {
                    $.each(input.files, function(index, file) {
                        var fileExtension = file.name.split('.').pop().toLowerCase();
                        var validExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
                        var maxSizeMB = 2;
                        var fileSizeMB = file.size / 1024 / 1024;

                        if ($.inArray(fileExtension, validExtensions) === -1) {
                            valid = false;
                            errorMessages +=
                                'Only PDF, DOC, DOCX, XLS, and XLSX files are allowed.<br>';
                            return false;
                        }

                        if (fileSizeMB > maxSizeMB) {
                            valid = false;
                            errorMessages += 'File size exceeds the limit of 2 MB.<br>';
                            return false;
                        }
                    });

                    if (!valid) {
                        new PNotify({
                            // title: 'Validation Error!',
                            text: errorMessages,
                            type: 'error',
                            delay: 5000,
                            nonblock: {
                                nonblock: true
                            }
                        });
                        return;
                    }
                }
            });

            if (valid) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log('AJAX Success:', response);

                        var $tableContainer = $('#documentTableContainer');
                        var $table = $('#documentTable');

                        if ($table.length === 0) {
                            var tableHtml = `
                            <table class="table table-bordered" id="documentTable">
                                <thead>
                                    <tr>
                                        <th>Document Name</th>
                                        <th>Uploaded Document</th>
                                        <th>Uploaded By</th>
                                        <th>Uploaded Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="document-${response.document_id}">
                                        <td>${response.document_name}</td>
                                        <td><a href="${response.document_url}" target="_blank">${response.document_file}</a></td>
                                        <td>${response.user_name}</td>
                                        <td>${response.uploaded_time}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger delete-button" data-toggle="modal" data-target="#deleteModal" data-id="${response.document_id}">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        `;
                            $tableContainer.html(tableHtml).show();
                        } else {
                            var newRow = `
                            <tr id="document-${response.document_id}">
                                <td>${response.document_name}</td>
                                <td><a href="${response.document_url}" target="_blank">${response.document_file}</a></td>
                                <td>${response.user_name}</td>
                                <td>${response.uploaded_time}</td>
                                <td>
                                    <button type="button" class="btn btn-danger delete-button" data-toggle="modal" data-target="#deleteModal" data-id="${response.document_id}">Delete</button>
                                </td>
                            </tr>
                        `;
                            $table.find('tbody').append(newRow);
                            $tableContainer.show();
                        }

                        $('.btn-primary[data-target="#documentModal"]').show();

                        $('#documentFormContainer').hide();

                        $(formId)[0].reset();
                        $(formId).closest('.modal').modal('hide');
                    },
                    error: function(response) { // On AJAX request error
                        console.log('Error:', response);
                    }
                });
            }
        });
    }

    $('#deleteModal').on('show.bs.modal', function(e) {
        var button = $(e.relatedTarget);
        var id = button.data('id');
        var modal = $(this);

        modal.find('.confirm-delete').data('id', id);
    });

    $('.confirm-delete').on('click', function() {
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: "{{ url('admin/equipments/delete-document') }}/" + id,
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "document_id": id
            },
            success: function(response) {
                $('#document-' + id).remove();
                $('#deleteModal').modal('hide');
            },
            error: function(response) {
                console.log('Error:', response);
            }
        });
    });


    $("#imagesForm").on("submit", function(e) {
        e.preventDefault();

        var validExtensions = ['jpg', 'jpeg', 'png'];
        var isValid = true;
        var errorMessage = '';

        function validateFiles(inputFiles) {
            for (var i = 0; i < inputFiles.length; i++) {
                var file = inputFiles[i];
                var fileName = file.name.toLowerCase();
                var extension = fileName.split('.').pop();
                if ($.inArray(extension, validExtensions) === -1) {
                    errorMessage = 'Only .jpg, .png and .jpeg files are allowed.';
                    isValid = false;
                    break;
                }
            }
        }

        var thumbnailImageInput = document.getElementById('thumbnail_image');
        if (thumbnailImageInput && thumbnailImageInput.files.length > 0) {
            validateFiles(thumbnailImageInput.files);
        }

        var multipleImagesInput = document.getElementById('multiple_images');
        if (isValid && multipleImagesInput && multipleImagesInput.files.length > 0) {
            validateFiles(multipleImagesInput.files);
        }

        if (!isValid) {
            new PNotify({
                // title: 'Error!',
                text: errorMessage,
                type: 'error',
                delay: 2000,
                nonblock: {
                    nonblock: true
                }
            });
            return;
        }

        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);

                new PNotify({
                    title: 'Success!',
                    text: "Images Uploaded successfully",
                    type: 'success',
                    delay: 2000,
                    nonblock: {
                        nonblock: true
                    }
                });

                $("#imageThumbnail").remove();

                if (response.thumbnail_image) {
                    var thumbnailPath = assetBaseUrl + "uploads/EquipImages/" + response
                        .thumbnail_image;
                    console.log("Thumbnail image path: ", thumbnailPath);

                    var thumbnailHtml = `
                <div class="col-md-12" id="imageThumbnail">
                    <h4 class="demo mt-5">Thumbnail Image</h4>
                    <div class="image-container-thumbnail position-relative">
                        <img src="${thumbnailPath}" alt="Thumbnail Image" class="img-thumbnail-container" height="100px" width="100px">
                        <div class="overlay-thumbnail">
                           <a class="delete-image1" data-target="#confirmDeleteModal" data-toggle="modal"><i class="fa fa-trash" aria-hidden="true" style="color:red;"></i></a> &nbsp;
                            
                           <a href="#" class="view-thumbnail" data-toggle="modal" data-target="#imageModal" data-image-src="${thumbnailPath}">
                        <i class="fa fa-eye" aria-hidden="true" style="color:black;"></i>
                    </a>
                        </div>
                    </div>
                </div>`;
                    $("#imagesForm").prepend(thumbnailHtml);
                    $(".demo").text("Thumbnail Image");
                    $("#thumbnail_image").closest('.form-group').hide();
                }

                if (response.multiple_images && response.multiple_images.length > 0) {
                    console.log(response.multiple_images);
                    var imageGallery = $("#imageGallery");
                    imageGallery.empty();
                    var titleHtml =
                        '<h4 style="margin-left:5px;margin-top:20px;">Equipment Images</h4>';
                    imageGallery.append(titleHtml);
                    response.multiple_images.forEach(function(img, index) {
                        var imageHtml = `
                    <div class="col-md-3 container-img">
                        <div class="image-container position-relative">
                            <img src="${assetBaseUrl}uploads/EquipImages/${img}" alt="Image" class="equipment-image">
                            <div class="overlay">
                            <a href="#" class="delete-image" data-image-type="multiple" data-image-id="${response.equip_id}" data-image-index="${index}" data-target="#deleteImageModal">
                                    <i class="fa fa-trash" aria-hidden="true" style="color:red;"></i>
                                    </a>&nbsp;
                                   <a href="#" class="view-image" data-toggle="modal" data-target="#viewImageModal" data-image-src="${assetBaseUrl}uploads/EquipImages/${img}">
                                            <i class="fa fa-eye" aria-hidden="true" style="color:black;"></i>
                                        </a>
                                </div>
                        </div>
                    </div>`;
                        imageGallery.append(imageHtml);
                    });
                }

                $("#multiple_images").val("");
            },
            error: function(response) {
                console.error("Error response:", response);

                new PNotify({
                    title: 'Error!',
                    text: "There was an error uploading the images",
                    type: 'error',
                    delay: 2000,
                    nonblock: {
                        nonblock: true
                    }
                });
            }
        });
    });

    $(document).on('click', '[data-target="#viewImageModal"]', function() {
        var imageUrl = $(this).data('image-src');
        $('#viewImageModal .modal-body img').attr('src', imageUrl);
    });

    $('#viewImageModal').on('show.bs.modal', function() {
        $(this).find('.modal-body img').attr('src', '');
    });

    $(document).on('click', '[data-target="#imageModal"]', function() {
        var imageUrl = $(this).data('image-src');
        $('#imageModal .modal-body img').attr('src', imageUrl);
    });
    $(document).on("show.bs.modal", "#imageModal", function(event) {
        var imageUrl = $(this).data('image-src');
        if (imageUrl) {
            $(this).find('.modal-body img').attr('src', imageUrl);
        }
    });
    // $(document).on("click", ".view-thumbnail", function(event) {
    //     // console.log('modal called',$('#imageModal').length);
    //     console.dir($('#imageModal'));
    // // $('#imageModal').on('show.bs.modal', function() {
    //     var imageUrl = $(this).data('image-src');
    //     if (imageUrl) {
    //         $(this).find('.modal-body img').attr('src', imageUrl);
    //     }
    //     $('#imageModal').modal('show');
    // });

    var imageToDelete = {
        id: null,
        index: null
    };
    $(document).on("click", ".delete-image", function(event) {
        event.preventDefault();
        imageToDelete.id = $(this).data('image-id');
        imageToDelete.index = $(this).data('image-index');
        $('#deleteImageModal').modal('show');
    });

    $(document).on("click", ".delete-image1", function() {
        $('#confirmDeleteModal').modal('show');
    });
    // $(document).on("click",".view-thumbnail", function(){
    //     console.log("clicked");
    //     $('#imageModal').modal('show');
    // })
    $('#confirmDelete').on('click', function() {
        var equipId = "{{ $equipment->id }}";

        $.ajax({
            url: "{{ route('delete.thumbnail', ':id') }}".replace(':id', equipId),
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#confirmDeleteModal').modal('hide');
                    $('.image-container-thumbnail').remove();
                    $('#imageThumbnail').remove();
                    var thumbnailInputHtml = `
                        <div class="col-md-12" id="Thumbnail" style="margin-left:-10px;">
                            <div class="form-group">
                                <label for="thumbnail_image" style="margin-left:5px;margin-top:15px;">ThumbNail Image</label>
                                <input type="file" class="form-control file input-width-300" id="thumbnail_image"
                                    name="thumbnail_image" accept=".jpg,.jpeg,.png,.gif" required>
                            </div>
                        </div>`;
                    $('#imagesForm').prepend(thumbnailInputHtml);

                    if ($('#Thumbnail').length) {
                        $('#Thumbnail').show();
                    } else {
                        var thumbnailInputHtml = `
                        <div class="col-md-12" id="Thumbnail" style="margin-left:-10px;">
                            <div class="form-group">
                                <label for="thumbnail_image">ThumbNail Image</label>
                                <input type="file" class="form-control file input-width-300" id="thumbnail_image"
                                    name="thumbnail_image" accept=".jpg,.jpeg,.png,.gif" required>
                            </div>
                        </div>`;
                        $('#imagesForm').prepend(thumbnailInputHtml);
                    }

                    new PNotify({
                        title: 'Success!',
                        text: "Thumbnail Image Deleted successfully",
                        type: 'success',
                        delay: 2000,
                        nonblock: {
                            nonblock: true
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error deleting thumbnail image:', error);
                alert('Error deleting thumbnail image.');
            }
        });
    });
    $('#confirmDeleteImageButton').on('click', function() {
        $.ajax({
            url: '{{ route("delete.image") }}',
            method: 'Delete',
            data: {
                _token: '{{ csrf_token() }}',
                image_id: imageToDelete.id,
                image_index: imageToDelete.index
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#deleteImageModal').modal('hide');

                    $('a[data-image-index="' + imageToDelete.index + '"]').parents(
                        '.container-img').remove();
                    // console.log($('a[data-image-id="'+imageToDelete.index+'"]'));
                    // console.log($('a[data-image-index="'+imageToDelete.index+'"]').parents('.container-img').remove());

                    if ($('.container-img').length === 0) {
                        $('#heading')
                            .hide(); // Assuming 'title' is the ID of the element to hide
                    }
                    new PNotify({
                        title: ' Success!',
                        text: "Image deleted successfully",
                        type: 'success',
                        delay: 2000,
                        nonblock: {
                            nonblock: true
                        }
                    });
                    // location.reload();
                } else {
                    new PNotify({
                        title: 'Error!',
                        text: "Failed to delete image",
                        type: 'error',
                        delay: 2000,
                        nonblock: {
                            nonblock: true
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("An error occurred");
                new PNotify({
                    title: 'Error!',
                    text: "An error occurred while deleting the image",
                    type: 'error',
                    delay: 2000,
                    nonblock: {
                        nonblock: true
                    }
                });
            }
        });
    });
});
</script>
@endsection
@section('styles')
<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<style type="text/css">
.image-container {
    width: 200px;
    height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    margin: 5px 5px 5px -14px;
    overflow: hidden;
}

img.equipment-image {
    height: 150px;
    width: 165px;
    max-width: 100%;
    max-height: 100%;
    margin: 0 auto;
    display: block;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    justify-content: center;
    align-items: center;
}

.image-container:hover .overlay {
    opacity: 1;
}

.fa-trash {
    font-size: 24px;
    color: red;
    cursor: pointer;
}

.fa-eye {
    font-size: 24px;
    color: red;
    cursor: pointer;
}

.container {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    margin-left: 0;
    padding-top: inherit;
    margin-bottom: 10px;
}

.container-img {
    max-height: 200px;
    box-sizing: border-box;
    display: flex;
    justify-content: center;
    margin-top: 10px;
    margin-left: -40px;
}

.add-button {
    display: inline-block;
    width: 100%;
    height: 100%;
    padding: 10px 0;
    background-color: #caced2;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
}

.plus-symbol {
    display: block;
    line-height: 1;
}

.image-container-thumbnail {
    position: relative;
    display: inline-block;
}

.overlay-thumbnail {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(246, 244, 241, 0.62);
    color: white;
    font-size: 24px;
    text-align: center;
    line-height: 100px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-container-thumbnail:hover .overlay-thumbnail {
    opacity: 1;
}

.input-width-300 {
    width: 400px;
}

#heading {
    margin-top: 20px;
}

#imageThumbnail {
    margin-bottom: 15px;
}

.box-body {
    margin-top: -25px;
}

.col-md-3 {
    width: 23%;
}

#largeImage {
    /* max-width: 100%;
        max-height: 80vh;  */
    height: 300px;
    width: 250px;
}

@media (max-width: 576px) {
    .nav-tabs-custom .nav-tabs {
        display: flex;
        flex-direction: column;
    }

    .nav-tabs-custom .nav-pills {
        display: block;
    }

    .nav-tabs-custom .tab-content {
        padding: 15px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .heading {
        margin-left: -30px;
    }

    .col-md-3 {
        width: 70%;
    }
}
</style>
@endsection