<!DOCTYPE html>
<!--
Author:
    1. Siti Nawwarah
Bootstrap Name: Metronic
laravel Version: 11.45.1
PHP Version: 8.3.19
Website: https://www.ppst.kelantan.gov.my
-->
<html lang="en">
	<head><base href=""/>
        <title>{{ config('app.name') }} &bull; @yield('title')</title>
		<meta charset="utf-8" />
		<meta name="description" content="E-Learning" />
		<meta name="keywords" content="E-Learning" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="E-Learning" />
		<meta property="og:url" content="https://eLearning.com.my/" />
		<meta property="og:site_name" content="Nawwarah" />
		<link rel="shortcut icon" href="{{ asset ('metronic/assets/media/logos/sukicon.png')}}" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        @yield('css_after')
		<link href="{{ asset ('metronic/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{ asset ('metronic/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
	</head>
	<body id="kt_body" class="aside-enabled" data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on">
		{{-- <script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script> --}}
        <div class="page-loader page-loader flex-column bg-dark bg-opacity-25">
            <span class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </span>
        </div>
		<div class="d-flex flex-column flex-root">
			<div class="page d-flex flex-row flex-column-fluid">
                @include('layouts.leftsidebar')
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					<div id="kt_header" style="" class="header align-items-stretch">
                        <div class="header-brand">
                            <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none ms-n2">
                                <svg width="200" height="60" viewBox="0 0 200 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="0" y="8" width="44" height="44" rx="12" fill="url(#emeraldGrad)" />

                                    <path d="M11 22C11 22 16 20 22 20C28 20 33 22 33 22V40C33 40 28 38 22 38C16 38 11 40 11 40V22Z" fill="white" fill-opacity="0.2"/>
                                    <path d="M22 20V38M22 20C16 20 11 22 11 22V40C11 40 16 38 22 38M22 20C28 20 33 22 33 22V40C33 40 28 38 22 38"
                                        stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>

                                    <text x="52" y="37"
                                        font-family="Inter, -apple-system, sans-serif"
                                        font-size="22" font-weight="800" fill="#ffffff"
                                        letter-spacing="-0.02em">Tuition Center<tspan fill="#34d399">

                                    {{-- <text x="52" y="52"
                                        font-family="Inter, sans-serif"
                                        font-size="9" font-weight="500" fill="#94a3b8"
                                        letter-spacing="0.05em" text-transform="uppercase">Management System</text> --}}

                                    <defs>
                                        <linearGradient id="emeraldGrad" x1="0" y1="8" x2="44" y2="52" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#10b981"/>
                                            <stop offset="1" stop-color="#059669"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </a>
                            <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-minimize" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
                                <i class="ki-duotone ki-entrance-right fs-1 me-n1 minimize-default">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <i class="ki-duotone ki-entrance-left fs-1 minimize-active">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <div class="d-flex align-items-center d-lg-none me-n2" title="Show aside menu">
                                <div class="btn btn-icon btn-active-color-primary w-30px h-30px" id="kt_aside_mobile_toggle">
                                    <i class="ki-duotone ki-abstract-14 fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                            </div>
                        </div>
                        <div class="toolbar d-flex align-items-stretch">
                            <div class="container-xxl py-6 py-lg-0 d-flex flex-column flex-lg-row align-items-lg-stretch justify-content-lg-between">
                                <div class="page-title d-flex justify-content-center flex-column me-5">
                                    <h1 class="d-flex flex-column text-dark fw-bold fs-3 mb-0">@yield('page-header')</h1>
                                    <ul class="breadcrumb fs-7 pt-1 fw-semibold">
										<li class="breadcrumb-item text-dark">
                                            {{-- @yield('breadcrumbs') --}}
                                         </li>
                                    </ul>
                                </div>
                                <div class="d-flex align-items-stretch overflow-auto pt-3 pt-lg-0">
                                    <div class="d-flex align-items-center" style="padding-right: 10px">
                                        <div class="d-flex">
                                            <a href="#" class="text-black text-hover-primary fs-6 fw-bold"></a>
                                            <h2 class="d-flex flex-column text-dark fw-bold fs-6 mb-0"> {{ Illuminate\Support\Str::title(optional(Auth::user())-> name) }}</h2>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center" style="padding-right: 5px">
                                        <div class="d-flex">
                                            <div class="d-flex align-items-center position-relative"> {{-- Added position-relative --}}
                                                <div class="symbol symbol-35px">
                                                    <a href="{{ route('chat.index') }}" class="text-black text-hover-primary fs-6 fw-bold">
                                                        <i class="ki-duotone ki-messages fs-1">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
                                                        </i>

                                                        {{-- Notification Badge --}}
                                                        @php
                                                            $unreadCount = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', false)->count();
                                                        @endphp

                                                        <span id="navbar-unread-badge"
                                                            class="badge badge-circle badge-danger position-absolute top-0 start-100 translate-middle h-20px w-20px fs-9"
                                                            style="{{ $unreadCount > 0 ? '' : 'display:none;' }}">
                                                            {{ $unreadCount > 0 ? $unreadCount : '' }}
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <a href="#" class="btn btn-sm btn-icon btn-icon-muted btn-active-icon-primary" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                            <i class="ki-duotone ki-night-day theme-light-show fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                                <span class="path6"></span>
                                                <span class="path7"></span>
                                                <span class="path8"></span>
                                                <span class="path9"></span>
                                                <span class="path10"></span>
                                            </i>
                                            <i class="ki-duotone ki-moon theme-dark-show fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                                            <div class="menu-item px-3 my-0">
                                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                                    <span class="menu-icon" data-kt-element="icon">
                                                        <i class="ki-duotone ki-night-day fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
                                                            <span class="path6"></span>
                                                            <span class="path7"></span>
                                                            <span class="path8"></span>
                                                            <span class="path9"></span>
                                                            <span class="path10"></span>
                                                        </i>
                                                    </span>
                                                    <span class="menu-title">Light</span>
                                                </a>
                                            </div>
                                            <div class="menu-item px-3 my-0">
                                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                                    <span class="menu-icon" data-kt-element="icon">
                                                        <i class="ki-duotone ki-moon fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </span>
                                                    <span class="menu-title">Dark</span>
                                                </a>
                                            </div>
                                            <div class="menu-item px-3 my-0">
                                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                                    <span class="menu-icon" data-kt-element="icon">
                                                        <i class="ki-duotone ki-screen fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                        </i>
                                                    </span>
                                                    <span class="menu-title">System</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<div class="post d-flex flex-column-fluid" id="kt_post">
                            @yield('content')
						</div>
					</div>
                    <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
						<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
							<div class="text-dark order-2 order-md-1">

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<script>var hostUrl = "assets/";</script>
		<script src="{{ asset ('metronic/assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{ asset ('metronic/assets/js/scripts.bundle.js')}}"></script>
        @yield('js_after')
		<script src="https://unpkg.com/sweetalert2@11.9.0/dist/sweetalert2.all.js"></script>
        @include('sweetalert::alert')
        @vite(['resources/js/app.js'])
        @auth
            <script>
                (function waitForEcho() {
                    if (typeof window.Echo === 'undefined') {
                        return setTimeout(waitForEcho, 200);
                    }

                    const myId = {{ Auth::id() }};
                    const badge = document.getElementById('navbar-unread-badge');

                    // which conversation is open right now? (set by messenger page)
                    function openChatWith() {
                        return window.currentOpenChatId || null;
                    }

                    window.Echo.private(`chat.${myId}`)
                        .listen('.MessageSent', (e) => {
                            // if I'm currently viewing this sender's chat, don't count it
                            if (openChatWith() && parseInt(e.message.sender_id) === parseInt(openChatWith())) {
                                return;
                            }
                            let current = parseInt(badge.textContent) || 0;
                            badge.textContent = current + 1;
                            badge.style.display = '';
                        });
                })();
            </script>
        @endauth
	</body>
</html>
