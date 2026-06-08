
{{-- <section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section> --}}
<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Profile Details </h3>
        </div>
    </div>

    <div id="kt_account_settings_profile_details" class="collapse show">
        <div class="card-body border-top p-9">

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-semibold fs-6">Profile Photo</label>
                <div class="col-lg-8">
                    <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('{{ asset('metronic/assets/media/avatars/blank.png') }}')">

                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('metronic/assets/media/avatars/blank.png') }}')">
                            <div class="image-input-wrapper w-125px h-125px"
                                style="background-image: url('{{
                                    $user->role === 'student'
                                    ? ($user->profile_photo ? asset('storage/' . $user->profile_photo) : asset('metronic/assets/media/avatars/blank.png'))
                                    : ($user->tutorProfile && $user->tutorProfile->profile_photo ? asset('storage/' . $user->tutorProfile->profile_photo) : asset('metronic/assets/media/avatars/blank.png'))
                                }}')">
                            </div>
                        </div>

                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change Photo">
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
                    @error('profile_photo') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
                <div class="col-lg-8">
                    <input type="text" name="name" class="form-control form-control-lg form-control-solid @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}"/>
                    @error('name') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Phone Number</label>
                <div class="col-lg-8">
                    <input type="tel" name="phone_number" class="form-control form-control-lg form-control-solid @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $user->phone_number) }}"/>
                    @error('phone_number') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Email Address</label>
                <div class="col-lg-8">
                    <input type="email" name="email" class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}"/>
                    @error('email') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Age</label>
                <div class="col-lg-8">
                    <input type="number" name="age" class="form-control form-control-lg form-control-solid @error('age') is-invalid @enderror" value="{{ old('age', $user->age) }}"/>
                    @error('age') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Home Address</label>
                <div class="col-lg-8">
                    <textarea name="address" class="form-control form-control-lg form-control-solid @error('address') is-invalid @enderror" rows="3">{{ old('address', $user->address) }}</textarea>
                    @error('address') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                </div>
            </div>

            @if($user->role === 'student')
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Academic Category</label>
                    <div class="col-lg-8">
                        <select name="category" data-control="select2" class="form-select form-select-solid form-select-lg fw-semibold @error('category') is-invalid @enderror">
                            <option value="">Select Level Category...</option>
                            @foreach ($listCategory as $cat)
                                <option value="{{ $cat->code }}" {{ old('category', $user->category) == $cat->code ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Preferred Online Learning Style</label>
                    <div class="col-lg-8">
                        <textarea name="student_style_description" class="form-control form-control-solid" rows="3" placeholder="Describe your preferred online learning style...">{{ old('student_style_description', $user->student_style_description) }}</textarea>
                        @error('student_style_description') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            @if($user->role === 'tutor')
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Subject Expertise</label>
                    <div class="col-lg-8">
                        <select name="subject_expertise[]" class="form-select form-select-solid" data-control="select2" data-placeholder="Select subjects..." data-allow-clear="true" multiple="multiple">
                            @foreach($listSubject as $subject)
                                <option value="{{ $subject->id }}" {{ in_array($subject->id, old('subject_expertise', is_array($user->subject_expertise) ? $user->subject_expertise : json_decode($user->subject_expertise ?? '[]', true))) ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_expertise') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Preferred Teaching Style</label>
                    <div class="col-lg-8">
                        <textarea name="tutor_style_description" class="form-control form-control-solid" rows="3" placeholder="Describe your preferred online teaching style...">{{ old('tutor_style_description', $user->tutor_style_description) }}</textarea>
                        @error('tutor_style_description') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Years of Experience</label>
                    <div class="col-lg-8">
                        <input type="number" name="experience" class="form-control form-control-lg form-control-solid @error('experience') is-invalid @enderror" value="{{ old('experience', $user->experience) }}"/>
                        @error('experience') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Current CGPA</label>
                    <div class="col-lg-8">
                        <input type="number" step="0.01" name="cgpa" class="form-control form-control-lg form-control-solid @error('cgpa') is-invalid @enderror" value="{{ old('cgpa', $user->cgpa) }}"/>
                        @error('cgpa') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Highest Education Level</label>
                    <div class="col-lg-8">
                        <select name="education_level_id" class="form-select form-select-solid fw-semibold" data-control="select2">
                            @foreach($level as $educationLevel)
                                <option value="{{ $educationLevel->id }}" {{ old('education_level_id', $user->education_level_id) == $educationLevel->id ? 'selected' : '' }}>
                                    {{ $educationLevel->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('education_level_id') <span class="text-danger fs-7">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Attached Documents (Resume / Transcript)</label>
                    <div class="col-lg-8 pt-3">
                        <span class="text-muted fs-7">To change your official validation documents, please contact the system administrator (Admin) for verification and score recalculation.</span>
                    </div>
                </div> --}}
            @endif

        </div>

        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="submit" id="kt_account_profile_details_submit" class="btn btn-success button-loading">
                <i class="ki-duotone ki-send me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <span class="indicator-label">Save Changes</span>
                <span class="indicator-progress">
                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>

    </div>
</div>
