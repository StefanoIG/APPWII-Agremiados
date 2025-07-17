<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class RolController extends Controller {
    public function index() {
        $roles = Role::all();
        return view('rol.index', ['roles' => $roles]);
    }
    public function create() {
        return view('rol.create');
    }
    public function store(Request $request) {
        $request->validate(['name'=>'required']);
        Role::create(['name' => $request->input('name')]);
        return redirect()->route('roles.index');
    }
    public function edit($id) {
        $rol = Role::findOrFail($id);
        return view('rol.edit', compact('rol'));
    }
    public function update(Request $request, $id) {
        $request->validate(['name'=>'required']);
        $rol = Role::findOrFail($id);
        $rol->name = $request->input('name');
        $rol->save();
        return redirect()->route('roles.index');
    }
    public function destroy($id) {
        $rol = Role::findOrFail($id);
        $rol->delete();
        return redirect()->route('roles.index');
    }
}
