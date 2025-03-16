@extends('admin.layouts.admin')

@section('content')
    <!-- content area start -->
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Board Member</h3>
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
                            <div class="text-tiny">Members</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit Member</div>
                    </li>
                </ul>
            </div>
            <!-- new-Blog -->
            <div class="wg-box">
                <form class="form-new-product form-style-1 needs-validation" action="{{ route('admin.member.update', $member->id) }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                    <fieldset class="name">
                        <div class="body-title">{{ __('Name') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('name') is-invalid @enderror" type="text"
                            placeholder="Name" name="name" tabindex="0" aria-required="true"
                            value="{{ old('name', $member->name) }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">{{ __('Designation') }} <span class="tf-color-1"></span></div>
                        <input class="flex-grow @error('designation') is-invalid @enderror" type="text" placeholder="Designation"
                            name="designation" tabindex="0" value="{{ old('designation', $member->designation) }}" aria-required="true"
                            autocomplete="designation" autofocus>
                        @error('designation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>



                    <fieldset>
                        <div class="body-title">{{ __('description') }} <span class="tf-color-1">*</span></div>
                        <textarea class="flex-grow @error('description') is-invalid @enderror" placeholder="Description"
                            name="description" tabindex="0" aria-required="true" required autocomplete="name" autofocus>{{ old('description', $member->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('Order') }} <span class="tf-color-1">(optional)</span></div>
                        <input class="flex-grow @error('order') is-invalid @enderror" type="number" placeholder=""
                            name="order" aria-required="true" value="{{ old('order', $member->order) }}" required
                            autocomplete="order" autofocus>
                        @error('order')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>
                    <fieldset>
                        <div class="body-title">{{ __('Upload image') }} <span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" @if(!$member->image) style="display:none" @endif>
                                <img src="{{ asset($member->image?->file) }}" class="effect8" alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">{{ __('Drop your images here or select') }} <span
                                            class="tf-color">{{ __('click to browse') }}</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                        @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- content area end -->
@endsection

@push('scripts')

    <script>
        $(function() {
            $('#myFile').on('change', function() {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imgpreview').show();
                    $('#imgpreview img').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

        })
        $('#gFile').on('change', function() {
            console.log(this.files);
            $("#galPreview").show();
            var photos = this.files;
            $.each(photos, function(i, photo) {
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
