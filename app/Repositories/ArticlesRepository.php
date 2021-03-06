<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 31.01.2018
 * Time: 11:59
 */

namespace App\Repositories;

use App\Article;
use Intervention\Image\Facades\Image;

class ArticlesRepository extends Repository
{

    public function __construct(Article $article)
    {
        $this->model = $article;
    }
    public function one($alias, $attr = [])
    {
        $article = parent::one($alias, $attr);



        if ($article && $attr){
            $article->load('comments');
            $article->comments->load('user');
        }
        return $article;
    }


    /**
     * @param $request
     * @return array С ошибкой или статусом
     */
    public function addArticle($request)
    {
        //Проверяется в реквесте
//        if (\Gate::denies('create', $this->model)){
//            abort(403);
//        }
        $data = $request->except('_token', 'image');


        //Обработка пустого alias
        if(empty($data['alias'])){
            $data['alias'] = $this->transliterate($data['title']);
        }

        //Если уже есть такой псевдоним - отправить назад
        //Делаем руками то, что автоматически делает валидация
        if ($this->one($data['alias'], false)){
            $request->merge(['alias' => $data['alias']]);
            $request->flash();
            dd($request->session()->all());
            return ['error' => 'Данный псевдоним уже используется'];
        }

        //Преобразование загруженной картинки
        if ($request->hasFile('image')){
            $image = $request->file('image');

            //проверка загруженного файла
            if ($image->isValid()){
                $str = str_random(8);
                $obj = new \stdClass;
                $obj->mini = $str.'_mini.jpg';
                $obj->max = $str.'_max.jpg';
                $obj->path = $str.'_path.jpg';

                $img = Image::make($image);
                //пропорционально обрезали и сохранили
                $img->fit(\Config::get('settings.image')['width'], \Config::get('settings.image')['height'])
                    ->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->path);
                $img->fit(\Config::get('settings.articles_img.max.width'), \Config::get('settings.articles_img.max.height'))->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->max);
                $img->fit(\Config::get('settings.articles_img.mini.width'), \Config::get('settings.articles_img.mini.height'))->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->mini);

                $data['img'] = json_encode($obj);



            }
        }
        $this->model->fill($data);

        if ($request->user()->articles()->save($this->model)){
            return ['status' => 'Материал добавлен'];
        };



    }

    public function updateArticle($request, $article)
    {
        $data = $request->except('_token', 'image', '_method');


        //Обработка пустого alias
        if(empty($data['alias'])){
            $data['alias'] = $this->transliterate($data['title']);
        }

        //Если уже есть такой псевдоним - отправить назад
        //Делаем руками то, что автоматически делает валидация
        $result = $this->one($data['alias'], false);
        if (isset($result->id) && $result->id != $article->id){
            $request->merge(['alias' => $data['alias']]);
            $request->flash();
            dd($request->session()->all());
            return ['error' => 'Данный псевдоним уже используется'];
        }

        //Преобразование загруженной картинки
        if ($request->hasFile('image')){
            $image = $request->file('image');

            //проверка загруженного файла
            if ($image->isValid()){
                $str = str_random(8);
                $obj = new \stdClass;
                $obj->mini = $str.'_mini.jpg';
                $obj->max = $str.'_max.jpg';
                $obj->path = $str.'_path.jpg';

                $img = Image::make($image);
                //пропорционально обрезали и сохранили
                $img->fit(\Config::get('settings.image')['width'], \Config::get('settings.image')['height'])
                    ->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->path);
                $img->fit(\Config::get('settings.articles_img.max.width'), \Config::get('settings.articles_img.max.height'))->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->max);
                $img->fit(\Config::get('settings.articles_img.mini.width'), \Config::get('settings.articles_img.mini.height'))->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->mini);

                $data['img'] = json_encode($obj);



            }
        }
        $article->fill($data);

        if ($article->update()){
            return ['status' => 'Материал добавлен'];
        };
    }

    public function deleteArticle($article)
    {
        if (\Gate::denies('delete', $article)){
            abort(403);
        }

        //Удаляем комменты
        $article->comments()->delete();

        //Удаляем статью
        if($article->delete()){
            return ['status' => 'Материал удален'];
        }
    }


}