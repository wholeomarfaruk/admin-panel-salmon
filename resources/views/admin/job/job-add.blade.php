@extends('admin.layouts.admin')

@section('content')
    <!-- content area start -->
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Add Job</h3>
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
                        <a href="{{ route('admin.job.list') }}">
                            <div class="text-tiny">jobs</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">New Job</div>
                    </li>
                </ul>
            </div>
            <!-- new-applicant -->


            <div class="wg-box">
                <form class="form-new-product form-style-1 needs-validation" action="{{ route('admin.job.store') }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <fieldset class="name">
                        <div class="body-title">{{ __('Title') }} <span class="tf-color-1">*</span></div>
                        <input class="flex-grow @error('title') is-invalid @enderror" type="text"
                            placeholder="Title" name="title" tabindex="0" aria-required="true"
                            value="{{ old('title') }}" required autocomplete="title" autofocus>
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('Description') }} <span class="tf-color-1">(optional)</span></div>
                        <textarea class="flex-grow @error('description') is-invalid @enderror" placeholder="description"
                            name="description" tabindex="0"  autocomplete="description" autofocus>{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">{{ __('Status') }} <span class="tf-color-1">*</span></div>
                        <select class="@error('status') is-invalid @enderror" name="status" required>
                            <option value="1" {{ old('status', ) }}> Active</option>
                            <option value="0" {{ old('status', ) }}> Inactive</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">{{ __('Requirements') }} <span class="tf-color-1">*</span></div>
                        <div id="requirements-container" class="w-full">
                            <!-- Default requirement item -->
                        </div>
                    </fieldset>

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>

            <!-- Include Sortable.js and TinyMCE -->

            <!-- JavaScript -->
            <script>
            document.addEventListener("DOMContentLoaded", function () {
                let container = document.getElementById("requirements-container");

                function updateInputNames() {
                    document.querySelectorAll(".requirement-item").forEach((item, index) => {
                        item.querySelector(".requirement-title").setAttribute("name", `requirements[${index}][title]`);
                        item.querySelector(".requirement-details").setAttribute("name", `requirements[${index}][description]`);
                    });
                }

                function createRequirementItem(title = "", description = "") {
                    let item = document.createElement("div");
                    item.classList.add("requirement-item");
                    item.classList.add("mb-10");
                    const uniqueId = `requirement-description-${Date.now()}`; // Unique ID for each description
                    item.innerHTML = `
                        <div class="requirement-header">
                            <button type="button" class="add-requirement"><i class="fas fa-plus"></i></button>
                            <button type="button" class="remove-requirement"><i class="fas fa-trash"></i></button>
                            <button type="button" class="drag-handle"><i class="fas fa-arrows-alt"></i></button>
                        </div>
                        <input type="text" class="requirement-title" placeholder="Requirement Title" value="${title}" required>
                        <textarea id="${uniqueId}" class="requirement-details description-editor" placeholder="Requirement Description">${description}</textarea>
                    `;
                    container.appendChild(item);
                    updateInputNames();

                    // Initialize TinyMCE for the dynamically created description textarea
                    tinymce.init({
                        selector: `#${uniqueId}`,
                        height: 300,
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

                    // Remove item event
                    item.querySelector(".remove-requirement").addEventListener("click", function () {
                        item.remove();
                        updateInputNames();
                    });

                    // Add new requirement
                    item.querySelector(".add-requirement").addEventListener("click", function () {
                        createRequirementItem();
                    });

                    // Detect changes
                    item.querySelector(".requirement-title").addEventListener("input", updateInputNames);
                    item.querySelector(".requirement-details").addEventListener("input", updateInputNames);
                }

                // Initialize sortable (drag & drop)
                new Sortable(container, {
                    animation: 150,
                    handle: ".drag-handle",
                    onEnd: updateInputNames
                });

                // Load existing requirements if any, otherwise create one default item
                let existingRequirements = @json(old('requirements', []));
                if (existingRequirements.length > 0) {
                    existingRequirements.forEach(req => createRequirementItem(req.title, req.description));
                } else {
                    createRequirementItem(); // Default item
                }
            });
            </script>


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
