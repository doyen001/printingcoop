{{-- CI: application/views/elements/HomeSections/section_1.php --}}
{{-- ABOUT US Section --}}
<style>
    .about-img img {
        box-shadow: 7px 7px 7px 0px grey;
        border-radius: 7px;
    }
</style>
<div class="what-wedo-section universal-spacing">
    @if(isset($section_1))
        @if($language_name == 'french')
            {!! $section_1->content_french ?? '' !!}
        @else
            {!! $section_1->content ?? '' !!}
        @endif
    @endif
</div>
