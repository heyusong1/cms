<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\friend;

header("content-type:text/html;charset=utf-8");

class link extends Controller
{
   public function __construct()
   {
       $this->prevent_login();
   }

    public function link_insert()
    {
        return view("home/link_insert");
    }
    public function insert()
    {
        $request=Request::instance();
        $data['link_name']=$this->xss($request->post("link_name"));
        $data['logo_name']=$this->xss($request->post("logo_name"));
        $data['link_url']=$this->xss($request->post("link_url"));
        $data['link_desc']=$this->xss($request->post("link_desc"));
        $data['link_order']=$this->xss($request->post("link_order"));
        $data['link_type']=$this->xss($request->post("link_type"));
        foreach ($data as $key => $value) {
          if(empty($value))
          {
            $this->error("所有内容不能为空");
          }
        }

        $db= new friend();
        $arr=$db->select_linkname($data['link_name']);
        // echo $db->getLastSql();die;
        // var_dump($arr);die; 
        if($arr)
        {
            $this->error("此网站名称已被使用");
        }
        $res=$db->insert($data);
        if ($res)
        {
                $this->success("添加成功！","Link/link_show");
        }
        else
        {
            $this->error("添加失败","Link/link_insert");
        }
    }
    public function link_show()
    {
        $db=new friend();
        $data=$db->select();
        return view("home/link_show",['data'=>$data]);
    }
    public function link_delete()
    {
        $Request=Request::instance();
        $link_id=$Request->get('link_id');
        $friend=new friend();
        $data=$friend->deleteData($link_id);
        if($data)
        {
            $this->success("删除成功！","Link/link_show");
        }
        else
        {
            $this->error("删除失败！","Link/Link_show");
        }
    }
    public function link_update()
    {
        $Request=Request::instance();
        $link_id=$Request->get('link_id');
        // var_dump($link_id);die;
        $friend=new friend();
        $res=$friend->find($link_id);
        return view('home/link_manage',['res'=>$res]);
    }
    public function link_manage()
    {
        $Request=Request::instance();
        $link_id=$Request->post('link_id');
        $Request=Request::instance();
        $data=$Request->post();
        $friend=new friend();
        $reg=$friend->updateData($data,$link_id);
        if ($reg)
        {
            $this->success("修改成功","Link/link_show");
        }
        else
        {
            $this->error("修改失败","Link/link_show");
        }
        return view("home/link_manage");
    }
    public function deteleall()
    {

        $Request=Request::instance();
        $link_id=$Request->post();
        foreach ($link_id as $key=>$vel)
        {
            $idlist=implode($vel,',');
        }
        $link=new friend;
        $res=$link->delAll($idlist);
        if($res)
        {
            return json(['state'=>1,"message"=>"成功！"]);
        }
        else
        {
            return json(['state'=>0,"message"=>"失败！"]);
        }
    }
    public function link_about()
    {
        $db=new friend();
        $data=$db->select_link();
        return view("admin/about",['data'=>$data]);
    }
    public function link_index()
    {
        $db=new friend();
        $data=$db->select_index();
        return view("admin/index",['data'=>$data]);
    }
    public function link_price()
    {
        $db=new friend();
        $data=$db->select_price();
        return view("admin/price",['data'=>$data]);
    }
}