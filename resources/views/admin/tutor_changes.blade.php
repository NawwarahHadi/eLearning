@extends('layouts.app')

@section('content')
<div class="container-xxl">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title fw-bold text-gray-800">Tutor Change Requests</h3>
        </div>
        <div class="card-body">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th>Student</th>
                        <th>Subject</th>
                        <th>New Requested Tutor</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @foreach ($changeRequests as $request)
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold">{{ $request->student->nama_penuh }}</span>
                                    <span class="text-muted fs-7">{{ $request->student->email }}</span>
                                </div>
                            </td>
                            <td>{{ $request->class->subject->name }}</td>
                            <td>
                                <span class="badge badge-light-primary fs-7">
                                    {{ $request->tutor->nama_penuh }}
                                </span>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('enrollment.admin.approveChange', $request->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="ki-duotone ki-arrows-loop fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        Approve Changes & Cancel Old
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($changeRequests->isEmpty())
                <div class="text-center p-10">
                    <span class="text-muted">No pending tutor change requests.</span>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
