<?php
namespace app\index\model;
use think\Model;
use \Think\Db;
class TeamModel extends Model
{
	public function show_department()
	{
		return Db::table("department")->select();
	}
	public function show_position()
	{
		return Db::table("position")->alias('p')->join('department d','d.department_id = p.department_id')->select();
	}
	public function del_department($department_id)
	{
		return Db::table("department")->where("department_id","=",$department_id)->delete();
	}
	public function del_position($position_id)
	{
		return Db::table("position")->where("position_id","=",$position_id)->delete();
	}
	public function insert_department($data)
	{
		return Db::table("department")->insert($data);
	}
	public function insert_position($data)
	{
		return Db::table("position")->insert($data);
	}
	public function select_departmentname($department_name)
	{
		return Db::table("department")->where("department_name","=","$department_name")->find();
	}
	public function select_positionname($position_name)
	{
		return Db::table("position")->where("position_name","=","$position_name")->find();
	}
}

?>	