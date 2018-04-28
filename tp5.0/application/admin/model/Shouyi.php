<?php
namespace app\admin\model;

use think\Model;

class Shouyi extends Model
{
    protected $table='shouyi';
    public static function insertData($data){
        $shouyi=new Shouyi($data);
        return $shouyi->save();
    }
    public static function deleteData($id){
        return self::destroy($id);
    }
    public static function updateData($id,$data){
        $shouyi=new Shouyi();
        return $shouyi->save($data,['id'=>$id]);
    }
    public static function selectAllRow(){
        return self::all();
       // return self::select()->toArray();
    }
    public static function selectOneRow($id){
        return self::get(['id'=>$id]);
    }
   
}