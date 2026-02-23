{{-- 
    Home page view (replicate CI Homes/index.php)
    Lines 1-20: Store-specific section includes
--}}

{{-- Slider section (line 1) --}}
@include('elements.slider')

{{-- Our printed products section (lines 2-7) --}}
@if ($website_store_id != 5)
    @include('elements.home-sections.our_printed_products')
@else
    @include('elements.home-sections.our_ink_printed_products')
    @include('elements.home-sections.ecoink_search')
@endif

{{-- Section 1: ABOUT US (line 9) --}}
@include('elements.home-sections.section_1')

{{-- Section 2: Proudly Display Your Brand (line 10) --}}
@include('elements.home-sections.section_2')

{{-- Section 3: OUR SERVICES (line 11) --}}
@include('elements.home-sections.section_3')

{{-- Section 4: Montreal book printing (lines 12-14) --}}
@if ($website_store_id != 5)
    @include('elements.home-sections.section_4')
@endif

{{-- Section 5: Our Promise To You (line 16) --}}
@include('elements.home-sections.section_5')

{{-- Section 6: Main Services (line 17) --}}
@include('elements.home-sections.section_6')

{{-- Section 7: REGISTER FOR FREE! (line 18) --}}
@include('elements.home-sections.section_7')

{{-- COVID-19 message (line 19 - commented out) --}}
{{-- @include('elements.home-sections.covind19-msg') --}}
