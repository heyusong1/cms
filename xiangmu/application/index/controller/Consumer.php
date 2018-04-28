<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\User;
use \think\Cookie; 
header("content-type:text/html;charset=UTF-8");
class Consumer  extends Controller
{
	public function __construct()
	{
		   $this->prevent_login();
	}
	public function user_list()
   {
      $db   = new User(); 
      $request=Request::instance();
      $page = $this->xss($request->get("page",1));
      $where = $this->xss($request->get("where",""));
      $home_user_id = $this->xss($request->get("home_user_id",""));
      if(!empty($home_user_id))
      {
         $home_user =  Cookie::get('home_user_id');
         if($home_user_id==$home_user)
         {
            $this->error("不能删除自己本身");
         }
         $home = $db->select_id($home_user);
         if($home['home_user_state']!=1)
         {
            $this->error("您没有删除权限");
         }
         else
         {
            $db->del_user($home_user_id);
         }
      }
      $size = 4;
      
      $data = $db->select_all($where);
      $last = $db->last();
      $arr  = $this->fenye($data,$last,$page,$size);
      // var_dump($arr);die;
      return view("home/user_list",['arr'=>$arr,'where'=>$where]);
   }
   public function fenye($data,$last,$page,$size)
   {
      foreach ($data as $key => $value) {
         $data[$key]['time']="0000000000";
      }
      foreach ($data as $key => $value) {
         foreach ($last as $k => $v) {
            // echo $v['home_user_id']; echo "&nbsp;&nbsp;";
            // echo $value['home_user_id']; echo "<hr/>";
            if($v['home_user_id']==$value['home_user_id'])
            {
               if($v['last_time']>$data[$key]['time'])
               {
                  $data[$key]['time']=$v['last_time'];
               }
            }
         }
      }
      
      foreach ($data as $key => $value) 
      {
         if($data[$key]['time']==0)
         {
            $data[$key]['time']="暂未登录";
         }
         else
         {
            $data[$key]['time']=date("Y-m-d H:i:s",$data[$key]['time']);
         }
      }
      $arr['num']=count($data);
      $xiao = ($page-1)*$size;
      $da   = $page*$size-1;
      // echo $da;die;
      foreach ($data as $key => $value) {
         if($key>$da)
         {
            unset($data[$key]);
         }
         if($key<$xiao)
         {
            unset($data[$key]);
         }
      }
      // var_dump($data);die;
      $arr['data'] = $data;
      $arr['page'] = $page;
      
      $arr['num_page']=ceil($arr['num']/$size);
      $arr['up']   = $page-1<1?$page:$page-1;
      $arr['next']   = $page+1>$arr['num_page']?$page:$page+1;
      return $arr;
   }
   public function user_add()
   {
      return view("home/add_user");
   }
   public function add()
   {
      $db   = new User(); 
      $request=Request::instance();
      $home_user_name = $this->xss($request->post("home_user_name"));
      $home_user_pwd = $this->xss($request->post("home_user_pwd"));
      $home_user_pwd1 = $this->xss($request->post("home_user_pwd1"));
      
      
      if($home_user_pwd!=$home_user_pwd1)
      {
         $this->error("两次密码不一致");
      }
      if($home_user_name==""||$home_user_pwd=="")
      {
         $this->error("用户名密码不能为空");
      }
      $data=array(
         'home_user_name'=>$home_user_name,
         'home_user_pwd' =>md5($home_user_pwd),
      );
      $home_user_id =  Cookie::get('home_user_id');
      $home = $db->select_id($home_user_id);
      if($home['home_user_state']!=1)
      {
         $this->error("您没有权限添加");
      }
      $arr=$db->selece_username($home_user_name);
      if($arr)
      {
         $this->error("该用户名已经存在");
      }
      $res= $db ->add_user($data);
      if($res)
      {
         return $this->success("添加成功","Consumer/user_list");
      }
      else
      {
          $this->error("添加用户失败");
      }

   }
   public function update_pwd()
   {
      return view("home/update_pwd");
   }
   public function update()
   {
      $db   = new User(); 
      $request=Request::instance();
      $home_user_pwd2 = $this->xss($request->post("home_user_pwd2"));
      $home_user_pwd = $this->xss($request->post("home_user_pwd"));
      $home_user_pwd1 = $this->xss($request->post("home_user_pwd1"));
      if($home_user_pwd2!=$home_user_pwd1)
      {
         $this->error("两次密码不一致");
      }
      if($home_user_pwd1==""||$home_user_pwd=="")
      {
         $this->error("密码不能为空");
      }
      $home_user_id =  Cookie::get('home_user_id');
      $home = $db->select_id($home_user_id);

      if(md5($home_user_pwd)!=$home['home_user_pwd'])
      {
         $this->error("原密码错误");
      }
      $res= $db->update_pwd($home_user_id,array('home_user_pwd'=>md5($home_user_pwd1)));
      if($res)
      {
         $this->success("密码修改成功，重新登录","Login/login");
      }
      else
      {
         $this->error("密码修改失败");
      }

   }
   //获取ip
   public function get_ip()
   {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){ 

            $ip = getenv("HTTP_CLIENT_IP"); 

        }else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"),"unknown")){

            $ip = getenv("HTTP_X_FORWARDED_FOR");

        }else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){

            $ip = getenv("REMOTE_ADDR"); 
        
        }else if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){

            $ip = $_SERVER['REMOTE_ADDR']; 

        }else{ 
            $ip = "unknown";
        }

        return ($ip); 

   }
}
?>