<?php
namespace app\index\model;

use think\Model;
use think\Db;
use think\paginate;

class reply extends Model
{
protected $table = 'leave';
public function select()
{
    return Db::table($this->table)->select();
}
    public function insert($data){
        return Db::table($this->table)->insert($data);
    }
    public function select_leave($leave_id)
    {
    	 return Db::table($this->table)->where("leave_id","=",$leave_id)->find();
    }
    public function select_ip($ip)
    {
    	return Db::table($this->table)->where("leave_ip","=",$ip)->select();
    }

}