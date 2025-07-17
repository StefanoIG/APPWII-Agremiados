<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class UserTeamController extends Controller {
    public function index() {
        $user_teams = DB::table('user_team')->get();
        return view('user_team.index', compact('user_teams'));
    }
    public function create() {
        $users = DB::table('users')->get();
        $teams = DB::table('team')->get();
        return view('user_team.create', compact('users','teams'));
    }
    public function store(Request $request) {
        $request->validate([
            'user_id'=>'required|exists:users,id',
            'team_id'=>'required|exists:team,id',
        ]);
        DB::table('user_team')->insert([
            'user_id'=>$request->user_id,
            'team_id'=>$request->team_id
        ]);
        return redirect()->route('user_teams.index');
    }
    public function edit($user_id, $team_id) {
        $user_team = DB::table('user_team')->where('user_id',$user_id)->where('team_id',$team_id)->first();
        $users = DB::table('users')->get();
        $teams = DB::table('team')->get();
        return view('user_team.edit', compact('user_team','users','teams'));
    }
    public function update(Request $request, $user_id, $team_id) {
        $request->validate([
            'user_id'=>'required|exists:users,id',
            'team_id'=>'required|exists:team,id',
        ]);
        DB::table('user_team')->where('user_id',$user_id)->where('team_id',$team_id)
            ->update([
                'user_id'=>$request->user_id,
                'team_id'=>$request->team_id
            ]);
        return redirect()->route('user_teams.index');
    }
    public function destroy($user_id, $team_id) {
        DB::table('user_team')->where('user_id',$user_id)->where('team_id',$team_id)->delete();
        return redirect()->route('user_teams.index');
    }
}
