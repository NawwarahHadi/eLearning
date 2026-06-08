<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }} &bull; Tutor Application</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('metronic/assets/media/logos/elearning-favicon.png') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" />
    <link href="{{ asset('metronic/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />

    <style>
        :root {
            --el-navy: #0a0a0a;
            --el-navy-2: #16181d;
            --el-primary: #1d4ed8;
            --el-ink: #1a1d23;
            --el-muted: #5b626e;
            --el-line: #e4e6eb;
            --el-paper: #f7f8fa;
        }

        body.el-reg {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background: var(--el-paper);
            color: var(--el-ink);
            min-height: 100vh;
        }

        /* ---------- Formal top header ---------- */
        .el-masthead {
            background: var(--el-navy);
            color: #fff;
            border-bottom: 3px solid var(--el-primary);
        }
        .el-masthead-inner {
            max-width: 920px; margin: 0 auto;
            padding: 1.1rem 1.5rem;
            display: flex; align-items: center; gap: 0.85rem;
        }
        .el-masthead img { width: 40px; height: 40px; }
        .el-masthead .org {
            font-family: 'Libre Franklin', sans-serif;
            font-weight: 700; font-size: 1.1rem; letter-spacing: 0.01em;
        }
        .el-masthead .org small {
            display: block; font-family: 'Inter', sans-serif;
            font-weight: 400; font-size: 0.72rem; letter-spacing: 0.08em;
            text-transform: uppercase; color: #9aa3b2; margin-top: 1px;
        }

        /* ---------- Page title block ---------- */
        .el-pagehead {
            max-width: 920px; margin: 0 auto;
            padding: 2.25rem 1.5rem 0;
            text-align: center;
        }
        .el-breadcrumb {
            font-size: 0.8rem; color: var(--el-muted); margin-bottom: 0.5rem;
        }
        .el-breadcrumb a { color: var(--el-primary); text-decoration: none; }
        .el-pagehead h1 {
            font-family: 'Libre Franklin', sans-serif; font-weight: 800;
            font-size: 1.95rem; margin: 0 0 0.5rem; letter-spacing: -0.01em;
        }
        .el-pagehead p { color: var(--el-muted); margin: 0 auto; font-size: 0.98rem; max-width: 560px; }

        /* ---------- Centered form ---------- */
        .el-wrap {
            max-width: 920px; margin: 1.75rem auto 3rem;
            padding: 0 1.5rem;
        }

        .el-card {
            background: #fff; border: 1px solid var(--el-line);
            border-radius: 4px;
        }
        .el-card + .el-card { margin-top: 1.5rem; }

        .el-card-head {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--el-line);
            background: #fbfcfd;
        }
        .el-card-head h2 {
            font-family: 'Libre Franklin', sans-serif; font-weight: 700;
            font-size: 1.02rem; margin: 0; letter-spacing: 0.01em;
        }
        .el-card-body { padding: 1.5rem; }

        /* form controls — formal, squared */
        .el-card .form-control,
        .el-card .form-select {
            border: 1px solid #ccd0d9 !important;
            border-radius: 3px;
            background: #fff !important;
            color: var(--el-ink);
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .el-card .form-control:focus,
        .el-card .form-select:focus {
            border-color: var(--el-primary) !important;
            box-shadow: 0 0 0 3px rgba(29,78,216,0.12);
        }
        .el-card .form-floating > label { color: var(--el-muted); }
        .el-field-note { font-size: 0.8rem; color: var(--el-muted); margin-top: 0.35rem; }
        .el-req { color: #c0392b; }

        .el-btn {
            border: 0; border-radius: 3px;
            padding: 0.85rem 2rem; font-family: 'Libre Franklin', sans-serif;
            font-weight: 700; font-size: 0.98rem; letter-spacing: 0.02em;
            color: #fff; cursor: pointer; background: var(--el-primary);
            transition: background .15s ease;
        }
        .el-btn:hover { background: #1640a8; color: #fff; }

        .el-link { color: var(--el-primary); font-weight: 600; text-decoration: none; }
        .el-link:hover { text-decoration: underline; }

        .el-actions {
            display: flex; align-items: center; justify-content: space-between;
            margin-top: 1.5rem; gap: 1rem; flex-wrap: wrap;
        }

        .el-footer {
            background: var(--el-navy-2); color: #9aa3b2;
            font-size: 0.83rem; text-align: center; padding: 1.25rem;
        }

        @media (max-width: 575.98px) {
            .el-card-body { padding: 1.25rem; }
            .el-pagehead h1 { font-size: 1.65rem; }
        }
         .nav {
            position: sticky; top: 0; z-index: 50;
            background: rgba(10,10,10,0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; height: 72px; }
        .brand { display: flex; align-items: center; gap: 0.65rem; }
        .brand img { width: 38px; height: 38px; }
        .brand span {
            font-family: 'Poppins', sans-serif; font-weight: 700;
            color: #fff; font-size: 1.15rem; letter-spacing: -0.01em;
        }
        .nav-actions { display: flex; align-items: center; gap: 0.75rem; }
        .btn {
            font-family: 'Poppins', sans-serif; font-weight: 600;
            font-size: 0.92rem; border-radius: 10px; cursor: pointer;
            padding: 0.6rem 1.25rem; border: 0; display: inline-block;
            transition: transform .12s ease, filter .18s ease, background .18s ease, color .18s ease;
        }
        .btn-ghost {
            background: transparent; color: #fff;
            border: 1.5px solid rgba(255,255,255,0.25);
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.08); }
        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            box-shadow: 0 12px 24px -12px rgba(37,99,235,0.7);
        }
        .btn-primary:hover { transform: translateY(-1px); filter: brightness(1.07); }
        .btn-lg { padding: 0.95rem 1.75rem; font-size: 1rem; }
    </style>
</head>
<body id="kt_body" class="el-reg">

    <!-- Masthead -->
     <!-- Masthead -->
     <header class="nav">
        <div class="container nav-inner">
            <a href="#" class="brand">
                <img src="{{ asset('metronic/assets/media/logos/elearning-logo.png') }}" alt="E-Learning" />
                <span>E-Learning</span>
            </a>
            <nav class="nav-actions">
                <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
            </nav>
        </div>
    </header>


    <!-- Page title -->
    <div class="el-pagehead">
        <div class="el-breadcrumb"><a href="{{ route('login') }}">Home</a> &nbsp;/&nbsp; Tutor Application</div>
        <h1>Tutor Application</h1>
        <p>Complete your application below to become a tutor. Fields marked <span class="el-req">*</span> are mandatory.</p>
    </div>

    <!-- Centered form -->
    <div class="el-wrap">
        <form method="POST" action="{{ route('register.tutor') }}" enctype="multipart/form-data" id="kt_sign_up_form">
            @csrf

            <!-- Personal information -->
            <div class="el-card">
                <div class="el-card-head"><h2>Personal Information</h2></div>
                <div class="el-card-body">

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Profile Photo</label>
                        <div class="col-lg-8">
                            <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('{{ asset('metronic/assets/media/avatars/blank.png') }}')">
                                <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset('metronic/assets/media/avatars/blank.png') }}')"></div>
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Upload Photo">
                                    <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                                    <input type="file" name="profile_photo" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="avatar_remove" />
                                </label>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel photo">
                                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove photo">
                                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            @error('profile_photo') <div class="text-danger fs-7">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Full Name" value="{{ old('name') }}">
                        <label>Full Name <span class="el-req">*</span></label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="phone" placeholder="Phone Number" value="{{ old('phone') }}">
                        <label>Phone Number <span class="el-req">*</span></label>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}">
                                <label>Email Address <span class="el-req">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="age" placeholder="Age" value="{{ old('age') }}">
                                <label>Age</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="nationality" placeholder="Nationality" value="{{ old('nationality') }}">
                                <label>Nationality</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="status" placeholder="Status" value="{{ old('status') }}">
                                <label>Status</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" step="0.01" class="form-control" name="cgpa" placeholder="CGPA" value="{{ old('cgpa') }}">
                                <label>CGPA</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="education_level_id" class="form-select" data-control="select2" data-placeholder="Select Highest Education Level..." data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach($level as $educationLevel)
                                        <option value="{{ $educationLevel->id }}">{{ $educationLevel->name }}</option>
                                    @endforeach
                                </select>
                                <label for="education_level_id">Highest Education Level</label>
                            </div>
                        </div>
                    </div>

                    <div class="fv-row mb-3">
                        <select name="subject_expertise[]" class="form-select" data-control="select2" data-placeholder="Select subjects (Select 1 or more)..." data-allow-clear="true" multiple="multiple">
                            @foreach($listSubject as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        <div class="el-field-note">Choose the subjects you are qualified to teach.</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="experience" placeholder="Years of Experience" value="{{ old('experience') }}">
                        <label>Years of Experience</label>
                    </div>

                    <div class="form-floating">
                        <textarea class="form-control" name="address" placeholder="Address" style="height: 80px">{{ old('address') }}</textarea>
                        <label>Address</label>
                    </div>

                </div>
            </div>

            <!-- Teaching information -->
            <div class="el-card">
                <div class="el-card-head"><h2>Teaching Information</h2></div>
                <div class="el-card-body">
                    <div class="form-floating">
                        <textarea class="form-control" name="tutor_style_description" placeholder="Teaching Style" style="height: 110px">{{ old('tutor_style_description') }}</textarea>
                        <label>Describe your preferred online teaching style</label>
                    </div>
                </div>
            </div>

            <!-- Upload documents -->
            <div class="el-card">
                <div class="el-card-head"><h2>Upload Documents</h2></div>
                <div class="el-card-body">
                    <div class="mb-5">
                        <label class="fs-6 fw-semibold mb-2 d-block">Resume <span class="el-req">*</span></label>
                        <input type="file" name="resume" class="lampiran filepond" data-max-file-size="50MB" multiple>
                        @error('resume') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="fs-6 fw-semibold mb-2 d-block">Academic Transcript <span class="el-req">*</span></label>
                        <input type="file" name="tutor_cert" class="lampiran filepond" data-max-file-size="50MB" multiple>
                        @error('tutor_cert') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="el-actions">
                <a href="{{ route('login') }}" class="el-link">Already registered? Sign in</a>
                <button type="submit" id="kt_sign_up_submit_standard" class="el-btn">
                    <span class="indicator-label">Submit Application</span>
                    <span class="indicator-progress">Please wait...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>

        </form>
    </div>

    <footer class="el-footer">
        &copy; {{ date('Y') }} Nawwarah — E-Learning. All rights reserved.
    </footer>

    <script src="{{ asset('metronic/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('metronic/assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-rename/dist/filepond-plugin-file-rename.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginFileRename);
        FilePond.registerPlugin(FilePondPluginFileValidateSize);

        const allInputs = document.querySelectorAll('.lampiran');
        allInputs.forEach(input => {
            FilePond.create(input, {
                storeAsFile: true,
                server: {
                    process: '{{ route('tutor.upload') }}',
                    revert: '{{ route('tutor.revert') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            });
        });
    </script>
</body>
</html>
