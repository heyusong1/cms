<?php
namespace app\index\controller;
use  think\Db;
class Index
{
    public function index()
    {

        return '系统建设中 ... <a href="'.url('admin/index/index').'">进入后台</a>';

    }
}
