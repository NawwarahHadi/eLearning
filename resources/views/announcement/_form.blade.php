<div class="card-body border-top p-9">
    {{-- Title Field --}}
    <div class="row mb-6">
        <label class="col-lg-2 col-form-label required fw-semibold fs-6">Title</label>
        <div class="col-lg-10">
           <input type="text" name="title"
                  class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('title') is-invalid @enderror"
                  placeholder="Enter announcement title"
                  value="{{ old('title', $announcement->title) }}">
           @error('title')
               <div class="fv-plugins-message-container invalid-feedback">
                   {{ $message }}
               </div>
           @enderror
        </div>
    </div>

    {{-- Description Field --}}
    <div class="row">
        <label class="col-lg-2 col-form-label required fw-semibold fs-6">Description</label>
        <div class="col-lg-10">
           {{-- Changed to textarea since descriptions are usually longer --}}
           <textarea name="description" rows="4"
                     class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 @error('description') is-invalid @enderror"
                     placeholder="Enter details here...">{{ old('description', $announcement->description) }}</textarea>
           @error('description')
               <div class="fv-plugins-message-container invalid-feedback">
                   {{ $message }}
               </div>
           @enderror
        </div>
    </div>
</div>
