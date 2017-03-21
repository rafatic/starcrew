@if(empty($missions))
    <h4>There's no mission here...</h4>
@endif

@foreach ($missions as $mission)
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <h3 class="mission-goal"> {{ $mission->goalName }}</h3>

                    <div class="mission-time">
                        <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                        {{ date("Y-m-d", $mission->starttime) }}
                        <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
                        {{ date("H:i", $mission->starttime) }}
                        <br />
                        Est. duration {{ $mission->duration }}
                    </div>

                </div>

                <div class="col-md-2">

                    <div class="mission-languages">
                        <span class="glyphicon glyphicon-volume-up" aria-hidden="true"></span>
                        <ul class="list-languages">
                            @foreach ($mission->languages as $language)
                                <li>
                                    {{ $language->name }}
                                </li>

                            @endforeach
                        </ul>

                    </div>

                </div>

                <div class="col-md-3">
                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                    <ul class="list-positions">
                        @foreach ($mission->openSlots as $position)
                            <li>
                                {{ $position->name }}
                            </li>

                        @endforeach
                    </ul>

                    <br />
                    <div class="nbSlots">
                        Nb. of players :{{$mission->nbTakenSlot}} / {{ $mission->totalSlots }}
                    </div>

                </div>

                <div class="col-md-2">
                    <form action="lobby/{{$mission->id}}" method="GET">
                        <input type="submit" class="btn btn-primary" value="Lobby">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
