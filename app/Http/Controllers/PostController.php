<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();

        foreach ($posts as $post) {
            $post_id = $post->id;
        }

        $user = auth()->user();

        $user_id = Auth::user()->id;
        // $like = Like::where('post_id', $post->id)->where('user_id', $user)->first();
        $like = Like::where([
            ['post_id', $post_id],
            ['user_id', $user_id]
        ])->first();

        return view('post.index', compact('posts', 'user', 'like'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|max:1000',
            'image' => 'image|max:1024'
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = auth()->user()->id;

        // 画像保存
        if (request('image')) { // もし送信されたデータの中にimageがあれば
            $original = request()->file('image')->getClientOriginalName();  // 元々のファイル名を取得し、これを$originalに代入する
            $name = date('Ymd_His') . '_' . $original;  // 他のユーザーとのファイル名の重複を避けるため、$originalに秒まで含めた日時を付けたものを$nameに代入する
            request()->file('image')->move('storage/images', $name);    // $nameの名前で画像ファイルを指定した場所（storage/images）に保存する
            $post->image = $name;   // $nameの名前で画像ファイルのファイル名をデータベースに保存する
        }

        $post->save();
        return redirect()->route('post.create')->with('message', '投稿を作成しました。');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $user = Auth::user()->id;
        // $like = Like::where('post_id', $post->id)->where('user_id', $user)->first();
        $like = Like::where([
            ['post_id', $post->id],
            ['user_id', $user]
        ])->first();

        return view('post.show', compact('post', 'like'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('post.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $inputs = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|max:1000',
            'image' => 'image|max:1024'
        ]);

        $post->title = $request->title;
        $post->body = $request->body;

        if (request('image')) { // もし送信されたデータの中にimageがあれば
            $original = request()->file('image')->getClientOriginalName();  // 元々のファイル名を取得し、これを$originalに代入する
            $name = date('Ymd_His') . '_' . $original;  // 他のユーザーとのファイル名の重複を避けるため、$originalに秒まで含めた日時を付けたものを$nameに代入する
            request()->file('image')->move('storage/images', $name);    // $nameの名前で画像ファイルを指定した場所（storage/images）に保存する
            $post->image = $name;   // $nameの名前で画像ファイルのファイル名をデータベースに保存する
        }

        $post->save();
        return redirect()->route('post.show', $post)->with('message', '投稿を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('post.index')->with('message', '投稿を削除しました');
    }
}
