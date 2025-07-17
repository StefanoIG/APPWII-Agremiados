<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PaymentController extends Controller {
    public function index() {
        $payments = DB::table('payment')->get();
        return view('payment.index', compact('payments'));
    }
    public function create() {
        $users = DB::table('users')->get();
        return view('payment.create', compact('users'));
    }
    public function store(Request $request) {
        $request->validate([
            'total'=>'required|numeric',
            'paid'=>'required|numeric',
            'balance'=>'required|numeric',
            'user_id'=>'required|exists:users,id',
        ]);
        DB::table('payment')->insert([
            'total'=>$request->total,
            'paid'=>$request->paid,
            'balance'=>$request->balance,
            'observation'=>$request->observation,
            'attach'=>$request->attach,
            'date_paid'=>$request->date_paid,
            'type'=>$request->type,
            'user_id'=>$request->user_id,
            'created_at'=>now()
        ]);
        return redirect()->route('payments.index');
    }
    public function edit($id) {
        $payment = DB::table('payment')->find($id);
        $users = DB::table('users')->get();
        return view('payment.edit', compact('payment','users'));
    }
    public function update(Request $request, $id) {
        $request->validate([
            'total'=>'required|numeric',
            'paid'=>'required|numeric',
            'balance'=>'required|numeric',
            'user_id'=>'required|exists:users,id',
        ]);
        DB::table('payment')->where('id',$id)->update([
            'total'=>$request->total,
            'paid'=>$request->paid,
            'balance'=>$request->balance,
            'observation'=>$request->observation,
            'attach'=>$request->attach,
            'date_paid'=>$request->date_paid,
            'type'=>$request->type,
            'user_id'=>$request->user_id,
            'updated_at'=>now()
        ]);
        return redirect()->route('payments.index');
    }
    public function destroy($id) {
        DB::table('payment')->where('id',$id)->delete();
        return redirect()->route('payments.index');
    }
}
