<?php
namespace app\admin\model;

use think\Model;

class CampusArea extends Model
{
    protected $table='tsms_campusearea';
    public static function insertData($data){
        $campusArea=new CampusArea($data);
        return $campusArea->save();
    }
    public static function deleteData($id){
        return self::destroy($id);
    }
    public static function updateData($id,$data){
        $campusArea=new CampusArea();
        return $campusArea->save($data,['id'=>$id]);
    }
    public static function selectAllRow(){
        return self::all();
       // return self::select()->toArray();
    }
    public static function selectOneRow($id){
        return self::get(['id'=>$id]);
    }
   
}