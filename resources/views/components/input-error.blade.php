@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-danger mt-1']) }}>{{ $message }}</p>
@enderror
