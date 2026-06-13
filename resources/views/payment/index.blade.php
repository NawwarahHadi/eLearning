@extends('layouts.app')

@section('title', 'Payment History')
@section('page-header', 'Payment History')

@section('css_after')
    <link href="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js_after')
    <script src="{{ asset('metronic/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('metronic/js/datatable.js') }}"></script>
@endsection

@section('content')
<div id="kt_content_container" class="container-xxl">

    {{-- Success / Error Alerts --}}
    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center mb-6">
        <i class="ki-duotone ki-shield-tick fs-2 me-3 text-success">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center mb-6">
        <i class="ki-duotone ki-shield-cross fs-2 me-3 text-danger">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    {{-- Summary Cards --}}
    <div class="row g-5 mb-6">
        {{-- Total Paid --}}
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
                            <span class="text-muted fs-7 d-block">Total Paid</span>
                            <span class="text-gray-800 fw-bolder fs-4">
                                RM {{ number_format($payments->where('status', 'paid')->sum('amount'), 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Transactions --}}
        <div class="col-sm-4">
            <div class="card card-flush">
                <div class="card-body p-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-trello fs-2x text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fs-7 d-block">Total Transactions</span>
                            <span class="text-gray-800 fw-bolder fs-4">
                                {{ $payments->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending --}}
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
                            <span class="text-muted fs-7 d-block">Pending</span>
                            <span class="text-gray-800 fw-bolder fs-4">
                                {{ $payments->where('status', 'pending')->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Table --}}
    <div class="card card-flush">
        <div class="card-header pt-6">
            <div class="card-title">
                <h3 class="fw-bold text-gray-800">My Payment History</h3>
            </div>
        </div>
        <div class="card-body py-4">
            <table class="m-datatable table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th>Subject</th>
                        <th>Month</th>
                        <th>Order ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Paid At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold fs-6">
                                    {{ $payment->enrollment->class->subject->name ?? 'N/A' }}
                                </span>
                                <span class="text-muted fs-8">
                                    Bill: {{ $payment->bill_code }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-600 fs-7">
                                {{ $payment->billing_month
                                    ? \Carbon\Carbon::parse($payment->billing_month . '-01')->format('M Y')
                                    : '—' }}
                            </span>
                        <td>
                            <span class="text-gray-600 fs-7">{{ $payment->order_id }}</span>
                        </td>

                        <td>
                            <span class="text-gray-800 fw-bold">
                                RM {{ number_format($payment->amount, 2) }}
                            </span>
                        </td>

                        <td>
                            @if($payment->status === 'paid')
                                <span class="badge badge-light-success fs-7">Paid</span>
                            @elseif($payment->status === 'pending')
                                <span class="badge badge-light-warning fs-7">Pending</span>
                            @else
                                <span class="badge badge-light-danger fs-7">Failed</span>
                            @endif
                        </td>

                        <td>
                            @if($payment->paid_at)
                                <span class="text-gray-600 fs-7">
                                    {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y, h:i A') }}
                                </span>
                            @else
                                <span class="text-muted fs-8">—</span>
                            @endif
                        </td>

                        <td>
                            @if($payment->status === 'pending')
                                <a href="{{ route('payment.show', $payment->enrollment_id) }}"
                                class="btn btn-sm btn-primary">
                                    Pay Now
                                </a>
                            @else
                                <a href="{{ route('payment.receipt', $payment->id) }}"
                                class="btn btn-sm btn-success" target="_blank">
                                    <i class="ki-duotone ki-file-down fs-4 me-1">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    View Receipt
                                </a>
                            @endif
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
        </div>
    </div>

</div>
@endsection
