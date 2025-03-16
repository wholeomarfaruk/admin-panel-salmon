
@extends('admin.layouts.admin')

@push('styles')
<style>
    .table-striped th:nth-child(2), .table-striped td:nth-child(2) {
    width: inherit;
    padding-bottom: inherit;
}
.table-striped th:nth-child(2), .table-striped td:nth-child(2) {
    width: auto;
    padding-bottom: inherit;
}
.table-striped th:nth-child(1), .table-striped td:nth-child(1) {
    width: 100px;
    padding-bottom: inherit;
}
.table-striped th:nth-child(1), .table-striped td:nth-child(1) {
    width: 100px;
    padding-bottom: inherit;
}
.table-striped th:nth-child(4), .table-striped td:nth-child(4) {
    width: 200px;
    padding-bottom: inherit;
}
</style>
@endpush
@section('content')

    <!-- content area start -->
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>File Uploads</h3>
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
                        <div class="text-tiny">File Uploads</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow gap-2">
                        {{-- <form class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Search here..." class="" name="name"
                                    tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form> --}}
                        <button class="btn btn-outline-secondary" id="bulk-select-button">Select</button>
                        <button class="btn btn-outline-secondary" id="all-select-button">All Select</button>


                            <button id="bulk-delete-button" type="submit" class="btn btn-outline-danger" >
                                <i class="icon-trash-2"></i>Bulk Delete
                            </button>
                            <script>
                                var  toggle= false;
                                document.getElementById('all-select-button').addEventListener('click', () => {

                                    toggle = !toggle;
                                    if (toggle) {
                                        $('input.select-item').show();
                                        document.querySelectorAll('input.select-item').forEach(el => el.checked = true);
                                    } else {
                                        $('input.select-item').hide();
                                        document.querySelectorAll('input.select-item').forEach(el => el.checked = false);
                                    }

                                });



                            </script>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.fileupload.add') }}"><i
                            class="icon-plus"></i>Upload New</a>
                </div>
                <div class="wg-table">

                        @if (Session::has('status'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('status') }}
                        </div>
                         @endif

                         <style>
                            .grid{
                                display: grid;
                                gap: 0.5rem;
                                grid-auto-flow: row;
                            }
                            .grid-cols-4{
                                grid-template-columns: repeat(auto-fit,minmax(200px, 1fr));

                            }
                            .grid-item{
                                height: 200px;
                            }
                            .grid-item:hover{

                                box-shadow: 0px 0px 8px 0px rgba(207, 206, 206, 0.75);
                                transition: box-shadow 0.2s ease-in-out;
                            }
                         </style>



                    <div class="divider"></div>
                    <div class="grid grid-cols-4 gap-4 p-3">
                        @foreach ($fileuploads as $fileupload )
                        <div class="border rounded p-2 grid-item ">
                            <div data-id="{{ $fileupload->id }}" class="p-2 d-flex flex-column position-relative justify-content-center align-items-center" style="height: 80%">
                                <input type="checkbox" class="form-check-input select-item position-absolute  p-2" name="ids[] " value="{{ $fileupload->id }}" style="display: none; z-index: 1; top:10px;left:10px; background-color: seashell !important;">
                                @if ($fileupload->type==\App\Enums\UploadFileTypes::Image->value)
                                <img style="cursor: pointer; "  src="{{ asset($fileupload->file) }}" alt="" class=" rounded w-full h-full object-cover">
                                @elseif ($fileupload->type==\App\Enums\UploadFileTypes::Video->value)
                                <img style="cursor: pointer;  "  src="{{ asset("admin/images/icons/video.png") }}" alt="" class="rounded w-50 object-cover">
                                <span style="cursor: pointer; text-align: center; line-height: 16px;" >{{ pathinfo($fileupload->file)['basename'] }}</span>
                                @elseif ($fileupload->type==\App\Enums\UploadFileTypes::Audio->value)
                                <img style="cursor: pointer;  "  src="{{ asset("admin/images/icons/audio.png") }}" alt="" class="rounded w-50 object-cover">
                                <span style="cursor: pointer; text-align: center; line-height: 16px;" >{{ pathinfo($fileupload->file)['basename'] }}</span>
                                @elseif ($fileupload->type==\App\Enums\UploadFileTypes::Gif->value)
                                <img style="cursor: pointer; "  src="{{ asset($fileupload->file) }}" alt="" class="rounded w-full h-full object-cover">
                                @elseif ($fileupload->type==\App\Enums\UploadFileTypes::PDF->value)
                                <img style="cursor: pointer;  "  src="{{ asset("admin/images/icons/pdf.png") }}" alt="" class="rounded w-50 object-cover">
                                <span style="cursor: pointer; text-align: center; line-height: 16px;" >{{ pathinfo($fileupload->file)['basename'] }}</span>
                                @elseif ($fileupload->type==\App\Enums\UploadFileTypes::Docs->value)
                                <img style="cursor: pointer;  "  src="{{ asset("admin/images/icons/docs.png") }}" alt="" class="rounded w-50 object-cover">

                                <span style="cursor: pointer; text-align: center; " >{{ $fileupload->file->GetClientOriginalName() }}</span>
                                @endif
                            </div>


                                <div class="d-flex flex-row gap-2 justify-content-center align-items-center mt-2" style="height: 20%">
                                    <a href="{{ asset($fileupload->file) }}" target="_blank" class="btn  btn-circle btn-outline-secondary">
                                        <i class="icon-eye"></i>
                                    </a>

                                    <button class="btn  btn-circle btn-outline-primary" type="button"
                                        onclick="copyToClipboard('{{ asset($fileupload->file) }}')">
                                        <i class="icon-copy"></i>
                                    </button>
                                    {{-- <a href="{{ route('admin.fileupload.edit', ['id'=>$fileupload->id]) }}"
                                        class="btn  btn-circle btn-outline-primary">
                                        <i class="icon-edit-3"></i>
                                    </a> --}}
                                    <form style="display: inline-block;" action="{{ route('admin.fileupload.delete', ['id'=>$fileupload->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn delete  btn-circle btn-outline-danger"
                                            type="submit"><i class="icon-trash-2"></i></button>
                                    </form>
                                </div>


                        </div>
                        @endforeach



                    </div>
                    <div class="divider"></div>
                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination mt-5">
                        {{ $fileuploads->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- content area end -->
@endsection
@push('scripts')


<script>
    $("#bulk-select-button").click(function() {
        $(".select-item").toggle();

        $(".select-item").prop('checked', false);

    })



    document.getElementById('bulk-delete-button').addEventListener('click', () => {
        console.log('clicked');

        var selected = document.querySelectorAll('input.select-item:checked');
        const ids = selected ? [...selected].map(el => el.value) : [];

        if (ids.length > 0) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('admin.fileupload.bulk.delete') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            ids: ids
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Files deleted successfully',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Something went wrong'
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while deleting files'
                        });
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'No selection',
                text: 'Please select at least one enquiry to delete.'
            });
        }
    });

</script>

    <script>
        $(document).ready(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
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

        function copyToClipboard(link) {

            navigator.clipboard.writeText(link).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied to clipboard',
                    showConfirmButton: false,
                    timer: 1500
                })
                console.log('Link copied to clipboard');
            }).catch(err => {
                console.error('Failed to copy link: ', err);
            });

        }
    </script>
@endpush
