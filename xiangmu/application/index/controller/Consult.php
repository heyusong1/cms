<?php
namespace app\index\controller;
use app\index\model\Cons;
use think\Controller;
use think\Request;
header("content-type:text/html;charset=UTF-8");
class Consult  extends Controller
{
    public function __construct(){
        $this->prevent_login();
      }
    public function insert()
    {
        return view('home/consult_insert');
    }
    public function insert_add()
    {
        $db=new Cons();
        $request=Request::instance();
        $data['product_name']=$this->xss($request->post("product_name"));
        $data['product_style']=$this->xss($request->post("product_style"));
        $data['product_cost']=$this->xss($request->post("product_cost"));
        $data['product_stylist']=$this->xss($request->post("product_stylist"));
    

     foreach ($data as $key => $value) {
          if(empty($value))
          {
            $this->error("所有内容不能为空");
          }
      }
        $file = request()->file('product_img');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息
            $data['product_img']= $info->getSaveName();
         }
         else
             {
            // 上传失败获取错误信息
            echo $file->getError();
        }



        $db= new  Cons();
        $arr = $db->select_productname($data['product_name']);
        if($arr)
        {
            $this->error("该商品名称已经存在");
        }
        $res=$db->insert($data);
        if ($res)
        {
            $this->success("添加成功！","Consult/show");
        }
        else
        {
            $this->error("添加失败","Home/consult_insert");
        }
    }

    public function show()
    {
        $db=new Cons();
        $data=$db->select();
        return view("home/consult_show_video",['data'=>$data]);
    }
    public function delete_add()
    {
        $Request=Request::instance();
        $id=$this->xss($Request->get('product_id'));
        $friend=new Cons();
        $data=$friend->delete_a($id);
        if($data)
        {
            $this->success("删除成功！","Consult/show");
        }
        else
        {
            $this->error("删除失败！","Consult/show");
        }
    }
    public function update_add()
    {
        $Request=Request::instance();
        $product_id=$this->xss($Request->get('product_id'));
        // var_dump($link_id);die;
        $cons=new Cons();
        $res=$cons->find($product_id);
        return view('home/consult_update',['res'=>$res]);
    }
    public function product_manage()
    {
        $Request=Request::instance();
        $product_id=$this->xss($Request->post('product_id'));
        $Request=Request::instance();
        $data=$Request->post();
        foreach ($data as $key => $value) {
          if(empty($value))
          {
            $this->error("所有内容不能为空");
          }
      }
        $file = request()->file('product_img');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息
            $data['product_img']= $info->getSaveName();
        }
        else
        {
            // 上传失败获取错误信息
            echo $file->getError();
        }
        $friend=new Cons();
        $reg=$friend->update_a($data,$product_id);
        if ($reg)
        {
            $this->success("修改成功","Consult/show");
        }
        else
        {
            $this->error("修改失败","Consult/show");
        }
        return view("home/link_manage");
    }
    public function show_video()
    {
      $db=new Cons();
      $add=$db->select_video();
    return view("home/consult_show",['add'=>$add]);
    }
    public function insert_video()
    {
//        print_r($_FILES["file"]);die;
        $Request=Request::instance();
        $product_id=$this->xss($Request->post("product_id"));
        $myfile =$_FILES["product_video"];
//        var_dump($myfile);die;
        $tmp=$myfile['tmp_name'];
        $a='uploads/'.time().'.mp4';
        $path=ROOT_PATH .'public/'.$a ;

        $data['file']=$a;
        if(!move_uploaded_file($tmp,$path)) die('视频上传失败');
        $db = new Cons();
        $num = $db->insert_video($data,$product_id);
//        $num= \think\Db::table('sg_fruits')->insert($data);
        if($num){
            $this->success("添加成功","Consult/show");

        }
    }
    public function show_index()
    {
        $db=new Cons();
        $app=$db->select_ll();
        return view("home/price",['app '=>$app]);
    }

}