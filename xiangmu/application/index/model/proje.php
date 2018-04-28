<?php
namespace app\index\model;

use think\Model;
use think\Db;
use think\paginate;

class proje extends Model
{
    protected $table = 'boss_team';

    public function insert($data)
    {
        return Db::table($this->table)->insert($data);
    }
    public function select()
    {
        return Db::table($this->table)->select();
    }
    public function select_teamname($boss_team)
    {
    	return Db::table($this->table)->where("team_name","=","$boss_team")->find();
    }
}