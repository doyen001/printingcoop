@php
$total_amount = session('total_amount', 0);
@endphp
<div class="universal-border-sitecolor-btn">
    <button type="submit" onclick="showLoder()">Confirm {{ CURREBCY_SYMBOL }}{{ number_format($total_amount, 2) }}</button>
</div>
