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
		<link rel="shortcut icon" href="{{ asset('metronic/assets/media/logos/elearning-favicon.png')}}" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" />
		<link href="{{ asset ('metronic/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{ asset ('metronic/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />

		<style>
			:root {
				--el-primary: #2563eb;
				--el-primary-dark: #1e3a8a;
				--el-accent: #0ea5e9;
				--el-ink: #0f172a;
				--el-muted: #64748b;
				--el-line: #e2e8f0;
				--el-black: #0a0a0a;     /* matches sidebar */
				--el-black-2: #161616;
			}

			body.el-auth {
				font-family: 'Inter', sans-serif;
				margin: 0;
				min-height: 100vh;
				background: #f1f5f9;
			}

			.el-shell {
				display: grid;
				grid-template-columns: 1fr 1fr;
				min-height: 100vh;
			}

			/* ---------- Left: form panel ---------- */
			.el-form-panel {
				display: flex;
				align-items: center;
				justify-content: center;
				padding: 2.5rem 1.5rem;
				background:
					/* radial-gradient(1200px 600px at -10% -20%, rgba(37,99,235,0.06), transparent 60%), */
					#ffffff;
			}

			.el-card {
				width: 100%;
				max-width: 430px;
				background: #ffffff;
				border: 1px solid var(--el-line);
				border-radius: 22px;
				padding: 2.75rem 2.5rem;
				box-shadow: 0 24px 60px -28px rgba(15, 23, 42, 0.28);
				animation: el-rise 0.6s cubic-bezier(.22,.61,.36,1) both;
			}

			@keyframes el-rise {
				from { opacity: 0; transform: translateY(18px); }
				to   { opacity: 1; transform: translateY(0); }
			}

			.el-brand { text-align: center; margin-bottom: 1.75rem; }
			.el-brand img {
				width: 76px; height: 76px;
				/* filter: drop-shadow(0 10px 18px rgba(37,99,235,0.28)); */
			}
			.el-title {
				font-family: 'Poppins', sans-serif;
				font-weight: 800; font-size: 1.9rem;
				color: var(--el-ink);
				margin: 1rem 0 0.25rem; letter-spacing: -0.02em;
			}
			.el-subtitle { color: var(--el-muted); font-size: 0.95rem; margin: 0; }

			.el-field { position: relative; margin-bottom: 1.1rem; }
			.el-field label {
				display: block; font-size: 0.82rem; font-weight: 600;
				color: var(--el-ink); margin-bottom: 0.45rem;
			}
			.el-input {
				width: 100%; border: 1.5px solid var(--el-line);
				border-radius: 12px; padding: 0.85rem 1rem;
				font-size: 0.95rem; color: var(--el-ink);
				background: #f8fafc;
				transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
				box-sizing: border-box;
			}
			.el-input:focus {
				outline: none; border-color: var(--el-primary);
				background: #fff; box-shadow: 0 0 0 4px rgba(37,99,235,0.12);
			}
			.el-input.is-invalid { border-color: #ef4444; }

			.el-pw-wrap { position: relative; }
			.el-pw-toggle {
				position: absolute; top: 50%; right: 0.85rem;
				transform: translateY(-50%); background: none; border: 0;
				cursor: pointer; color: var(--el-muted);
				font-size: 0.78rem; font-weight: 600; padding: 0.2rem 0.4rem;
			}
			.el-pw-toggle:hover { color: var(--el-ink); }

			.el-error { display: block; color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem; }

			.el-row-forgot { display: flex; justify-content: flex-end; margin: -0.2rem 0 1.4rem; }
			.el-link { color: var(--el-primary); font-weight: 600; font-size: 0.85rem; text-decoration: none; }
			.el-link:hover { text-decoration: underline; }

			.el-btn {
				width: 100%; border: 0; border-radius: 12px;
				padding: 0.95rem 1rem; font-family: 'Poppins', sans-serif;
				font-weight: 600; font-size: 1rem; color: #fff; cursor: pointer;
				/* background: linear-gradient(135deg, var(--el-primary), var(--el-primary-dark)); */
				box-shadow: 0 14px 26px -12px rgba(37,99,235,0.6);
				transition: transform .12s ease, box-shadow .18s ease, filter .18s ease;
			}
			.el-btn:hover { transform: translateY(-1px); filter: brightness(1.05); }
			.el-btn:active { transform: translateY(0); }

			.el-alert {
				border-radius: 10px; padding: 0.7rem 0.9rem; font-size: 0.85rem;
				margin-top: 1rem; background: #fef2f2;
				color: #b91c1c; border: 1px solid #fecaca;
			}

			.el-foot { margin-top: 1.6rem; text-align: center; color: var(--el-muted); font-size: 0.88rem; }
			.el-foot a { margin-left: 0.25rem; }
			.el-divider { height: 1px; background: var(--el-line); margin: 1.25rem 0; }

			/* ---------- Right: BLACK brand panel (matches sidebar) ---------- */
			.el-hero {
				position: relative; display: flex; flex-direction: column;
				align-items: center; justify-content: center;
				color: #fff; text-align: center; padding: 3rem; overflow: hidden;
				background: var(--el-black);
			}
			/* subtle depth without changing the black base */
			.el-hero::after {
				content: ""; position: absolute; inset: 0;
				background:
					radial-gradient(600px 380px at 72% 22%, rgba(37,99,235,0.16), transparent 62%),
					radial-gradient(520px 380px at 22% 88%, rgba(14,165,233,0.12), transparent 62%);
				pointer-events: none;
			}
			.el-hero-logo {
				width: 120px; height: 120px; margin-bottom: 1.5rem;
				/* filter: drop-shadow(0 18px 36px rgba(0,0,0,0.6)); */
				animation: el-float 5s ease-in-out infinite; z-index: 1;
			}
			/* @keyframes el-float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-12px); } } */
			.el-hero h2 {
				font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 2.4rem;
				margin: 0 0 0.75rem; letter-spacing: -0.02em; z-index: 1;
			}
			.el-hero p { max-width: 380px; font-size: 1.02rem; line-height: 1.6; opacity: 0.75; z-index: 1; }

			@media (max-width: 992px) {
				.el-shell { grid-template-columns: 1fr; }
				.el-hero { display: none; }
				.el-form-panel { padding: 1.5rem; }
			}
		</style>
	</head>

	<body id="kt_body" class="el-auth">
		<div class="el-shell">

			<!-- Form panel -->
			<div class="el-form-panel">
				<div class="el-card">
					<div class="el-brand">
						<img src="{{ asset('metronic/assets/media/logos/elearning-logo.png')}}" alt="E-Learning" />
						<h1 class="el-title">Welcome Back</h1>
						<p class="el-subtitle">Sign in to continue your learning journey</p>
					</div>

					<form class="form w-100" novalidate="novalidate" id="recaptcha" action="{{ route ('login')}}" method="POST">
						@csrf

						{{-- <div class="el-field">
							<label for="floatinginput">Email</label>
							<input type="text" class="el-input @error('email') is-invalid @enderror"
								id="floatinginput" name="email" placeholder="you@example.com" value="{{ old('email') }}">
							@error('email')
								<span class="el-error">{{ $message }}</span>
							@enderror
						</div> --}}

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('email') is-invalid @enderror" id="floatinginput" name="email" placeholder="Email" value="{{ old('email') }}">
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

						<div class="el-row-forgot">
							<a href="{{ route('password.request') }}" class="el-link">Forgot Password?</a>
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
							<div class="el-alert" role="alert">{{ $errors->first('message') }}</div>
						@endif
						@if ($errors->has('g-recaptcha-response'))
							<div class="el-alert" role="alert">{{ $errors->first('g-recaptcha-response') }}</div>
						@endif

						<div class="el-divider"></div>

						<div class="el-foot">
							Not registered yet?
							<a href="{{ route ('register')}}" class="el-link">Create an account</a>
						</div>
						<div class="el-foot">
							Interested to be a Tutor?
							<a href="{{ route ('register.tutor')}}" class="el-link">Apply here</a>
						</div>
					</form>
				</div>
			</div>

			<!-- Brand hero panel -->
			<div class="el-hero">
				<img src="{{ asset('metronic/assets/media/logos/elearning-logo.png')}}" alt="Logo" class="el-hero-logo" />
				<h2 class="text-white">Tuition Center</h2>
				<p>Learn anytime, anywhere. Access courses, connect with tutors, and grow your skills on one platform.</p>
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
			(function () {
				var btn = document.getElementById('pwToggle');
				var pw = document.getElementById('password');
				if (btn && pw) {
					btn.addEventListener('click', function () {
						var show = pw.type === 'password';
						pw.type = show ? 'text' : 'password';
						btn.textContent = show ? 'Hide' : 'Show';
					});
				}
			})();
		</script>
	</body>
</html>

{{-- <!DOCTYPE html>
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
		<link rel="shortcut icon" href="{{ asset('metronic/assets/media/logos/elearning-favicon.png')}}" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" />
		<link href="{{ asset ('metronic/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{ asset ('metronic/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />

		<style>
			:root {
				--el-primary: #2563eb;
				--el-primary-dark: #1e3a8a;
				--el-accent: #0ea5e9;
				--el-ink: #0f172a;
				--el-muted: #64748b;
				--el-line: #e2e8f0;
				--el-black: #0a0a0a;     /* matches sidebar */
				--el-black-2: #161616;
			}

			body.el-auth {
				font-family: 'Inter', sans-serif;
				margin: 0;
				min-height: 100vh;
				background: #f1f5f9;
			}

			.el-shell {
				display: grid;
				grid-template-columns: 1fr 1fr;
				min-height: 100vh;
			}

			/* ---------- Left: form panel (white) ---------- */
			.el-form-panel {
				display: flex;
				align-items: center;
				justify-content: center;
				padding: 2.5rem 1.5rem;
				background:
					radial-gradient(1200px 600px at -10% -20%, rgba(37,99,235,0.06), transparent 60%),
					#ffffff;
			}

			.el-card {
				width: 100%;
				max-width: 430px;
				background: #ffffff;
				border: 1px solid var(--el-line);
				border-radius: 22px;
				padding: 2.75rem 2.5rem;
				box-shadow: 0 24px 60px -28px rgba(15, 23, 42, 0.28);
				animation: el-rise 0.6s cubic-bezier(.22,.61,.36,1) both;
			}

			@keyframes el-rise {
				from { opacity: 0; transform: translateY(18px); }
				to   { opacity: 1; transform: translateY(0); }
			}

			.el-brand { text-align: center; margin-bottom: 1.75rem; }
			.el-brand img {
				width: 76px; height: 76px;
				filter: drop-shadow(0 10px 18px rgba(37,99,235,0.28));
			}
			.el-title {
				font-family: 'Poppins', sans-serif;
				font-weight: 800; font-size: 1.9rem;
				color: var(--el-ink);
				margin: 1rem 0 0.25rem; letter-spacing: -0.02em;
			}
			.el-subtitle { color: var(--el-muted); font-size: 0.95rem; margin: 0; }

			.el-field { position: relative; margin-bottom: 1.1rem; }
			.el-field label {
				display: block; font-size: 0.82rem; font-weight: 600;
				color: var(--el-ink); margin-bottom: 0.45rem;
			}
			.el-input {
				width: 100%; border: 1.5px solid var(--el-line);
				border-radius: 12px; padding: 0.85rem 1rem;
				font-size: 0.95rem; color: var(--el-ink);
				background: #f8fafc;
				transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
				box-sizing: border-box;
			}
			.el-input:focus {
				outline: none; border-color: var(--el-primary);
				background: #fff; box-shadow: 0 0 0 4px rgba(37,99,235,0.12);
			}
			.el-input.is-invalid { border-color: #ef4444; }

			.el-pw-wrap { position: relative; }
			.el-pw-toggle {
				position: absolute; top: 50%; right: 0.85rem;
				transform: translateY(-50%); background: none; border: 0;
				cursor: pointer; color: var(--el-muted);
				font-size: 0.78rem; font-weight: 600; padding: 0.2rem 0.4rem;
			}
			.el-pw-toggle:hover { color: var(--el-ink); }

			.el-error { display: block; color: #ef4444; font-size: 0.8rem; margin-top: 0.35rem; }

			.el-row-forgot { display: flex; justify-content: flex-end; margin: -0.2rem 0 1.4rem; }
			.el-link { color: var(--el-primary); font-weight: 600; font-size: 0.85rem; text-decoration: none; }
			.el-link:hover { text-decoration: underline; }

			.el-btn {
				width: 100%; border: 0; border-radius: 12px;
				padding: 0.95rem 1rem; font-family: 'Poppins', sans-serif;
				font-weight: 600; font-size: 1rem; color: #fff; cursor: pointer;
				background: linear-gradient(135deg, var(--el-primary), var(--el-primary-dark));
				box-shadow: 0 14px 26px -12px rgba(37,99,235,0.6);
				transition: transform .12s ease, box-shadow .18s ease, filter .18s ease;
			}
			.el-btn:hover { transform: translateY(-1px); filter: brightness(1.05); }
			.el-btn:active { transform: translateY(0); }

			.el-alert {
				border-radius: 10px; padding: 0.7rem 0.9rem; font-size: 0.85rem;
				margin-top: 1rem; background: #fef2f2;
				color: #b91c1c; border: 1px solid #fecaca;
			}

			.el-foot { margin-top: 1.6rem; text-align: center; color: var(--el-muted); font-size: 0.88rem; }
			.el-foot a { margin-left: 0.25rem; }
			.el-divider { height: 1px; background: var(--el-line); margin: 1.25rem 0; }

			/* ---------- Right: BLACK brand panel (matches sidebar) ---------- */
			.el-hero {
				position: relative; display: flex; flex-direction: column;
				align-items: center; justify-content: center;
				color: #fff; text-align: center; padding: 3rem; overflow: hidden;
				background: var(--el-black);
			}
			/* subtle depth without changing the black base */
			.el-hero::after {
				content: ""; position: absolute; inset: 0;
				background:
					radial-gradient(600px 380px at 72% 22%, rgba(37,99,235,0.16), transparent 62%),
					radial-gradient(520px 380px at 22% 88%, rgba(14,165,233,0.12), transparent 62%);
				pointer-events: none;
			}
			.el-hero-logo {
				width: 120px; height: 120px; margin-bottom: 1.5rem;
				filter: drop-shadow(0 18px 36px rgba(0,0,0,0.6));
				animation: el-float 5s ease-in-out infinite; z-index: 1;
			}
			@keyframes el-float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
			.el-hero h2 {
				font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 2.4rem;
				margin: 0 0 0.75rem; letter-spacing: -0.02em; z-index: 1;
			}
			.el-hero p { max-width: 380px; font-size: 1.02rem; line-height: 1.6; opacity: 0.75; z-index: 1; }

			@media (max-width: 992px) {
				.el-shell { grid-template-columns: 1fr; }
				.el-hero { display: none; }
				.el-form-panel { padding: 1.5rem; }
			}
		</style>
	</head>

	<body id="kt_body" class="el-auth">
		<div class="el-shell">

			<!-- Form panel (white) -->
			<div class="el-form-panel">
				<div class="el-card">
					<div class="el-brand">
						<img src="{{ asset('metronic/assets/media/logos/elearning-logo.png')}}" alt="E-Learning" />
						<h1 class="el-title">Welcome Back</h1>
						<p class="el-subtitle">Sign in to continue your learning journey</p>
					</div>

					<form class="form w-100" novalidate="novalidate" id="recaptcha" action="{{ route ('login')}}" method="POST">
						@csrf

						<div class="el-field">
							<label for="floatinginput">Email</label>
							<input type="text" class="el-input @error('email') is-invalid @enderror"
								id="floatinginput" name="email" placeholder="you@example.com" value="{{ old('email') }}">
							@error('email')
								<span class="el-error">{{ $message }}</span>
							@enderror
						</div>

						<div class="el-field el-field--pw">
							<label for="password">Password</label>
							<div class="el-pw-wrap">
								<input type="password" placeholder="Enter your password" name="password"
									autocomplete="off" class="el-input" id="password" />
								<button type="button" class="el-pw-toggle" id="pwToggle">Show</button>
							</div>
							@error('password')
								<span class="el-error">{{ $message }}</span>
							@enderror
						</div>

						<div class="el-row-forgot">
							<a href="{{ route('password.request') }}" class="el-link">Forgot Password?</a>
						</div>

						<button type="submit" class="el-btn g-recaptcha button-loading"
							data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"
							data-callback='onSubmit'
							data-action='submit'>
							<span class="indicator-label">Login</span>
							<span class="indicator-progress">Loading...
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
							</span>
						</button>

						@if ($errors->has('message'))
							<div class="el-alert" role="alert">{{ $errors->first('message') }}</div>
						@endif
						@if ($errors->has('g-recaptcha-response'))
							<div class="el-alert" role="alert">{{ $errors->first('g-recaptcha-response') }}</div>
						@endif

						<div class="el-divider"></div>

						<div class="el-foot">
							Not registered yet?
							<a href="{{ route ('register')}}" class="el-link">Create an account</a>
						</div>
						<div class="el-foot">
							Interested to be a Tutor?
							<a href="{{ route ('register.tutor')}}" class="el-link">Apply here</a>
						</div>
					</form>
				</div>
			</div>

			<!-- Black brand hero panel -->
			<div class="el-hero">
				<img src="{{ asset('metronic/assets/media/logos/elearning-logo.png')}}" alt="Logo" class="el-hero-logo" />
				<h2>E-Learning</h2>
				<p>Learn anytime, anywhere. Access courses, connect with tutors, and grow your skills on one platform.</p>
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
			(function () {
				var btn = document.getElementById('pwToggle');
				var pw = document.getElementById('password');
				if (btn && pw) {
					btn.addEventListener('click', function () {
						var show = pw.type === 'password';
						pw.type = show ? 'text' : 'password';
						btn.textContent = show ? 'Hide' : 'Show';
					});
				}
			})();
		</script>
	</body>
</html> --}}
