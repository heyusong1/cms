<?php
namespace app\index\model;
use think\Model;
use \Think\Db;
class User extends Model
{
	protected $table='home_user';
	public function select_name($where)
	{
		$data = DB::table($this->table)->where("home_user_name","=","$where")->find();
		return $data;
	}
	public function error_update($id,$data)
	{
		$data = DB::table($this->table)->where("home_user_id","=",$id)->update($data);
	}
	public function select_id($where)
	{
		$data = DB::table($this->table)->where("home_user_id","=","$where")->find();
		return $data;
	}
	public function insert($data)
	{
		return Db::table("user_login")->insert($data);
	}
	public function select_id_all($where)
	{
		$data = DB::table("user_login")->where("home_user_id","=","$where")->select();
		return $data;
	}
	public function select_all($where)
	{
		$data = DB::table($this->table)->where("home_user_name","like","%$where%")->select();
		return $data;
	}
	public function last()
	{
		$data = DB::table("user_login")->select();
		return $data;
	}
	public function del_user($home_user_id)
	{
		Db::table($this->table)->where('home_user_id','=',$home_user_id)->delete();    
	}
	public function add_user($data)
	{
		return Db::table($this->table)->insertGetId($data);
	}
	public function update_pwd($where,$data)
	{
		return Db::table($this->table)->where("home_user_id","=","$where")->update($data);
	}
	public function selece_username($home_user_name)
	{
		return Db::table($this->table)->where("home_user_name","=","$home_user_name")->find();
	}
}

?>	