@extends('admin.layouts.admin')

@section('content')
    <!-- content area start -->
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Settings</h3>
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
                        <div class="text-tiny">Settings</div>
                    </li>
                </ul>
            </div>
            <!-- settings -->
            <div class="wg-box mb-20">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        {{-- <form class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Search here..." class="" name="name"
                                    tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form> --}}
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.settings.add') }}"><i class="icon-plus"></i>Add
                        new field</a>
                </div>
            </div>
            @foreach ($settings as $setting)
                <div class="wg-box mb-20">
                    <form class="form-new-product form-style-1 needs-validation"
                        action="{{ route('admin.settings.update', $setting->id) }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')
                        <fieldset class="name">
                            <div class="body-title">{{ __($setting->key) }} <span class="tf-color-1"></span></div>
                            <input class="flex-grow @error('name') is-invalid @enderror" type="text" placeholder="value"
                                name="data[{{ $setting->key }}]" tabindex="0" aria-required="true" value="{{ $setting->value }}"
                                required autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </fieldset>



                        <div class="bot">
                            <div></div>
                            <button class="tf-button w208" type="submit"><i class="icon-save"></i>
                                {{ __('Save') }}</button>



                                <button class="tf-button w208 delete"
                                    type="button"><i class="icon-trash-2"></i> {{ __('Delete') }}</button>

                        </div>
                    </form>
                    <form class="deleteform" action="{{ route('admin.settings.delete', $setting->id) }}"
                        method="post">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            @endforeach

        </div>
    </div>
    <!-- content area end -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).parents('div.wg-box').find('.deleteform');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
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
