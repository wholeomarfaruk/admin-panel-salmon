@extends('admin.layouts.admin')

@section('content')
    <!-- content area start -->
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Applicant</h3>
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
                        <a href="{{ route('admin.job.applicant.list') }}">
                            <div class="text-tiny">Applicants</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit Applicant</div>
                    </li>
                </ul>
            </div>
            <!-- new-applicant -->
            <div class="wg-box">
                <form class="form-new-product form-style-1 needs-validation"
                    action="{{ route('admin.job.applicant.update',['id'=>$applicant->id]) }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                    <fieldset class="name">
                        <div class="body-title">{{ __('Name') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('name') is-invalid @enderror" type="text"
                            placeholder="Name" name="name" tabindex="0" aria-required="true"
                            value="{{ old('name', $applicant->name) }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('Phone') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('phone') is-invalid @enderror" type="text"
                            placeholder="Phone" name="phone" tabindex="0" aria-required="true"
                            value="{{ old('phone', $applicant->phone) }}" required autocomplete="phone" autofocus>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('Email') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('email') is-invalid @enderror" type="email"
                            placeholder="Email" name="email" tabindex="0" aria-required="true"
                            value="{{ old('email', $applicant->email) }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('Subject') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('subject') is-invalid @enderror" type="text"
                            placeholder="Subject" name="subject" tabindex="0" aria-required="true"
                            value="{{ old('subject', $applicant->subject) }}" required autocomplete="subject" autofocus>
                        @error('subject')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('Message') }} <span class="tf-color-1">*</span></div>
                        <textarea class="flex-grow @error('message') is-invalid @enderror" placeholder="Message"
                            name="message" tabindex="0" aria-required="true" required autocomplete="message" autofocus>{{ old('message', $applicant->message) }}</textarea>
                        @error('message')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('CV Link') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('cv_link') is-invalid @enderror" type="text"
                            placeholder="CV Link" name="cv_link" tabindex="0" aria-required="true"
                            value="{{ old('cv_link', $applicant->cv_link) }}" required autocomplete="cv_link" autofocus>
                        @error('cv_link')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('Status') }} <span class="tf-color-1">*</span></div>
                        <select class="@error('status') is-invalid @enderror" name="status" required>
                            <option value="0" {{ old('status', $applicant->status) == \App\Enums\applicantStatus::Pending->value ? 'selected' : '' }}> Pending
                            </option>
                            <option value="1" {{ old('status', $applicant->status) == \App\Enums\applicantStatus::Accepted->value ? 'selected' : '' }}> Accepted
                            </option>
                            <option value="2" {{ old('status', $applicant->status) == \App\Enums\applicantStatus::Rejected->value ? 'selected' : '' }}> Rejected
                            </option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('Link Job') }} <span class="tf-color-1">(Optional)</span></div>
                        <select class="@error('job_id') is-invalid @enderror" name="job_id">
                            <option value="">Select Job</option>
                            @foreach ($jobs as $job)
                                <option value="{{ $job->id }}" {{ old('job_id', $applicant->job_id) == $job->id ? 'selected' : '' }}> {{ $job->title }}
                                </option>
                            @endforeach
                        </select>

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
