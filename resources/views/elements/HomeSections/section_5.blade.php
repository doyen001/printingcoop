{{-- CI: application/views/elements/HomeSections/section_5.php --}}
{{-- Our Promise To You Section --}}
@php
    // Get background image based on language (CI lines 3-12)
    $background_image = $language_name == 'french' ? ($section_5->french_background_image ?? '') : ($section_5->background_image ?? '');
    $imageUrl = url('assets/images/parallax2-3.jpg');
    if (!empty($background_image)) {
        $imageUrl = url('uploads/sections/' . $background_image);
    }
@endphp

<div class="capability-section universal-spacing" style="background-image: url({{ $imageUrl }})">
    <div class="container">
        <div class="tab-products-section-inner">
            <div class="universal-light-title">
                <span>
                    @if($language_name == 'french')
                        {{ $section_5->name_french ?? '' }}
                    @else
                        {{ $section_5->name ?? '' }}
                    @endif
                </span>
            </div>
            <div class="universal-light-info">
                <span>
                    @if($language_name == 'french')
                        {{ $section_5->description_french ?? '' }}
                    @else
                        {{ $section_5->description ?? '' }}
                    @endif
                </span>
            </div>
            <div class="universal-light-info">
                <span>
                    @if($language_name == 'french')
                        {!! $section_5->content_french ?? '' !!}
                    @else
                        {!! $section_5->content ?? '' !!}
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>


