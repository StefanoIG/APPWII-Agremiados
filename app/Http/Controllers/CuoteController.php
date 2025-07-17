<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CuoteController extends Controller {
    public function index() {
        $cuotas = DB::table('cuote')->get();
        return view('cuote.index', compact('cuotas'));
    }
    public function create() {
        $users = DB::table('users')->get();
        return view('cuote.create', compact('users'));
    }
    public function store(Request $request) {
        $request->validate([
            'month'=>'required|integer|min:1|max:12',
            'year'=>'required|integer',
            'total'=>'required|numeric',
            'user_id'=>'required|exists:users,id',
        ]);
        DB::table('cuote')->insert([
            'month'=>$request->month,
            'year'=>$request->year,
            'total'=>$request->total,
            'paid'=>$request->paid ?? 0,
            'balance'=>$request->balance ?? 0,
            'user_id'=>$request->user_id,
            'created_at'=>now()
        ]);
        return redirect()->route('cuotes.index');
    }
    public function edit($id) {
        $cuota = DB::table('cuote')->find($id);
        $users = DB::table('users')->get();
        return view('cuote.edit', compact('cuota','users'));
    }
    public function update(Request $request, $id) {
        $request->validate([
            'month'=>'required|integer|min:1|max:12',
            'year'=>'required|integer',
            'total'=>'required|numeric',
            'user_id'=>'required|exists:users,id',
        ]);
        DB::table('cuote')->where('id',$id)->update([
            'month'=>$request->month,
            'year'=>$request->year,
            'total'=>$request->total,
            'paid'=>$request->paid ?? 0,
            'balance'=>$request->balance ?? 0,
            'user_id'=>$request->user_id,
            'updated_at'=>now()
        ]);
        return redirect()->route('cuotes.index');
    }
    public function destroy($id) {
        DB::table('cuote')->where('id',$id)->delete();
        return redirect()->route('cuotes.index');
    }
}
