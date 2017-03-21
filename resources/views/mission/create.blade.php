@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Create a mission</div>

                <div class="panel-body">
                    <form action="publish" method="post">

                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="goal">Select a goal for the mission</label>
                            <select class="" name="goal">
                                <option value="">-- Select --</option>

                                @foreach ($goals as $goal)
                                    <option value="{{ $goal->id }}"
                                    @if( old('goal') == $goal->id)
                                        selected
                                    @endif
                                    >{{ $goal->name }}</option>

                                @endforeach
                            </select>
                        </div>
                        <br>
                        <div class="form-group">
                            <textarea name="details" rows="4" cols="80" placeholder="Enter the details of the mission here">{{ old('details')}}</textarea>
                            <br>
                        </div>

                        <div class="form-group">
                            <label for="languageSelect">Select one or more language(s)</label>
                            <br>
                            @foreach ($languages as $language)
                                <input type="checkbox" name="language[]" value="{{ $language->id }}"
                                @if (count(old('language')) > 0)
                                    @foreach(old('language') as $oldLanguage)
                                        @if($oldLanguage == $language->id)
                                            checked="true"
                                        @endif
                                    @endforeach
                                @endif
                                > {{ $language->name }}<br>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="datetime">Select a date and a time</label>
                                    <br>
                                    <input type="text" id="datetimepicker" name="date" value="{{ old("date") }}-">
                                </div>

                                <div class="col-md-6">
                                    <label for="duration">(Optional) Estimate the duration of the mission</label>
                                    <br>
                                    <input type="text" id="durationpicker" name="duration" value="{{ old("duration") }}-">
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="row">
                            <div class="col-md-12">
                                <h3>Manage your crew</h3>
                                <div class="captain">
                                    <label for="captain-role">Your role : </label>
                                    <select class="captain-role" name="captain-role">
                                        <option value="0">-- Select --</option>

                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br />
                                <div class="crew-slots">

                                </div>

                                <div class="add-crew-slot">
                                    <button type="button" id="add_slot" class="btn btn-default" name="add_slot">Add a crew slot</button>
                                </div>
                            </div>
                        </div>
                        <hr />

                        <input type="submit" class="btn btn-primary" value="publish">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/jquery.datetimepicker.css') }}"/ >

<script src="{{ URL::asset('js/jquery.datetimepicker.full.min.js') }}"></script>

<script src="{{ URL::asset('js/addCrewSlot.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#datetimepicker').datetimepicker({
            format:'d.m.Y H:i',
            step: 30,
            inline: true
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
