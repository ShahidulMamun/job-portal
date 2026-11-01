@php
    $f        = $filters ?? [];
    $cats     = $allCategories ?? collect();
    $trending = $popular ?? [];
@endphp
<form class="sbox" action="{{ route('jobs.index') }}" method="GET" role="search"
      data-search
      data-suggest-url="{{ route('search.suggest') }}"
      data-trending="{{ json_encode($trending, JSON_UNESCAPED_UNICODE) }}"
      autocomplete="off">

    <div class="sbox-field sbox-q">
        <i class="fa-solid fa-magnifying-glass lead"></i>
        <div class="sbox-input">
            <label for="sq">What</label>
            <input id="sq" type="text" name="q" value="{{ $f['q'] ?? '' }}" maxlength="100"
                   placeholder="Job title, skill or company" role="combobox"
                   aria-autocomplete="list" aria-expanded="false" aria-controls="sdropQ">
        </div>
        <button type="button" class="sbox-icon" data-clear hidden aria-label="Clear search"><i class="fa-solid fa-xmark"></i></button>
        <button type="button" class="sbox-lang" data-voice-lang hidden title="Voice language">EN</button>
        <button type="button" class="sbox-icon sbox-mic" data-mic hidden aria-label="Search by voice"><i class="fa-solid fa-microphone"></i></button>
        <div class="sdrop" id="sdropQ" data-drop-q role="listbox" hidden></div>
    </div>

    <div class="sbox-field sbox-loc">
        <i class="fa-solid fa-location-dot lead"></i>
        <div class="sbox-input">
            <label for="sl">Where</label>
            <input id="sl" type="text" name="location" value="{{ $f['location'] ?? '' }}" maxlength="60"
                   placeholder="City or &ldquo;Remote&rdquo;" role="combobox"
                   aria-autocomplete="list" aria-expanded="false" aria-controls="sdropL">
        </div>
        <div class="sdrop" id="sdropL" data-drop-l role="listbox" hidden></div>
    </div>

    <div class="sbox-field sbox-cat">
        <i class="fa-solid fa-layer-group lead"></i>
        <div class="sbox-input">
            <label for="sc">Field</label>
            <select id="sc" name="category">
                <option value="">All categories</option>
                @foreach ($cats as $c)
                    <option value="{{ $c->id }}" @selected((string) ($f['category'] ?? '') === (string) $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <button class="btn btn-gold sbox-submit" type="submit"><i class="fa-solid fa-magnifying-glass"></i><span>Search jobs</span></button>
</form>
