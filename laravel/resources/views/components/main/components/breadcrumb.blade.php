@props(['links' => []])

<nav aria-label="breadcrumb">
    <ol {{ $attributes->class(['breadcrumb', 'bg-light', 'border', 'rounded', 'px-3', 'py-2']) }}>
        @foreach ($links as $linkText => $linkURL)
            @if (empty($linkURL))
                <li class="breadcrumb-item active" aria-current="page">{{ $linkText }}</li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $linkURL }}">{{ $linkText }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>