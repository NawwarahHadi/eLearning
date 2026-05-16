<div class="d-flex flex-stack w-lg-50">
    <span class="text-muted fs-7">Strongly Disagree</span>
    <div class="d-flex align-items-center">
        @foreach(range(1, 5) as $val)
            <div class="form-check form-check-custom form-check-solid mx-2">
                <input class="form-check-input h-20px w-20px" type="radio" name="{{ $name }}" value="{{ $val }}" required />
                <label class="form-check-label fw-bold">{{ $val }}</label>
            </div>
        @endforeach
    </div>
    <span class="text-muted fs-7">Strongly Agree</span>
</div>
