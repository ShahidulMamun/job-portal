{{-- resources/views/admin/companies/_form.blade.php  (shared by create + edit) --}}
@php
    $field = fn ($k) => old($k, $company->{$k} ?? '');
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold" for="name">Company name <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" value="{{ $field('name') }}" class="form-control @error('name') is-invalid @enderror" required maxlength="150">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold" for="location">Location</label>
        <input type="text" id="location" name="location" value="{{ $field('location') }}" class="form-control @error('location') is-invalid @enderror" placeholder="Dhaka">
        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold" for="website">Website</label>
        <input type="url" id="website" name="website" value="{{ $field('website') }}" class="form-control @error('website') is-invalid @enderror" placeholder="https://example.com">
        @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold" for="description">About the company</label>
        <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror" maxlength="5000">{{ $field('description') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold" for="logo">Logo <small class="text-muted">(JPG, PNG, WEBP · max 2 MB)</small></label>
        <input type="file" id="logo" name="logo" accept="image/*" class="form-control @error('logo') is-invalid @enderror">
        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror

        @if ($company->exists && $company->logo)
            <div class="d-flex align-items-center gap-3 mt-2">
                <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" style="height:56px;width:56px;object-fit:cover;border-radius:10px;border:1px solid #dee2e6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo" value="1">
                    <label class="form-check-label" for="remove_logo">Remove current logo</label>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $company->is_active ?? true))>
            <label class="form-check-label" for="is_active">Active (visible on the website)</label>
        </div>
    </div>
</div>