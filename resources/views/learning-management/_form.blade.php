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
        <input type="text" name="topic" class="form-control form-control-solid" placeholder="e.g., EA Concept">
    </div>
    <div class="row mb-8">
        <label class="fs-5 fw-semibold mb-2">Online Meeting Details</label>
        <div class="col-md-4">
            <input type="text" name="webex_link" class="form-control form-control-solid" placeholder="Meeting Link URL">
        </div>
        <div class="col-md-4">
            <input type="text" name="webex_meeting_code" class="form-control form-control-solid" placeholder="Meeting Code">
        </div>
        <div class="col-md-4">
            <input type="text" name="webex_passcode" class="form-control form-control-solid" placeholder="Passcode">
        </div>
    </div>

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

    {{-- <div class="card-footer d-flex justify-content-end py-6 px-9">
        <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
        <button type="submit" class="btn btn-primary" style="background-color: #388E3C;">Create Class</button>
    </div> --}}
