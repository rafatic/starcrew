@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Mission lobby</div>

                <div class="panel-body">
                    <div class="page-header">
                        <h1>{{ $mission->goalName }} <small>{{ $mission->captainName }}'s mission</small></h1>
                    </div>

                    @if ($mission->captainId == Auth::user()->id)
                        <button type="button" class="btn btn-default" aria-label="modify details" id="modInfos">
                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Modify mission's informations
                        </button>
                    @endif

                    <h4><strong>Details : </strong><span id="details">{{ $mission->details }}</span></h4>

                    @if ($mission->captainId == Auth::user()->id)
                        <textarea style="display:none" name="modDetails" id="modDetails" rows="8" cols="80">{{ $mission->details }}</textarea>
                    @endif

                    <h4>Start time : {{ date("d F - H:i", $mission->starttime)}}
                        @if ($mission->duration != NULL)
                            <small id="duration">Est. duration : {{ $mission->duration }}</small>
                        @else
                            <small id="duration">Est. duration : unknown</small>
                        @endif
                        @if ($mission->captainId == Auth::user()->id)
                            <div id="modDuration">
                                <label for="modDurations">Duration :</label>
                                <input type="text" id="durationpicker" name="modDuration" value="-">
                            </div>

                        @endif

                    </h4>

                    @if ($mission->captainId == Auth::user()->id)
                        <input type="hidden" id="missionId" value="{{ $mission->id }}"/>
                        <button type="button" class="btn btn-primary" id="submitModifs">Modify</button>
                    @endif

                    <hr>

                    <h3>Crew slots</h3>

                    <h4>Taken crew slots</h4>

                    <table class="table">
                        <th style="width:33%">Role</th>
                        <th style="width:33%">User name</th>
                        <th style="width:33%">Action</th>

                        @foreach ($crewSlots as $slot)
                            @if ($slot->userId != NULL)
                                <tr>
                                    <td>
                                        @if ($slot->userId == Auth::user()->id)
                                            <b>
                                        @endif

                                        {{ $slot->name }}
                                        @if ($slot->userId == $mission->captainId)
                                            (captain)
                                        @endif

                                        @if ($slot->userId == Auth::user()->id)
                                            </b>
                                        @endif
                                    </td>
                                    <td>

                                        @if ($slot->userId == Auth::user()->id)
                                            <b> You </b>
                                        @else
                                            {{ $slot->userName }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($slot->userId == Auth::user()->id)
                                            <form action="leave" method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="slotId" value="{{$slot->id}}">
                                                <input type="submit" class="btn btn-danger" value="Leave mission">
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>


                

                    <hr>
                    <h4>Vacant positions</h4>
                    <table class="table" style="width:75%">
                        <th>Role</th>
                        <th>Action</th>

                        @foreach ($crewSlots as $slot)
                            @if($slot->userId == NULL)
                                <tr>
                                    <td>
                                        {{ $slot->name }}
                                    </td>
                                    <td>
                                        @if($isCrewMember)
                                            <form action="switch" method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="slotId" value="{{$slot->id}}">
                                                <input type="hidden" name="userSlotId" value="{{ $userCurrentSlotId }}" />
                                                <input type="submit" class="btn btn-success" value="Switch position">
                                            </form>
                                        @else
                                            <form action="apply" method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="slotId" value="{{$slot->id}}">
                                                <input type="submit" class="btn btn-success" value="Apply">
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endif

                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/jquery.datetimepicker.css') }}"/ >

<script src="{{ URL::asset('js/jquery.datetimepicker.full.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function(){

        $('#durationpicker').datetimepicker({
            datepicker:false,
            step: 30,
            defaultTime: '00:00',
            format:'H:i',
            inline: true
        });

        $('#modDuration').hide();
        $('#submitModifs').hide();

        $("#modInfos").click(function(){
            $('#modDuration').toggle();
            $('#modDetails').toggle();
            $('#details').toggle();
            $('#duration').toggle();
            $('#submitModifs').toggle();
        })

        $('#submitModifs').click(function(){

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');


            var request = $.post(
                "/starcrew_master/public/mission/lobby/update",
                {
                    details:$("#modDetails").val(),
                    duration:$("#durationpicker").val(),
                    missionId:$("#missionId").val(),
                    _token:CSRF_TOKEN
                },
                "text/json"
            );

            request.done(function(data){
                location.reload();
            })


        })


    })
</script>

@endsection
