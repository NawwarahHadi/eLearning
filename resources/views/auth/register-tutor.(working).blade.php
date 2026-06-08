<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }} &bull; Tutor Application</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('metronic/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"rel="stylesheet" />
</head>
<body id="kt_body" class="auth-bg bgi-size-cover bgi-attachment-fixed bgi-position-center">
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <div class="w-lg-600px p-10">

                        <form method="POST" action="{{ route('register.tutor') }}" enctype="multipart/form-data" id="kt_sign_up_form">
                            @csrf
                            <div class="text-center mb-11">
                                <h1 class="text-dark fw-bolder mb-3">Tutor Application</h1>
                                <div class="text-muted fw-semibold fs-6">                                Complete your application to become a tutor.
                                </div>
                            </div>

                            <div class="mb-10">

                                <h3 class="fw-bold text-gray-800 mb-6">
                                    Personal Information
                                </h3>

                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Profile Photo</label>
                                    <div class="col-lg-8">
                                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('{{ asset('metronic/assets/media/avatars/blank.png') }}')">

                                            <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset('metronic/assets/media/avatars/blank.png') }}')"></div>

                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Upload Photo">
                                                <i class="ki-duotone ki-pencil fs-7">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <input type="file" name="profile_photo" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="avatar_remove" />
                                                </label>
                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel photo">
                                                <i class="ki-duotone ki-cross fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove photo">
                                                <i class="ki-duotone ki-cross fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </span>
                                            </div>
                                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                        @error('profile_photo') <div class="text-danger fs-7">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control bg-transparent" name="name" placeholder="Full Name" value="{{ old('name') }}">
                                    <label>Full Name</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control bg-transparent" name="" placeholder="" value="{{ old('') }}">
                                    <label>Phone Number</label>
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
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-transparent" name="" placeholder="" value="{{ old('') }}">
                                            <label>Nationality</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="number" class="form-control bg-transparent" name="" placeholder="" value="{{ old('') }}">
                                            <label>Status</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number" class="form-control bg-transparent" name="cgpa" placeholder="Email" value="{{ old('cgpa') }}">
                                            <label>Cgpa</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select name="education_level_id" class="form-select bg-transparent" data-control="select2" data-placeholder="Select Highest Education Level..." data-allow-clear="true">
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
                                    {{-- <label class="fs-5 fw-bold mb-2">Subject Expertise (Select 1 or more)</label> --}}
                                    <select name="subject_expertise[]" class="form-select form-select bg-transparent" data-control="select2"  data-placeholder="Select subjects (Select 1 or more)..." data-allow-clear="true"  multiple="multiple">
                                        @foreach($listSubject as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-muted fs-7">Choose the subjects you are qualified to teach.</div>
                                </div>

                                <div class="form-floating mb-3">
                                    <div class="form-floating">
                                        <input type="number" class="form-control bg-transparent" name="experience" placeholder="experience" value="{{ old('experience') }}">
                                        <label>Years of Experience</label>
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control bg-transparent" name="address" placeholder="Address" style="height: 80px">{{ old('address') }}</textarea>
                                    <label> Address</label>
                                </div>


                            </div>

                            <div class="mb-10">
                                <h3 class="fw-bold text-gray-800 mb-6">
                                    Teaching Information
                                </h3>

                                <div class="form-floating mb-8 mt-5">
                                    <textarea class="form-control bg-transparent" name="tutor_style_description" placeholder="Teaching Style" style="height: 100px">{{ old('tutor_style_descriptions') }}</textarea>
                                    <label>Describe your preferred online learning style</label>
                                </div>
                            </div>

                            <div class="mb-10">

                                 <h3 class="fw-bold text-gray-800 mb-6">
                                    Upload Documents
                                </h3>

                                <div class="d-flex flex-column mb-4 col-lg-12 row">
                                    <label class="fs-5 fw-semibold mb-2">Resume</label>
                                    <input type="file" name="resume" class="lampiran filepond form-control form-control-lg form-control-solid mb-3 mb-lg-0" data-max-file-size="50MB" multiple>
                                    @error('resume')
                                        <span class="indicator-label" style="color: red">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="d-flex flex-column mb-4 col-lg-12 row">
                                    <label class="fs-5 fw-semibold mb-2">Academic Transcript</label>
                                    <input type="file" name="tutor_cert" class="lampiran filepond form-control form-control-lg form-control-solid mb-3 mb-lg-0" data-max-file-size="50MB" multiple>
                                    @error('tutor_cert')
                                        <span class="indicator-label" style="color: red">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                            </div>

                            <div class="d-flex flex-stack pt-5 border-top">

                                <a href="{{ route('login') }}"
                                   class="link-primary fw-semibold fs-6">
                                    Already registered?
                                </a>

                                <button type="submit"
                                        class="btn btn-primary">

                                    <span class="indicator-label">
                                        Submit Application
                                    </span>

                                    <span class="indicator-progress">
                                        Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>

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
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-rename/dist/filepond-plugin-file-rename.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginFileRename);
        FilePond.registerPlugin(FilePondPluginFileValidateSize);
        // Get all file inputs with class "lampiran"
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
