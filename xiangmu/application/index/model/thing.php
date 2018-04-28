<?php
namespace app\index\model;

use think\Model;
use think\Db;
use think\paginate;

class thing extends Model
{
    protected $table='big_thing';
    public function insert($data)
    {
        return Db::table($this->table)->insert($data);
    }
    public function select()
    {
        return Db::table($this->table)->paginate(3);
    }
    public function pro_delete($id)
    {
        return Db::table($this->table)->where('thing_id','=',$id)->delete();
    }
}