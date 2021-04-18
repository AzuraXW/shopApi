<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->query('page');
        $limit = $request->query('limit');
        $name = $request->query('name');
        $email = $request->query('email');
        // 分页查询
        $paginate = User::when($name, function ($query) use($name) {
            return $query->where('name', 'like', "%$name%");
        })->when($email, function ($query) use($email) {
            return $query->where('email', 'like', "%$email%");
        })->paginate($limit);
        return $this->response->paginator($paginate, new UserTransformer())->setMeta([
            'success' => true,
            'message' => '成功获取用户列表'
        ]);
    }

    // 用户锁定
    public function lock (User $user) {
        $user->is_locked = $user->is_locked === 0 ? 1 : 0;
        $user->save();
        return $this->response->array([
            'success' => 200,
            'message' => '用户状态更新成功',
            'is_lock' => $user->is_locked
        ]);
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
     * 单个用户详情.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // 返回用户
        return $this->response->item($user, new UserTransformer());
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
