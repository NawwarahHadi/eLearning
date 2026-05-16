<!DOCTYPE html>

<html lang="en">
	<head>
		<title>{{ config('app.name') }} &bull; Login</title>
		<meta charset="utf-8" />
		<meta name="description" content="E-Learning" />
		<meta name="keywords" content="E-Learning" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="E-Learning" />
		<meta property="og:url" content="https://eLearning.com.my/" />
		<meta property="og:site_name" content="Nawwarah" />
		{{-- <link rel="canonical" href="{{ asset('metronic/assets/media/logos/sukicon.png')}}" /> --}}
		<link rel="shortcut icon" href="{{ asset('metronic/assets/media/logos/sukicon.png')}}" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<link href="{{ asset ('metronic/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{ asset ('metronic/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
	</head>
	<body id="kt_body" class="auth-bg">
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
					<div class="d-flex flex-center flex-column flex-lg-row-fluid">
                        <div class="card shadow">
                            <div class="w-lg-500px p-10">
                                <form class="form w-100" novalidate="novalidate" id="recaptcha" action="{{ route ('login')}}" method="POST">
                                    @csrf
                                    <div class="text-center mb-11">
                                        <h1 class="text-dark fw-bolder mb-3">Login</h1>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="floatinginput" name="email" placeholder="Kad Pengenalan" value="{{ old('email') }}">
                                        <label for="floatingInput">Email</label>
                                        @error('email')
                                            <span class="indicator-label" style="color: red">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="fv-row mb-3">
                                        <div class="form-floating mb-3" data-kt-password-meter="true">
                                            <input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent"  id="password" />
                                            <label for="floatingInput">Password </label>
                                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                                <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </span>
                                            @error('password')
                                                <span class="indicator-label" style="color: red">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                        <div></div>
                                        <a href="{{ route('password.request') }}" class="link-primary">Forgot Password ?</a>
                                    </div>
                                    <div class="d-grid mb-10">
                                        <button type="submit" class="btn btn-primary g-recaptcha button-loading"
                                            data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"
                                            data-callback='onSubmit'
                                            data-action='submit'>
                                            <i class="ki-duotone ki-send">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            <span class="indicator-label">Login</span>
                                            <span class="indicator-progress">Loading...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </div>
                                    @if ($errors->has('message'))
                                        <div class="alert alert-danger" role="alert">
                                            {{ $errors->first('message') }}
                                        </div>
                                    @endif
                                    @if ($errors->has('g-recaptcha-response'))
                                        <div class="alert alert-danger" role="alert">
                                            {{ $errors->first('g-recaptcha-response') }}
                                        </div>
                                    @endif
                                    <div class="text-gray-500 text-center fw-semibold fs-6">Already Register?
                                        <a href="{{ route ('register')}}" class="link-primary">Register</a>
                                    </div>
                                    <div class="text-gray-500 text-center fw-semibold fs-6">Interested To be Tutor?
                                        <a href="{{ route ('register.tutor')}}" class="link-primary">Apply</a>
                                    </div>
                                </form>
                            </div>

                        </div>
					</div>
				</div>
				<div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url({{ asset ('metronic/assets/media/background5.jpg')}})">
					<div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
						{{-- <img alt="Logo" src="{{ asset ('metronic/assets/media/logos/sukicon.png')}}" class="h-200px h-lg-200px" />
						<h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">E-Learning</h1>
                        <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">(E-Learning)</h1> --}}
						{{-- <div class="d-none d-lg-block text-white fs-base text-center">Pejabat Setiausaha Kerajaan Negeri Kelantan</div> --}}
					</div>
				</div>
			</div>
		</div>
		<script src="{{ asset ('metronic/assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{ asset ('metronic/assets/js/scripts.bundle.js')}}"></script>
        <script src="{{ asset ('metronic/js/button_loading.js')}}"></script>
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script>
            function onSubmit(token) {
              document.getElementById("recaptcha").submit();
            }
        </script>
	</body>
</html>



