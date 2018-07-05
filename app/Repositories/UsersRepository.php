<?php
/**
 * Created by PhpStorm.
 * User: maxus
 * Date: 27.06.2018
 * Time: 17:56
 */

namespace App\Repositories;



use App\Http\Requests\UserRequest;
use App\User;

class UsersRepository extends Repository
{


    /**
     * UsersRepository constructor.
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @param UserRequest $request
     * @return array
     */
    public function addUser(UserRequest $request)
    {
        $data = $request->all();
        $user = $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'login' => $data['login'],
            'password' => bcrypt($data['password']),
        ]);
        if ($user){
            $user->roles()->attach($data['role_id']);
        }
        return ['status' => 'Пользователь добавлен'];

    }

    /**
     * @param UserRequest $request
     * @param User $user
     * @return array
     */
    public function updateUser(UserRequest $request, User $user)
    {
        $data = $request->all();
        if (!empty($data['password'])){
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }


        $user->fill($data)->update();
        $user->roles()->sync([$data['role_id']]);

        return ['status' => 'Пользователь изменен'];

    }

    public function deleteUser(User $user)
    {
        $user->roles()->detach();
        if ($user->delete()){
            return ['status' => 'Пользователь удален'];
        }
    }
}