{{-- Data Properties ['label', 'options', 'selected'] Attributes ['name'] --}}

@props(['label', 'options' => [], 'selected' => '', 'defaultOption' => false])

<div class="form-group">
    <label for="select{{ Str::studly($attributes->get('name')) }}">{{ $label }}</label>
    <select
        {{ $attributes
            ->class(['form-control', 'is-invalid' => $errors->has($attributes->get('name'))]) 
            ->merge(['id' => 'select' . Str::studly($attributes->get('name'))]) }}
        @error($attributes->get('name')) aria-describedby="validation{{ Str::studly($attributes->get('name')) }}Feedback" @enderror
    >
        @if ($defaultOption)
            <option value="">{{ $defaultOption }}</option>
        @endif
        @foreach ($options as $optionValue => $option)
            <option value="{{ $optionValue }}" @selected($optionValue == $selected)>{{ $option }}</option>
        @endforeach
    </select>
    @error($attributes->get('name'))
        <div class="invalid-feedback" id="validation{{ Str::studly($attributes->get('name')) }}Feedback">
            {{ $message }}
        </div>
    @enderror
</div>