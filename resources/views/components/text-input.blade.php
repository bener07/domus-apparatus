@props(['disabled' => false])
{{-- <input @disabled($disabled) {{ $attributes->merge(['class' => '']) }}> --}}
{{-- <span {{ $attributes->merge(['class'=>"input-group-text", 'id'=>"basic-addon1" ]) }}>{{ $slot }}</span> --}}
<div class="input-group mb-3">
    <input {{ $attributes->merge([
        'aria-label'=>'',
        'aria-describedby'=>'',
        'placeholder' => '',
        'class' => 'form-control',
        'type' => 'text',
        'id' => ''
        ]) }}>
</div>