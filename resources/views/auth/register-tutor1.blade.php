<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }} &bull; Tutor Application</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('metronic/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('metronic/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>

<body id="kt_body" class="bg-light-primary">

<div class="d-flex flex-column flex-root">

    <div class="d-flex flex-column flex-column-fluid">

        <div class="d-flex flex-center flex-column flex-column-fluid p-10">

            <!-- Form Container -->
            <div class="w-lg-850px">

                <!-- Card -->
                <div class="card shadow-sm border-0 rounded-4">

                    <!-- Header -->
                    <div class="card-header border-0 pt-10 px-10">
                        <div class="card-title flex-column">

                            <h1 class="fw-bolder text-dark fs-2qx mb-3">
                                Tutor Registration
                            </h1>

                            <div class="text-muted fw-semibold fs-6">
                                Complete your application to become a tutor.
                            </div>

                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body px-10 pb-10">

                        <form method="POST"
                              action="{{ route('register.tutor') }}"
                              enctype="multipart/form-data">

                            @csrf

                            <!-- Personal Information -->
                            <div class="mb-10">

                                <h3 class="fw-bold text-gray-800 mb-6">
                                    Personal Information
                                </h3>

                                <div class="row g-5">

                                    <div class="col-md-6">
                                        <div class="form-floating">

                                            <input type="text"
                                                   name="name"
                                                   value="{{ old('name') }}"
                                                   class="form-control bg-transparent"
                                                   placeholder="Full Name"
                                                   required>

                                            <label>Full Name</label>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">

                                            <input type="email"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   class="form-control bg-transparent"
                                                   placeholder="Email"
                                                   required>

                                            <label>Email Address</label>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">

                                            <input type="number"
                                                   name="age"
                                                   class="form-control bg-transparent"
                                                   placeholder="Age"
                                                   required>

                                            <label>Age</label>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">

                                            <input type="number"
                                                   name="experience"
                                                   class="form-control bg-transparent"
                                                   placeholder="Experience"
                                                   required>

                                            <label>Years of Experience</label>

                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">

                                            <textarea name="address"
                                                      class="form-control bg-transparent"
                                                      placeholder="Address"
                                                      style="height: 100px"
                                                      required>{{ old('address') }}</textarea>

                                            <label>Location / Address</label>

                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!-- Teaching Style -->
                            <div class="mb-10">

                                <h3 class="fw-bold text-gray-800 mb-6">
                                    Teaching Information
                                </h3>

                                <div class="form-floating">

                                    <textarea name="tutor_style"
                                              class="form-control bg-transparent"
                                              placeholder="Teaching Style"
                                              style="height: 150px"
                                              required>{{ old('tutor_style') }}</textarea>

                                    <label>Teaching Style Description</label>

                                </div>

                            </div>

                            <!-- Documents -->
                            <div class="mb-10">

                                <h3 class="fw-bold text-gray-800 mb-6">
                                    Upload Documents
                                </h3>

                                <div class="row g-5">

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Profile Photo
                                        </label>

                                        <input type="file"
                                               name="profile_photo"
                                               accept="image/*"
                                               class="form-control">

                                    </div>

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Academic Certificate
                                        </label>

                                        <input type="file"
                                               name="tutor_cert"
                                               accept=".pdf"
                                               class="form-control"
                                               required>

                                    </div>

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Resume / CV
                                        </label>

                                        <input type="file"
                                               name="resume"
                                               accept=".pdf"
                                               class="form-control"
                                               required>

                                    </div>

                                </div>

                            </div>

                            <!-- Security -->
                            <div class="mb-10">

                                {{-- <h3 class="fw-bold text-gray-800 mb-6">
                                    Account Security
                                </h3> --}}

                                <div class="row g-5">

                                    {{-- <div class="col-md-6">

                                        <div class="form-floating">

                                            <input type="password"
                                                   name="password"
                                                   class="form-control bg-transparent"
                                                   placeholder="Password"
                                                   required>

                                            <label>Password</label>

                                        </div>

                                    </div> --}}

                                    {{-- <div class="col-md-6">

                                        <div class="form-floating">

                                            <input type="password"
                                                   name="password_confirmation"
                                                   class="form-control bg-transparent"
                                                   placeholder="Confirm Password"
                                                   required>

                                            <label>Confirm Password</label>

                                        </div>

                                    </div> --}}

                                </div>

                            </div>

                            <!-- Actions -->
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

        </div>

    </div>

</div>

<script src="{{ asset('metronic/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('metronic/assets/js/scripts.bundle.js') }}"></script>

</body>
</html>
