@php
$containerFooter =
isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact'
? 'container-xxl'
: 'container-fluid';

$footerText = $globalSettings['footer_text'] ?? '';
$showCredit = ($globalSettings['footer_show_credit'] ?? '1') === '1';
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ $containerFooter }}">
    <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
      <div class="text-body">
        @if ($footerText)
          {{ $footerText }}
        @else
          &#169;
          <script>document.write(new Date().getFullYear());</script>
          , MAN 1 Kota Bandung
        @endif
      </div>
      @if ($showCredit)
        <div class="text-body">
          dibuat dengan <span class="text-danger">&#9829;</span> oleh <a href="{{ !empty(config('variables.creatorUrl')) ? config('variables.creatorUrl') : '' }}" target="_blank" class="footer-link">{{ !empty(config('variables.creatorName')) ? config('variables.creatorName') : '' }}</a>
        </div>
      @endif
    </div>
  </div>
</footer>
<!-- / Footer -->
