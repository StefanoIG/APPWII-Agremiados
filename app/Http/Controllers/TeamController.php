<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TeamController extends Controller {
    public function index() {
        $teams = DB::table('team')->get();
        return view('team.index', compact('teams'));
    }
    public function create() {
        return view('team.create');
    }
    public function store(Request $request) {
        $request->validate(['name'=>'required']);
        DB::table('team')->insert(['name'=>$request->name, 'created_at'=>now()]);
        return redirect()->route('teams.index');
    }
    public function edit($id) {
        $team = DB::table('team')->find($id);
        return view('team.edit', compact('team'));
    }
    public function update(Request $request, $id) {
        $request->validate(['name'=>'required']);
        DB::table('team')->where('id',$id)->update(['name'=>$request->name, 'updated_at'=>now()]);
        return redirect()->route('teams.index');
    }
    public function destroy($id) {
        DB::table('team')->where('id',$id)->delete();
        return redirect()->route('teams.index');
    }
}
