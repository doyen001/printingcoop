{{-- CI: application/views/elements/slider.php --}}
<div class="main-slider-section">
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($Branrers as $key => $list)
                <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></li>
            @endforeach
        </ol>
        
        <div class="carousel-inner">
            @php
                $showIndicators = false;
                if ($Branrers && count($Branrers) > 1) {
                    $showIndicators = true;
                }
            @endphp
            
            @if($Branrers && count($Branrers) > 0)
                @foreach($Branrers as $key => $list)
                    @php
                        $class = $key == 0 ? 'active' : '';
                        // Get banner image based on language (CI lines 29-32)
                        $imageurl = getBannerImage($list->banner_image, 'large');
                        if ($language_name == 'french' && !empty($list->banner_image_french)) {
                            $imageurl = getBannerImage($list->banner_image_french, 'large');
                        }
                    @endphp
                    <div class="carousel-item {{ $class }}">
                        <img src="{{ $imageurl }}" alt="{{ $list->name ?? 'Banner' }}">
                    </div>
                @endforeach
            @else
                {{-- Default banner if no banners exist (CI lines 44-46) --}}
                <div class="carousel-item active">
                    <a href="javascript:void(0)">
                        <img src="{{ asset('defaults/banner-no-image.png') }}" alt="Default Banner">
                    </a>
                </div>
            @endif
        </div>
        
        {{-- Carousel controls (CI lines 52-61) --}}
        @if($showIndicators)
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <i class="las la-angle-left"></i>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <i class="las la-angle-right"></i>
            </a>
        @endif
    </div>
</div>
