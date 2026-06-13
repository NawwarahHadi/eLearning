@extends('layouts.app')

@section('title', 'Add New User')
@section('page-header', 'Add New User')

@section('content')
<div id="kt_content_container" class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card card-flush">
                <div class="card-header pt-6">
                    <div class="card-title">
                        <h3 class="fw-bold text-gray-800">
                            <i class="ki-duotone ki-profile-circle fs-2x text-primary me-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                            Create New User
                        </h3>
                    </div>
                </div>
                <div class="card-body pt-6">
                    @if($errors->any())
                    <div class="alert alert-danger mb-6">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('user-management.simpan') }}" method="POST">
                        @csrf

                        <div class="mb-5">
                            <label class="form-label fw-bold required">Full Name</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name') }}" placeholder="Enter full name" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold required">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email') }}" placeholder="Enter email address" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold required">Password</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Minimum 8 characters" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold required">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                   placeholder="Repeat password" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold required">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Select Role --</option>
                                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="tutor"   {{ old('role') === 'tutor'   ? 'selected' : '' }}>Tutor</option>
                                <option value="admin"   {{ old('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div class="mb-8">
                            <label class="form-label fw-bold required">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending"  {{ old('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="d-flex gap-3">
                            <a href="{{ route('user-management.index') }}" class="btn btn-light w-100 fw-bold">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                <i class="ki-duotone ki-check fs-2x me-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
