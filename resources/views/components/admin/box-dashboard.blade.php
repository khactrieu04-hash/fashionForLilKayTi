@props(['data', 'title', 'route', 'boxtype'])
{{-- <div class="small-box bg-{{ $boxtype }}">
    <div class="inner">
      <h3>
        {{ number_format ($data , $decimals = 0 , $dec_point = "," , $thousands_sep = "." ) }}
      </h3>
      <p>{{ $title }}</p>
    </div>
    <div class="icon">
      <i class="ion ion-cash"></i>
    </div>
</div> --}}

<div class="card-body">
  <h5 class="card-title">{{ $title }}</h5>

  <div class="d-flex align-items-center">
    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
      {!! $boxtype !!}
    </div>
    <div class="ps-3">
      <h6>{{ number_format ($data , $decimals = 0 , $dec_point = "," , $thousands_sep = "." ) }}</h6>
    </div>
  </div>
</div>