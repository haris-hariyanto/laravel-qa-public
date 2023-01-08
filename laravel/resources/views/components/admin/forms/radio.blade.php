{{-- Data Properties ['label', 'selected'] Attributes ['name', 'value'] --}}

@props(['label', 'selected' => ''])

<div class="custom-control custom-radio">
    <input type="radio"
        {{ $attributes
            ->class(['custom-control-input'])
            ->merge(['id' => 'radio' . Str::studly($attributes->get('name')) . Str::studly($attributes->get('value'))]) }}
        @checked($selected == $attributes->get('value'))
    >
    <label for="radio{{ Str::studly($attributes->get('name')) . Str::studly($attributes->get('value')) }}" class="custom-control-label">{{ $label }}</label>
</div>