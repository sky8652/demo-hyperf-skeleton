<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/18
 * Time: 9:20
 */

namespace App\Controller\V1\Permission;


use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface as req;
use Hyperf\HttpServer\Contract\ResponseInterface as res;
use Psr\Http\Message\ResponseInterface as rsi;
use xooooooox\awe\ApiStatusCode;
use xooooooox\awe\Tree;

/**
 * @AutoController("v1/permission")
 */
class Permission
{

    /**
     * @param req $req
     * @param res $res
     * @return rsi
     */
    public function add(req $req,res $res) : rsi {
        $insert = [
            'name'=>$req->input('name',''),
            'group'=>$req->input('group',0),
            'parent'=>$req->input('parent',0),
            'sort'=>$req->input('sort',0),
            'role'=>$req->input('role',0) == 1 ? 1 : 2,
            'icon'=>$req->input('icon',''),
            'note'=>$req->input('note',''),
        ];
        if ($insert['name'] == ''){
            return $res->json(ApiStatusCode::Warning('权限名称不能为空'));
        }
        $result = Db::table('permission')->insertGetId($insert);
        if ($result > 0){
            return $res->json(ApiStatusCode::Success('添加成功'));
        }
        return $res->json(ApiStatusCode::Failure('添加失败'));
    }

    /**
     * @param req $req
     * @param res $res
     * @return rsi
     */
    public function del(req $req,res $res) : rsi {
        $id = $req->input('id',0);
        if ($id == 0){
            return $res->json(ApiStatusCode::Success('删除成功'));
        }
        $data = Db::table('permission')
            ->select('id')
            ->where('id','=',$id)
            ->first();
        if (empty($data)){
            return $res->json(ApiStatusCode::Failure('权限不存在'));
        }
        $result = Db::table('permission')
            ->where('id','=',$id)
            ->delete();
        if ($result > 0){
            return $res->json(ApiStatusCode::Success('删除成功'));
        }
        return $res->json(ApiStatusCode::Failure('删除失败'));
    }

    /**
     * @param req $req
     * @param res $res
     * @return rsi
     */
    public function edit(req $req,res $res) : rsi {
        $id = $req->input('id',0);
        if ($id == 0){
            return $res->json(ApiStatusCode::Warning('id不能为0'));
        }
        $data = Db::table('permission')
            ->select('id','name')
            ->where('id','=',$id)
            ->first();
        if (empty($data)){
            return $res->json(ApiStatusCode::Failure('权限不存在'));
        }
        $update = [];
        $name = $req->input('name','');
        if ($name != $data['name']){
            $update['name'] = $name;
        }
        if ($update != []){
            $result = Db::table('permission')
                ->where('id','=',$id)
                ->update($update);
            if ($result < 1){
                return $res->json(ApiStatusCode::Failure('更新失败'));
            }
        }
        return $res->json(ApiStatusCode::Success('更新成功'));
    }

    /**
     * @param req $req
     * @param res $res
     * @return rsi
     */
    public function lists(req $req,res $res) : rsi {
//        $result = Db::table('permission')
//            ->select('id','name','group','parent','time','role','icon')
//            ->where('status','=',0)
//            ->orderBy('id','DESC')
//            ->limit(1000)
//            ->get()
//            ->toArray();
        $result = Db::table('address')
            ->select('id','name','parent')
            ->get()
            ->toArray();
        $data = ApiStatusCode::$Success;
        $data['data'] = Tree::Infinite($result,'id','parent','children');
        return $res->json($data);
    }

}