{{-- Data Properties ['label'] Attributes ['name'] --}}

@props(['label'])

<div class="form-group" x-data="{ showPassword: false }">
    <label for="field{{ Str::studly($attributes->get('name')) }}" class="form-label">{{ $label }}</label>
    <div @class(['input-group', 'has-validation' => $errors->has($attributes->get('name'))])>
        <input 
            {{ $attributes
                ->class(['form-control', 'is-invalid' => $errors->has($attributes->get('name'))])
                ->merge([':type' => 'showPassword ? "text" : "password"', 'id' => 'field' . Str::studly($attributes->get('name'))]) }}
            value="{{ old($attributes->get('name')) }}"
            @error($attributes->get('name')) aria-describedby="validation{{ Str::studly($attributes->get('name')) }}Feedback" @enderror
        >
        <div class="input-group-append" @click="showPassword = ! showPassword" x-cloak>
            <span class="input-group-text">
                <i class="fas fa-eye fa-fw" x-show="showPassword === false"></i>
                <i class="fas fa-eye-slash fa-fw" x-show="showPassword === true"></i>
            </span>
        </div>
        @error($attributes->get('name'))
            <div class="invalid-feedback" id="validation{{ Str::studly($attributes->get('name')) }}Feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>