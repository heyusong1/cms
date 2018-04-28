<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\User;
use \think\Cookie; 
header("content-type:text/html;charset=UTF-8");
class Login  extends Controller
{
	//展示模板
   public function login()
   {
   		//返回模板
         // echo ＄＿ＳＥＲＶＥＲ［＇ＲＥＭＯＤＥ＿ＡＲＲＲ＇］;die;

        return view("home/login");
   }
   //接收数据判断用户登录
   public function login_do()
   {
   		// Cookie::set('error_num',3);
   		// echo Cookie::get('error_num');die;
   		//接值
   		$request=Request::instance();
   		$home_user_name = $this->xss($request->post("home_user_name"));
   		$home_user_pwd  = $this->xss($request->post("home_user_pwd"));
   		// echo $home_user_name;die;
   		//实例化user
   		$db   = new User();
   		$data = $db->select_name("$home_user_name");
   		if($data)
   		{
   			if($data['home_user_pwd']!=md5($home_user_pwd))
   			{
   				if($data['home_error_time']==3)
   				{
   					$time=time();
   					if($time>$data['home_user_time']+60)
   					{
   						$db->error_update($data['home_user_id'],array('home_error_time'=>1,'home_user_time'=>time()));
   						$this->error('密码错误，错误三次后账户将被冻结');
   					}
   					else
   					{
   						$this->error('此账户仍在冻结中，请稍后重试');
   					}
   				}
   				else
   				{
   					$home_error_time=$data["home_error_time"]+1;
   					if($home_error_time==3)
   					{
   						$db->error_update($data['home_user_id'],array('home_error_time'=>3,'home_user_time'=>time()));
   						$this->error('密码3次输入错误，此账户被冻结一分钟');
   					}
   					else
   					{
   						$db->error_update($data['home_user_id'],array('home_error_time'=>$home_error_time));
   						$this->error('密码错误，错误三次后账户将被冻结');
   					}
   				}
   			 	
   			}
   			else
   			{
   				$time=time();
				if($time>$data['home_user_time']+60)
				{
					$db->error_update($data['home_user_id'],array('home_error_time'=>0));
					Cookie::set('home_user_id',$data["home_user_id"]);
					Cookie::set('home_user_name',$data["home_user_name"]);
               $arr['ip'] = $this->get_ip();
               $arr['last_time'] = time();
               $arr['home_user_id'] = $data['home_user_id'];
               $res = $db -> insert($arr);
   				$this->success("登录成功","Home/index");
				}
				else
				{
					$this->error('此账户仍在冻结中，请稍后重试');
				}
   				
   			}
   		}
   		else
   		{
   			 $this->error('用户名不存在');
   		}

   }
   public function del()
   {
       Cookie::set('home_user_id',"");
//       $this->success("退出登录","Login/login");
//       setcookie("home_user_id", "", time() - 3600);
       $this->success("退出登录","Login/login");
   }


   
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
