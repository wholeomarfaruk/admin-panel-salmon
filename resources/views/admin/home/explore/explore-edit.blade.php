@extends('admin.layouts.admin')

@section('content')
    <!-- content area start -->
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Explore</h3>
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
                        <a href="{{ route('admin.home.explore.list') }}">
                            <div class="text-tiny">Explores</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit Explore</div>
                    </li>
                </ul>
            </div>
            <!-- new-Blog -->
            <div class="wg-box">
                <form class="form-new-product form-style-1 needs-validation" action="{{ route('admin.home.explore.update',['id'=>$explore->id]) }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <fieldset class="name">
                        <div class="body-title">{{ __('Name') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('name') is-invalid @enderror" type="text"
                            placeholder="Name" name="name" tabindex="0" aria-required="true"
                            value="{{ old('name', $explore->name) }}" required autocomplete="name" autofocus
                            >
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">{{ __('Address') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('address') is-invalid @enderror" type="text" placeholder="Address"
                            name="address" tabindex="0" value="{{ old('address',$explore->address) }}" aria-required="true"
                            autocomplete="address" autofocus >
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('Project') }} <span class="tf-color-1">*</span></div>
                        <select class="flex-grow @error('project_id') is-invalid @enderror" name="project_id"
                            aria-required="true" required autocomplete="project_id" autofocus>
                            <option value="">{{ __('Select Project') }}</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $explore->project_id) == $project->id ? 'selected' : '' }}>{{ $project->title }}</option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>


                    <fieldset>
                        <div class="body-title">{{ __('Upload Video') }} <span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="{{$explore->video ? 'display:flex; justify-content: center, align-items: center' : 'display:none'}}">
                                <video src="{{asset($explore->video->file)}}" controls></video>
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">{{ __('Drop your videos here or select') }} <span
                                            class="tf-color">{{ __('click to browse') }}</span></span>
                                    <input type="file" id="myFile" name="video_url" accept="video/*">
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
                    $('#imgpreview').html('<video src="' + e.target.result + '" controls></video>');
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
