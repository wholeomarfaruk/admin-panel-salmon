@extends('admin.layouts.admin')

@section('content')
    <!-- content area start -->
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Stats</h3>
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
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit Stats</div>
                    </li>
                </ul>
            </div>
            <!-- new-Blog -->
            @foreach ($stats as $stat)


            <div class="wg-box">
                <form class="form-new-product form-style-1 needs-validation" action="{{ route('admin.home.stats.update',['id'=>$stat->id]) }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <fieldset class="name">
                        <div class="body-title">{{ __('Title') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('title') is-invalid @enderror" type="text"
                            placeholder="Title" name="title" tabindex="0" aria-required="true"
                            value="{{ old('title', $stat->title) }}" required autocomplete="title" autofocus
                            >
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>



                    <fieldset>
                        <div class="body-title">{{ __('Upload Image') }} <span class="tf-color-1">(Optional)</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="{{$stat->image ? 'display:flex; justify-content: center, align-items: center' : 'display:none'}}">
                                <img src="{{asset($stat->image?->file)}}" />
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

                    @foreach (json_decode($stat->stats) as $index => $item)


                    <h4>Stats Item {{ $index + 1 }}</h4>


                    <fieldset class="name">
                        <div class="body-title">{{ __('stats tile '). $index + 1 }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow " type="text" placeholder="stats title" required
                            name="stats[{{ $index }}][title]" tabindex="0" value="{{ $item->title }}">
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">{{ __('stats number '). $index + 1 }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow " type="text" placeholder="stats number" required
                            name="stats[{{ $index }}][number]" tabindex="0" value="{{ $item->number }}" >
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">{{ __('stats number Prefix '). $index + 1 }} <span class="tf-color-1"></span></div>
                        <input class="flex-grow " type="text" placeholder="number Prefix"
                            name="stats[{{ $index }}][number_prefix]" tabindex="0" value="{{ $item->number_prefix }}">
                    </fieldset>
                    @endforeach

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">{{ __('Update') }}</button>
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
