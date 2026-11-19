{{-- resources/views/partials/desk-art.blade.php  – recruiter desk illustration (colors come from home.css) --}}
<svg class="desk-svg" viewBox="0 0 520 560" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false">
    {{-- backdrop --}}
    <circle class="ds-soft" cx="262" cy="250" r="214"/>
    <circle class="ds-soft2" cx="262" cy="250" r="150"/>

    {{-- monitor --}}
    <rect class="ds-ink" x="70" y="108" width="380" height="254" rx="20"/>
    <rect class="ds-white" x="84" y="122" width="352" height="226" rx="11"/>
    <rect class="ds-ink2" x="236" y="362" width="48" height="50"/>
    <rect class="ds-ink" x="186" y="406" width="148" height="14" rx="7"/>

    {{-- screen: top bar --}}
    <path class="ds-paper2" d="M84 133a11 11 0 0 1 11-11h330a11 11 0 0 1 11 11v17H84z"/>
    <circle class="ds-rose" cx="100" cy="136" r="3.5"/><circle class="ds-gold" cx="112" cy="136" r="3.5"/><circle class="ds-mint" cx="124" cy="136" r="3.5"/>
    <rect class="ds-line" x="300" y="131" width="110" height="10" rx="5"/>

    {{-- screen: sidebar --}}
    <rect class="ds-paper" x="84" y="150" width="62" height="198"/>
    <rect class="ds-gold" x="94" y="164" width="42" height="9" rx="4.5"/>
    <rect class="ds-line" x="94" y="184" width="42" height="7" rx="3.5"/>
    <rect class="ds-line" x="94" y="200" width="34" height="7" rx="3.5"/>
    <rect class="ds-line" x="94" y="216" width="40" height="7" rx="3.5"/>

    {{-- screen: candidate rows --}}
    <g>
        <rect class="ds-card" x="158" y="162" width="266" height="38" rx="9"/>
        <circle class="ds-soft" cx="180" cy="181" r="11"/><circle class="ds-gold" cx="180" cy="177" r="4"/><path class="ds-gold" d="M172 190a8 8 0 0 1 16 0z"/>
        <rect class="ds-ink" x="199" y="173" width="84" height="7" rx="3.5"/><rect class="ds-line" x="199" y="185" width="56" height="6" rx="3"/>
        <rect class="ds-gold" x="368" y="172" width="44" height="18" rx="9"/>
        <path class="ds-stroke-w" d="M382 181l4 4 8-8"/>
    </g>
    <g>
        <rect class="ds-card" x="158" y="208" width="266" height="38" rx="9"/>
        <circle class="ds-soft" cx="180" cy="227" r="11"/><circle class="ds-gold-deep" cx="180" cy="223" r="4"/><path class="ds-gold-deep" d="M172 236a8 8 0 0 1 16 0z"/>
        <rect class="ds-ink" x="199" y="219" width="70" height="7" rx="3.5"/><rect class="ds-line" x="199" y="231" width="62" height="6" rx="3"/>
        <rect class="ds-mint-soft" x="368" y="218" width="44" height="18" rx="9"/>
        <rect class="ds-mint" x="378" y="224" width="24" height="6" rx="3"/>
    </g>
    <g>
        <rect class="ds-card" x="158" y="254" width="266" height="38" rx="9"/>
        <circle class="ds-soft" cx="180" cy="273" r="11"/><circle class="ds-gold" cx="180" cy="269" r="4"/><path class="ds-gold" d="M172 282a8 8 0 0 1 16 0z"/>
        <rect class="ds-ink" x="199" y="265" width="92" height="7" rx="3.5"/><rect class="ds-line" x="199" y="277" width="48" height="6" rx="3"/>
        <rect class="ds-paper2" x="368" y="264" width="44" height="18" rx="9"/>
    </g>

    {{-- screen: mini bar chart --}}
    <rect class="ds-gold-soft" x="158" y="302" width="40" height="36" rx="4"/>
    <rect class="ds-gold" x="206" y="314" width="40" height="24" rx="4"/>
    <rect class="ds-gold-deep" x="254" y="306" width="40" height="32" rx="4"/>
    <rect class="ds-gold" x="302" y="320" width="40" height="18" rx="4"/>
    <rect class="ds-gold-soft" x="350" y="310" width="40" height="28" rx="4"/>

    {{-- desk --}}
    <rect class="ds-gold-deep" x="24" y="424" width="472" height="24" rx="8"/>
    <rect class="ds-ink2" x="44" y="448" width="432" height="120"/>
    <rect class="ds-ink" x="44" y="448" width="432" height="6" opacity=".35"/>

    {{-- keyboard + mouse --}}
    <rect class="ds-white" x="140" y="428" width="170" height="11" rx="5"/>
    <rect class="ds-line" x="150" y="432" width="150" height="3" rx="1.5"/>
    <rect class="ds-white" x="336" y="428" width="22" height="11" rx="5.5"/>

    {{-- mug --}}
    <rect class="ds-white" x="52" y="394" width="30" height="32" rx="6"/>
    <path class="ds-stroke-g" d="M82 402h6a6 6 0 0 1 0 14h-6"/>
    <rect class="ds-gold" x="52" y="394" width="30" height="9" rx="4"/>

    {{-- plant --}}
    <path class="ds-gold-deep" d="M418 396h38l-5 30h-28z"/>
    <path class="ds-mint" d="M437 396c-14-6-18-24-12-38 12 6 16 22 12 38z"/>
    <path class="ds-gold" d="M437 396c2-18 12-30 26-34 2 16-8 30-26 34z"/>
    <path class="ds-mint" d="M437 396c-2-10 2-20 8-26 4 10 2 20-8 26z" opacity=".8"/>

    {{-- floating cards --}}
    <g>
        <rect class="ds-card-sh" x="372" y="56" width="132" height="58" rx="16"/>
        <circle class="ds-gold" cx="398" cy="85" r="14"/>
        <path class="ds-stroke-w" d="M391 85l5 5 9-10"/>
        <rect class="ds-ink" x="420" y="75" width="64" height="8" rx="4"/>
        <rect class="ds-line" x="420" y="90" width="46" height="7" rx="3.5"/>
    </g>
    <g>
        <rect class="ds-card-sh" x="14" y="196" width="118" height="52" rx="16"/>
        <circle class="ds-mint-soft" cx="38" cy="222" r="13"/>
        <path class="ds-stroke-m" d="M32 222l4 4 8-9"/>
        <rect class="ds-ink" x="58" y="213" width="58" height="8" rx="4"/>
        <rect class="ds-line" x="58" y="227" width="40" height="7" rx="3.5"/>
    </g>
</svg>