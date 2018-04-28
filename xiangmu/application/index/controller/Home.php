<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\User;
use \think\Cookie; 
class Home extends Controller
{
   	public function __construct(){
        $this->prevent_login();
      }
      public function main()
      {
         $home_user_id = Cookie::get('home_user_id');
         $db = new User();
         $data = $db -> select_id($home_user_id);
         $arr  = $db -> select_id_all($home_user_id);     
         $data['num'] = count($arr);
         
         if($data['num']==1)
         {
            $data['last_time']=$arr[count($arr)-1]['last_time'];
            $data['last_time']=date("Y-m-d H:i:s",$data['last_time']);               
            $data['ip']=$arr[count($arr)-1]['ip']; 
         }
         else
         {
            $data['last_time']=$arr[count($arr)-2]['last_time'];
            $data['last_time']=date("Y-m-d H:i:s",$data['last_time']);
            $data['ip']=$arr[count($arr)-2]['ip']; 
         }
          
         return view("home/main",['data'=>$data]);
      }

   	public function main_list()
   	{
   		return view("home/main_list");
   	}

   	public function main_info()
   	{
   		return view("home/main_info");
   	}

   	public function main_message()
   	{
   		return view("home/main_message");
   	}

   	public function main_menu()
   	{
   		return view("home/main_menu");
   	}
   	public function index()
   	{
   		return view("home/index");
   	}
   	public function left()
   	{
   		return view("home/left");
   	}
   	public function top()
   	{
   		return view("home/top");
   	}
   	public function swich()
   	{
   		return view("home/swich");
   	}

   	public function bottom()
   	{
   		return view("home/bottom");
   	}
      public function message_info()
      {
         return view("home/message_info");
      }
      public function message_replay()
      {
         return view("home/message_replay");
      }
    public function advert_add(){
        return view("home/advert_add");
    }

    public function advert_show(){
        return view("advert/advert_list");
    }

}
