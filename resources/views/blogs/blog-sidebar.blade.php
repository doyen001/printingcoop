{{-- CI: application/views/Blogs/blog-sidebar.php --}}
<div class="blog-sidebar">
    <div class="blog-search-bar">
        <form action="{{ url('Blogs/search') }}">
            <input type="text" 
                   placeholder="{{ $language_name == 'french' ? 'Rechercher dans le blog ici ...' : 'Search blog here ...' }}" 
                   name="search" 
                   required 
                   value="{{ request('search', '') }}">
            <button><i class="las la-search"></i></button>
        </form>
    </div>
    <div class="blog-sidebar-posts">
        <ul class="nav nav-pills">
            <li><a class="active" data-toggle="pill" href="#Popular">{{ $language_name == 'french' ? 'Populaire' : 'Popular' }}</a></li>
            <li><a class="" data-toggle="pill" href="#Latest">{{ $language_name == 'french' ? 'dernière' : 'Latest' }}</a></li>
        </ul>
        <div class="tab-content">
            <div id="Popular" class="tab-pane fade active show">
                @if(isset($popularblogs))
                    @foreach($popularblogs as $pblog)
                        @php
                            $imageurl = getBlogImage($pblog['image'], 'large');
                        @endphp
                        <div class="blog-sidebar-single-post">
                            <a href="{{ url('Blogs/singleview/' . ($pblog['blog_slug'] ?? '')) }}">
                                <div class="blog-sidebar-single-post-img" style="background-image: url({{ $imageurl }})"></div>
                                <div class="blog-sidebar-single-detail">
                                    <div class="single-blog-title">
                                        <span>{{ $language_name == 'french' ? $pblog['title_french'] : $pblog['title'] }}</span>
                                    </div>
                                    <div class="single-blog-date">
                                        <span>{{ date('F d Y', strtotime($pblog['created'])) }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
            <div id="Latest" class="tab-pane fade">
                @if(isset($latestblogs))
                    @foreach($latestblogs as $lblog)
                        @php
                            $imageurl = getBlogImage($lblog['image'], 'large');
                        @endphp
                        <div class="blog-sidebar-single-post">
                            <a href="{{ url('Blogs/singleview/' . ($lblog['blog_slug'] ?? '')) }}">
                                <div class="blog-sidebar-single-post-img" style="background-image: url({{ $imageurl }})"></div>
                                <div class="blog-sidebar-single-detail">
                                    <div class="single-blog-title">
                                        <span>{{ $language_name == 'french' ? $lblog['title_french'] : $lblog['title'] }}</span>
                                    </div>
                                    <div class="single-blog-date">
                                        <span>{{ date('F d Y', strtotime($lblog['created'])) }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="blog-category-sidebar">
        <div class="universal-dark-title">
            <span>{{ $language_name == 'french' ? 'Catégories' : 'Categories' }}</span>
        </div>
        <div class="blog-category-list">
            @if(isset($category))
                @foreach($category as $cat)
                    <a href="{{ url('Blogs/category/' . ($cat['blog_category_slug'] ?? $cat['id'] ?? '')) }}">
                        <i class="las la-folder-open"></i>
                        {{ $language_name == 'french' ? $cat['category_name_french'] : $cat['category_name'] }}
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</div>
