@props(['number'])
<div style="display: inline;" class="start-container">
    @for($i = 1; $i <= 5; $i++)
        @if ($i <= floor($number))
            <i class="fa fa-star"></i>
        @else
            @if (strlen(rtrim($number, '0')) >= 3 && floor($number) + 1 == $i)
                <i class="fas fa-star-half-alt"></i>
            @else
                <i class="fa fa-star none"></i>
            @endif
        @endif
    @endfor
</div>
