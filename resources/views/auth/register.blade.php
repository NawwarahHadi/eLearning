<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }} &bull; Register</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('metronic/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>
<body id="kt_body" class="auth-bg bgi-size-cover bgi-attachment-fixed bgi-position-center">
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <div class="w-lg-600px p-10">

                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="kt_sign_up_form">
                            @csrf
                            <div class="text-center mb-11">
                                <h1 class="text-dark fw-bolder mb-3">Create an Account</h1>
                                {{-- <div class="text-muted fw-semibold fs-6">Please select your role below</div> --}}
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control bg-transparent" name="name" placeholder="Full Name" value="{{ old('name') }}">
                                <label>Full Name</label>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <div class="form-floating">
                                        <input type="email" class="form-control bg-transparent" name="email" placeholder="Email" value="{{ old('email') }}">
                                        <label>Email Address</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="number" class="form-control bg-transparent" name="age" placeholder="Age" value="{{ old('age') }}">
                                        <label>Age</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control bg-transparent" name="address" placeholder="Address" style="height: 80px">{{ old('address') }}</textarea>
                                <label>Home Address</label>
                            </div>

                            <div class="form-floating mb-5">
                                <select class="form-select bg-transparent" name="category">
                                    <option value="">Select Level</option>
                                    @foreach($listCategory as $cat)
                                        <option value="{{ $cat->code }}" {{ old('category') == $cat->code ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <label>Category (Current Form)</label>
                            </div>

                            <label class="fw-bold mb-2">Current Exam Results</label>
                            <div id="kt_repeater_results">
                                <div data-repeater-list="student_results">
                                    <div data-repeater-item class="form-group row mb-5 align-items-center">
                                        <div class="col-md-6">
                                            <select name="subject_id" class="form-select bg-transparent" data-kt-repeater="select2" data-placeholder="Select Subject">
                                                <option value="">Subject</option>
                                                @foreach($listSubject as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="number" name="score" class="form-control bg-transparent" placeholder="Score" />
                                        </div>
                                        <div class="col-md-1 text-end">
                                            <a href="javascript:;" data-repeater-delete class="btn btn-icon btn-light-danger btn-sm">
                                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" data-repeater-create class="btn btn-sm btn-light-primary mb-5">
                                    <i class="ki-duotone ki-plus fs-3"></i>Add Subject
                                </button>
                            </div>

                            <div class="form-floating mb-8 mt-5">
                                <textarea class="form-control bg-transparent" name="student_style_description" placeholder="Learning Style" style="height: 100px">{{ old('student_style_description') }}</textarea>
                                <label>Describe your preferred online learning style</label>
                            </div>

                            <div class="fv-row mb-8" data-kt-password-meter="true">
                                <div class="form-floating position-relative mb-3">
                                    <input type="password" class="form-control bg-transparent" name="password" placeholder="Password">
                                    <label>Password</label>
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                        <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                        <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                </div>
                                <div class="text-muted fs-7">Use 8 or more characters with a combination of letters, numbers & symbols.</div>
                            </div>

                            <div class="fv-row mb-8" data-kt-password-meter="true">
                                <div class="form-floating position-relative mb-3">
                                    <input type="password" class="form-control bg-transparent" name="password_confirmation" placeholder="Confirm Password">
                                    <label>Confirm Password</label>
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                        <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                        <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </span>
                                </div>
                            </div>

                            <div class="d-grid mb-10">
                                <button type="submit" id="kt_sign_up_submit_standard" class="btn btn-primary">
                                    <span class="indicator-label">Register Now</span>
                                    <span class="indicator-progress">Please wait...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url({{ asset('metronic/assets/media/background5.jpg') }})"></div>
        </div>
    </div>

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
