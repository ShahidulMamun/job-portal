@php
    $cvUrl   = auth()->check() ? route('candidate.dashboard') : route('register');
    $cvLabel = auth()->check() ? 'Open dashboard to upload' : 'Create free account to continue';
@endphp
<div class="cvdrop" id="cvDrop" tabindex="0" role="button" aria-label="Drop your CV here or press Enter to browse files">
    <input type="file" id="cvFile" accept=".pdf,.doc,.docx" hidden>
    <div class="cvdrop-idle">
        <span class="cvdrop-ico"><i class="fa-solid fa-cloud-arrow-up"></i></span>
        <span class="cvdrop-txt">
            <strong>Drop your CV and find your job</strong>
            <small>PDF, DOC or DOCX &middot; up to 2 MB</small>
        </span>
        <span class="btn btn-dark btn-sm">Browse files</span>
    </div>
    <div class="cvdrop-done" hidden>
        <span class="cvdrop-ico is-ok"><i class="fa-solid fa-circle-check"></i></span>
        <span class="cvdrop-txt">
            <strong data-cv-name></strong>
            <small>Ready. Nothing is uploaded until you sign in.</small>
        </span>
        <a class="btn btn-gold btn-sm" href="{{ $cvUrl }}">{{ $cvLabel }} <i class="fa-solid fa-arrow-right"></i></a>
        <button type="button" class="cvdrop-reset" data-cv-reset>Choose another file</button>
    </div>
    <p class="cvdrop-error" role="alert" hidden></p>
</div>
