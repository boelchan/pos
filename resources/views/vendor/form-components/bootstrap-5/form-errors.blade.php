@error($name, $bag)
    <div {!! $attributes->merge(['class' => 'invalid-feedback mb-2']) !!}>
        {{ $message }}
    </div>
@enderror