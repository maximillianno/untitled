<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use Auth;
use Illuminate\Http\Request;

class CommentController extends SiteController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //формируем данные для записи коммента в базу
        $data = $request->except('_token', 'comment_post_id', 'comment_parent');
        $data['article_id'] = $request->input('comment_post_id');
        //нельзя использовать isset()... для выражений
        $data['parent_id'] = (null !== $request->input('comment_parent')) ? $request->input('comment_parent') : '0';
//        dd($data);
        print_r($data);

        //Валидируем поля
        $validator = \Validator::make($data, [
            'article_id' => 'integer|required',
            'parent_id' => 'integer|required',
            'text' => 'string|required'
        ]);
        //Некоторые валидируем только при гостевом входе
        $validator->sometimes(['name', 'email'], 'required|max:255', function ($input){
            return !Auth::check();
        });
        //передаем ошибки
        if ($validator->fails()){
            return \Response::json(['error' => $validator->errors()->all()]);
        }
        //Формируем модели пользователя и коммента
        $user = Auth::user();
        $comment = new Comment($data);

        //дописываем модель коммента при авторизационном входе
        if ($user){
            $comment->user_id = $user->id;
        }
        //Формируем модель статьи, через которую записываем коммент
        $post = Article::find($data['article_id']);
        $post->comments()->save($comment);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
