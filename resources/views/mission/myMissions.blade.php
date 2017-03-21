@extends('layouts.app')

@section('specific_css_includes')
    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active">
                            <a href="#1" role="tab" data-toggle="tab">
                                Created Missions
                            </a>
                        </li>
                        <li><a href="#2" role="tab" data-toggle="tab">
                                 Applies
                            </a>
                        </li>

                        <li><a href="#3" role="tab" data-toggle="tab">
                                 Past Missions
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="1">
                            @include('layouts.missionList', ['missions' => $createdMissions])
                        </div>

                        <div class="tab-pane" id="2">
                            @include('layouts.missionList', ['missions' => $appliedMissions])
                        </div>

                        <div class="tab-pane" id="3">
                            @include('layouts.missionList', ['missions' => $pastMissions])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
