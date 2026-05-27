@php
    $emptyMessage = $message ?? \App\CPU\translate('No data to show');
@endphp
<div class="text-center p-4">
    <img class="mb-3 w-160" src="{{ asset('assets/back-end/svg/illustrations/sorry.svg') }}" alt="">
    <p class="mb-0">{{ $emptyMessage }}</p>
</div>
