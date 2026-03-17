@extends('elements.app')

@section('title', 'Home - Printing Coop')

@section('content')
{{-- CI: application/views/Homes/index.php --}}

{{-- Slider Section (CI line 1) --}}
@include('elements.slider')

{{-- Store-specific product sections (CI lines 2-7) --}}
@if($website_store_id != 5)
    @include('elements.HomeSections.our_printed_products')
@else
    @include('elements.HomeSections.our_ink_printed_products')
    @include('elements.HomeSections.ecoink_search')
@endif

{{-- Section 1: ABOUT US (CI line 9) --}}
@include('elements.HomeSections.section_1')

{{-- Section 4: Montreal book printing (CI lines 12-14, not for EcoInk) --}}
@if($website_store_id != 5)
    @include('elements.HomeSections.section_4')
@endif

{{-- Section 2: Proudly Display Your Brand (CI line 10) --}}
@include('elements.HomeSections.section_2')

{{-- Section 3: OUR SERVICES (CI line 11) --}}
@include('elements.HomeSections.section_3')

{{-- Section 5: Our Promise To You (CI line 16) --}}
@include('elements.HomeSections.section_5')

{{-- Section 6: Main Services (CI line 17) --}}
@include('elements.HomeSections.section_6')

{{-- Section 7: REGISTER FOR FREE! (CI line 18) --}}
@include('elements.HomeSections.section_7')

@endsection
