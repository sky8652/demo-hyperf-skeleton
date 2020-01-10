<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/14
 * Time: 14:36
 */

namespace App\Controller\V1;


use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * @Controller()
 */
class UserController
{

    /**
     * @RequestMapping(path="index",methods="get,post")
     */
    public function index(RequestInterface $request,ResponseInterface $response){
        var_dump($request->post('abc',''));

        return $response->json(['abc'=>123,'def'=>'ABC']);
    }

}