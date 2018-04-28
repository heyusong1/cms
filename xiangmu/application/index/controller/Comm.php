<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\reply;

header("content-type:text/html;charset=utf-8");

class comm extends Controller
{
	function __construct(){
           $this->prevent_login();
     }
    public function link_insert()
    {
        $db=new reply();
        $data=$db->select();
//        var_dump($data);die;
        return view("admin/base",['data'=>$data]);
    }
}