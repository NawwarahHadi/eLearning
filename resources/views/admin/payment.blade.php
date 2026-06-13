@extends('layouts.app')

@section('title', 'Payment Management')
@section('page-header', 'Payment Management')

@section('content')
<div id="kt_content_container" class="container-xxl">

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center mb-6">
        <i class="ki-duotone ki-shield-tick fs-2x me-3 text-success">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center mb-6">
        <i class="ki-duotone ki-shield-cross fs-2x me-3 text-danger">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    {{-- Summary Cards --}}
    <div class="row g-5 mb-6">
        <div class="col-sm-4">
            <div class="card card-flush">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-success">
                                <i class="ki-duotone ki-dollar fs-2x text-success">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fs-7 d-block">Total Collected</span>
                            <span class="text-gray-800 fw-bolder fs-4">
                                RM {{ number_format($totalPaid, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-flush">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-time fs-2x text-warning">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fs-7 d-block">Pending Payments</span>
                            <span class="text-gray-800 fw-bolder fs-4">{{ $totalPending }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-flush">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-danger">
                                <i class="ki-duotone ki-cross-circle fs-2x text-danger">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fs-7 d-block">Failed Payments</span>
                            <span class="text-gray-800 fw-bolder fs-4">{{ $totalFailed }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-6">

        {{-- LEFT: Generate Bills --}}
        <div class="col-lg-4">

            {{-- Generate All Classes --}}
            <div class="card card-flush mb-6">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <h5 class="fw-bold text-gray-800">
                            <i class="ki-duotone ki-bill fs-2x text-primary me-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                <span class="path4"></span><span class="path5"></span><span class="path6"></span>
                            </i>
                            Generate All Bills
                        </h5>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <p class="text-muted fs-7 mb-4">Generate monthly bills for ALL students across all classes at once.</p>
                    <form action="{{ route('payment.generate-all-bills') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Billing Month</label>
                            <input type="month"
                                   name="billing_month"
                                   class="form-control"
                                   value="{{ now()->format('Y-m') }}"
                                   required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="ki-duotone ki-send fs-2 me-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Generate All Monthly Bills
                        </button>
                    </form>
                </div>
            </div>

            {{-- Generate Bill by Student --}}
            <div class="card card-flush mb-6">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <h5 class="fw-bold text-gray-800">
                            <i class="ki-duotone ki-bill fs-2 text-warning me-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                <span class="path4"></span><span class="path5"></span><span class="path6"></span>
                            </i>
                            Generate Bill by Student
                        </h5>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <p class="text-muted fs-7 mb-4">Generate monthly bill for a specific student.</p>
                    <form action="{{ route('payment.generate-single-bill', 0) }}" method="POST" id="studentBillForm">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Student</label>
                            <select name="student_id" class="form-select" required>
                                <option ></option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->name }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Billing Month</label>
                            <input type="month"
                                name="billing_month"
                                class="form-control"
                                value="{{ now()->format('Y-m') }}"
                                required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">
                            <i class="ki-duotone ki-send fs-2x me-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Generate Bill for This Student
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- RIGHT: Transaction Table --}}
        <div class="col-lg-8">
            <div class="card card-flush">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <h5 class="fw-bold text-gray-800">All Transactions</h5>
                    </div>
                    <div class="card-toolbar">
                        <span class="text-muted fs-7">
                            Showing {{ $payments->firstItem() }}–{{ $payments->lastItem() }}
                            of {{ $payments->total() }} records
                        </span>
                    </div>
                </div>
                <div class="card-body py-4">
                    <table class="table align-middle table-row-dashed fs-6 gy-4">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th>#</th>
                                <th>Student</th>
                                <th>Subject</th>
                                <th>Month</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Paid At</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payments->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold fs-7">
                                            {{ $payment->student->name ?? 'N/A' }}
                                        </span>
                                        <span class="text-muted fs-8">
                                            {{ $payment->student->email ?? '' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-gray-800 fs-7">
                                        {{ $payment->enrollment->class->subject->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-gray-600 fs-7">
                                        {{ $payment->billing_month
                                            ? \Carbon\Carbon::parse($payment->billing_month . '-01')->format('M Y')
                                            : '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-gray-800 fw-bold">
                                        RM {{ number_format($payment->amount, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->status === 'paid')
                                        <span class="badge badge-light-success fs-8">Paid</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge badge-light-warning fs-8">Pending</span>
                                    @else
                                        <span class="badge badge-light-danger fs-8">Failed</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-gray-600 fs-8">
                                        {{ $payment->paid_at
                                            ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y')
                                            : '—' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-8">
                                    <i class="ki-duotone ki-receipt fs-3x text-muted mb-3 d-block">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    No payment records found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    @if($payments->hasPages())
                    <div class="d-flex justify-content-end mt-4">
                        <ul class="pagination">
                            {{-- Previous --}}
                            <li class="page-item previous {{ $payments->onFirstPage() ? 'disabled' : '' }}">
                                <a href="{{ $payments->previousPageUrl() }}" class="page-link">
                                    <i class="previous"></i>
                                </a>
                            </li>

                            {{-- Page Numbers --}}
                            @foreach($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
                                <li class="page-item {{ $payments->currentPage() == $page ? 'active' : '' }}">
                                    <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                                </li>
                            @endforeach

                            {{-- Next --}}
                            <li class="page-item next {{ !$payments->hasMorePages() ? 'disabled' : '' }}">
                                <a href="{{ $payments->nextPageUrl() }}" class="page-link">
                                    <i class="next"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>

<script>
function updateSingleBillAction(classId) {
    if (classId) {
        document.getElementById('singleBillForm').action = '/payment/bills/' + classId;
    }
}
</script>
@endsection
