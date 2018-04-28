<?php
namespace app\index\controller;
use app\index\model\thing;
use think\Controller;
use think\Request;
use app\index\model\CompanyModel;
use app\index\model\proje;
use app\index\model\Link;
use app\index\model\TeamModel;
use app\index\model\Cons;
use app\index\model\reply;

use \think\Cookie;
use app\index\model\friend;
header("content-type:textml;charset=UTF-8");
class Index extends Controller
{
    public function index()
    {
        $advert=new Link;//实例化model
        $img=$advert->img();//执行查询
        $desc=$advert->desc();
        $projects=$advert->projects();
        $dba=new friend();
        $arr=$dba->select_link();
        $db = new CompanyModel();
      $data = $db ->select();
      return view("admin/index",['data'=>$data,'arr'=>$arr,'img'=>$img,'desc'=>$desc,'projects'=>$projects]);
    }
    public function top()
    {

        $db = new CompanyModel();
        $data    = $db ->select();
        return view("admin/top",['data'=>$data]);
    }

    public function about()
    {
        $dba=new friend();
        $arr=$dba->select_link();
        $db = new CompanyModel();
        $achievement=$db->select_achievement();
        foreach ($achievement as $key => $value)
        {
            // echo $data[$key]['achievement_time'];die;
            $achievement[$key]['achievement_time']=date("Y-m-d",$achievement[$key]['achievement_time']);
        }
        $dbb= new TeamModel();
        $team = $dbb->show_position();
        $projects = $db->select_team();
        $data = $db ->select();
        return view("admin/about",['data'=>$data,'arr'=>$arr,'achievement'=>$achievement,'team'=>$team,'projects'=>$projects]);
    }

    public function services()
    {
        $dba=new friend();
        $arr=$dba->select_link();
        $db = new CompanyModel();
        $data = $db ->select();
        $projects = $db->select_team();
        return view("admin/services",['data'=>$data,'arr'=>$arr,'projects'=>$projects]);
    }

    public function price()
    {
        $dba=new friend();
        $arr=$dba->select_link();
        $db = new CompanyModel();
        $data = $db ->select();
        $dbc=new Cons();
        $daa=$dbc->select_a();
        $add=$dbc->select();
        return view("admin/price",[ 'daa'=>$daa,'data'=>$data,'arr'=>$arr,'add'=>$add]);
    }
    public function projects()
    {
        $dba=new friend();
        $arr=$dba->select_link();
        $db = new CompanyModel();
        $data = $db ->select();
        $dbb = new proje();
        $res = $dbb->select();
        $dbs=new thing();
        $reg=$dbs->select();
        return view("admin/projects",['data'=>$data, 'reg'=>$reg,'arr'=>$arr,'res'=>$res]);
    }
    public function contact()
    {
        $dba=new friend();
        $arr=$dba->select_link();
        $db = new CompanyModel();
        $data = $db ->select();
        return view("admin/contact",['data'=>$data,'arr'=>$arr]);
    }
    public function login()
    {
        return view("admin/login");
    }
    public function show_video()
    {
        $request = Request::instance();
        $dbs = new Cons();
        $db = new CompanyModel();
        $data = $db ->select();
        $product_id=$request->get('product_id');
        $app=$dbs->select_uu($product_id);
//    var_dump($app);die;
        return view("admin/price_show",['app'=>$app,'data'=>$data]);
    }
    public function contact_a()
    {
        $request=Request::instance();
        $data['leave_name']=$this->xss($request->post("leave_name"));
        $data['leave_desc']=$this->xss($request->post("leave_desc"));
        $data['leave_email']=$this->xss($request->post("leave_email"));
        $data['leave_time']=time();
        $data['leave_ip']=$this->get_ip();
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$data['leave_email'])) {
            $this->error("请输入正确的邮箱");
        }
        $db= new reply();
        $arr = $db->select_ip($data['leave_ip']);
        if(!empty($arr))
        {
            $data['leave_time']=intval($data['leave_time']);
            // echo $data['leave_time'];
            // echo "<br>";
            $arr[count($arr)-1]['leave_time']=intval($arr[count($arr)-1]['leave_time']);
            // echo $arr[count($arr)-1]['leave_time'];
            // echo "<br>";
            $cha = $data['leave_time']-$arr[count($arr)-1]['leave_time'];

            // echo $cha;
            $ying = 60;
            // echo "<br>";
            if($cha<$ying)
            {

                $this->error("一分钟只能留言一次");
            }
        }

        foreach ($data as $key => $value) {
            if(empty($value))
            {
                $this->error("所有内容不能为空");
            }
        }
        $db= new reply();
        $res=$db->insert($data);
        if ($res)
        {
            $this->success("添加成功！","Index/index");
        }
        else
        {
            $this->error("添加失败","Index/contact");
        }
        return view("admin/contact");
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
