<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class EquipoController extends Controller {
    public function index() { $equipos = DB::table('team')->get(); return view('equipo.index', compact('equipos')); }
    public function create() { return view('equipo.create'); }
    public function store(Request $request) {
        $request->validate(['name'=>'required']);
        DB::table('team')->insert(['name'=>$request->name, 'created_at'=>now()]);
        return redirect()->route('equipo.index');
    }
    public function edit($id) { $equipo = DB::table('team')->find($id); return view('equipo.edit', compact('equipo')); }
    public function update(Request $request, $id) {
        $request->validate(['name'=>'required']);
        DB::table('team')->where('id',$id)->update(['name'=>$request->name, 'updated_at'=>now()]);
        return redirect()->route('equipo.index');
    }
    public function destroy($id) {
        DB::table('team')->where('id',$id)->delete();
        return redirect()->route('equipo.index');
    }
}
