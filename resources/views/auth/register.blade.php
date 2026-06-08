<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }} &bull; Register</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('metronic/assets/media/logos/elearning-favicon.png') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" />
    <link href="{{ asset('metronic/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

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
            width: 100%; border: 0; border-radius: 3px;
            padding: 0.9rem 1rem; font-family: 'Libre Franklin', sans-serif;
            font-weight: 700; font-size: 0.98rem; letter-spacing: 0.02em;
            color: #fff; cursor: pointer; background: var(--el-primary);
            transition: background .15s ease;
        }
        .el-btn:hover { background: #1640a8; color: #fff; }

        .el-link { color: var(--el-primary); font-weight: 600; text-decoration: none; }
        .el-link:hover { text-decoration: underline; }

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
        <div class="el-breadcrumb"><a href="{{ route('login') }}">Home</a> &nbsp;/&nbsp; Registration</div>
        <h1>Student Registration</h1>
        <p>Please complete all required fields below to create your account. Fields marked <span class="el-req">*</span> are mandatory.</p>
    </div>

    <!-- Centered form -->
    <div class="el-wrap">
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="kt_sign_up_form">
            @csrf

            <!-- Personal details -->
            <div class="el-card">
                <div class="el-card-head"><h2>Personal Details</h2></div>
                <div class="el-card-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Full Name" value="{{ old('name') }}">
                        <label>Full Name <span class="el-req">*</span></label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-7">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="phone" placeholder="Phone Number" value="{{ old('phone') }}">
                                <label>Phone Number <span class="el-req">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-floating">
                                <input type="number" class="form-control" name="age" placeholder="Age" value="{{ old('age') }}">
                                <label>Age</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}">
                        <label>Email Address <span class="el-req">*</span></label>
                    </div>
                    <div class="form-floating">
                        <textarea class="form-control" name="address" placeholder="Address" style="height: 90px">{{ old('address') }}</textarea>
                        <label>Home Address</label>
                    </div>
                </div>
            </div>

            <!-- Academic information -->
            <div class="el-card">
                <div class="el-card-head"><h2>Academic Information</h2></div>
                <div class="el-card-body">
                    <div class="form-floating mb-4">
                        <select class="form-select" name="category">
                            <option value="">Select Level</option>
                            @foreach($listCategory as $cat)
                                <option value="{{ $cat->code }}" {{ old('category') == $cat->code ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <label>Category (Current Form) <span class="el-req">*</span></label>
                    </div>

                    <label class="fw-bold mb-1 d-block">Current Exam Results</label>
                    <p class="el-field-note mb-3">Add the subjects and most recent scores you would like us to consider.</p>
                    <div id="kt_repeater_results">
                        <div data-repeater-list="student_results">
                            <div data-repeater-item class="form-group row mb-4 align-items-center">
                                <div class="col-md-6">
                                    <select name="subject_id" class="form-select" data-kt-repeater="select2" data-placeholder="Select Subject">
                                        <option value="">Subject</option>
                                        @foreach($listSubject as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="number" name="score" class="form-control" placeholder="Score" />
                                </div>
                                <div class="col-md-1 text-end">
                                    <a href="javascript:;" data-repeater-delete class="btn btn-icon btn-light-danger btn-sm">
                                        <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <button type="button" data-repeater-create class="btn btn-sm btn-light-primary">
                            <i class="ki-duotone ki-plus fs-3"></i>Add Subject
                        </button>
                    </div>

                    <div class="form-floating mt-4">
                        <textarea class="form-control" name="student_style_description" placeholder="Learning Style" style="height: 100px">{{ old('student_style_description') }}</textarea>
                        <label>Preferred online learning style</label>
                    </div>
                </div>
            </div>

            <!-- Account security -->
            <div class="el-card">
                <div class="el-card-head"><h2>Account Security</h2></div>
                <div class="el-card-body">
                    <div class="fv-row mb-4" data-kt-password-meter="true">
                        <div class="form-floating position-relative mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                            <label>Password <span class="el-req">*</span></label>
                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                        </div>
                        <div class="d-flex align-items-center mb-2" data-kt-password-meter-control="highlight">
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                        </div>
                        <div class="el-field-note">Use 8 or more characters with a combination of letters, numbers & symbols.</div>
                    </div>

                    <div class="fv-row" data-kt-password-meter="true">
                        <div class="form-floating position-relative">
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                            <label>Confirm Password <span class="el-req">*</span></label>
                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" id="kt_sign_up_submit_standard" class="el-btn">
                    <span class="indicator-label">Submit Registration</span>
                    <span class="indicator-progress">Please wait...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <p class="el-field-note text-center mt-3">
                    Already have an account? <a href="{{ route('login') }}" class="el-link">Sign in here</a>
                </p>
            </div>
        </form>
    </div>

    <footer class="el-footer">
        &copy; {{ date('Y') }} Nawwarah — E-Learning. All rights reserved.
    </footer>

    <script src="{{ asset('metronic/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('metronic/assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('metronic/assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('[data-kt-repeater="select2"]').select2();

            $('#kt_repeater_results').repeater({
                initEmpty: false,
                show: function() {
                    $(this).slideDown();
                    var select = $(this).find('[data-kt-repeater="select2"]');
                    $(this).find('.select2-container').remove();
                    select.select2();
                },
                hide: function(deleteElement) { $(this).slideUp(deleteElement); }
            });
        });
    </script>
</body>
</html>
