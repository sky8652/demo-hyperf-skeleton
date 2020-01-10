<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/19
 * Time: 10:40
 */

namespace App\Controller\V1\Interfaces;


use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface as req;
use Hyperf\HttpServer\Contract\ResponseInterface as res;
use Psr\Http\Message\ResponseInterface as rsi;
use xooooooox\awe\ApiDocument;
use xooooooox\awe\Time;

/**
 * @AutoController("v1/interfaces")
 */
class Interfaces
{

    /**
     * @param req $req
     * @param res $res
     * @return rsi
     */
    public function build_md(req $req,res $res) : rsi {
        $obj = Db::table('interface_describe')
            ->where('status','=',0);
        $limit = $req->input('limit',100);
        $keyword = $req->input('keyword','');
        if ($keyword != ''){
            $obj = $obj->where('keyword','LIKE','%'.$keyword.'%');
        }
         $obj = $obj->select('id','name','keyword','title','path','request','response','note','time');
        $result = $obj
            ->orderBy('id','DESC')
            ->limit($limit)
            ->get()
            ->toArray();
        if (empty($result)){
            if ($keyword != ''){
                return $res->raw('暂时没有接口数据,请耐心等待');
            }
            return $res->raw('未找到任何相关的接口信息,请换个关键字试试');
        }
        for($i=0;$i<count($result);$i++){
            $result[$i]['time'] = Time::IntToString($result[$i]['time'],'');
            $result[$i]['producer'] = 'xooooooox';
            $result[$i]['project'] = 'A计划';
            $result[$i]['method'] = 'POST';
            $result[$i]['format'] = 'application/json';
        }
        $resultMarkdownTableString = ApiDocument::MarkdownTableString($result);
        return $res->raw($resultMarkdownTableString);
    }

    public function build_table(req $req,res $res) : rsi {
        $obj = Db::table('interface_describe')
            ->where('status','=',0);
        $limit = $req->input('limit',100);
        $keyword = $req->input('keyword','');
        if ($keyword != ''){
            $obj = $obj->where('keyword','LIKE','%'.$keyword.'%');
        }
        // $obj = $obj->select('id','name','title');
        $result = $obj
            ->orderBy('id','DESC')
            ->limit($limit)
            ->get()
            ->toArray();
        if (empty($result)){
            if ($keyword != ''){
                return $res->raw('暂时没有接口数据,请耐心等待');
            }
            return $res->raw('未找到任何相关的接口信息,请换个关键字试试');
        }
        $table = self::HtmlTable($result);
        return $res->raw($table);
    }

    public static function HtmlTable(array $result) : string {
        $rows = count($result);
        if ($rows < 1){
            return '';
        }
        $table = "<table border=\"1\" width=\"100%\">\n";
        for ($i=0;$i<$rows;$i++){
            if ($i == 0){
                $table .= "\t<tr>\n";
                foreach ($result[$i] as $k => $v){
                    $table .= "\t\t<td>$k</td>\n";
                }
                $table .= "\t</tr>\n";
            }
            $table .= "\t<tr>\n";
            foreach ($result[$i] as $k => $v){
                if (!is_string($v)){
                    $v = (string)$v;
                }
                $table .= "\t\t<td>$v</td>\n";
            }
            $table .= "\t</tr>\n";
        }
        $table .= "</table>\n";
        return $table;
    }

}