@extends('admin.layouts.admin')

@section('content')
    <!-- content area start -->
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Upload New File</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="#">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.fileupload.list') }}">
                            <div class="text-tiny">File Uploads</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">File Uploads New </div>
                    </li>
                </ul>
            </div>
            <!-- new-Blog -->
            <div class="wg-box">
                <form class="form-new-product form-style-1 needs-validation" action="{{ route('admin.fileupload.store') }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf

                    <fieldset class="name">
                        <div class="body-title">{{ __('Name') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('name') is-invalid @enderror" type="text" placeholder="Name"
                            name="name" tabindex="0" aria-required="true" value="{{ old('name') }}" required
                            autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('File Type') }} <span class="tf-color-1">*</span></div>
                        <select name="type" id="" required class=" @error('type') is-invalid @enderror">
                            <option value="" selected>Select File Type</option>
                            @foreach (\App\Enums\UploadFileTypes::cases() as $type)
                                <option value="{{ $type->value }}">{{ $type->name }}</option>
                            @endforeach
                        </select>

                    </fieldset>
                    @error('type')


                    <div class="bot">
                        <div></div>
                        <div class="invalid-feedback d-flex" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    </div>
                    @enderror

                    <fieldset>
                        <div class="body-title">{{ __('Upload image') }} <span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="display:none">
                                <img src="" class="effect8" alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">{{ __('Drop your images here or select') }} <span
                                            class="tf-color">{{ __('click to browse') }}</span></span>
                                    <input class="@error('file') is-invalid @enderror" type="file" id="myFile" name="file" accept="">
                                    @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </label>
                            </div>
                        </div>

                    </fieldset>

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- content area end -->
@endsection

@push('scripts')


    <script>
        $(function () {

            const maxSize = 2 * 1024 * 1024; // 5MB file size limit

            function validateFileSize(input, file) {
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: "error",
                        title: "File Too Large",
                        text: `The file "${file.name}" exceeds the 2MB limit. Please upload a smaller file.`,
                    });
                    input.value = ""; // Clear the selected file
                    return false;
                }
                return true;
            }

            $('#pdf').on('change', function () {
                var file = this.files[0];
                if (!validateFileSize(this, file)) return;

                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#imgpreview-pdf').addClass('d-flex justify-content-center align-items-center');
                    $('#imgpreview-pdf').html('<span>' + file.name + '</span>');
                }
                reader.readAsDataURL(file);
            });
            $("#model_type").on('change', function () {
                var model_type = $(this).val();
                if (model_type !== '') {
                    $("#model_id_field").show();
                } else {
                    $("#model_id_field").hide();
                }
            });
            var validFiles = ["{{ implode(',', array_column(\App\Enums\UploadFileTypes::cases(), 'value')) }}"];
            $('#myFile').on('change', function () {
                console.log(this.files);
                var validTypes = ['image', 'pdf', 'video', 'audio', 'docs', 'text'];
                var fileType = this.files[0].type; // Example: "image/png" or "application/pdf"
                var typevalue="";
                // Check if any valid type is present in the file type
                validTypes.forEach(type => {
                    if (fileType.includes(type)) {
                        typevalue = type;
                    }
                })
                if (!validTypes.some(type => fileType.includes(type) )) {
                    Swal.fire({
                        icon: "error",
                        title: "Invalid File Type",
                        text: `The file type "${fileType}" is not allowed. Please upload a valid file.`,
                    });
                    this.value = ""; // Clear the selected file
                    return;
                }
                console.log(typevalue);

                if (typevalue !== 'image') {
                    var file = this.files[0];
                    var reader = new FileReader();
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#imgpreview').addClass('d-flex justify-content-center align-items-center');

                        $('#imgpreview').html('<img src="/admin/images/icons/'+typevalue+'.png" class="effect8" /><br><span>' + file.name + '</span>');
                    }
                    reader.readAsDataURL(file);
                } else {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#imgpreview').show();
                        $('#imgpreview').html('<img src="' + e.target.result + '" class="effect8" />');
                    }
                    reader.readAsDataURL(this.files[0]);
                }

            });

        })
        $('#gFile').on('change', function () {
            console.log(this.files);
            $("#galPreview").show();
            var photos = this.files;
            $.each(photos, function (i, photo) {
                $('#galPreview').prepend('<div class="item"><img src="' + URL.createObjectURL(
                    photo) + '"></div>');
            });

        });

        function stringtoSlug(str) {
            str = str.replace(/^\s+|\s+$/g, ''); // trim leading/trailing spaces
            str = str.toLowerCase();
            str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes

            console.log(str);

            $('#slug_input').val(str);

        }
    </script>
@endpush
