<?php
namespace app\admin\model;

use think\Model;

class Usersize extends Model
{
    protected $table='tsms_usersize';
    public static function insertData($data){
        $usersize=new Usersize($data);
        return $usersize->save();
    }
    public static function deleteData($id){
        return self::destroy($id);
    }
    public static function updateData($id,$data){
        $usersize=new Usersize();
        return $usersize->save($data,['id'=>$id]);
    }
    public static function selectAllRow(){
        return self::all();
       // return self::select()->toArray();
    }
    public static function selectOneRow($id){
        return self::get(['id'=>$id]);
    }
   
}