<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ArticleController extends Controller {
    public function index() {
        $articles = DB::table('article')->get();
        return view('article.index', compact('articles'));
    }
    public function create() {
        $users = DB::table('users')->get();
        return view('article.create', compact('users'));
    }
    public function store(Request $request) {
        $request->validate([
            'name'=>'required',
            'user_id'=>'required|exists:users,id',
        ]);
        DB::table('article')->insert([
            'name'=>$request->name,
            'description'=>$request->description,
            'photo'=>$request->photo,
            'author'=>$request->author,
            'status'=>$request->status,
            'user_id'=>$request->user_id,
            'created_at'=>now()
        ]);
        return redirect()->route('articles.index');
    }
    public function edit($id) {
        $article = DB::table('article')->find($id);
        $users = DB::table('users')->get();
        return view('article.edit', compact('article','users'));
    }
    public function update(Request $request, $id) {
        $request->validate([
            'name'=>'required',
            'user_id'=>'required|exists:users,id',
        ]);
        DB::table('article')->where('id',$id)->update([
            'name'=>$request->name,
            'description'=>$request->description,
            'photo'=>$request->photo,
            'author'=>$request->author,
            'status'=>$request->status,
            'user_id'=>$request->user_id,
            'updated_at'=>now()
        ]);
        return redirect()->route('articles.index');
    }
    public function destroy($id) {
        DB::table('article')->where('id',$id)->delete();
        return redirect()->route('articles.index');
    }
}
