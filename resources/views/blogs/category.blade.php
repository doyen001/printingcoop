@extends('elements.app')

@section('content')
<div class="blog-boxes-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="blog-boxes">
            <div class="row">
                <div class="col-md-8 col-lg-9 col-xl-9">
                    <div class="blog-boxes">
                        <div class="row">
                            @if(count($blogs) > 0)
                                @foreach($blogs as $blog)
                                    @php
                                        $imageurl = getBlogImage($blog['image'], 'large');
                                    @endphp
                                    <div class="col-md-12 col-lg-6 col-xl-4">
                                        <div class="single-blog-box">
                                            <div class="single-blog-area">
                                                <div class="single-blog-img" style="background-image: url({{ $imageurl }})"></div>
                                                <div class="single-blog-box-inner">
                                                    <div class="single-blog-category">
                                                        <a href="{{ url('Blogs/category/' . ($blog['blog_category_slug'] ?? $blog['category_id'] ?? '')) }}">
                                                            <span>
                                                                {{ $language_name == 'french' ? ($blog['category_name_french'] ?? '') : ($blog['category_name'] ?? '') }}
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="universal-small-dark-title">
                                                        <span>
                                                            <a href="{{ url('Blogs/singleview/' . ($blog['blog_slug'] ?? '')) }}">
                                                                {{ $language_name == 'french' ? $blog['title_french'] : $blog['title'] }}
                                                            </a>
                                                        </span>
                                                    </div>
                                                    <div class="single-blog-date">
                                                        <span>{{ date('F d Y', strtotime($blog['created'])) }}</span>
                                                    </div>
                                                    <div class="universal-dark-info less-content">
                                                        <span>
                                                            {!! $language_name == 'french' ? $blog['content_french'] : $blog['content'] !!}
                                                        </span>
                                                    </div>
                                                    <div class="single-blog-more universal-dark-info">
                                                        <a href="{{ url('Blogs/singleview/' . ($blog['blog_slug'] ?? '')) }}">
                                                            <button class="checkout-view" type="submit">
                                                                {{ $language_name == 'french' ? 'Lire la suite' : 'Read more' }}
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-12 col-lg-12 col-xl-12 text-center">
                                    {{ $language_name == 'french' ? 'Aucun blog trouvé' : 'No blog found' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 col-xl-3">
                    @include('blogs.blog-sidebar')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
