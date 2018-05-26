<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Category;
use App\Http\Requests\ArticleRequest;
use App\Repositories\ArticlesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticlesController extends AdminController
{
    public function __construct(ArticlesRepository $articlesRepository)
    {
        parent::__construct();

        $this->a_rep = $articlesRepository;

        $this->template = env('THEME').'.admin.articles';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (\Gate::denies('VIEW_ADMIN_ARTICLES')){
            abort(403);
        }

        $this->title = 'Менеджер статей';
        $articles = $this->getArticles();
        $this->content = view(env('THEME').'.admin.articles_content')->with('articles', $articles)->render();


        return $this->renderOutput();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //проверка на создание не требует объекта Article поэтому тут Article::class
        if (\Gate::denies('create', Article::class)){
            abort(403);
        }
        $this->title = 'Добавить новый материал';

        $categories = Category::select(['title', 'alias', 'parent_id', 'id'])->get();


        //формируем массив для тега select смотреть нужный в доке laravelCollective
        $list = [];
        foreach ($categories as $category) {
            if ($category->parent_id == 0){
                $list[$category->title] = [];
            } else {
                $list[$categories->where('id', $category->parent_id)->first()->title][$category->id] = $category->title;
            }

        }

        $this->content = view(env('THEME').'.admin.articles_create_content')->with('categories', $list)->render();
        return $this->renderOutput();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        //Авторизация в реквесте

        //либо массив с ошибкой либо со статусом
        $result = $this->a_rep->addArticle($request);


        //в сессию записывается error
        if (is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }

        //в данном случае ->with($result) записывается в сессию (У нас status)
        return redirect('/admin')->with($result);
        dd($request);
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
    public function edit(Article $article)
    {
        //$article = Article::where('alias', $alias);
        if (\Gate::denies('update', new Article)){
            abort(403);
        }
        //Формируем данные для передачи в шаблон
        $article->img = json_decode($article->img);
        $this->title = 'Редактирование материала'.$article->title;

        $categories = Category::select(['title', 'alias', 'parent_id', 'id'])->get();


        //формируем массив для тега select смотреть нужный в доке laravelCollective
        $list = [];
        foreach ($categories as $category) {
            if ($category->parent_id == 0){
                $list[$category->title] = [];
            } else {
                $list[$categories->where('id', $category->parent_id)->first()->title][$category->id] = $category->title;
            }
        }
        $this->content = view(env('THEME').'.admin.articles_create_content')->with(['categories' => $list, 'article' => $article])->render();
        return $this->renderOutput();


        dd($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        //
        //Авторизация в реквесте

        //либо массив с ошибкой либо со статусом
        $result = $this->a_rep->updateArticle($request, $article);


        //в сессию записывается error
        if (is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }

        //в данном случае ->with($result) записывается в сессию (У нас status)
        return redirect('/admin')->with($result);
//        $data = $request->except([''])
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

    private function getArticles()
    {
        return $this->a_rep->get();
    }
}
