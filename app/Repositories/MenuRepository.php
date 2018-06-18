<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 31.01.2018
 * Time: 11:59
 */

namespace App\Repositories;

use App\Http\Requests\MenusRequest;
use App\Menu;
use Validator;

class MenuRepository extends Repository
{

    public function __construct(Menu $menu)
    {
        $this->model = $menu;
    }

    /**
     * @param MenusRequest $request
     * @return array
     */
    public function addMenu($request)
    {
        //TODO: checked radiobutton при первом открытии and write validator
        $data = $request->only('title', 'parent', 'type');
//        dd($input);
        //if ($input['type'] == )
        if(empty($data)){
            return ['error' => 'Нет данных'];
        }
//        dd($request->all());

        switch ($data['type']){
            case "customLink":
                $data['path'] = $request->input('custom_link');

                break;

            //2й аккорд
            case "blogLink":
                //
                if ($request->input('category_alias')){
                    //Если ссылка на корневой раздел блога
                    if ($request->input('category_alias') == 'parent'){
                        $data['path'] = route('articles.index');

                    //Если ссылка на категорию
                    } else {

                        $data['path'] = route('articlesCat', ['cat_alias' => $request->input('category_alias')]);
                    }
                }

                break;


        }

//        dd($data);
        unset($data['type']);
        if($this->model->fill($data)->save()){
            return ['status' => 'Ссылка добавлена'];
        }
        dd($this->model);
    }


}