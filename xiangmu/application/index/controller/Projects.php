<?php
namespace app\index\controller;


use app\index\model\proje;
use app\index\model\thing;
use think\Controller;
use think\Request;
use app\index\model\proj;

class projects extends Controller
{
    public function __construct(){
        $this->prevent_login();
      }
    public function pro_insert()
    {
        return view("home/pro_insert");
    }
    public function pro_inserta()
    {
        $request=Request::instance();
        $data['team_name']=$this->xss($request->post("team_name"));
        $data['team_desc']=$this->xss($request->post("team_desc"));
        foreach ($data as $key => $value) {
          if(empty($value))
          {
            $this->error("所有内容不能为空");
          }
      }
        $db= new proje();
        $arr=$db->select_teamname($data['team_name']);
        if($arr)
        {
            $this->error("此类型名称已被使用");
        }
        $res=$db->insert($data);
        if ($res)
        {
            $this->success("添加成功！","Projects/pro_type");
        }
        else
        {
            $this->error("添加失败","Projects/pro_insert");
        }
    }
    public  function pro_type()
    {
        $db=new proje();
        $data=$db->select();
        return view("home/pro_type",['data'=>$data]);
    }
    public function pro_add()
    {
        $request=Request::instance();
        $data['event_name']=$this->xss($request->post("event_name"));
        $data['team_id']=$this->xss($request->post("team_id"));
        foreach ($data as $key => $value) {
          if(empty($value))
          {
            $this->error("所有内容不能为空");
          }
      }
        $file = request()->file('thing_img');
        // 移动到框架应用根目录/public/uploads/ 目录下
    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
      if($info){
                // 成功上传后 获取上传信息
        $data['thing_img']= $info->getSaveName();
          }else{
                // 上传失败获取错误信息
        echo $file->getError();
        }

        $db=new thing();
        $reg=$db->insert($data);
        if($reg)
        {
            $this->success('添加成功','Projects/pro_show');
        }
        else
        {
            $this->error('添加失败','Projects/pro_type');
        }
    }
    public function pro_show()
    {
        $db=new thing();
        $data=$db->select();
        return view('home/pro_show',['data'=>$data]);
    }
    public function pro_delete()
    {
        $Request=Request::instance();
        $id=$Request->get('thing_id');
        $friend=new thing();
        $data=$friend->pro_delete($id);
        if($data)
        {
            $this->success("删除成功！","Projects/pro_show");
        }
        else
        {
            $this->error("删除失败！","Projects/pro_show");
        }
    }
}