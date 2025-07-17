<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CategoriaController extends Controller {
    public function index() { $categorias = DB::table('category')->get(); return view('categoria.index', compact('categorias')); }
    public function create() { return view('categoria.create'); }
    public function store(Request $request) {
        $request->validate(['name'=>'required']);
        DB::table('category')->insert(['name'=>$request->name, 'created_at'=>now()]);
        return redirect()->route('categoria.index');
    }
    public function edit($id) { $categoria = DB::table('category')->find($id); return view('categoria.edit', compact('categoria')); }
    public function update(Request $request, $id) {
        $request->validate(['name'=>'required']);
        DB::table('category')->where('id',$id)->update(['name'=>$request->name, 'updated_at'=>now()]);
        return redirect()->route('categoria.index');
    }
    public function destroy($id) {
        DB::table('category')->where('id',$id)->delete();
        return redirect()->route('categoria.index');
    }
}
