@extends('layouts.app')

{{-- @section('title', 'User Management')

@section('page-header',  'User Management')

@section('breadcrumbs',  Breadcrumbs::render('pengguna') )

@section('css_after')
    <link href="{{ asset ('metronic/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
    <script>
        $(document).ready(function() {
            var table = $('.jadual').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('pengurusan-pengguna.index') }}",
                columns: [
                { data: 'nama_penuh', name: 'nama_penuh', searchable: true },
                { data: 'no_kad_pengenalan', name: 'no_kad_pengenalan' },
                { data: 'no_telefon', name: 'no_telefon' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end'  }
                ],
                dom:"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + // Length menu and filter at the top
                    "<'row'<'col-sm-12'tr>>" +                               // Table in the middle
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>", // Info and pagination at the bottom
                language: {
                    lengthMenu: "Paparan _MENU_ rekod",
                    search: "Carian:", // Change "Search" to "Carian"
                }
            });

            $('.jadual').on('click', '.hapus-data', function() {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Klik Teruskan untuk hapuskan data.',
                    icon: 'error',
                    confirmButtonText: 'Teruskan',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-danger",
                    }
                }).then((result) => {
                    if (result.value) {
                        window.location.href = $(this).attr("href");
                    }
                });
            });

            $('.jadual').on('click', '.set-kata-laluan', function() {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Klik Teruskan untuk set semula kata laluan.',
                    icon: 'error',
                    confirmButtonText: 'Teruskan',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-danger",
                    }
                }).then((result) => {
                    if (result.value) {
                        window.location.href = $(this).attr("href");
                    }
                });
            });
        });
    </script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">List of User</h3>
            <div class="card-toolbar">
                <a href="{{ route ('pengurusan-pengguna.tambah')}}" class="btn btn-sm btn-primary">
                    <i class="ki-duotone ki-plus-square">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Tambah
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table align-middle table-row-dashed fs-6 gy-5 jadual">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone Number </th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    <td class="text-end">
                        <a href="{{}}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Log Sebagai Pengguna">
                            <i class="ki-duotone text-info ki-fingerprint-scanning fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </a>
                        <a href="{{)}}"  type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="Kemaskini">
                            <i class="ki-duotone text-warning ki-notepad-edit fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </a>
                        <a href="{{}}" type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 set-kata-laluan" data-bs-toggle="tooltip" onclick="return false" title="Set Kata Laluan">
                            <i class="ki-duotone text-danger ki-password-check fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </a>
                        <a href="{{)}}" type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm hapus-data" data-bs-toggle="tooltip" onclick="return false" title="Padam">
                            <i class="ki-duotone text-danger ki-trash fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </a>
                    </td>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection --}}

@extends('layouts.app')

@section('title', 'User Management')

@section('page-header', 'User Management')

{{-- @section('breadcrumbs', Breadcrumbs::render('pengguna') ) --}}

@section('css_after')
    <link href="{{ asset ('metronic/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
    <script>
        $(document).ready(function() {
            // 5 Dummy users with Full Name, Email, and Phone Number only
            var dummyUsers = [
                { name: 'YASMIN ABDULLAH', email: 'cumin63@yahoo.com', phone: '0139375073' },
                { name: 'NOR HASLINA ZAKARIA', email: 'haslinabptm@gmail.com', phone: '0139830085' },
                { name: 'WAN HAFIDZULLAH BIN WAN YUSOFF', email: 'cogan.alam@gmail.com', phone: '0199563114' },
                { name: 'MUHAMMAD FAIZ BIN JAMALUDIN', email: 'muhammadfaiz@kelantan.gov.my', phone: '0139099413' },
                { name: 'MOHD ZAIDI BIN MOKHTAR', email: 'abediey@gmail.com', phone: '0135761800' }
            ];

            var table = $('.jadual').DataTable({
                responsive: true,
                processing: true,
                serverSide: false, // Turned off serverSide so it reads your array directly

                // Intercept data and load from our dummy array
                ajax: function(data, callback, settings) {
                    var mappedData = dummyUsers.map(function(user) {
                        return {
                            nama_penuh: `<a href="javascript:;" class="text-gray-800 text-hover-primary fw-bold mb-1 fs-6">${user.name}</a>`,
                            email: `<span class="text-gray-600">${user.email}</span>`,
                            no_telefon: `<span class="text-gray-600">${user.phone}</span>`,
                            actions: `
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="javascript:;" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="tooltip" title="Log Sebagai Pengguna">
                                        <i class="ki-duotone text-info ki-fingerprint-scanning fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="tooltip" title="Kemaskini">
                                        <i class="ki-duotone text-warning ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm set-kata-laluan" data-bs-toggle="tooltip" title="Set Kata Laluan">
                                        <i class="ki-duotone text-danger ki-password-check fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm hapus-data" data-bs-toggle="tooltip" title="Padam">
                                        <i class="ki-duotone text-danger ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </a>
                                </div>`
                        };
                    });

                    callback({ data: mappedData });
                },
                columns: [
                    { data: 'nama_penuh', name: 'nama_penuh', searchable: true },
                    { data: 'email', name: 'email' },
                    { data: 'no_telefon', name: 'no_telefon' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' }
                ],
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                language: {
                    lengthMenu: "Paparan _MENU_ rekod",
                    search: "Carian:",
                },
                drawCallback: function() {
                    // Reinitialize tooltips on search/pagination change
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });
                }
            });

            $('.jadual').on('click', '.hapus-data', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Klik Teruskan untuk hapuskan data.',
                    icon: 'error',
                    confirmButtonText: 'Teruskan',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-danger",
                    }
                }).then((result) => {
                    if (result.value) {
                        Swal.fire('Success', 'Data row removed.', 'success');
                    }
                });
            });

            $('.jadual').on('click', '.set-kata-laluan', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Klik Teruskan untuk set semula kata laluan.',
                    icon: 'error',
                    confirmButtonText: 'Teruskan',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-danger",
                    }
                }).then((result) => {
                    if (result.value) {
                        Swal.fire('Success', 'Password has been reset.', 'success');
                    }
                });
            });
        });
    </script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">List of User</h3>
            <div class="card-toolbar">
                <a href="" class="btn btn-sm btn-primary">
                    <i class="ki-duotone ki-plus-square">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    Add User
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table align-middle table-row-dashed fs-6 gy-5 jadual">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
