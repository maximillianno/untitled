<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 31.01.2018
 * Time: 12:00
 */

namespace App\Repositories;

use Config;



abstract class Repository
{
    /**
     * @var model
     */
    protected $model = false;

    /**
     * @return mixed
     */
    public function get($select = '*', $take = false, $pagination = false, $where = false)
    {
        $builder = $this->model->select($select);
        if ($take) {
            $builder->take($take);
        }
        if ($where){
            $builder->where($where[0], $where[1]);
        }

        if ($pagination) {
            return $this->check($builder->paginate( \Config::get('settings.paginate')));
        }

        return $this->check($builder->get());
    }

    /*
     * декодирование json в объект
     */
    protected function check($result)
    {
        if($result->isEmpty()){
            return false;
        }

        $result->transform(function ($item, $key){
            if (is_string($item->img) && is_object(json_decode($item->img)) && json_last_error() == JSON_ERROR_NONE){

                $item->img = json_decode($item->img);
            }
            return $item;
        });

        return $result;
    }

    public function one($alias, $attr = [])
    {
        $result = $this->model->where('alias', $alias)->first();
        return $result;

    }

    /**
     * @param $title
     * @return mixed|null|string|string[]
     */
    public function transliterate($title)
    {
        $str = mb_strtolower($title, 'UTF-8');
        $leter_array = [
            'a' => 'а',
            'b' => 'б',
            'v' => 'в',
            'g' => 'г,ґ',
            'd' => 'д',
            'e' => 'е,є,э',
            'jo' => 'ё',
            'zh' => 'ж',
            'z' => 'з',
            'i' => 'и,і',
            'ji' => 'ї',
            'j' => 'й',
            'k' => 'к',
            'l' => 'л',
            'm' => 'м',
            'n' => 'н',
            'o' => 'о',
            'p' => 'п',
            'r' => 'р',
            's' => 'с',
            't' => 'т',
            'u' => 'у',
            'f' => 'ф',
            'kh' => 'х',
            'ts' => 'ц',
            'ch' => 'ч',
            'sh' => 'ш',
            'shch' => 'щ',
            '' => 'ъ',
            'y' => 'ы',
            '' => 'ь',
            'yu' => 'ю',
            'ya' => 'я',
        ];

        foreach ($leter_array as $letter => $kyr) {
            $kyr = explode(',',$kyr);

            $str = str_replace($kyr, $letter, $str);

        }
        $str = preg_replace('/(\s|[^A-Za-z0-9\-])+/','-',$str);

        $str = trim($str,'-');

        return $str;



    }


}