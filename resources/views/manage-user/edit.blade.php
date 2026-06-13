@extends('layouts.app')

@section('title', 'Edit User')
@section('page-header', 'Edit User')

@section('content')
<div id="kt_content_container" class="container-xxl">

    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center mb-6">
        <i class="ki-duotone ki-shield-tick fs-2x me-3 text-success">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger d-flex align-items-center mb-6">
        <i class="ki-duotone ki-shield-cross fs-2x me-3 text-danger">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <ul class="mb-0 ms-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('user-management.update', $user->id) }}" method="POST">
        @csrf
        @method('POST')

        <div class="row g-6">

            {{-- LEFT: Single Profile Card --}}
            <div class="col-lg-8">
                <div class="card card-flush">

                    {{-- Card Header --}}
                    <div class="card-header pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-55px me-4">
                                    @if($user->role === 'tutor' && $user->tutorProfile?->profile_photo)
                                        <img src="{{ asset('storage/' . $user->tutorProfile->profile_photo) }}"
                                             class="rounded-circle" style="width:55px;height:55px;object-fit:cover;">
                                    @elseif($user->role === 'student' && $user->studentProfile?->profile_photo)
                                        <img src="{{ asset('storage/' . $user->studentProfile->profile_photo) }}"
                                             class="rounded-circle" style="width:55px;height:55px;object-fit:cover;">
                                    @else
                                        <div class="symbol-label fw-bold fs-2
                                            {{ $user->role === 'student' ? 'bg-light-primary text-primary' :
                                               ($user->role === 'tutor' ? 'bg-light-success text-success' : 'bg-light-warning text-warning') }}">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="fw-bold text-gray-800 mb-1">{{ $user->name }}</h3>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted fs-7">{{ $user->email }}</span>
                                        @if($user->role === 'student')
                                            <span class="badge badge-light-primary">Student</span>
                                        @elseif($user->role === 'tutor')
                                            <span class="badge badge-light-success">Tutor</span>
                                        @else
                                            <span class="badge badge-light-warning">Admin</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            @if($user->status === 'approved')
                                <span class="badge badge-light-success fs-7">
                                    <i class="fas fa-circle text-success fs-9 me-1"></i> Active
                                </span>
                            @elseif($user->status === 'pending')
                                <span class="badge badge-light-warning fs-7">
                                    <i class="fas fa-circle text-warning fs-9 me-1"></i> Pending
                                </span>
                            @else
                                <span class="badge badge-light-danger fs-7">
                                    <i class="fas fa-circle text-danger fs-9 me-1"></i> Rejected
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body pt-6">

                        {{-- ── Account Information ── --}}
                        <div class="d-flex align-items-center mb-5">
                            <h5 class="fw-bold text-gray-700 mb-0">Account Information</h5>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold required">Full Name</label>
                                <input type="text" name="name" class="form-control form-control-solid"
                                       value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold required">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-solid"
                                       value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control form-control-solid"
                                       value="{{ old('phone_number', $user->phone_number ?? '') }}"
                                       placeholder="e.g. 0123456789">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold required">Account Status</label>
                                <select name="status" class="form-select form-select-solid" required>
                                    <option value="approved" {{ old('status', $user->status) === 'approved' ? 'selected' : '' }}>✅ Approved</option>
                                    <option value="pending"  {{ old('status', $user->status) === 'pending'  ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="rejected" {{ old('status', $user->status) === 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                                </select>
                            </div>
                        </div>

                        {{-- ── Student Profile ── --}}
                        @if($user->role === 'student')
                        <div class="separator my-6"></div>
                        <div class="d-flex align-items-center mb-5">
                            <h5 class="fw-bold text-gray-700 mb-0">Student Profile</h5>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Age</label>
                                <input type="number" name="age" class="form-control form-control-solid"
                                       value="{{ old('age', $user->studentProfile?->age) }}"
                                       placeholder="e.g. 15" min="10" max="20">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Form Level</label>
                                <select name="category" class="form-select form-select-solid">
                                    <option value="">-- Select Form Level --</option>
                                    @foreach(['form-1','form-2','form-3','form-4','form-5'] as $cat)
                                        <option value="{{ $cat }}"
                                            {{ old('category', $user->studentProfile?->category) === $cat ? 'selected' : '' }}>
                                            {{ strtoupper($cat) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold">Address</label>
                            <input type="text" name="address" class="form-control form-control-solid"
                                   value="{{ old('address', $user->studentProfile?->address) }}"
                                   placeholder="e.g. Penang">
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold">Learning Style Description</label>
                            <textarea name="student_style_description" class="form-control form-control-solid" rows="3"
                                      placeholder="Describe student's learning preferences...">{{ old('student_style_description', $user->studentProfile?->student_style_description) }}</textarea>
                        </div>

                        {{-- ── Student Diagnostic Results (read-only) ── --}}
                        @if($user->results && $user->results->count() > 0)
                        <div class="separator my-6"></div>
                        <div class="d-flex align-items-center mb-5">
                            <h5 class="fw-bold text-gray-700 mb-0">Diagnostic Results</h5>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle fs-6 gy-3">
                                <thead>
                                    <tr class="text-muted fw-bold fs-7 text-uppercase">
                                        <th>Subject</th>
                                        <th class="text-center">Score</th>
                                        <th class="text-center">Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->results as $result)
                                    <tr>
                                        <td class="fw-bold text-gray-800">
                                            {{ $result->subject->name ?? 'Subject #' . $result->subject_id }}
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold
                                                {{ $result->score >= 70 ? 'text-success' :
                                                   ($result->score >= 40 ? 'text-warning' : 'text-danger') }}">
                                                {{ $result->score }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($result->score >= 70)
                                                <span class="badge badge-light-success fs-8">Top</span>
                                            @elseif($result->score >= 40)
                                                <span class="badge badge-light-warning fs-8">Intermediate</span>
                                            @else
                                                <span class="badge badge-light-danger fs-8">Weak</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        @endif

                        {{-- ── Tutor Profile ── --}}
                        @if($user->role === 'tutor')
                        <div class="separator my-6"></div>
                        <div class="d-flex align-items-center mb-5">
                            <h5 class="fw-bold text-gray-700 mb-0">Tutor Profile</h5>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Age</label>
                                <input type="number" name="age" class="form-control form-control-solid"
                                       value="{{ old('age', $user->tutorProfile?->age) }}"
                                       placeholder="e.g. 25">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Experience (years)</label>
                                <input type="number" name="experience" class="form-control form-control-solid"
                                       value="{{ old('experience', $user->tutorProfile?->experience ?? 0) }}"
                                       placeholder="e.g. 3" min="0">
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold">Address</label>
                            <input type="text" name="address" class="form-control form-control-solid"
                                   value="{{ old('address', $user->tutorProfile?->address) }}"
                                   placeholder="e.g. Kota Bharu, Kelantan">
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">University</label>
                                <input type="text" name="university" class="form-control form-control-solid"
                                       value="{{ old('university', $user->tutorProfile?->university) }}"
                                       placeholder="e.g. USM">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Course</label>
                                <input type="text" name="course" class="form-control form-control-solid"
                                       value="{{ old('course', $user->tutorProfile?->course) }}"
                                       placeholder="e.g. Computer Science">
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Teaching Style Description</label>
                            <textarea name="tutor_style_description" class="form-control form-control-solid" rows="3"
                                      placeholder="Describe tutor's teaching approach...">{{ old('tutor_style_description', $user->tutorProfile?->tutor_style_description) }}</textarea>
                        </div>
                        @endif

                    </div>

                    {{-- Card Footer --}}
                    <div class="card-footer d-flex justify-content-between align-items-center py-5 px-8">
                        <a href="{{ route('user-management.index') }}" class="btn btn-light fw-bold px-8">
                            <i class="ki-duotone ki-arrow-left fs-2x me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary fw-bold px-8">
                            <i class="ki-duotone ki-check fs-2x me-1">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                            Save Changes
                        </button>
                    </div>

                </div>
            </div>

            {{-- RIGHT: Stats & Info --}}
            <div class="col-lg-4">

                {{-- Account Stats --}}
                <div class="card card-flush mb-6">
                    <div class="card-header pt-6">
                        <div class="card-title">
                            <h5 class="fw-bold text-gray-800">Account Stats</h5>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="text-gray-500 fs-7">Member Since</span>
                            <span class="text-gray-800 fw-bold fs-7">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="text-gray-500 fs-7">Role</span>
                            <span class="fw-bold fs-7
                                {{ $user->role === 'student' ? 'text-primary' :
                                   ($user->role === 'tutor' ? 'text-success' : 'text-warning') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="text-gray-500 fs-7">XP Points</span>
                            <span class="text-gray-800 fw-bold fs-7">
                                <i class="ki-duotone ki-star fs-6 text-warning me-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                {{ number_format($user->xp) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="text-gray-500 fs-7">Level</span>
                            <span class="badge badge-light-primary fs-7">Lv. {{ $user->level }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3">
                            <span class="text-gray-500 fs-7">Coins</span>
                            <span class="text-gray-800 fw-bold fs-7">🪙 {{ number_format($user->coins) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Student Quick Info --}}
                @if($user->role === 'student' && $user->studentProfile)
                <div class="card card-flush mb-6">
                    <div class="card-header pt-6">
                        <div class="card-title">
                            <h5 class="fw-bold text-gray-800">Student Info</h5>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="text-gray-500 fs-7">Form Level</span>
                            <span class="text-gray-800 fw-bold fs-7">
                                {{ strtoupper($user->studentProfile->category ?? '—') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="text-gray-500 fs-7">Age</span>
                            <span class="text-gray-800 fw-bold fs-7">{{ $user->studentProfile->age ?? '—' }} yrs</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="text-gray-500 fs-7">Total Quizzes</span>
                            <span class="text-gray-800 fw-bold fs-7">{{ $user->results->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3">
                            <span class="text-gray-500 fs-7">Address</span>
                            <span class="text-gray-800 fw-bold fs-7">{{ $user->studentProfile->address ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Tutor Quick Info --}}
                @if($user->role === 'tutor' && $user->tutorProfile)
                <div class="card card-flush mb-6">
                    <div class="card-header pt-6">
                        <div class="card-title">
                            <h5 class="fw-bold text-gray-800">Tutor Info</h5>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="text-gray-500 fs-7">Experience</span>
                            <span class="text-gray-800 fw-bold fs-7">{{ $user->tutorProfile->experience ?? 0 }} year(s)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="text-gray-500 fs-7">Qualification Score</span>
                            <span class="fw-bold fs-7
                                {{ ($user->tutorProfile->qualification_score ?? 0) >= 70 ? 'text-success' :
                                   (($user->tutorProfile->qualification_score ?? 0) >= 40 ? 'text-warning' : 'text-danger') }}">
                                {{ $user->tutorProfile->qualification_score ?? 0 }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                            <span class="text-gray-500 fs-7">Recommendation</span>
                            @php $rec = $user->tutorProfile->recommendation_status ?? '—'; @endphp
                            @if($rec === 'Recommended')
                                <span class="badge badge-light-success fs-8">Recommended</span>
                            @elseif($rec === 'Standard')
                                <span class="badge badge-light-warning fs-8">Standard</span>
                            @else
                                <span class="badge badge-light-danger fs-8">{{ $rec }}</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-3">
                            <span class="text-gray-500 fs-7">Address</span>
                            <span class="text-gray-800 fw-bold fs-7">{{ $user->tutorProfile->address ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection
