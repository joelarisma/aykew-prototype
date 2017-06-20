<div class="panel panel-default">
    <div class="panel-heading text-center">
        <strong>Your Training Activity This Week</strong>
    </div>

    <div class="panel-body text-center">
        <ul class="list-inline week" style="width: 80%; margin: 10px auto;">
            @for ($i=0; $i<7; $i++)
            <li>
                <h3>{{ $days[$i] }}</h3>
                <button type="button" class="btn
                @if (isset($week[$i]))
                    btn-success btn-block" data-toggle="tooltip" data-placement="bottom" title="{{ $week[$i] }} WPM">
                    <i class="fa fa-check" style="color:#fff;"></i>
                    </button>
                @elseif ($dow == $i)
                    btn-default btn-block"><i class="fa fa-sun-o fa-fw" style="color:#fff;"></i></button>
                @else
                    btn-default btn-block" disabled>&nbsp</button>
                @endif
            </li>
            @endfor
        </ul>
    </div>
</div>
