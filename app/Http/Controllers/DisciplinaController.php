<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DisciplinaController extends Controller {
    public function index() { $disciplinas = DB::table('discipline')->get(); return view('disciplina.index', compact('disciplinas')); }
    public function create() { return view('disciplina.create'); }
    public function store(Request $request) {
        $request->validate(['name'=>'required']);
        DB::table('discipline')->insert(['name'=>$request->name, 'created_at'=>now()]);
        return redirect()->route('disciplina.index');
    }
    public function edit($id) { $disciplina = DB::table('discipline')->find($id); return view('disciplina.edit', compact('disciplina')); }
    public function update(Request $request, $id) {
        $request->validate(['name'=>'required']);
        DB::table('discipline')->where('id',$id)->update(['name'=>$request->name, 'updated_at'=>now()]);
        return redirect()->route('disciplina.index');
    }
    public function destroy($id) {
        DB::table('discipline')->where('id',$id)->delete();
        return redirect()->route('disciplina.index');
    }
}
