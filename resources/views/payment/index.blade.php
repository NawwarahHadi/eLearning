@extends('layouts.app')

@section('title', 'Payment')

@section('page-header', 'Fee Management')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
    <script>
        $(document).on('click', '.action-button', function(e) {
            e.preventDefault();
            let action = $(this).data('action'); // Example: 'approve' or 'reject'
            let url = $(this).attr("href");

            Swal.fire({
                title: 'Warning!',
                text: 'Click Proceed to ' + action + ' this registration application.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Proceed',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-danger",
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // This part creates a hidden form to send a secure POST request
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = '@csrf';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
@endsection

@section('content')
{{-- <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Fee Management</h2>
        <!-- BULK GENERATE BUTTON -->
        <form action="{{ route('payment.generate-all-bills') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary" onclick="return confirm('Generate bills for all students for next month?')">
                <i class="fas fa-file-invoice-dollar"></i> Generate All Monthly Bills
            </button>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Current Enrollments</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->enrollments_count }} Classes</td>
                        <td>
                            <!-- SPECIFIC GENERATE BUTTON -->
                            <form action="{{ route('payment.generate-single-bill', $student->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    Generate Specific Bill
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> --}}

<div id="kt_content_container" class="container-xxl">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Payment</h3>
            <div class="card-toolbar">
                <form action="{{ route('payment.generate-all-bills') }}" method="POST">
                    @csrf
                    <a href="" class="btn btn-sm btn-primary">
                        <i class="ki-duotone ki-plus-square fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        Generate All Bills
                    </a>
                </form>


            </div>
        </div>
        <div class="card-body">

    </div>
</div>
@endsection
