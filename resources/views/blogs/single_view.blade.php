@extends('elements.app')

@section('content')
<div class="blog-boxes-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="blog-boxes">
            <div class="row">
                <div class="col-md-8 col-lg-9 col-xl-9">
                    <div class="single-inner-blog-box">
                        <div class="single-blog-area">
                            <div class="universal-dark-title">
                                <span>{{ $language_name == 'french' ? $blog['title_french'] : $blog['title'] }}</span>
                            </div>
                            @php
                                $imageurl = getBlogImage($blog['image'], 'large');
                            @endphp
                            <div class="single-blog-img">
                                <img src="{{ $imageurl }}">
                            </div>
                            <div class="single-blog-box-inner">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="single-blog-category">
                                            <a href="{{ url('Blogs/category/' . base64_encode($blog['category_id'])) }}">
                                                <span>
                                                    {{ $language_name == 'french' ? ($blog['category_name_french'] ?? '') : ($blog['category_name'] ?? '') }}
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="single-blog-date">
                                            <span>{{ date('F d Y', strtotime($blog['created'])) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="single-blog-inner-content">
                                    {!! $language_name == 'french' ? $blog['content_french'] : $blog['content'] !!}
                                    
                                    <div class="universal-small-dark-title">
                                        <span>{{ $language_name == 'french' ? 'Articles Liés:' : 'Related Articles:' }}</span>
                                    </div>
                                    <div class="universal-dark-info">
                                        <span>
                                            @if(isset($releted_blog))
                                                @foreach($releted_blog as $val)
                                                    @if($val['id'] != $blog['id'])
                                                        <a href="{{ url('Blogs/singleview/' . base64_encode($val['id'])) }}">
                                                            {{ $language_name == 'french' ? $val['title_french'] : $val['title'] }}
                                                        </a>
                                                        <br>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-share-section">
                                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5983d393d9a9b2c9" async="async"></script>
                                <div class="blog-share-section-inner">
                                    <div class="row align-items-center">
                                        <div class="col-md-5">
                                            <div class="blog-share-title">
                                                <span>{{ $language_name == 'french' ? 'Partager cette publication' : 'Share this post' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="post-sharing-button">
                                                <div class="addthis_inline_share_toolbox"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
