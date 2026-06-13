<div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">

    <div class="aside-menu flex-column-fluid">
        <div class="hover-scroll-overlay-y mx-3 my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="{default: '#kt_aside_toolbar, #kt_aside_footer', lg: '#kt_header, #kt_aside_toolbar, #kt_aside_footer'}"
            data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="5px">

            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true">

                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Main Menu</span>
                    </div>
                </div>

                {{-- ==========================================
                     1. ADMIN & SUPERADMIN SECTION
                     ========================================== --}}
                @if(auth()->user()->role == 'admin' || auth()->user()->role == 'superadmin')
                <div class="menu-item">
                    <a class="menu-link" href="{{route ('dashboard.admin')}}">
                        <span class="menu-icon">
                            {{-- <i class="ki-duotone ki-document fs-2"><span class="path1"></span><span class="path2"></span></i> --}}
                            <i class="ki-duotone ki-shop fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Dashboard</span>
                    </a>
                </div>
                <div class="menu-item">
                   <a class="menu-link" href="{{ route('application.indexTutor')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-address-book fs-2">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Tutor Application</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="{{route ('feedback.show-admin')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-like-folder fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Feedback</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="{{route ('payment.admin-index')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-dollar fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Payment</span>
                    </a>
                </div>
                {{-- <div class="menu-item">
                    <a class="menu-link" href="{{route ('enrollment.admin.index')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-document fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                        <span class="menu-title fw-semibold">All Enrollments</span>
                    </a>
                </div> --}}

                {{-- <div class="menu-item">
                    <a class="menu-link" href="{{route ('payment.index')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-document fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                        <span class="menu-title fw-semibold">Payment</span>
                    </a>
                </div> --}}
                {{-- <div class="menu-item">
                    <a class="menu-link" href="{{route ('enrollment.admin.tutorChanges')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-document fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                        <span class="menu-title fw-semibold">Request Change Tutor</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="{{route ('user-management.index')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-document fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                        <span class="menu-title fw-semibold">Request Change Tutor</span>
                    </a>
                </div> --}}
                @endif

                {{-- ==========================================
                     2. TUTOR SECTION
                     ========================================== --}}
                @if(auth()->user()->role == 'tutor' || auth()->user()->role == 'superadmin')
                <div class="menu-item">
                    <a class="menu-link" href="{{route ('dashboard.tutor')}}">
                        <span class="menu-icon">
                           <i class="ki-duotone ki-shop fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Dashboard</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="{{route ('class.index')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-briefcase fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                        <span class="menu-title fw-semibold">Class Management</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="{{route ('feedback.show-tutor')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-like-folder fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Feedback</span>
                    </a>
                </div>
                @endif

                {{-- ==========================================
                     3. STUDENT SECTION
                     ========================================== --}}
                @if(auth()->user()->role == 'student' || auth()->user()->role == 'superadmin')
                <div class="menu-item">
                    <a class="menu-link" href="{{route ('dashboard.student')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-shop fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Dashboard</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="{{route ('enrollment.index')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-teacher fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                        <span class="menu-title fw-semibold">Enrollment</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="{{route ('student.class.index')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-code fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        </span>
                        <span class="menu-title fw-semibold">My Classes</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="{{route ('payment.index')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-dollar fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Payment</span>
                    </a>
                </div>
                @endif

                {{-- ==========================================
                     4. ANNOUNCEMENT (SHARED)
                     ========================================== --}}
                @if(in_array(auth()->user()->role, ['admin', 'superadmin', 'tutor','student']))
                <div class="menu-item">
                    <a class="menu-link" href="{{route ('announcement.index')}}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-information-2 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        </span>
                        <span class="menu-title fw-semibold">Announcements</span>
                    </a>
                </div>
                @endif

                {{-- ==========================================
                     5. SYSTEM DEVELOPER TOOLS
                     ========================================== --}}
                @if(auth()->user()->role == 'superadmin')
                {{-- <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7 text-danger">Developer Console</span>
                    </div>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="/laratrust" target="_blank">
                        <span class="menu-icon"><i class="ki-duotone ki-abstract-24 fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title fw-semibold">Laratrust</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="/terminal" target="_blank">
                        <span class="menu-icon"><i class="ki-duotone ki-code fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span>
                        <span class="menu-title fw-semibold">Terminal</span>
                    </a>
                </div> --}}
                @endif

                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Account Settings</span>
                    </div>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="{{route('profile.edit')}}">
                        <span class="menu-icon"><i class="ki-duotone ki-profile-circle fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                        <span class="menu-title fw-semibold">Profile</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="aside-footer flex-column-auto py-5" id="kt_aside_footer">
        <a href="{{ route('logout') }}" class="btn btn-flex btn-custom btn-danger w-100"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
            <span class="btn-label">Logout</span>
            <i class="ki-duotone ki-exit-left ms-2 fs-2"><span class="path1"></span><span class="path2"></span></i>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">@csrf</form>
    </div>
</div>
