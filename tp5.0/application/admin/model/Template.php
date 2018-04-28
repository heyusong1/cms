<?php
namespace app\admin\model;

use think\Model;

class Template extends Model
{
    protected $table='oson';
    public static function insertData($data){
        $template=new Template($data);
        return $template->save();
    }
    public static function deleteData($id){
        return self::destroy($id);
    }
    public static function updateData($id,$data){
        $template=new Template();
        return $template->save($data,['id'=>$id]);
    }
    public static function selectAllRow(){
        return self::all();
       // return self::select()->toArray();
    }
    public static function selectOneRow($id){
        return self::get(['id'=>$id]);
    }
}