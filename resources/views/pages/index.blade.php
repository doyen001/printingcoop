@extends('elements.app')

@section('content')
<div class="contact-section-detail universal-spacing universal-bg-white">
    <div class="container">
        <div style="text-align:center">
            <h1>{{ $pageData->title }}</h1>
        </div>
        <div>
            @if($language_name == 'french')
                {!! $pageData->description_french ?? '' !!}
            @else
                {!! $pageData->description ?? '' !!}
            @endif
        </div>
    </div>
</div>
@endsection
