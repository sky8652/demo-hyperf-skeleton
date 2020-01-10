<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/28
 * Time: 17:29
 */

namespace App\Controller\V1\System;


use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface as req;
use Hyperf\HttpServer\Contract\ResponseInterface as res;
use Psr\Http\Message\ResponseInterface as rsi;
use xooooooox\awe\ApiStatusCode;
use xooooooox\awe\Tree;

/**
 * @AutoController("v1/system")
 */
class Address
{

    /**
     * @param req $req
     * @param res $res
     * @return rsi
     */
    public function lists(req $req, res $res): rsi
    {
        $result = Db::table('address')
            ->select('id', 'name', 'parent')
            ->get()
            ->toArray();
        $data = ApiStatusCode::$Success;
        $data['data'] = Tree::Infinite($result, 'id', 'parent', 'children');
        return $res->json($data);
    }

}