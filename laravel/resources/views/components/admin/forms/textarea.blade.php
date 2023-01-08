{{-- Data Properties ['label'] Attributes ['name'] --}}

@props(['label', 'rows' => 10])

<div class="form-group">
    <label for="textarea{{ Str::studly($attributes->get('name')) }}">{{ $label }}</label>
    <textarea {{ $attributes
        ->class(['form-control', 'is-invalid' => $errors->has($attributes->get('name'))])
        ->merge(['id' => 'textarea' . Str::studly($attributes->get('name')), 'rows' => $rows]) }}
        @error($attributes->get('name')) aria-describedby="validation{{ Str::studly($attributes->get('name')) }}Feedback" @enderror
    >{{ $slot }}</textarea>
    @error($attributes->get('name'))
        <div class="invalid-feedback" id="validation{{ Str::studly($attributes->get('name')) }}Feedback">
            {{ $message }}
        </div>
    @enderror
</div>