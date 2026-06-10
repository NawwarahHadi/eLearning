<div class="card-body">
    <div class="d-flex flex-column mb-6">
        <label class="fs-6 fw-semibold mb-2">Tutor Name</label>
        <input type="text"
            class="form-control form-control-lg form-control-solid"
            value="{{ $class->tutor->name ?? Auth::user()->name }}"
            readonly>
        <input type="hidden" name="tutor_id" value="{{ $class->tutor_id ?? Auth::id() }}">
    </div>

    <div class="row mb-6">
        <div class="col-lg-6">
            <label class="col-form-label required fw-semibold fs-6">Subject</label>
            <select name="subject_id" class="form-select form-select-lg form-select-solid @error('subject_id') is-invalid @enderror">
                <option value="">Select Subject</option>
                @foreach($listSubject as $subject)
                    <option value="{{ $subject->id }}"
                        {{ old('subject_id', $class->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
            @error('subject_id')
                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-6">
            <label class="col-form-label required fw-semibold fs-6">Max Students (Quota)</label>
            <input type="number" name="max_students" class="form-control form-control-lg form-control-solid"
                placeholder="e.g., 30" value="{{ old('max_students', $class->max_students ?? '') }}">
        </div>
    </div>

    <div class="row mb-6">
        <label class="fs-6 fw-semibold mb-2">Class Level</label>
        <select name="level" class="form-select form-select-solid" required>
            <option value="">Select level...</option>
            <option value="low"    {{ old('level', $class->level ?? '') == 'low' ? 'selected' : '' }}>Low — Foundational (for struggling students)</option>
            <option value="medium" {{ old('level', $class->level ?? '') == 'medium' ? 'selected' : '' }}>Medium — Standard</option>
            <option value="good"   {{ old('level', $class->level ?? '') == 'good' ? 'selected' : '' }}>Good — Advanced (for strong students)</option>
        </select>
    </div>

    <div class="row mb-6">
        <div class="col-lg-6">
            <label class="col-form-label required fw-semibold fs-6">Category (Level)</label>
            <select name="category_code" class="form-select form-select-lg form-select-solid">
                <option value="">Select Category</option>
                @foreach($listCategory as $cat)
                    <option value="{{ $cat->code }}"
                        {{ old('category_code', $class->category_code ?? '') == $cat->code ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_code') <div class="text-danger fs-7 mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="col-lg-6">
            <label class="col-form-label required fw-semibold fs-6">Medium of Instruction</label>
            <select name="language_code" class="form-select form-select-lg form-select-solid">
                @foreach($listLanguage as $lang)
                    <option value="{{ $lang->code }}"
                        {{ old('language_code', $class->language_code ?? '') == $lang->code ? 'selected' : '' }}>
                        {{ $lang->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="d-flex flex-column mb-8">
        <label class="fs-6 fw-semibold mb-2">Learning Objective</label>
        <textarea name="learning_objective" class="form-control form-control-solid @error('learning_objective') is-invalid @enderror"
            rows="4" placeholder="Provide a brief overview of the topics, learning outcomes, or syllabus for this class...">{{ old('learning_objective', $class->learning_objective ?? '') }}</textarea>
        @error('learning_objective')
            <div class="text-danger fs-7 mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div id="kt_repeater_schedule">
        <div class="form-group">
            <div data-repeater-list="class_schedules">
                @php
                    // Use existing schedules on edit; otherwise one empty row
                    $schedules = old('class_schedules', isset($class) && $class->schedules->count()
                        ? $class->schedules->toArray()
                        : [['day' => '', 'start_time' => '', 'end_time' => '']]);
                @endphp

                @foreach($schedules as $sch)
                <div data-repeater-item class="form-group row mb-5 align-items-center">
                    <div class="col-md-5">
                        <label class="form-label">Day</label>
                        <select name="day" class="form-select form-select-solid">
                            @foreach($listDay as $day)
                                <option value="{{ $day->name }}"
                                    {{ ($sch['day'] ?? '') == $day->name ? 'selected' : '' }}>
                                    {{ $day->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Start Time</label>
                        <input type="text" name="start_time" class="form-control form-control-solid kt_timepicker"
                            placeholder="08:00"
                            value="{{ isset($sch['start_time']) ? \Carbon\Carbon::parse($sch['start_time'])->format('H:i') : '' }}" />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">End Time</label>
                        <input type="text" name="end_time" class="form-control form-control-solid kt_timepicker"
                            placeholder="10:00"
                            value="{{ isset($sch['end_time']) ? \Carbon\Carbon::parse($sch['end_time'])->format('H:i') : '' }}" />
                    </div>

                    <div class="col-md-1 mt-8">
                        <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                            <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <button type="button" data-repeater-create class="btn btn-sm btn-light-primary">
            <i class="ki-duotone ki-plus fs-5"></i> Add Another Slot
        </button>
    </div>
</div>
