<?php
namespace app\index\model;
use think\Model;
use \Think\Db;
class CompanyModel extends Model
{
	protected $table='company';
	public function select()
	{
		return Db::table($this->table)->where("company_id","=","1")->find();
	}
	public function update_company($data)
	{
		return Db::table($this->table)->where("company_id","=","1")->update($data);
	}
    public function select_achievement()
    {
        return Db::table("achievement")->select();
    }
    public function insert_achievement($data)
    {
        return Db::table("achievement")->insert($data);
    }
    public function del_achievement($achievement_id)
    {
        return Db::table("achievement")->where("achievement_id","=",$achievement_id)->delete();
    }
    public function select_team()
    {
        return Db::table("boss_team")->select();
    }

}

?>	