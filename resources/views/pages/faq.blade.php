@extends('elements.app')

@section('content')
<div class="contact-section-detail universal-spacing universal-bg-white">
    <div class="container">
        @if($language_name == 'french')
            {!! $pageData->description_french ?? '' !!}
        @else
            {!! $pageData->description ?? '' !!}
        @endif
    </div>
</div>
@endsection
