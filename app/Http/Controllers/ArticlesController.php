<?php

namespace App\Http\Controllers;

use App\Category;
use App\Menu;
use App\Repositories\ArticlesRepository;
use App\Repositories\CommentsRepository;
use App\Repositories\MenuRepository;
use App\Repositories\PortfolioRepository;
use Illuminate\Http\Request;

class ArticlesController extends SiteController
{
    private $c_rep;

    public function __construct(PortfolioRepository $portfolioRepository, ArticlesRepository $articlesRepository, CommentsRepository $commentsRepository)
    {
        //передаем в родительский контроллер репо с меню
        parent::__construct(new MenuRepository(new Menu()));


        $this->p_rep = $portfolioRepository;
        $this->a_rep = $articlesRepository;
        $this->c_rep = $commentsRepository;

        //указываем главный шаблон
        $this->template =  env('THEME').'.articles';
        $this->bar = 'right';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($catAlias = false)
    {
        //получаем статьи с пагинацией
        $articles = $this->getArticles($catAlias);

        //для правого сайдбара получаем комменты и портфолио
        $comments = $this->getComments(config('settings.recent_comments'));
        $portfolios = $this->getPortfolios(config('settings.recent_portfolios'));

        //
        $this->contentRightBar = view(env('THEME').'.articlesBar')->with(['comments' => $comments, 'portfolios' => $portfolios])->render();

        //рендерим контент и передаем в макет
        $content = view(env('THEME').'.articles_content')->with('articles', $articles)->render();
        $this->vars = array_add($this->vars,'content', $content);

        return $this->renderOutput();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($alias = false)
    {
        //получаем статью
        $article = $this->a_rep->one($alias, ['comments' => true]);

        //из JSON без ебли с transform - потому что тут 1 запись а не коллекция
        if ($article){
            $article->img = json_decode($article->img);
        }

        if ($article){
            $this->title = $article->title;
            $this->keywords = $article->keywords;
            $this->meta_desc = $article->meta_desc;
        }
//        dd($article->comments->groupBy('parent_id'));
        $content = view(env('THEME').'.articleContent')->with('article', $article)->render();
        $this->vars = array_add($this->vars, 'content', $content);

        //для правого сайдбара получаем комменты и портфолио
        $comments = $this->getComments(config('settings.recent_comments'));
        $portfolios = $this->getPortfolios(config('settings.recent_portfolios'));
        //
        $this->contentRightBar = view(env('THEME').'.articlesBar')->with(['comments' => $comments, 'portfolios' => $portfolios])->render();

        return $this->renderOutput();
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

    /**
     * @param bool $alias
     * @return mixed
     */
    private function getArticles($alias = false)
    {
        $where = false;
        if ($alias){
//            dd($alias);
            $id = Category::where('alias', $alias)->first()->id;
//            dd($id);
            $where = ['category_id', $id];

        }

        /**
         *
         */
        $articles    = $this->a_rep->get(['id', 'title', 'alias', 'created_at', 'img', 'desc', 'user_id', 'category_id', 'title', 'keywords'], false, true , $where);

        //для того, чтобы не плодить запросы
        if ($articles) {
            $articles->load('user', 'category', 'comments');
        }

        return $articles;

    }

    /**
     * @param $take
     * @return mixed
     */
    private function getComments($take)
    {
        $comments = $this->c_rep->get(['text', 'name', 'email', 'site', 'article_id' , 'user_id'], $take);
        if ($comments) {
            $comments->load('article', 'user');
        }
        return $comments;
    }

    /**
     * @param $take
     * @return mixed
     */
    private function getPortfolios($take)
    {
        $portfolios = $this->p_rep->get(['title', 'text', 'alias', 'customer', 'img', 'filter_alias'], $take);
        return $portfolios;

    }
}
