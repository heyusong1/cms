<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\CompanyModel;
use \think\Cookie; 
header("content-type:text/html;charset=UTF-8");
class Company  extends Controller
{
	public function __construct()
	{
		   $this->prevent_login();
	}
	public function show()
   {
      $db = new CompanyModel();
      $data = $db ->select();
      return view("home/company",['data'=>$data]);
   }
   public function update()
   {
      $request=Request::instance();
      $file = $request->file('company_logo');
       if (!empty($file)) { 
         $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'); 
          if ($info) { 
            $data['company_logo']     = $info->getSaveName();
          } else { 
            //上传失败获取错误信息 
            $this->error($file->getError()); 
          } 
       } 
      $db = new CompanyModel();
      $data['company_name']     = $this->xss($request->post("company_name"));
      $data['company_url']      = $this->xss($request->post("company_url"));
      $data['company_keyword']  = $this->xss($request->post("company_keyword"));
      $data['company_url_desc'] = $this->xss($request->post("company_url_desc"));
      $data['company_tel']      = $this->xss($request->post("company_tel"));
      $data['company_email']    = $this->xss($request->post("company_email"));
      $data['company_desc']     = $this->xss($request->post("company_desc"));
      $data['company_records']  = $this->xss($request->post("company_records"));
      $data['company_versions'] = $this->xss($request->post("company_versions"));
      $data['company_address']  = $this->xss($request->post("company_address"));
      $data['object_oriented']     = $this->xss($request->post("object_oriented"));
      foreach ($data as $key => $value) {
          if(empty($value))
          {
            $this->error("所有内容不能为空");
          }
      }
      
      $res  = $db -> update_company($data);
      if($res)
      {
         $this->success("修改成功");
      }
      else
      {
         $this->error("修改失败");
      }

      
   } public function achievement()
{
    $request=Request::instance();
    $db = new CompanyModel();
    $achievement_id     = $this->xss($request->get("achievement_id",""));
    if(!empty($achievement_id))
    {
        $db -> del_achievement($achievement_id);
    }

    $data = $db ->select_achievement();
    foreach ($data as $key => $value)
    {
        // echo $data[$key]['achievement_time'];die;
        $data[$key]['achievement_time']=date("Y-m-d",$data[$key]['achievement_time']);
    }
    return view("home/achievement",['data'=>$data]);
}
    public function add_achievement()
    {
        return view("home/add_achievement");
    }
    public function add_achievement_do()
    {
        $request=Request::instance();
        $data['achievement_time']     = $this->xss($request->post("achievement_time"));
        $data['achievement_desc']     = $this->xss($request->post("achievement_desc"));
        $db  = new CompanyModel();
        $data['achievement_time']     = strtotime($data['achievement_time']);
        foreach ($data as $key => $value) {
          if(empty($value))
          {
            $this->error("所有内容不能为空");
          }
      }
        $res = $db -> insert_achievement($data);
        if($res)
        {
            $this->success("添加成功");
        }
        else
        {
            $this->error("添加失败");
        }
    }
    public function team()
    {
        return view("home/team");
    }

}
?>