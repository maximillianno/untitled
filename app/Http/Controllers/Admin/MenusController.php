<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Filter;
use App\Repositories\ArticlesRepository;
use App\Repositories\MenuRepository;
use App\Repositories\PortfolioRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenusController extends AdminController
{
    //Меню репозиторий
    /**
     * @var MenuRepository
     */
    protected $m_rep;

    public function __construct(MenuRepository $menusRepository, ArticlesRepository $articlesRepository, PortfolioRepository $portfolioRepository)
    {
        parent::__construct();
        $this->m_rep = $menusRepository;
        $this->p_rep = $portfolioRepository;
        $this->a_rep = $articlesRepository;

        $this->template = env('THEME').'.admin.menus';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (\Gate::denies('VIEW_ADMIN_MENU')){
            abort(403);
        }

        $menu = $this->getMenus();

        $this->content = view(env('THEME').'.admin.menus_content')->with('menus', $menu);

        return $this->renderOutput();



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Меню
        $this->title = 'Новый пункт меню';
        $menus = $this->getMenus()->roots();

        //Преобразовываем коллекцию в массив для select option
        $menus = $menus->reduce(function ($returnMenu, $menu){
            $returnMenu[$menu->id] = $menu->title;
            return $returnMenu;

        }, ['0' => 'Родительский пункт меню']);



        //Категории
        $categories = Category::select('title', 'alias', 'id', 'parent_id')->get();
        $list = [];
        $list = array_add($list, '0', 'Не используется');
        $list = array_add($list, 'parent', 'Раздел Блог');

        foreach ($categories as $category) {
            if ($category->parent_id == 0){
                $list[$category->title] = [];
            } else {
                $list[$categories->where('id', $category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }


        //Статьи
        $articles = $this->a_rep->get(['id', 'title', 'alias']);
        $articles = $articles->reduce(function($returnArticles, $article){
            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;
        }, []);


        //работа с фильтрами
        $filters = Filter::select('id', 'title', 'alias')->get()->reduce(function ($returnFilter, $model){
            $returnFilter[$model->alias] = $model->title;
            return $returnFilter;
        },['parent' => 'Раздел портфолио']);


        //теперь работа с портфолио
        $portfolios = $this->p_rep->get(['id', 'alias', 'title']);
        $portfolios = $portfolios->reduce(function ($returnPortfolios, $model) {
            $returnPortfolios[$model->alias] = $model->title;
            return $returnPortfolios;
        },[]);


        $this->content = view(env('THEME').'.admin.menus_create_content')->with(['menus' => $menus, 'categories' => $list, 'articles' => $articles, 'filters' => $filters, 'portfolios' => $portfolios])->render();

        return $this->renderOutput();


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

    /**
     * @return bool|\Lavary\Menu\Menu
     */
    private function getMenus()
    {
        $menu = $this->m_rep->get();
        if ($menu->isEmpty()){
            return false;
        }

        return (new \Lavary\Menu\Menu)->make('forMenuPart', function ($m) use ($menu) {
            foreach ($menu as $item) {

                if ($item->parent == 0){
                    $m->add($item->title, $item->path)->id($item->id);
                } else {
                    if ($m->find($item->parent)){
                        $m->find($item->parent)->add($item->title, $item->path)->id($item->id);
                    }
                }
            }
        });
    }
}
