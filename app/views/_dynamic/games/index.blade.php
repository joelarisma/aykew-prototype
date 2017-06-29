@extends('layouts.home')

@section('content')
<style>
    .panel-body {
        height: 360px;
    }
    .panel-body img {
        max-height: 330px;
    }
</style>
<div id="page-wrapper">
    <div class="row" style="padding-top:15px;">
        @foreach ($games as $game)
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-gamepad fa-fw"></i>
                        {{ $game->game_name }}
                    </div>
                    <div class="panel-body">
                       <div class="text-center">
                            <img src="/images/gamethumbs/{{ $game->game_image }}" style="max-width:100%; border-radius:5px; margin-bottom:10px;">
                            <p>{{ $game->game_description }}</p>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <a class="btn btn-warning btn-lg btn-block" href="/{{ $url }}/games/{{ $game->identifier }}">Play {{ $game->game_name }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@stop