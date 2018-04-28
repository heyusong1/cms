<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\TeamModel;
use \think\Cookie; 
header("content-type:text/html;charset=UTF-8");
class Team  extends Controller
{
  public function __construct(){
        $this->prevent_login();
      }
	//展示模板
   public function department()
   {
      $request=Request::instance();
      $db   = new TeamModel();
      $department_id     = $this->xss($request->get("department_id",""));
      if(!empty($department_id))
      {
         $db -> del_department($department_id);
      }
     
      $data = $db->show_department(); 
      return view("home/department",['data'=>$data]);
   }
   public function add_department()
   {
      return view("home/add_department");
   }
   public function add_department_do()
   {
      $request=Request::instance();
       $data['department_name']     = $this->xss($request->post("department_name"));
      foreach ($data as $key => $value) {
          if(empty($value))
          {
            $this->error("所有内容不能为空");
          }
      }
       $db  = new TeamModel();
       $arr=$db->select_departmentname($data['department_name']);
       if($arr)
       {
          $this->error("部门名称已存在");
       }
       $res = $db -> insert_department($data); 
       if($res)
       {
         $this->success("添加成功");
       }
       else
       {
         $this->error("添加失败");
       }
   }
   public function position()
   {
      $request=Request::instance();
      $db   = new TeamModel();
      $position_id     = $this->xss($request->get("position_id",""));
      if(!empty($position_id))
      {
         $db -> del_position($position_id);
      }
      $data = $db->show_position(); 
      return view("home/position",['data'=>$data]);
   }
   public function add_position()
   {
      $db   = new TeamModel();
      $data = $db->show_department(); 
      return view("home/add_position",['data'=>$data]);
   }
   public function add_position_do()
   {
      $request=Request::instance();
      $data['position_name']     = $this->xss($request->post("position_name"));
      $data['department_id']     = $this->xss($request->post("department_id"));
      $data['f_url']     = $this->xss($request->post("f_url"));
      $data['w_url']     = $this->xss($request->post("w_url"));
      $data['desc']     = $this->xss($request->post("desc"));
      foreach ($data as $key => $value) {
          if(empty($value))
          {
            $this->error("所有内容不能为空");
          }
      }
      $file = $request->file('position_img');
       if (!empty($file)) { 
         $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'); 
          if ($info) { 
            $data['position_img']     = $info->getSaveName();
          } else { 
            //上传失败获取错误信息 
            $this->error($file->getError()); 
          } 
       } 
       // var_dump($data);die;
       $db  = new TeamModel();
       $arr=$db->select_positionname($data['position_name']);
       if($arr)
       {
          $this->error("负责人名称已经存在");
       }
       $res = $db -> insert_position($data); 
       if($res)
       {
         $this->success("添加成功");
       }
       else
       {
         $this->error("添加失败");
       }
   }
}
