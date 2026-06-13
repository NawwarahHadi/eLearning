@extends('layouts.app')

@section('title', 'Payment Summary')
@section('page-header', 'Payment Summary')

@section('content')
<div id="kt_content_container" class="container-xxl">

    {{-- Already Paid This Month --}}
    @if($existingPayment)
    <div class="alert alert-success d-flex align-items-center mb-6">
        <i class="ki-duotone ki-shield-tick fs-2x me-3 text-success">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div>
            <strong>Payment Already Completed</strong> —
            Paid on {{ $existingPayment->paid_at?->format('d M Y, h:i A') }}
        </div>
    </div>
    @endif

    {{-- Error / Success session --}}
    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center mb-6">
        <i class="ki-duotone ki-shield-cross fs-2x me-3 text-danger">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div>{{ session('error') }}</div>
    </div>
    @endif

    <div class="row g-6">

        {{-- LEFT: BILLING DETAILS --}}
        <div class="col-lg-8">
            <div class="card card-flush mb-6">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold text-gray-800">Billing Details</h3>
                    </div>
                    @if($pendingPayment && $pendingPayment->billing_month)
                    <div class="card-toolbar">
                        <span class="badge badge-light-primary fs-7">
                            Billing Month: {{ \Carbon\Carbon::parse($pendingPayment->billing_month . '-01')->format('F Y') }}
                        </span>
                    </div>
                    @endif
                </div>
                <div class="card-body pt-4">

                    {{-- Student & Class Info --}}
                    <div class="row mb-7">
                        <div class="col-6">
                            <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                                <tr>
                                    <td class="text-gray-400 min-w-175px w-175px">Bill To:</td>
                                    <td class="text-gray-800">{{ $enrollment->student->email ?? auth()->user()->email }}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-400">Student Name:</td>
                                    <td class="text-gray-800">{{ $enrollment->student->name ?? auth()->user()->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-400">Tutor:</td>
                                    <td class="text-gray-800">{{ $enrollment->tutor->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-400">Subject:</td>
                                    <td class="text-gray-800">{{ $enrollment->class->subject->name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-6">
                            <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                                <tr>
                                    <td class="text-gray-400 min-w-175px w-175px">Class Code:</td>
                                    <td class="text-gray-800">{{ $enrollment->class->category_code }}</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-400">Rate:</td>
                                    <td class="text-gray-800">RM 50.00 / hour</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-400">Total Duration:</td>
                                    <td class="text-gray-800">{{ $totalHours }} hour(s)</td>
                                </tr>
                                <tr>
                                    <td class="text-gray-400">Payment Method:</td>
                                    <td class="text-gray-800">FPX Online Banking</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="separator mb-6"></div>

                    {{-- Session Breakdown --}}
                    <h5 class="fw-bold text-gray-800 mb-4">Session Breakdown</h5>
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-4">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th class="text-center">Duration</th>
                                    <th class="text-end">Fee (RM)</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach($breakdown as $slot)
                                <tr>
                                    <td class="text-gray-800 fw-bold">{{ $slot['day'] }}</td>
                                    <td>{{ $slot['start_time'] }} – {{ $slot['end_time'] }}</td>
                                    <td class="text-center">{{ $slot['hours'] }} hour(s)</td>
                                    <td class="text-end">RM {{ number_format($slot['fee'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold text-gray-800 fs-6">
                                    <td colspan="2" class="text-end">Total</td>
                                    <td class="text-center">{{ $totalHours }} hour(s)</td>
                                    <td class="text-end text-primary fs-5">RM {{ number_format($totalAmount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT: SUMMARY SIDEBAR --}}
        <div class="col-lg-4">
            <div class="card card-flush mb-6 sticky-top" style="top: 20px">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold text-gray-800">Summary</h3>
                    </div>
                </div>
                <div class="card-body pt-4">

                    {{-- Student Avatar --}}
                    <div class="d-flex align-items-center mb-6">
                        <div class="symbol symbol-50px me-4">
                            <div class="symbol-label bg-light-primary text-primary fw-bold fs-4">
                                {{ substr($enrollment->student->name ?? auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-800 fw-bold fs-6 d-block">
                                {{ $enrollment->student->name ?? auth()->user()->name }}
                            </span>
                            <span class="text-muted fs-7">
                                {{ $enrollment->student->email ?? auth()->user()->email }}
                            </span>
                        </div>
                    </div>

                    <div class="separator mb-6"></div>

                    {{-- Class Details --}}
                    <div class="mb-6">
                        <h6 class="fw-bold text-gray-800 mb-3">Class Details</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-gray-600 fs-7">{{ $enrollment->class->subject->name }}</span>
                            <span class="text-gray-800 fw-bold fs-7">RM {{ number_format($totalAmount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted fs-8">{{ $breakdown->count() }} slot(s) × RM50/hr</span>
                        </div>
                    </div>

                    <div class="separator mb-6"></div>

                    {{-- Payment Details --}}
                    <div class="mb-6">
                        <h6 class="fw-bold text-gray-800 mb-3">Payment Details</h6>
                        <div class="d-flex align-items-center mb-2">
                            <div class="symbol symbol-35px me-3">
                                <div class="symbol-label bg-light-warning">
                                    <i class="ki-duotone ki-bank fs-2x text-warning">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-800 fw-bold fs-7 d-block">FPX Online Banking</span>
                                <span class="text-gray-400 fs-8">Powered by ToyyibPay</span>
                            </div>
                        </div>
                    </div>

                    <div class="separator mb-6"></div>

                    {{-- Enrollment Details --}}
                    <div class="mb-8">
                        <h6 class="fw-bold text-gray-800 mb-3">Enrollment Details</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-gray-400 fs-7">Enrollment ID:</span>
                            <span class="text-gray-800 fw-bold fs-7">#{{ $enrollment->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-gray-400 fs-7">Class Code:</span>
                            <span class="text-gray-800 fw-bold fs-7">{{ $enrollment->class->category_code }}</span>
                        </div>
                        @if($pendingPayment && $pendingPayment->billing_month)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-gray-400 fs-7">Billing Month:</span>
                            <span class="text-gray-800 fw-bold fs-7">
                                {{ \Carbon\Carbon::parse($pendingPayment->billing_month . '-01')->format('M Y') }}
                            </span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-gray-400 fs-7">Status:</span>
                            @if($existingPayment)
                                <span class="badge badge-light-success fs-8">Paid</span>
                            @else
                                <span class="badge badge-light-warning fs-8">Pending Payment</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-gray-400 fs-7">Total Amount:</span>
                            <span class="text-primary fw-bolder fs-6">RM {{ number_format($totalAmount, 2) }}</span>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    @if($existingPayment)
                        <button class="btn btn-success w-100 fw-bold" disabled>
                            <i class="ki-duotone ki-check-circle fs-2x me-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Payment Completed
                        </button>
                    @elseif($pendingPayment)
                        {{-- Pay existing pending monthly bill --}}
                        <form action="{{ route('payment.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="enrollment_id" value="{{ $pendingPayment->enrollment_id }}">
                            <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
                            <input type="hidden" name="payment_id" value="{{ $pendingPayment->id }}">
                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                <i class="ki-duotone ki-credit-cart fs-2x me-2">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                Proceed to Payment
                            </button>
                        </form>
                    @else
                        {{-- No bill generated yet --}}
                        <div class="notice d-flex bg-light-warning rounded border border-warning p-4">
                            <i class="ki-duotone ki-information fs-2x text-warning me-3">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            <div class="text-gray-700 fs-7">
                                No bill generated for this month yet. Please wait for admin to generate your monthly bill.
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('student.class.index') }}" class="btn btn-light w-100 fw-bold mt-3">
                        <i class="ki-duotone ki-arrow-left fs-2x me-2">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Back to My Classes
                    </a>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
