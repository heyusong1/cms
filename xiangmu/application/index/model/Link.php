<?php
namespace app\index\model;

use think\Model;
use think\Db;

class Link extends Model
{
	protected $table='advert';
	//添加
	function insert($data){
		return Db('advert')->insert($data);
	}
	//展示
	function show(){
        return Db('advert')->select();
    }

    function img(){
        // return Db::query("select * from advert where advert_state = 1");
        return Db('advert')->where('advert_state=1')->select();
    }

    function projects(){
        return Db::query("select * from advert order by advert_money desc limit 0,6");        
    }

    function desc(){
        return Db::query("select * from advert order by advert_money desc limit 0,4");
        // return Db('advert')->where('advert_state=1')->select();
    }
    //修改
    function findone($advert_id){
        return Db('advert')->where('advert_id='.$advert_id)->find();
    }

    function xiugai($data,$advert_id){
        return Db('advert')->where('advert_id='.$advert_id)->update($data);
    }
    //删除
    function del($advert_id){
        return Db('advert')->delete($advert_id);
    }

    function arch($advert_name){
        return Db::query("select * from advert where `advert_name` like '%$advert_name%'");
    }    
    function select_advertname($advert_name)
    {
        return Db('advert')->where('advert_name',"=","$advert_name")->find();
    }
}