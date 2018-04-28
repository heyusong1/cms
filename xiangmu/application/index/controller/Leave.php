<?php
namespace app\index\controller;

use app\index\model\reply;
use think\Controller;
use think\Request;
use phpmailer\phpmailer; 
use app\index\model\friend;   
class leave extends Controller
{
    public function __construct(){
        $this->prevent_login();
      }
    public function main_message()
    {
        $request=Request::instance();
        $db=new reply();
       
        $page=$this->xss($request->get("page","1"));
        $size=4;
         $data=$db->select();
//        var_dump($data);die;
        
        foreach ($data as $key => $value) {
            $data[$key]['leave_time']=date("Y-m-d H:i:s",$data[$key]['leave_time']);
        }
        $data=$this->fenye($data,$page,$size);  
        return view("home/main_message",['data'=>$data]);
    }
    public function fenye($data,$page,$size)
   {
      
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
    public function message_replay()
    {
        $db=new reply();
        $reg=$db->select();
//        var_dump($data);die;
        return view("home/message_replay",['reg'=>$reg]);
    }
    public function message_info()
    {
        $request=Request::instance();
        $leave_id=$this->xss($request->get("leave_id"));
        $db=new reply();
        $list=$db->select_leave($leave_id);
//        var_dump($data);die;
        
        $list['leave_time']=date("Y-m-d H:i:s",$list['leave_time']);
        
        return view("home/message_info",['data'=>$list]);
    }
    
    public function replyUser()
    {

        $request = Request::instance();
        $leave_email = $this->xss($request->post("leave_email"));
        $leave_reply = $this->xss($request->post("leave_reply"));

        $mail = new PHPMailer();
        // 使用SMTP方式发送
        $mail->IsSMTP();
        // 设置邮件的字符编码
        $mail->CharSet='UTF-8';
        // 企业邮局域名
        $mail->Host = 'smtp.qq.com';
        //---------qq邮箱需要的------//设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';
        //设置ssl连接smtp服务器的远程服务器端口号 可选465或587
        $mail->Port = 465;//---------qq邮箱需要的------
        // 启用SMTP验证功能
        $mail->SMTPAuth = true;
        //邮件发送人的用户名(请填写完整的email地址)
        $mail->Username = '1600951876@qq.com' ;
        // 邮件发送人的 密码 （授权码）
        $mail->Password = 'ygpbkakopgwijijj';  //修改为自己的授权码 
        //邮件发送者email地址
        $mail->From ='1600951876@qq.com';
        //发送邮件人的标题
        $mail->FromName ='1600951876@qq.com';
        //收件人的邮箱 给谁发邮件
        $email_addr = $leave_email;
        //收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
        $mail->AddAddress("$email_addr", substr(  $email_addr, 0 , strpos($email_addr ,'@')));
        //回复的地址
        $mail->AddReplyTo('1600951876@qq.com' , "" );
        //$mail->AddAttachment("./mail.rar"); // 添加附件
        // set email format to HTML //是否使用HTML格式
        $mail->IsHTML(true);
        //邮件标题
        $mail->Subject = '极创设计给您回复了';
        //邮件内容
        $mail->Body =  $leave_reply;
        if( !$mail->Send() ){    
            $mail_return_arr['mark'] = false ;    
            $str  =  "邮件发送失败. <p>";    
            $str .= "错误原因: " . $mail->ErrorInfo;    
            $mail_return_arr['info'] = $str ;
        }else{    
            $mail_return_arr['mark'] = true ;    
            $str =  "邮件发送成功";    
            $mail_return_arr['info'] = $str ;
        }
        if ($mail_return_arr['mark'] == 1) {
            $this->success('邮件发送成功','Leave/main_message');
        }else{
            return json(['state'=>0,"message"=>"邮件发送失败"]);
        }

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