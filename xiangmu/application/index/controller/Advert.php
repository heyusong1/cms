<?php
namespace app\index\controller;

use app\index\model\Link;
use think\Request;
use think\Db;
use think\Controller;

class Advert extends Controller{
     function __construct(){
           $this->prevent_login();
     }

    public function add(){
        $file = request()->file('advert_img');
//        var_dump($file);die;
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $reuest=Request::instance();//调用Request中instance方法
            $advert_name=$this->xss($reuest->post("advert_name"));
            $advert_money=$this->xss($reuest->post("advert_money"));
            $advert_url=$this->xss($reuest->post("advert_url"));
            $advert_desc=$this->xss($reuest->post("advert_desc"));
            if($reuest==""||$advert_name==""||$advert_money==""||$advert_url==""||$advert_desc=="")
            {
                $this->error("所有内容不能为空");
            }
            $data = array(
                "advert_name"=>$advert_name,
                "advert_money"=>$advert_money,
                "advert_url" =>$advert_url,
                "advert_desc"=>$advert_desc,
                "advert_img"=>$info->getSaveName(),
            );
            $link = new link();
            $arr=$db->select_advertname($advert_name);
            if($arr)
            {
                $this->error("广告名称已存在");
            }
            $reg = $link->insert($data);
            if($reg){
                return $this->success('添加成功','Advert/show');
            }else{
                return $this->success('添加失败');
            }
        }else{
            $this->error("请添加图片");
        }
	}

	public function show(){
		$advert=new Link;//实例化model
        $arr=$advert->show();//执行查询
        // var_dump($arr);die;
        return view('home/advert_list',['arr'=>$arr]);
	}

	public function update(){
        $reuest=Request::instance();
        $advert_id=$this->xss($reuest->get('advert_id'));
        $advert=new Link;
        $res=$advert->findone($advert_id);
        return view('home/advert_update',['res'=>$res]);
    }

    public function save(){
        $reuest=Request::instance();
        $advert_id=$this->xss($reuest->post("advert_id"));
        $advert_name=$this->xss($reuest->post("advert_name"));
        $advert_money=$this->xss($reuest->post("advert_money"));        
        $advert_url=$this->xss($reuest->post("advert_url"));
        $advert_desc=$this->xss($reuest->post("advert_desc"));
        $advert_state=$this->xss($reuest->post("advert_state"));
        if($advert_id==""||$advert_name==""||$advert_money==""||$advert_url==""||$advert_desc==""||$advert_desc==''||$advert_state=="")
        {
            $this->error("所有内容不能为空");
        }
        $data = array(
        	"advert_id"    =>  $advert_id,
        	"advert_name"  =>  $advert_name,
            "advert_money"=>$advert_money,
        	"advert_url"   =>  $advert_url,
        	"advert_desc"  =>  $advert_desc,
        	"advert_state" =>  $advert_state,
            // "advert_img"=>$info->getSaveName(),
        );
        $advert=new Link;
        $res=$advert->xiugai($data,$advert_id);
        if($res){
            return $this->success('修改成功','Advert/show');
        }else{
            return $this->error('修改失败','Advert/show');
        }
    }

    public function delete(){
        $reuest=Request::instance();
        $advert_id=$this->xss($reuest->get('advert_id'));
        $advert=new Link;
        $res=$advert->del($advert_id);
        if($res){
            return $this->success('删除成功','Advert/show');
        }else{
            return $this->error('删除失败','Advert/show');
        }    	
    }

    public function search(){
        $reuest=Request::instance();
        $advert_name=$this->xss($reuest->post("advert_name"));
        $advert=new Link;
        $arr=$advert->arch($advert_name);
        if($arr){
            return view('home/advert_search',['arr'=>$arr,'advert_name'=>$advert_name]);
        }else{
            return $this->error('没有相关信息，请确认后重试','show');
        }
    }    
}