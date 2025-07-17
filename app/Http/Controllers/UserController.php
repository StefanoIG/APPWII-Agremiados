<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller {
    public function index() {
        $users = User::where('is_active', true)->get();
        return view('user.index', compact('users'));
    }
    public function create() {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('user.create', compact('roles'));
    }
    public function store(Request $request) {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8',
            'role'=>'required|exists:roles,name',
        ]);
        $user = new User($request->except(['password','role']));
        $user->password = Hash::make($request->input('password'));
        $user->is_active = true;
        $user->save();
        $user->assignRole($request->input('role'));
        return redirect()->route('users.index');
    }
    public function edit($id) {
        $user = User::findOrFail($id);
        $roles = \Spatie\Permission\Models\Role::all();
        return view('user.edit', compact('user','roles'));
    }
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$id,
            'role'=>'required|exists:roles,name',
        ]);
        $user->fill($request->except(['password','role']));
        if($request->filled('password')) $user->password = Hash::make($request->input('password'));
        $user->save();
        $user->syncRoles([$request->input('role')]);
        return redirect()->route('users.index');
    }
    public function destroy($id) {
        User::destroy($id);
        return redirect()->route('users.index');
    }
    // Usuarios pendientes de activación
    public function pendientes() {
        $users = User::where('is_active', false)->get();
        return view('user.pendientes', compact('users'));
    }
    public function aceptar($id) {
        $user = User::findOrFail($id);
        $user->is_active = true;
        $user->save();
        return redirect()->route('users.pendientes')->with('success', 'Usuario activado correctamente.');
    }
    public function rechazar($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.pendientes')->with('success', 'Usuario rechazado y eliminado.');
    }
}
