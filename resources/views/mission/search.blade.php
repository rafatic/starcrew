@extends('layouts.app')

@section('specific_css_includes')
    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="input-group input-group_lg col-md-12">

                    <form>
                        {{ csrf_field() }}
                        <div class="col-md-5">
                            <div class="searchGoal">
                                <select id="cbxSearchGoal" name="goal">
                                    <option value="-1"></option>
                                    @foreach ($goals as $goal)
                                        <option value="{{ $goal->id }}">{{ $goal->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1">
                            AND / OR
                        </div>

                        <div class="col-md-5">
                            <div class="searchRole">
                                <select id="cbxSearchRole" name="role">
                                    <option value="-1"></option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary filter-search">Filter</button>
                        </div>
                    </form>

                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Filter missions</div>
                        <div class="panel-body">
                            <form>
                                {{ csrf_field() }}
                                <label for="languageSelect">Spoken language(s)</label>
                                <br>
                                @foreach ($languages as $language)
                                    <input type="checkbox" class="language" value="{{ $language->id }}"> {{ $language->name }}<br>
                                @endforeach

                                <hr />

                                <label for="datetime">Date and time</label>
                                <br>
                                <input type="text" id="datetimepicker" name="date" value="">

                                <hr />
                                <button type="button" class="btn btn-primary filter-search">Filter</button>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">Missions</div>
                        <div class="panel-body" id="missionList">
                            @include('layouts.missionList')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/jquery.datetimepicker.css') }}"/ >

<script src="{{ URL::asset('js/jquery.datetimepicker.full.min.js') }}"></script>
<script src="{{ URL::asset('js/combobox.js')}}"></script>
<script src="{{ URL::asset('js/filterSearch.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#datetimepicker').datetimepicker({
            format:'d.m.Y H:i',
            step: 30,
            minDate:'0'
        });


        $('#durationpicker').datetimepicker({
            datepicker:false,
            step: 30,
            defaultTime: '00:00',
            format:'H:i',
            inline: true
        });


    })
</script>
@endsection
