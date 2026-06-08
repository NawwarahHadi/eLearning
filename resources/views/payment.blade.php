@extends('layouts.app')

@section('title', 'Invoice Details')

@section('page-header', 'Invoice Management')

@section('content')
<div id="kt_content_container" class="container-xxl">

    <div class="d-flex flex-wrap flex-stack mb-6 print-none">
        <div class="d-flex align-items-center position-relative my-1">
            <h1 class="text-dark fw-bold fs-2 my-1">Invoice #INV-2026-0042</h1>
        </div>
        <div class="d-flex my-1 gap-3">
            <button type="button" class="btn btn-light-primary" onclick="window.print();">
                <i class="ki-duotone ki-printer fs-3 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i> Print Invoice
            </button>
            <button type="button" class="btn btn-primary">
                <i class="ki-duotone ki-send fs-3 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i> Send to Student
            </button>
        </div>
    </div>

    <div class="card shadow-sm invoice-printable">
        <div class="card-body p-lg-20">

            <div class="d-flex flex-column flex-sm-row justify-content-between mb-15">
                <div class="mb-6 mb-sm-0">
                    <h1 class="fw-bolder text-gray-800 fs-2x mb-2">AL-AMIN TUITION CENTRE</h1>
                    <div class="text-muted fw-semibold fs-6">
                        <p class="mb-1">123, Jalan Sultan Azlan Shah,</p>
                        <p class="mb-1">11700 Gelugor, Penang, Malaysia</p>
                        <p class="mb-0">Contact: +604-6533888 | info@alamin.edu.my</p>
                    </div>
                </div>

                <div class="text-sm-end">
                    <div class="text-primary fw-bolder fs-2 text-uppercase mb-3">Invoice</div>
                    <div class="text-gray-700 fw-bold fs-6 mb-1">Date Issued: <span class="fw-normal text-muted">20 May 2026</span></div>
                    <div class="text-gray-700 fw-bold fs-6">Due Date: <span class="fw-normal text-muted">05 June 2026</span></div>
                </div>
            </div>

            <div class="row mb-15">
                <div class="col-sm-6 mb-6 mb-sm-0">
                    <div class="text-gray-500 fw-bold fs-7 text-uppercase mb-3">Billed To (Student / Guardian):</div>
                    <div class="text-gray-800 fw-bolder fs-5 mb-1">Ahmad Daniel Bin Rosli</div>
                    <div class="text-muted fw-semibold fs-6">
                        <p class="mb-1">Guardian: Rosli Bin Ahmad</p>
                        <p class="mb-1">danial.rosli@gmail.com</p>
                        <p class="mb-0">+6017-6543210</p>
                    </div>
                </div>

                <div class="col-sm-6 text-sm-end">
                    <div class="text-gray-500 fw-bold fs-7 text-uppercase mb-3">Payment Overview:</div>
                    <div class="text-gray-800 fw-bolder fs-5 mb-1">Bank Transfer / Online FPX</div>
                    <div class="text-muted fw-semibold fs-6">
                        <p class="mb-1">Bank Name: Maybank Berhad</p>
                        <p class="mb-1">Account No: 1640-1234-5678</p>
                        <p class="mb-0">Status: <span class="badge badge-light-danger fw-bold fs-8">UNPAID</span></p>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-10">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0 border-bottom border-gray-200">
                            <th class="min-w-300px pb-4">Subject Description</th>
                            <th class="text-end min-w-100px pb-4">Rate / Month</th>
                            <th class="text-end min-w-100px pb-4">Hours / Class</th>
                            <th class="text-end min-w-150px pb-4">Total Price</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                        <tr class="border-bottom border-gray-200">
                            <td>
                                <div class="fw-bold fs-6 text-gray-800">Mathematics Class (Form 5)</div>
                                <div class="text-muted fs-7">Tutor: Jasmine Abdullah (Monthly Tuition Commitment fee)</div>
                            </td>
                            <td class="text-end">RM 60.00</td>
                            <td class="text-end">8 Hours</td>
                            <td class="text-end text-gray-800 fw-bolder">RM 60.00</td>
                        </tr>
                        <tr class="border-bottom border-gray-200">
                            <td>
                                <div class="fw-bold fs-6 text-gray-800">Physics Class (Form 5)</div>
                                <div class="text-muted fs-7">Tutor: Muhammad Faiz Bin Jamaludin</div>
                            </td>
                            <td class="text-end">RM 65.00</td>
                            <td class="text-end">8 Hours</td>
                            <td class="text-end text-gray-800 fw-bolder">RM 65.00</td>
                        </tr>
                        <tr class="border-bottom border-gray-200">
                            <td>
                                <div class="fw-bold fs-6 text-gray-800">Online Platform Access Fee</div>
                                <div class="text-muted fs-7">Registration & Cloud Portal system management maintenance</div>
                            </td>
                            <td class="text-end">RM 10.00</td>
                            <td class="text-end">1 Month</td>
                            <td class="text-end text-gray-800 fw-bolder">RM 10.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mb-10">
                <div class="w-100 w-sm-300px">
                    <div class="d-flex flex-stack mb-3">
                        <div class="fw-semibold text-muted fs-6">Subtotal:</div>
                        <div class="fw-bold text-gray-800 fs-6">RM 135.00</div>
                    </div>
                    <div class="d-flex flex-stack mb-3">
                        <div class="fw-semibold text-muted fs-6">Tax / Service Charge (0%):</div>
                        <div class="fw-bold text-gray-800 fs-6">RM 0.00</div>
                    </div>
                    <div class="d-flex flex-stack border-top border-gray-200 pt-3">
                        <div class="fw-bolder text-gray-800 fs-4">Grand Total:</div>
                        <div class="fw-bolder text-primary fs-4">RM 135.00</div>
                    </div>
                </div>
            </div>

            <div class="border-left border-3 border-primary bg-light-primary p-5 rounded">
                <div class="text-primary fw-bold fs-6 mb-1">Important Notice:</div>
                <div class="text-gray-700 fw-semibold fs-7">
                    Please upload your online transaction payment receipt through the mobile dashboard link window within 5 days of issue to ensure ongoing portal server assignment privileges.
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    @media print {
        .print-none { display: none !important; }
        body * { visibility: hidden; }
        .invoice-printable, .invoice-printable * { visibility: visible; }
        .invoice-printable { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none !important; }
    }
</style>
@endsection
