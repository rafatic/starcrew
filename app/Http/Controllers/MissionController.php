<?php

namespace starcrew\Http\Controllers;


use starcrew\User;
use starcrew\Language;
use starcrew\UserLanguage;
use starcrew\MissionLanguage;
use starcrew\Goal;
use starcrew\Mission;
use starcrew\Role;
use starcrew\CrewSlot;
use Auth;
use Validator;
use starcrew\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MissionController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Shows the mission creation view.
     *
     * Redirects to the mission creation view with the following arrays
     *  - $languages : Languages that the user speaks
     *  - $goals : mission goals
     *  - $roles : roles available for the crew
     *
     * @return Response (creation view)
     */
    public function create()
    {

        $userLanguages = UserLanguage::where('user_id', Auth::user()->id)->select('language_id')->get();

        $languages = Language::whereIN('id', $userLanguages)->select('id', 'name')->get();

        $goals = Goal::get();

        $roles = Role::get();



        return view('mission/create')->with(compact("languages", "goals", "roles"));
    }

    /**
     * Shows the mission search view.
     *
     *
     * Redirects to the mission creation view with the following arrays
     *  - $languages : Languages that the user speaks
     *  - $missions : Missions available
     *  - $goals : Goals available
     *  - $roles : Roles available
     *
     * @return Response (search view)
     */
    public function search()
    {
        $userLanguages = UserLanguage::where('user_id', Auth::user()->id)->select('language_id')->get();
        $languages = Language::whereIN('id', $userLanguages)->select('id', 'name')->get();

        $roles = DB::table('roles')
                ->select(
                    'id',
                    'name'
                    )
                ->get();

        $goals = DB::table('goals')
                ->select(
                    'id',
                    'name'
                    )
                ->get();


        $queryAppliedMissions = DB::table("crew_slots")
                        ->where('user_id', Auth::user()->id)
                        ->select('mission_id')
                        ->get();


        $appliedMissions = array();
        foreach ($queryAppliedMissions as $mission) {
            array_push($appliedMissions, $mission->mission_id);
        }

        // Retrieves an array of published missions
        $missions = DB::table('missions')
                    ->join('goals', 'goals.id', '=', 'missions.goal_id')
                    ->join('users', 'users.id', '=', 'missions.captain')
                    ->where('missions.state', '1')
                    ->where('missions.captain', '<>', Auth::user()->id)
                    ->where('missions.startTime', '>', time())
                    ->whereNotIn('missions.id', $appliedMissions)
                    ->select(
                        'missions.id',
                        'missions.starttime',
                        'missions.duration',
                        'goals.name as goalName',
                        'users.name as userName'
                    )
                    ->get();

        // Adds spoken languages to respective missions
        foreach ($missions as $mission) {
            $missionLanguages = DB::table('mission_languages')
                        ->join('languages', 'languages.id', '=', 'mission_languages.language_id')
                        ->where('mission_languages.mission_id', '=', $mission->id)
                        ->select(
                            'languages.id',
                            'languages.name'
                            )
                        ->get();

            $mission->languages = $missionLanguages;

            $openSlots = DB::table("crew_slots")
                        ->join('roles', 'roles.id', '=', 'crew_slots.role_id')
                        ->whereNull('crew_slots.user_id')
                        ->where('crew_slots.mission_id', '=', $mission->id)
                        ->select('roles.name')
                        ->distinct()
                        ->take(3)
                        ->get();

            $mission->openSlots = $openSlots;
            $totalSlots = DB::table("crew_slots")
                            ->where('mission_id', '=', $mission->id)
                            ->count();

            $nbTakenSlot =  DB::table("crew_slots")
                            ->where('mission_id', '=', $mission->id)
                            ->whereNotNull('user_id')
                            ->count();

            $mission->totalSlots = $totalSlots;
            $mission->nbTakenSlot = $nbTakenSlot;
        }


        return view('mission/search')->with(compact("languages", "missions", "goals", "roles"));
    }

    public function filter(Request $request)
    {

        extract($request->all());


        $queryAppliedMissions = DB::table("crew_slots")
                        ->where('user_id', Auth::user()->id)
                        ->select('mission_id')
                        ->get();


        $appliedMissions = array();
        foreach ($queryAppliedMissions as $mission) {
            array_push($appliedMissions, $mission->mission_id);
        }

        $query = DB::table('missions')
                ->distinct()
                ->join('goals', 'goals.id', '=', 'missions.goal_id')
                ->join('users', 'users.id', '=', 'missions.captain')
                ->join('crew_slots', 'missions.id', '=', 'crew_slots.mission_id')
                ->join('mission_languages', 'mission_languages.mission_id', '=', 'missions.id')
                ->whereNull('crew_slots.user_id')
                ->whereNotIn('missions.id', $appliedMissions)
                ->where([
                    ['missions.state', '1']
                ]);

        if($goal != NULL && $goal != "-1")
        {
            $query->where('missions.goal_id', "=", $goal);
        }
        if($role != NULL && $role != "-1")
        {
            $query->where('crew_slots.role_id', '=', $role);
        }
        if($dateTime != NULL && $dateTime != "")
        {
            $dateTime = strtotime($dateTime);
            if($dateTime > time()){
                $query->whereBetween('missions.startTime', [$dateTime - 3600, $dateTime + 3600]);
            }else{
                $query->where('missions.startTime', '>', time());
            }

        }else{
            $query->where('missions.startTime', '>', time());
        }

        if(isset($languages))
        {
            $query->whereIn('mission_languages.language_id', $languages);
        }
        $query->select([
            'missions.id',
            'missions.starttime',
            'missions.duration',
            'goals.name AS goalName',
            'users.id AS userId'
         ]);
        $missions = $query->get();


        // Adds spoken languages to respective missions
        foreach ($missions as $mission) {
            $missionLanguages = DB::table('mission_languages')
                        ->join('languages', 'languages.id', '=', 'mission_languages.language_id')
                        ->where('mission_languages.mission_id', '=', $mission->id)
                        ->select(
                            'languages.id',
                            'languages.name'
                            )
                        ->get();

            $mission->languages = $missionLanguages;

            $openSlots = DB::table("crew_slots")
                        ->join('roles', 'roles.id', '=', 'crew_slots.role_id')
                        ->whereNull('crew_slots.user_id')
                        ->where('crew_slots.mission_id', '=', $mission->id)
                        ->select('roles.name')
                        ->distinct()
                        ->take(3)
                        ->get();

            $mission->openSlots = $openSlots;
            $totalSlots = DB::table("crew_slots")
                            ->where('mission_id', '=', $mission->id)
                            ->count();

            $nbTakenSlot =  DB::table("crew_slots")
                            ->where('mission_id', '=', $mission->id)
                            ->whereNotNull('user_id')
                            ->count();

            $mission->totalSlots = $totalSlots;
            $mission->nbTakenSlot = $nbTakenSlot;
        }
        return view("layouts.missionList")->with(compact("missions"));
    }

    /**
     *  Stores a new mission in the database
     *
     *  This method is called when the user submits the mission he created. The mission in published, its state is set to '1'
     *
     * @param Request $request Array containing the data posted
     *
     * @return back() redirects to the previous view (todo, redirect to the published mission)
     */
    public function publish(Request $request)
    {

        // TODO : Set up user-friendly error messages

        $validator = Validator::make($request->all(), [
            'goal' => 'required',
            'details' => 'required',
            'language' => 'required',
            'date' => 'date|after:now',
            'captain-role' => 'required|not_in:0',
            'crew-role.*' => 'required|not_in:0'
        ]);

        if($validator->fails()) {
            return redirect()->back()
                    ->withInput()
                    ->withErrors($validator);

        }
        $mission = new Mission();
        $mission->goal_id = $request->input("goal");
        $mission->details = $request->input("details");
        $mission->startTime = strtotime($request->input("date"));
        $mission->duration = $request->input("duration");
        $mission->captain = Auth::user()->id;
        $mission->state = 1;
        $mission->save();

        foreach ($request->input("language") as $language) {
            MissionLanguage::create([
                'mission_id' => $mission->id,
                'language_id' => $language
            ]);
        }

        CrewSlot::create([
            'mission_id' => $mission->id,
            'role_id' => $request->input("captain-role"),
            'user_id' => Auth::user()->id
        ]);

        foreach ($request->input("crew-role") as $crewRole) {
            CrewSlot::create([
                'mission_id' => $mission->id,
                'role_id' => $crewRole
            ]);
        }

        return redirect()->action('MissionController@lobby', ['id' => $mission->id]);

    }

    /**
     * Shows the mission lobby view
     *
     *
     * This method is called when the user clicks on "enter lobby". The id of the mission is given in parameter
     *
     * @param $id : mission id
     *
     * @return view("mission.lobby") redirects the user to the lobby view with all the required data (crew, mission infos...)
     */
    public function lobby($id)
    {
        $mission = Mission::join('goals', 'goals.id', '=', 'missions.goal_id')
                    ->join('users', 'users.id', '=', 'missions.captain')
                    ->where('missions.id', '=', $id)
                    ->select([
                        'missions.id',
                        'goals.name AS goalName',
                        'missions.details',
                        'missions.starttime',
                        'missions.duration',
                        'users.name AS captainName',
                        'users.id AS captainId'
                    ])
                    ->get()
                    ->first();


        // Adds spoken languages to respective missions
        $languages = MissionLanguage::join('languages', 'languages.id', '=', 'mission_languages.language_id')
                    ->where('mission_languages.mission_id', '=', $id)
                    ->select(
                        'languages.id',
                        'languages.name'
                        )
                    ->get();


        $crewSlots = CrewSlot::join('roles', 'roles.id', '=', 'crew_slots.role_id')
                    ->where('crew_slots.mission_id', '=', $id)
                    ->select([
                        'roles.name',
                        'crew_slots.id',
                        'crew_slots.user_id AS userId'
                    ])
                    ->get();



        $isCrewMember = false;
        $userCurrentSlotId = NULL;
        foreach ($crewSlots as $slot) {
            if($slot->userId == Auth::user()->id)
            {
                $isCrewMember = true;
                $userCurrentSlotId = $slot->id;
            }
            if($slot->userId != NULL)
            {
                $slot->userName =  User::where('id', '=', $slot->userId)->select('name')->get()->first()->name;
            }
        }



        return view("mission.lobby")->with(compact("mission", "languages", "crewSlots", 'isCrewMember', "userCurrentSlotId"));
    }

    /**
     * Applies a user to a mission
     *
     * @param Request $request request containing the user id and the mission id
     *
     * @return back() redirects the user to the last visited page (lobby)
     */
    public function apply(Request $request)
    {
        extract($request->all());

        $crewSlot = crewSlot::find($slotId);
        if($crewSlot->user_id == NULL)
        {
            $crewSlot->user_id = Auth::user()->id;
            $crewSlot->save();

        }
        return redirect()->back();
    }


    /**
     * Removes a user from a mission
     *
     * @param Request $request request containing the user id and the mission id
     *
     * @return back() redirects the user to the last visited page (lobby)
     */
    public function leave(Request $request)
    {
        extract($request->all());

        $crewSlot = crewSlot::find($slotId);
        $crewSlot->user_id = NULL;
        $crewSlot->save();

        return redirect()->back();
    }

    /**
     *
     * Affects a user to a new position in a mission
     *
     * The old position is set as vacant
     *
     * @param Request $request request containing the user id and the mission id
     *
     * @return back() redirects the user to the last visited page (lobby)
     */
    public function switchSlot(Request $request)
    {
        extract($request->all());

        $newCrewSlot = crewSlot::find($slotId);
        if($newCrewSlot->user_id == NULL)
        {
            $crewSlot = crewSlot::find($userSlotId);
            $crewSlot->user_id = NULL;
            $crewSlot->save();


            $newCrewSlot->user_id = Auth::user()->id;
            $newCrewSlot->save();
        }

        return redirect()->back();

    }

    /**
    * Returns all the missions the user created
    *
    * @return array of missions
    *
    */
    public function getCreatedMissions()
    {
        $missions = DB::table('missions')
                    ->join('goals', 'goals.id', '=', 'missions.goal_id')
                    ->join('users', 'users.id', '=', 'missions.captain')
                    ->where('missions.captain', '=', Auth::user()->id)
                    ->where('missions.startTime', '>', time())
                    ->select(
                        'missions.id',
                        'missions.starttime',
                        'missions.duration',
                        'goals.name as goalName',
                        'users.name as userName'
                    )
                    ->get();

        // Adds spoken languages to respective missions
        foreach ($missions as $mission) {
            $this->getMissionAdditionalInfos($mission);
        }
        return $missions;
    }
    /**
    * Returns all the missions the user joined
    *
    * @return array of missions
    *
    */
    public function getAppliedMissions()
    {
        $queryAppliedMissions = DB::table("crew_slots")
                        ->where('user_id', Auth::user()->id)
                        ->select('mission_id')
                        ->get();


        $appliedMissions = array();
        foreach ($queryAppliedMissions as $mission) {
            array_push($appliedMissions, $mission->mission_id);
        }

        // Retrieves an array of published missions
        $missions = DB::table('missions')
                    ->join('goals', 'goals.id', '=', 'missions.goal_id')
                    ->join('users', 'users.id', '=', 'missions.captain')
                    ->whereIn('missions.id', $appliedMissions)
                    ->where('missions.captain', '<>', Auth::user()->id)
                    ->where('missions.startTime', '>', time())
                    ->select(
                        'missions.id',
                        'missions.starttime',
                        'missions.duration',
                        'goals.name as goalName',
                        'users.name as userName'
                    )
                    ->get();

        foreach ($missions as $mission) {
            $this->getMissionAdditionalInfos($mission);
        }
        return $missions;
    }

    /**
    * Returns all the past missions the user joined  or created
    *
    * @return array of missions
    *
    */
    public function getPastMissions()
    {
        $missions = DB::table('missions')
                    ->join('goals', 'goals.id', '=', 'missions.goal_id')
                    ->join('users', 'users.id', '=', 'missions.captain')
                    ->where('missions.captain', '=', Auth::user()->id)
                    ->where('missions.startTime', '<', time())
                    ->select(
                        'missions.id',
                        'missions.starttime',
                        'missions.duration',
                        'goals.name as goalName',
                        'users.name as userName'
                    )
                    ->get();

        // Adds spoken languages to respective missions
        foreach ($missions as $mission) {
            $this->getMissionAdditionalInfos($mission);
        }
        return $missions;
    }

    /**
    * Returns all the missions a user has ever taken part in
    *
    * This method calls the following methods
    *   - getCreatedMissions()
    *   - getAppliedMissions()
    *   - getPastMissions()
    *
    * @return array of missions
    *
    */
    public function myMissions()
    {
        $createdMissions = $this->getCreatedMissions();
        $appliedMissions = $this->getAppliedMissions();
        $pastMissions = $this->getPastMissions();


        return view('mission/myMissions')->with(compact("createdMissions", 'appliedMissions', 'pastMissions'));
    }

    /**
     * Adds to the mission given in parameter its informations (languages, crewSlots)
     *
     * @param $mission The mission
     */
    public function getMissionAdditionalInfos($mission)
    {
        $missionLanguages = DB::table('mission_languages')
                    ->join('languages', 'languages.id', '=', 'mission_languages.language_id')
                    ->where('mission_languages.mission_id', '=', $mission->id)
                    ->select(
                        'languages.id',
                        'languages.name'
                        )
                    ->get();

        $mission->languages = $missionLanguages;

        $openSlots = DB::table("crew_slots")
                    ->join('roles', 'roles.id', '=', 'crew_slots.role_id')
                    ->whereNull('crew_slots.user_id')
                    ->where('crew_slots.mission_id', '=', $mission->id)
                    ->select('roles.name')
                    ->distinct()
                    ->take(3)
                    ->get();

        $mission->openSlots = $openSlots;
        $totalSlots = DB::table("crew_slots")
                        ->where('mission_id', '=', $mission->id)
                        ->count();

        $nbTakenSlot =  DB::table("crew_slots")
                        ->where('mission_id', '=', $mission->id)
                        ->whereNotNull('user_id')
                        ->count();

        $mission->totalSlots = $totalSlots;
        $mission->nbTakenSlot = $nbTakenSlot;
    }

    public function updateMission(Request $request)
    {
        extract($request->all());

        $mission = mission::find($missionId);

        $mission->details = $details;
        if($duration != "-")
        {
            $mission->duration = $duration;
        }
        $mission->save();

        return json_encode("ok");
    }


}
