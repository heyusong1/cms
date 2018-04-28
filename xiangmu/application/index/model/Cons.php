<?php
namespace app\index\model;

use think\Model;
use think\Db;
use think\paginate;

class Cons extends Model
{
    protected $table='company_product';

    public function insert($data)
    {
        return Db::table($this->table)->insert($data);
    }
    public function select()
    {
        return Db::table($this->table)->select();
    }
    public function select_video()
    {
        return Db::table($this->table)->select();
    }
    public function delete_a($id)
    {
        return Db::table($this->table)->where('product_id','=',$id)->delete();
    }
    public function update_a($data,$product_id)
    {
        return Db::table($this->table)->where('product_id','=',$product_id)->update($data);
    }
    public function select_a()
    {
        return Db::query("select * from company_product order by product_id desc limit 0,4");
    }
    public function insert_video($data,$where)
    {
        return Db::table($this->table)->where('product_id',"=",$where)->update($data);
    }
    public function select_uu($where)
    {
        return Db::table($this->table)->where('product_id','=',$where)->find();
    }
    public function select_productname($product_name)
    {
        return Db::table($this->table)->where("product_name","=","$product_name")->find();
    }

}