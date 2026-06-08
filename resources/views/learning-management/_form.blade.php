<div class="card-body">

    <div class="row mb-5">
        <div class="col-md-6">
            <label class="fs-5 fw-semibold mb-2 required">Week</label>
            <input type="text" name="week" class="form-control form-control-solid @error('week') is-invalid @enderror" placeholder="e.g., Week 1" value="{{ old('week') }}">
            @error('week') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="col-md-6">
            <label class="fs-5 fw-semibold mb-2 required">Date</label>
            <input type="date" name="class_date" class="form-control form-control-solid @error('class_date') is-invalid @enderror" value="{{ old('class_date') }}">
            @error('class_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-md-12 mb-5">
        <label class="fs-5 fw-semibold mb-2">Topic Title</label>
        <input type="text" name="topic" class="form-control form-control-solid" placeholder="e.g., EA Concept" value="{{ old('topic') }}">
    </div>

    {{-- Zoom auto-created — manual fields hidden
    <div class="alert alert-light-primary d-flex align-items-center mb-8">
        <i class="ki-duotone ki-information fs-2x text-primary me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        <span>A Zoom meeting link will be generated automatically for the selected session.</span>
    </div> --}}

    <div class="row mb-8">
        <div class="col-12">
            <label class="fs-5 fw-semibold mb-2">Lecture Note (PDF, PPTX, DOCX)</label>
            <input type="file" name="lecture_note" class="lampiran filepond" data-max-file-size="10MB">
            @error('lecture_note') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mb-8">
        <div class="col-12">
            <label class="fs-5 fw-semibold mb-2">Exercise / Worksheet</label>
            <input type="file" name="exercise" class="lampiran filepond" data-max-file-size="10MB">
            @error('exercise') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
</div>
