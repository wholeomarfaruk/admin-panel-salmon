@extends('admin.layouts.admin')

@section('content')
    <!-- content area start -->
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit PopUp</h3>
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
                        <div class="text-tiny">Edit PopUp</div>
                    </li>
                </ul>
            </div>
            <!-- new-Blog -->



            <div class="wg-box">
                <form class="form-new-product form-style-1 needs-validation" action="{{ route('admin.popup.update',['id'=>$popup->id]) }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <fieldset class="name">
                        <div class="body-title">{{ __('is Popup Show') }} <span class="tf-color-1">*</span></div>
                        <select class=" @error('is_popup_show') is-invalid @enderror" name="is_popup_show" required>
                            <option value="0" {{ old('is_popup_show', $popup->is_popup_show) == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('is_popup_show', $popup->is_popup_show) == 1 ? 'selected' : '' }}> Yes
                            </option>
                        </select>
                        @error('is_popup_show')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">{{ __('Url Type') }} <span class="tf-color-1">*</span></div>
                        <select id="url_type" class=" @error('url_type') is-invalid @enderror" name="url_type" required>
                            <option value="">Select Url Type</option>
                            <option value="whatsapp" {{ old('url_type', $popup->url_type) == 'whatsapp' ? 'selected' : '' }}>Whatsapp</option>
                            <option value="phone" {{ old('url_type', $popup->url_type) == 'phone' ? 'selected' : '' }}> Phone</option>
                            <option value="link" {{ old('url_type', $popup->url_type) == 'link' ? 'selected' : '' }}> Link</option>
                        </select>
                        @error('url_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>
                    <fieldset class="name" id="url-box" style="{{$popup->url==null ? 'display:none' : ''}}">
                        <div class="body-title">{{ __('Url') }} <span class="tf-color-1"></span></div>
                        <input id="url" class="flex-grow @error('url') is-invalid @enderror" type="text" placeholder="url"
                            name="url" tabindex="0" aria-required="true" value="{{ old('url', $popup->url) }}"
                            autocomplete="url" autofocus placeholder="017....3444 || *https://example.com/example">
                        @error('url')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>



                    <fieldset>
                        <div class="body-title">{{ __('Upload Image') }} <span class="tf-color-1">(Optional)</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="{{$popup->image ? 'display:flex; justify-content: center, align-items: center' : 'display:none'}}">
                                <img src="{{asset($popup->image?->file)}}" />
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

    $(document).ready(function() {
        $('#url_type').on('change', function() {

            if (this.value == 'whatsapp') {
                $("#url-box").show();
                $('#url').attr('placeholder', '017....3444');
                $('#url').val('');
            } else if (this.value == 'phone') {
                $("#url-box").show();
                $('#url').attr('placeholder', '017....3444');
                $('#url').val('');
            } else if(this.value == 'link') {
                $("#url-box").show();
                $('#url').attr('placeholder', 'https://example.com/example');
                $('#url').val('');
            }else {
                $('#url-box').hide();
            }
        });
    })
</script>
    <script>
                    const maxSize = 100 * 1024 * 1024; // 5MB file size limit

function validateFileSize(input, file) {
    if (file.size > maxSize) {
        Swal.fire({
            icon: "error",
            title: "File Too Large",
            text: `The file "${file.name}" exceeds the 100MB limit. Please upload a smaller file.`,
        });
        input.value = ""; // Clear the selected file
        return false;
    }
    return true;
}

        $(function() {
            $('#myFile').on('change', function() {
                var file = this.files[0];
                if (!validateFileSize(this, file)) return;
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imgpreview').show();
                    $('#imgpreview img').attr('src',e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

        })


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
