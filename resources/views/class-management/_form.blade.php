<div class="card-body">
    <div class="d-flex flex-column mb-6">
        <label class="fs-6 fw-semibold mb-2">Tutor Name</label>
        <input type="text"
            class="form-control form-control-lg form-control-solid"
            value="{{ Auth::user()->name }}"
            readonly>
        <input type="hidden" name="tutor_id" value="{{ Auth::id() }}">
    </div>
    <div class="row mb-6">
        <div class="col-lg-6">
            <label class="col-form-label required fw-semibold fs-6">Subject</label>
            <select name="subject_id" class="form-select form-select-lg form-select-solid @error('subject_id') is-invalid @enderror">
                <option value="">Select Subject</option>
                @foreach($listSubject as $subject)
                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
            @error('subject_name')
                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-6">
            <label class="col-form-label required fw-semibold fs-6">Max Students (Quota)</label>
            <input type="number" name="max_students" class="form-control form-control-lg form-control-solid" placeholder="e.g., 30" value="{{ old('max_students') }}">
        </div>

    </div>

    <div class="row mb-6">
        <div class="col-lg-6">
            <label class="col-form-label required fw-semibold fs-6">Category (Level)</label>
            <select name="category_code" class="form-select form-select-lg form-select-solid">
                <option value="">Select Level</option>
                @foreach($listCategory as $cat)
                    <option value="{{ $cat->code }}" {{ old('category_code') == $cat->code ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="col-lg-6">
            <label class="col-form-label required fw-semibold fs-6">Medium of Instruction</label>
            <select name="language_code" class="form-select form-select-lg form-select-solid">
                @foreach($listLanguage as $lang)
                    <option value="{{ $lang->code }}" {{ old('language_code') == $lang->code ? 'selected' : '' }}>
                        {{ $lang->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="d-flex flex-column mb-8">
        <label class="fs-6 fw-semibold mb-2">Learning Objective</label>
        <textarea name="learning_objective" class="form-control form-control-solid @error('learning_objective') is-invalid @enderror" rows="4" placeholder="Provide a brief overview of the topics, learning outcomes, or syllabus for this class...">{{ old('learning_objective', $class->learning_objective ?? '') }}</textarea>
        @error('learning_objective')
            <div class="text-danger fs-7 mt-1">{{ $message }}</div>
        @enderror
    </div>
    <div id="kt_repeater_schedule">
        <div class="form-group">
            <div data-repeater-list="class_schedules">
                <div data-repeater-item class="form-group row mb-5 align-items-center">
                    <div class="col-md-5">
                        <label class="form-label">Day</label>
                        <select name="day" class="form-select form-select-solid">
                            @foreach($listDay as $day)
                                <option value="{{ $day->name }}">{{ $day->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Start Time</label>
                        <input type="text" name="start_time" class="form-control form-control-solid kt_timepicker" placeholder="08:00" />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">End Time</label>
                        <input type="text" name="end_time" class="form-control form-control-solid kt_timepicker" placeholder="10:00" />
                    </div>

                    <div class="col-md-1 mt-8">
                        <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>

                        </a>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" data-repeater-create class="btn btn-sm btn-light-primary">
            <i class="ki-duotone ki-plus fs-5"></i> Add Another Slot
        </button>
    </div>
    {{-- <div class="row mb-6">
        <div class="col-lg-6">
            <label class="col-form-label required fw-semibold fs-6">Monthly Fee (RM)</label>
            <div class="input-group input-group-solid">
                <span class="input-group-text">RM</span>
                <input type="number" step="0.01" name="fee" class="form-control form-control-lg form-control-solid" placeholder="0.00" value="{{ old('fee') }}">
            </div>
        </div>
        <div class="col-lg-6">
            <label class="col-form-label required fw-semibold fs-6">Max Students (Quota)</label>
            <input type="number" name="max_students" class="form-control form-control-lg form-control-solid" placeholder="e.g., 30" value="{{ old('max_students') }}">
        </div>
    </div> --}}

    {{-- <div class="row mb-6">
        <label class="col-auto col-form-label fw-semibold fs-6 me-3">Class Mode</label>
        <div class="col-auto d-flex align-items-center">
            <div class="form-check form-check-custom form-check-solid ">
                <input class="form-check-input" type="radio" name="mode" value="physical" id="physical" checked />
                <label class="form-check-label" for="physical">Online</label>
            </div>
        </div>
    </div> --}}

</div>

    {{-- <div class="card-footer d-flex justify-content-end py-6 px-9">
        <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
        <button type="submit" class="btn btn-primary" style="background-color: #388E3C;">Create Class</button>
    </div> --}}
