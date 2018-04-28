<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use app\admin\model\Template as TemplateModel;
class Template extends Controller
{
    public function index(){
        
        $data=TemplateModel::selectAllRow();
        // 调用obtainData函数，获取其他表的关联数据die;
        //$relationData=obtainListData('tsms_template');

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
       // print_r($data);
        return $this->fetch('Template/list',['data'=>$data]);
    }
    public function add(){
        // 调用obtainAddEditData函数，获取其他表中数据，做为模板中下拉列表框的值
        // 返回二维数组，需要在模板中分别读取处理二维数组中的元素，其中的每个一维数组是每个下拉列表框需要使用的数据
       // $data=obtainAddEditData('tsms_template');,['data'=>$data]
        return $this->fetch('Template/add');
    }
    public function add_ok(){
        $data=request()->param();
        if(TemplateModel::insertData($data)){
            $this->success('数据添加成功','Template/index');
        }
    }
    public function edit(){
        $id=request()->param('id');
        $data=TemplateModel::selectOneRow($id);
        //$relationData=obtainAddEditData('tsms_template');,'relationData'=>$relationData
        return $this->fetch('Template/edit',['data'=>$data]);
    }
    public function edit_ok(){
        $id=request()->param('id');
        $data=request()->param();
        if(TemplateModel::updateData($id,$data)){
            $this->success('数据编辑成功','Template/index');
        }
    }
    public function delete(){
        $id=request()->param('id');
        if(TemplateModel::deleteData($id)){
            $this->success('数据删除成功','Template/index');
        }
    }
}