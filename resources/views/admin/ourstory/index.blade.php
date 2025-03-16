@extends('admin.layouts.admin')

@section('content')
    <!-- content area start -->
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Our Story Images</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.dashboard.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>

                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Our Story Images </div>
                    </li>
                </ul>
            </div>
            <!-- new-story images -->

            @foreach ($our_story_images as $index=> $image )


            <div class="wg-box mb-27">
                <form class="form-new-product form-style-1 needs-validation" action="{{ route('admin.ourstory.update',['id'=>$image->id]) }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                    <fieldset class="name">
                        <div class="body-title">{{ __('Name') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('name') is-invalid @enderror" type="text" placeholder="Name"
                            name="name" tabindex="0" aria-required="true" value="{{ old('name', $image->name) }}" required
                            autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset>
                        <div class="body-title">{{ $image->file_for }} <span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview{{$index}}" style="">
                                <img src="{{asset($image?->file)}}" class="effect8" alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile{{$index}}">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">{{ __('Drop your images here or select') }} <span
                                            class="tf-color">{{ __('click to browse') }}</span></span>
                                    <input class="@error('file') is-invalid @enderror" type="file" id="myFile{{$index}}" name="file" accept="">
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
            @endforeach
        </div>
    </div>
    <!-- content area end -->
@endsection

@push('scripts')


    <script>
        $(function () {

            const maxSize = 10 * 1024 * 1024; // 5MB file size limit

            function validateFileSize(input, file) {
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: "error",
                        title: "File Too Large",
                        text: `The file "${file.name}" exceeds the 10MB limit. Please upload a smaller file.`,
                    });
                    input.value = ""; // Clear the selected file
                    return false;
                }
                return true;
            }


            @foreach ($our_story_images as $index=> $image )
                $('#myFile{{$index}}').on('change', function () {

                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#imgpreview{{$index}}').show();
                            $('#imgpreview{{$index}}').html('<img src="' + e.target.result + '" class="effect8" />');
                        }
                        reader.readAsDataURL(this.files[0]);

                });
            @endforeach

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
