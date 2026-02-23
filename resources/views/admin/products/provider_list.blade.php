<ul class="provider-list">
    @if(isset($providers))
        @foreach ($providers as $provider)
            <li class="provider-item">
                <a href="{{ $provider->official_link }}" target="_blank"><h2>{{ $provider->name }}</h2></a>
                <div class="description">{{ $provider->description }}</div>
            </li>
        @endforeach
    @endif
</ul>
