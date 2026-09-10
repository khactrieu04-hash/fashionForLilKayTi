@props(['label', 'col' => 'col-12', 'width' => '20%'])
<div class="form-input-custom {{$col}}">
  <label class="form-label">{{$label}}</label>
  {{ $slot }}
</div>