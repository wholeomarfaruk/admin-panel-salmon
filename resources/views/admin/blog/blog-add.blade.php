@extends('admin.layouts.admin')

@section('content')
<style>


</style>
    <!-- content area start -->
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Blog infomation</h3>
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
                        <a href="{{ route('admin.blog.list') }}">
                            <div class="text-tiny">Categories</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">New Blog</div>
                    </li>
                </ul>
            </div>
            <!-- new-Blog -->
            <div class="wg-box">
                <form class="form-new-product form-style-1 needs-validation" action="{{ route('admin.blog.store') }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <fieldset class="name">
                        <div class="body-title">{{ __('Blog Title') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('title') is-invalid @enderror" type="text"
                            placeholder="Blog title" name="title" tabindex="0" aria-required="true"
                            value="{{ old('title') }}" required autocomplete="name" autofocus
                             onkeyup="stringtoSlug(this.value)">
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Slug <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('slug') is-invalid @enderror" type="text" placeholder="Slug"
                            name="slug" tabindex="0" value="{{ old('slug') }}"  onchange="stringtoSlug(this.value)" required autocomplete="name"
                            autofocus id="slug_input" >
                        @error('slug')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>


                    <fieldset class="name">
                        <div class="body-title">Status <span class="tf-color-1">*</span> </div>
                        <div class="select flex-grow">
                            <select class=" @error('status') is-invalid @enderror" name="status" required>

                                <option value="0"
                                    {{ old('status', 0) == \App\Enums\BlogStatus::DRAFT->value ? 'selected' : '' }}>DRAFT
                                </option>
                                <option value="1"
                                    {{ old('status', '') == \App\Enums\BlogStatus::PUBLISHED->value ? 'selected' : '' }}>
                                    PUBLISHED</option>

                            </select>

                        </div>
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Category</div>
                        <div class="select flex-grow">
                            <select class=" @error('categories') is-invalid @enderror" name="categories" required>

                                <option value="" selected>Choose Category</option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach

                            </select>

                        </div>
                        @error('categories')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">{{ __('Tags') }} <span class="tf-color-1">*</span></div>
                        <input id="tags" class="flex-grow @error('tags') is-invalid @enderror" type="text" placeholder="Tags"
                            name="tags" tabindex="0" aria-required="true" value="{{ old('tags') }}"
                            autocomplete="tags" autofocus >
                        @error('tags')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset>
                        <div class="body-title">{{ __('Thumbnail') }} <span class="tf-color-1"></span></div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview-thumbnail" style="display:none">
                                <img src="" class="effect8" alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="thumbnail">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">{{ __('Drop your images here or select') }} <span
                                            class="tf-color">{{ __('click to browse') }}</span></span>
                                    <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('thumbnail')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <fieldset>
                        <div class="body-title">{{ __('Gallery') }} <span class="tf-color-1"></span></div>
                        <div class="upload-image flex-grow " id="galPreview">

                            <div class="item" style="display:none">
                                <img src="" class="effect8" alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="gallery">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">{{ __('Drop your images here or select') }} <span
                                            class="tf-color">{{ __('click to browse') }}</span></span>
                                    <input type="file" id="gallery" name="gallery[]" accept="image/*" multiple>
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('gallery')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <fieldset>
                        <div class="body-title">{{ __('Blog Content') }} <span class="tf-color-1">*</span></div>
                        <textarea id="contenteditor" class="flex-grow @error('content') is-invalid @enderror" placeholder="Blog Content"
                            name="content" tabindex="0" aria-required="true" value="{{ old('content') }}" required autocomplete="name"
                            autofocus>{{ old('content') }}</textarea>
                        @error('content')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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
        tinymce.init({
            selector: '#contenteditor',
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | removeformat | help'
        });
    </script>
    <script>

$(function () {
    const maxSize = 50 * 1024 * 1024; // 5MB file size limit

    function validateFileSize(input, file) {
        if (file.size > maxSize) {
            Swal.fire({
                icon: "error",
                title: "File Too Large",
                text: `The file "${file.name}" exceeds the 50MB limit. Please upload a smaller file.`,
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

    $('#banner').on('change', function () {
        var file = this.files[0];
        if (!validateFileSize(this, file)) return;

        var reader = new FileReader();
        reader.onload = function (e) {
            $('#imgpreview-banner').show();
            $('#imgpreview-banner img').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
    });

    $('#thumbnail').on('change', function () {
        var file = this.files[0];
        if (!validateFileSize(this, file)) return;

        var reader = new FileReader();
        reader.onload = function (e) {
            $('#imgpreview-thumbnail').show();
            $('#imgpreview-thumbnail img').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
    });

    $('#gallery').on('change', function () {
        $("#galPreview .item").show();
        $("#galPreview .item:not(:last)").remove();

        var photos = this.files;
        $.each(photos, function (i, photo) {
            if (!validateFileSize($('#gallery')[0], photo)) return;

            $('#galPreview').prepend('<div class="item"><img src="' + URL.createObjectURL(photo) + '"></div>');
        });
    });

    $('#amenities_images').on('change', function () {
        $("#imagepreview-amenities .item").show();
        $("#imagepreview-amenities .item:not(:last)").remove();

        var photos = this.files;
        $.each(photos, function (i, photo) {
            if (!validateFileSize($('#amenities_images')[0], photo)) return;

            $('#imagepreview-amenities').prepend('<div class="item"><img src="' + URL.createObjectURL(photo) + '"></div>');
        });
    });

    // Initialize tag input with tagging plugin
    $('#tags').tagsInput({
        'defaultText': 'Add a tag',
        'delimiter': [','],
        'removeWithBackspace': true,
        'minChars': 2,
        'maxChars': 30,
        'placeholderColor': '#666666',
        'height': '100px',
        'width': '100%',
        'interactive': true,
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
