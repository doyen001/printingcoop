@extends('elements.app')

@section('content')

<style>
    :root {
        --primary-color: #f28738;
        --secondary-color: #ff6b00;
        --text-dark: #2d3436;
        --text-light: #636e72;
        --border-color: #e9ecef;
        --bg-light: #f8f9fa;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    /* Blog Section */
    .blog-boxes-section {
        background: var(--bg-light);
        padding: 4rem 0;
    }

    .blog-boxes {
        margin-top: 2rem;
    }

    /* Blog Cards */
    .single-blog-box {
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        background-color: transparent;
        box-shadow: none;
    }

    .single-blog-box::after {
        display: none;
    }

    .single-blog-area {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .single-blog-area:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-8px);
    }

    /* Blog Image */
    .single-blog-img {
        position: relative;
        padding-top: 60%;
        background-size: cover;
        background-position: center;
        overflow: hidden;
        transition: transform 0.5s ease;
    }

    .single-blog-area:hover .single-blog-img {
        transform: scale(1.05);
    }

    .single-blog-img::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.1) 100%);
        z-index: 1;
    }

    /* Blog Content */
    .single-blog-box-inner {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Category Badge */
    .single-blog-category {
        margin-bottom: 1rem;
    }

    .single-blog-category a {
        text-decoration: none;
        display: inline-block;
    }

    /* .single-blog-category span {
        background: var(--primary-color);
        color: white;
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .single-blog-category:hover span {
        background: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(242, 135, 56, 0.3);
    } */

    /* Blog Title */
    .universal-small-dark-title {
        margin-bottom: 0.75rem;
    }

    .universal-small-dark-title span {
        display: block;
    }

    .universal-small-dark-title a {
        color: var(--text-dark);
        text-decoration: none;
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.4;
        transition: color 0.2s ease;
    }

    .universal-small-dark-title a:hover {
        color: var(--primary-color);
    }

    /* Blog Date */
    .single-blog-date {
        margin-bottom: 1rem;
    }

    .single-blog-date span {
        color: var(--text-light);
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .single-blog-date span::before {
        content: '\f073';
        font-family: 'Line Awesome Free';
        font-weight: 900;
        font-size: 0.8rem;
        opacity: 0.7;
    }

    /* Blog Content */
    .universal-dark-info {
        color: var(--text-light);
        line-height: 1.6;
        margin-bottom: 1.5rem;
        flex: 1;
    }

    .less-content {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Read More Button */
    .single-blog-more {
        margin-top: auto;
    }

    .single-blog-more a {
        text-decoration: none;
    }

    .checkout-view {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.875rem 1.5rem;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
    }

    .checkout-view::before {
        content: '\f054';
        font-family: 'Line Awesome Free';
        font-weight: 900;
        font-size: 0.8rem;
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
    }

    .checkout-view:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(242, 135, 56, 0.3);
    }

    .checkout-view:hover::before {
        opacity: 1;
        transform: translateX(0);
    }

    /* Empty State */
    .text-center {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-light);
        font-size: 1.1rem;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .blog-boxes-section {
            padding: 3rem 0;
        }

        .single-blog-box-inner {
            padding: 1.25rem;
        }

        .universal-small-dark-title a {
            font-size: 1.1rem;
        }

        .checkout-view {
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .blog-boxes-section {
            padding: 2rem 0;
        }

        .single-blog-img {
            padding-top: 50%;
        }

        .single-blog-box-inner {
            padding: 1rem;
        }

        .universal-small-dark-title a {
            font-size: 1rem;
        }
    }
</style>
<div class="blog-boxes-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="blog-boxes">
            <div class="row">
                @if(count($blogs) > 0)
                    @foreach($blogs as $blog)
                        @php
                            $imageurl = getBlogImage($blog['image'], 'large');
                        @endphp
                        <div class="col-md-6 col-lg-4 col-xl-4">
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
</div>
@endsection
