<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\admin\controller\Auth;
class Generate extends controller
{
    // 输入表名的表单
    public function openForm()
    {
        return $this->fetch('generate/form');
    }
    // 生成全部文件（控制器、模型、add、edit、list）
    public function generateAll()
    {
        // 源（模板）文件 路径
        $sourcePath='../application/template/';
        // 目标文件 路径
        $targetPath='../application/admin/';
        // 接收表名
        $tablename=input('get.name');     // 使用助手函数input接收以get方式传递的变量，表的全名
        $shortname=str_replace('oson','',$tablename);   //去除表名前缀tsms_
        $tables=Db::query('show tables from oson');
        // 判断文件是否存在
       if(file_exists($targetPath.'view/'.$shortname.'/add.html')){
           echo "<script>alert('文件已存在，不能再次生成，请联系管理员')</script>";
           return;
       }
        // 判断输入的表名是否存在于数据库中
        $flag=0;
        foreach($tables as $value){
            if(in_array(strtolower($tablename),$value)){
                $flag=1;
                break;
            }
        }
        if(!$flag){
            echo "<script>alert('您输入的表名与数据库中存在的表名不匹配，请查验后重新输入！');</script>";
            return;
            //$this->redirect('admin/generate/openform');
        }
        // 控制器名称
        $cName=ucfirst($shortname);
        // 模型名称
        $mName=ucfirst($shortname);
        // 模型别名
        $mNameAlias=ucfirst($shortname).'Model';
        // 1 getTableInfo方法：获取表中所有字段名称(fields键)、字段类型(type键)、主键(pk键)，返回二维数组，
        // 第1个一维数组是每个元素的字段名称集合，
        // 第2个一维数组是每个元素的字段类型集合
        $tableStructure=DB::getTableInfo($tablename);
        foreach($tableStructure as $key=>$value){
            $tableStructure;
        }
        // 2 query方法执行原生SQL语句，查询表结构： 返回二维数组，其中每个一维数组是一个字段的相关信息，获取表中字段用于构建add.html、edit.html、list.html文件
        $tableStructure=DB::query("SHOW FULL COLUMNS FROM ".$tablename);
        //dump($tableStructure);return;
        //echo '<pre>';print_r($tableStructure);return;

        // 1 -----------生成控制器文件
        // 获取源控制器文件中代码
        //Controller
        $cStr=file_get_contents($sourcePath.'templateController.php');
        $cStr=str_replace('CampusArea',$cName,$cStr);                  // 替换类名
        $cStr=str_replace('CampuseAreaModel',$mNameAlias,$cStr);      // 替换引入的模型文件别名
        $cStr=str_replace('tsms_campusArea',$tablename,$cStr);        // 替换add方法中使用的表名
        // 生成新控制器文件
        file_put_contents($targetPath.'controller/'.$cName.'.php',$cStr);
        // 2 ---------------生成模型文件
        $mStr=file_get_contents($sourcePath.'templateModel.php');
        $mStr=str_replace('CampusArea',$mName,$mStr);                  // 替换类名
        $mStr=str_replace('tsms_campusearea',''.strtolower($shortname),$mStr);  //  替换表名
        $mStr=str_replace('$campusArea','$'.$shortname,$mStr);
        //return $mStr;
        file_put_contents($targetPath.'model/'.$mName.'.php',$mStr);
        // 3 --------------------------生成add文件
        ob_start();
        include $sourcePath.'add.html';
        $addStr=ob_get_contents();
        $addFolder=$targetPath.'view/'.$shortname.'/';    // 存放新add.html文件的文件夹
        //return $addFolder;
        if(!is_dir($addFolder)){
            mkdir($addFolder,0777,true);
        }
        file_put_contents($addFolder.'add.html',$addStr);
        ob_clean();     // 清空缓冲区
        // 4 ----------------------------------------生成edit文件
        include $sourcePath.'edit.html';
        $editStr=ob_get_contents();
        $editFolder=$targetPath.'view/'.$shortname.'/';
        if(!is_dir($editFolder)){
            mkdir($editFolder,0777,true);
        }
        file_put_contents($editFolder.'edit.html',$editStr);
        ob_clean();
        // 5 ------------------------生成list文件
        include $sourcePath.'list.html';
        $listStr=ob_get_contents();
        $listFolder=$targetPath.'view/'.$shortname.'/';
        if(!is_dir($listFolder)){
            mkdir($listFolder,0777,true);
        }
        file_put_contents($editFolder.'list.html',$listStr);
        ob_end_clean();     // 清除缓冲区内容后关闭缓冲区
        // echo $addStr;
       // return $cStr;
        echo '生成成功';
    }


}
