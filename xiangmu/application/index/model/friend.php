<?php
namespace app\index\model;

use think\Model;
use think\Db;
use think\paginate;

class friend extends Model
{
    protected $table = 'friend_link';

    public function insert($data){
        return Db::table($this->table)->insert($data);
    }
    public function select()
    {
        return Db::table($this->table)->paginate(5);
    }
    public function deleteData($link_id)
    {
        return Db::table($this->table)->where('link_id','=',$link_id)->delete();
    }
    public function updateData($data,$link_id)
    {
        return Db::table($this->table)->where('link_id','=',$link_id)->update($data);
    }
    public function delAll($link_id)
    {
        return Db::table($this->table)->where('link_id','in',($link_id))->delete();
    }
    public function select_link()
    {
       return Db::table($this->table)->order('link_order desc')->limit(1)->select();
    } 
    public function select_linkname($link_name)
    {
        return Db::table($this->table)->where("link_name","=","$link_name")->find();
    }


}