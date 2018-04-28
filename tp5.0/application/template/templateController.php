<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\CampusArea as CampusAreaModel;
class CampusArea extends Controller
{
    public function index(){
        $data=CampusAreaModel::selectAllRow();
        // 调用obtainData函数，获取其他表的关联数据
        //$relationData=obtainListData('tsms_campusArea');
        // 将关联数据合并到$data数组中
          // 如未定义$relationData（说明未返回$relationData），则不做合并处理
        if(isset($relationData)){
            foreach($data as $k1=>$v1){
                foreach($relationData as $k2=>$v2){
                    foreach($v2 as $k3=>$v3)
                        if($v1["$k2".'_id']==$v3['id']){
                            $data[$k1]["$k2"]=$v3['name'];
                        }
                }
            }
        }
        return $this->fetch('CampusArea/list',['data'=>$data]);
    }
    public function add(){
        // 调用obtainAddEditData函数，获取其他表中数据，做为模板中下拉列表框的值
        // 返回二维数组，需要在模板中分别读取处理二维数组中的元素，其中的每个一维数组是每个下拉列表框需要使用的数据
       // $data=obtainAddEditData('tsms_campusArea');,['data'=>$data]
        return $this->fetch('CampusArea/add');
    }
    public function add_ok(){
        $data=request()->param();
        if(CampusAreaModel::insertData($data)){
            $this->success('数据添加成功','CampusArea/index');
        }
    }
    public function edit(){
        $id=request()->param('id');
        $data=CampusAreaModel::selectOneRow($id);
       // $relationData=obtainAddEditData('tsms_campusArea');,'relationData'=>$relationData
        return $this->fetch('CampusArea/edit',['data'=>$data]);
    }
    public function edit_ok(){
        $id=request()->param('id');
        $data=request()->param();
        if(CampusAreaModel::updateData($id,$data)){
            $this->success('数据编辑成功','CampusArea/index');
        }
    }
    public function delete(){
        $id=request()->param('id');
        if(CampusAreaModel::deleteData($id)){
            $this->success('数据删除成功','CampusArea/index');
        }
    }
}