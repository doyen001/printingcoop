<!-- {{-- CI: application/views/elements/breadcrumb.php --}}
@php
    $language_name = $language_name ?? config('store.language_name', 'english');
    $page_title = $page_title ?? '';
    $category_name = $category_name ?? '';
    $sub_category_name = $sub_category_name ?? '';
    $category_slug = $category_slug ?? '';
@endphp

@if(!in_array($page_title, ['Home', 'Product Details', 'Accueil']))
<div class="page-title-section universal-bg-white">
    <div class="container">
        <div class="page-title-section-inner universal-half-spacing">
            {{-- Schema.org Breadcrumb Markup for SEO --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb inner-breadcrum" itemscope itemtype="https://schema.org/BreadcrumbList">
                    {{-- Home Link (Always First) --}}
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="{{ url('/') }}" itemprop="item">
                            <span itemprop="name">
                                {{ $language_name == 'french' ? 'Accueil' : 'Home' }}
                            </span>
                        </a>
                        <meta itemprop="position" content="1" />
                    </li>
                    
                    {{-- Category Breadcrumb Logic (CI lines 18-52) --}}
                    @if(empty($sub_category_name) && empty($category_name))
                        {{-- All Categories --}}
                        <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <span itemprop="name">{{ $language_name == 'french' ? 'Toutes catégories' : 'All categories' }}</span>
                            <meta itemprop="position" content="2" />
                        </li>
                    @endif
                    
                    @if(!empty($sub_category_name))
                        {{-- Category with Subcategory --}}
                        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="{{ url('Products/' . $category_slug) }}" itemprop="item">
                                <span itemprop="name">{{ $category_name }}</span>
                            </a>
                            <meta itemprop="position" content="2" />
                        </li>
                        <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <span itemprop="name">{{ $sub_category_name }}</span>
                            <meta itemprop="position" content="3" />
                        </li>
                    @elseif(!empty($category_name))
                        {{-- Category Only --}}
                        <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <span itemprop="name">{{ $category_name }}</span>
                            <meta itemprop="position" content="2" />
                        </li>
                    @endif
                    
                    {{-- Custom Breadcrumbs Array Support --}}
                    @if(isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs) > 0)
                        @foreach($breadcrumbs as $index => $breadcrumb)
                            @php
                                $position = $index + 2; // Start after Home
                                $isLast = ($index === count($breadcrumbs) - 1);
                            @endphp
                            
                            @if($isLast)
                                {{-- Last Item (Current Page) - Non-clickable --}}
                                <li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                    <span itemprop="name">{{ $breadcrumb['title'] ?? $breadcrumb['name'] ?? '' }}</span>
                                    <meta itemprop="position" content="{{ $position }}" />
                                </li>
                            @else
                                {{-- Intermediate Items - Clickable --}}
                                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                    <a href="{{ $breadcrumb['url'] ?? '#' }}" itemprop="item">
                                        <span itemprop="name">{{ $breadcrumb['title'] ?? $breadcrumb['name'] ?? '' }}</span>
                                    </a>
                                    <meta itemprop="position" content="{{ $position }}" />
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</div>
@endif -->

<!-- 
@if (!in_array($page_title, ['Home', 'Product Details', 'Accueil']))
<div class="page-title-section universal-bg-white">
    <div class="container">
        <div class="page-title-section-inner universal-half-spacing kkk">
            <div class="inner-breadcrum bbb">
                <a href="{{ config('app.url') }}">
                    {{ app()->getLocale() == 'fr' ? 'Accueil' : 'Home' }}
                </a>
                
                @if(empty($sub_category_name) && empty($category_name))
                    /
                    <span class="current gg">
                        {{ __('all categories') }}
                    </span>
                @endif
                
                @if(!empty($sub_category_name))
                    /
                    <span class="current">
                        <a href="{{ url('Products/' . $category_slug) }}">
                            {{ $category_name }}
                        </a>
                    </span>
                @endif
                
                @if(empty($sub_category_name) && !empty($category_name))
                    /
                    <span class="current">
                        {{ $category_name }}
                    </span>
                @endif
                
                @if(isset($sub_category_name))
                    /
                    <span>
                        {{ $sub_category_name }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
@endif -->