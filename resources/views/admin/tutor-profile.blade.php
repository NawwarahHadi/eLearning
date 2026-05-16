@extends('layouts.app')

@section('title', 'Tutor Profile')

@section('page-header', 'Tutor Profile')


@section('content')
<div class="container-xxl mt-10">
    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 12px; border: 1px solid #e5e7eb;">
        <div class="card-body p-0">
            <div class="d-flex flex-column flex-md-row" style="min-height: 650px;">

                {{-- LEFT COLUMN: PROFESSIONAL IDENTITY --}}
                <div class="d-flex flex-column align-items-center text-center p-10" style="width: 280px; min-width: 280px; background: #000000;">

                    {{-- Profile Circle --}}
                    <div class="symbol symbol-100px symbol-circle mb-5" style="border: 4px solid rgba(255,255,255,0.2);">
                        @if($tutor->tutorProfile?->profile_photo)
                            <img src="{{ asset('storage/' . $tutor->tutorProfile->profile_photo) }}" alt="profile photo" style="border-radius: 50%; width: 100px; height: 100px; object-fit: cover;" />
                        @else
                            <div style="width:100px;height:100px;border-radius:50%;background:#222;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;color:#fff;">
                                {{ strtoupper(substr($tutor->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <h2 style="color:#fff;font-size:18px;font-weight:600;margin-bottom:4px;">{{ $tutor->name }}
                        <i class="ki-duotone ki-verify fs-2x text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </h2>

                    {{-- Age & Badge Row --}}
                    {{-- <div class="d-flex align-items-center gap-2 mb-6">
                    </div> --}}
                    <p style="color:rgba(255,255,255,0.6);font-size:12px; margin-bottom:1px">{{ $tutor->age ?? $tutor->tutorProfile->age }} Years Old</p>

                    <p style="color:rgba(255,255,255,0.5);font-size:12px;margin-bottom:15px;">{{ $tutor->email }}</p>

                    <hr style="width:100%;border:none;border-top:1px solid rgba(255,255,255,0.1);margin:10px 0 20px;">

                    <p style="font-size:10px;font-weight:700;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:10px;align-self:flex-start;">
                        About Tutor
                    </p>
                    <p style="font-size:13px;color:rgba(255,255,255,0.9);line-height:1.7;text-align:left;font-style:italic;">
                        "{{ $tutor->tutorProfile->ai_summary ?? 'An experienced educator specializing in student-centered learning.' }}"
                    </p>

                    <hr style="width:100%;border:none;border-top:1px solid rgba(255,255,255,0.1);margin:20px 0;">

                    <div class="d-flex gap-2 w-100 mt-auto">
                        <div style="background:rgba(255,255,255,0.1);border-radius:8px;padding:12px;flex:1;text-align:center;">
                            <div style="font-size:20px;font-weight:700;color:#fff;">{{ $tutor->tutorProfile->experience ?? '—' }}</div>
                            <div style="font-size:10px;color:rgba(255,255,255,0.5);text-transform:uppercase;">Years Exp.</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.1);border-radius:8px;padding:12px;flex:1;text-align:center;">
                            <div style="font-size:20px;font-weight:700;color:#fff;">{{ number_format($tutor->tutorProfile->cgpa, 2) }}</div>
                            <div style="font-size:10px;color:rgba(255,255,255,0.5);text-transform:uppercase;">CGPA</div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: ACADEMIC & EXPERTISE --}}
                <div class="flex-grow-1 p-10 bg-white">

                    <p style="font-size:11px;font-weight:700;color:#000;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:15px;">Academic Qualifications</p>
                    <div class="row g-4 mb-8">
                        <div class="col-md-6">
                            <div style="background:#fcfcfc; border: 1px solid #efefef; border-radius:10px; padding:15px;">
                                <label style="font-size:11px;color:#999;display:block;margin-bottom:2px;text-transform:uppercase;">University</label>
                                <span style="font-size:14px;font-weight:600;color:#111;">{{ $tutor->tutorProfile->university ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="background:#fcfcfc; border: 1px solid #efefef; border-radius:10px; padding:15px;">
                                <label style="font-size:11px;color:#999;display:block;margin-bottom:2px;text-transform:uppercase;">Address</label>
                                <span style="font-size:14px;font-weight:600;color:#111;">{{ $tutor->tutorProfile->address ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    <p style="font-size:11px;font-weight:700;color:#000;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:15px;">Subject Expertise</p>
                    <div class="d-flex flex-wrap gap-2 mb-10">
                        @forelse($tutor->subjects as $subject)
                            <span style="font-size:12px;font-weight:600;color:#000;background:#fff;border:1.5px solid #000;border-radius:4px;padding:5px 15px;">
                                {{ $subject->name }}
                            </span>
                        @empty
                            <span class="text-muted fs-7">No subjects declared.</span>
                        @endforelse
                    </div>

                    <div class="row g-5 mb-10">
                        {{-- AI Suggested Subjects --}}
                        <div class="col-md-6">
                            <div style="background:#fafafa; border-radius:12px; padding:20px; height:100%; border: 1px dashed #ccc;">
                                <p style="font-size:10px;font-weight:700;color:#555;text-transform:uppercase;margin-bottom:15px;">Teaching Subjects</p>
                                @forelse($tutor->tutorProfile->suggested_subjects ?? [] as $subject)
                                    <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #eee;font-size:13px;">
                                        <i class="ki-duotone ki-check text-success fs-5"><span class="path1"></span><span class="path2"></span></i> {{ $subject }}
                                    </div>
                                @empty
                                    <span class="text-muted fs-7">No system matches.</span>
                                @endforelse
                            </div>
                        </div>
                        {{-- Career Roles --}}
                        <div class="col-md-6">
                            <div style="background:#fafafa; border-radius:12px; padding:20px; height:100%; border: 1px dashed #ccc;">
                                <p style="font-size:10px;font-weight:700;color:#555;text-transform:uppercase;margin-bottom:15px;">Relevant Job Roles</p>
                                @forelse($tutor->tutorProfile->experience_titles ?? [] as $title)
                                    <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #eee;font-size:13px;">
                                        <i class="ki-duotone ki-check text-success fs-5"><span class="path1"></span><span class="path2"></span></i> {{ $title }}
                                    </div>
                                @empty
                                    <span class="text-muted fs-7">No roles detected.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <p style="font-size:11px;font-weight:700;color:#000;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:15px;">Supporting Documents</p>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <a href="{{ asset('storage/' . $tutor->tutorProfile->resume) }}" target="_blank" class="doc-link">
                                <div class="doc-icon">
                                    <i class="ki-duotone ki-file-up fs-2x">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <div>
                                    <div class="doc-title">Resume</div>
                                    <div class="doc-sub">View / Download PDF</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ asset('storage/' . $tutor->tutorProfile->tutor_cert) }}" target="_blank" class="doc-link">
                                <div class="doc-icon">
                                    <i class="ki-duotone ki-briefcase fs-2x">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <div>
                                    <div class="doc-title">Academic Transcript</div>
                                    <div class="doc-sub">View / Download PDF</div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .doc-link {
        display:flex; align-items:center; gap:12px; padding:15px; background:#fff;
        border:1px solid #e5e7eb; border-radius:10px; text-decoration:none; color:inherit;
        transition: all 0.2s ease;
    }
    .doc-link:hover { border-color: #000; background: #f9fafb; }
    .doc-icon {
        width:40px; height:40px; border-radius:8px; background:#000; color:#fff;
        display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:18px;
    }
    .doc-title { font-size:14px; font-weight:700; color:#111; }
    .doc-sub { font-size:11px; color:#999; }
</style>
@endsection
