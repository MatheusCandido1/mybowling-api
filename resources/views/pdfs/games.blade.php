<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    @page {
        margin-top: 0px;
        margin-left: 20px;
        margin-right: 20px;
    }
    header {
        width: 100%;
        text-align: center;
    }
    .hr {
        width: 100%;
        border: 1px solid black;
    }

    .date {
        margin-top: 8px;
        width: 100%;
        text-align: right;
        font-size: 18px;
    }

    .row {
        margin-top: 12px;
        width: 100%;
        display: inline-block;
    }

    .frame {
        width: 73.5px;
        height: 80px;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        border-right: 1px solid black;
        border-left: 1px solid black;

        display: inline-block; /* Display sub-frame as inline-block */
    }

    .frame > header {
        width: 100%;
        border-bottom: 1px solid black;
    }

    .sub-frame {
        display: inline-block; /* Display sub-frame as inline-block */
        width: 100%;
        margin-top: -2px;
    }

    .ball {
        margin-top: 10px;
        width: 20px;
        height: 30px;
        text-align: center;
        line-height: 25px;
        display: inline-block; /* Display balls as inline-block */
    }

    footer {
        margin-top: -8px;
        width: 100%;
        border-top: 1px solid black;
    }

    footer > p {
        margin-top: 4px;
        width: 100%;
        text-align: center;
        font-size: 18px;
    }

</style>

<body>
    <header>
        <h2>{{ $game->location->name }}</h2>
        <div class="hr"></div>
        <p class="date">{{ Carbon\Carbon::parse($game->game_date)->format('m/d/Y') }}</p>
    </header>
    <main>
        <section class="row">
            @foreach($game->frames as $frame)
                @if($frame->frame_number == 10)
                <div class="frame" style="margin-right: -4px;">
                    <header> {{ $frame->frame_number }} </header>
                    <div class="sub-frame">
                        @if(!isset($frame->third_shot))
                        <div style="width: 20px; height: 30px; display: inline-block; margin-left: 2px"></div>
                        @endif
                        <div class="ball" style="border-right: 1px solid black; width: 22px">
                            {{ $frame->first_shot == 10 ? 'X' : ($frame->first_shot == 0 ? '-':$frame->first_shot) }}
                        </div>
                        <div class="ball" style="border-right: 1px solid black; width: 20px">
                            {{ $frame->second_shot == 10 ? 'X' : ($frame->first_shot + $frame->second_shot == 10 ? '/' : ($frame->second_shot == 0 ? '-':$frame->second_shot)) }}
                        </div>
                        @if(isset($frame->third_shot))
                        <div class="ball">
                            {{ $frame->third_shot == 10 ? 'X' : ($frame->third_shot == 0 ? '-':$frame->third_shot) }}
                        </div>
                        @endif

                    </div>
                    <footer>
                        <p>{{$frame->score}}</p>
                    </footer>
                </div>



                @else
                <div class="frame" style="margin-right: -4px;">
                    <header> {{ $frame->frame_number }} </header>
                    <div class="sub-frame">
                        @if($frame->frame_number != 10)
                        <div style="width: 20px; height: 30px; display: inline-block; margin-left: 2px"></div>
                        @endif

                        @if($frame->frame_number != 10)
                        <div class="ball" style="border-right: 1px solid black; border-left: 1px solid black;">
                            {{ $frame->first_shot == 10 ? 'X' : ($frame->first_shot == 0 ? '-':$frame->first_shot) }}
                        </div>
                        @else
                        <div class="ball" style="border-right: 1px solid black;">
                            {{ $frame->first_shot == 10 ? 'X' : ($frame->first_shot == 0 ? '-':$frame->first_shot) }}
                        </div>
                        @endif

                        @if($frame->frame_number != 10)
                        <div class="ball">
                            @if($frame->first_shot != 10)
                            {{ $frame->first_shot + $frame->second_shot == 10 ? '/' : ($frame->second_shot == 0 ? '-':$frame->second_shot) }}
                            @endif
                        </div>
                        @else
                        <div class="ball" style="border-right: 1px solid black;">
                            @if($frame->first_shot != 10)
                            {{ $frame->first_shot + $frame->second_shot == 10 ? '/' : ($frame->second_shot == 0 ? '-':$frame->second_shot) }}
                            @endif
                        </div>

                        @endif
                    </div>
                    <footer>
                        <p>{{$frame->score}}</p>

                    </footer>
                </div>
                @endif
                @endforeach
        </section>
    </main>

</body>
</html>
