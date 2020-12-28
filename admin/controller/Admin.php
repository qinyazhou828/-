<?php

namespace app\admin\controller;

use app\admin\validate\User;
use think\Controller;
use think\Db;
use think\Request;

class Admin extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function list()
    {
        //
        $arr = Db::table('system')->order('sort','desc')->paginate(1);
        return view('list',['arr'=>$arr]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return view('create');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //验证器
        $data = [
            'name'  => $request->param('name'),
            'type' => $request->param('type'),
            'face' => $request->param('face'),
            'price' => $request->param('price'),
        ];

        $validate = new User();

        if (!$validate->check($data)) {
            return dump($validate->getError());
        }
        $arr = [
            'name'=>$request->param('name'),
            'type'=>$request->param('type'),
            'face'=>$request->param('face'),
            'price'=>$request->param('price'),
            'time'=>$request->param('time'),
            'sort'=>$request->param('sort'),
            'create_time'=> date('Y-m-d H:i:s',time()),
        ];
        //添加
        $as = Db::name('system')->insert($arr);
        if ($as){
            return $this->redirect('admin/list');
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read(Request $request)
    {
        //搜索
            $whether = $request->param('whether');
            $type = $request->param('type');
            $name = $request->param('name');
        $arr = Db::name('system')->where('whether',$whether)
            ->where('type',$type)->where('name',"like","%$name%")->paginate(1);
        if ($arr){
            return $this->redirect('admin/list');
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
        //删除前查找
        $arr = Db::name('system')->where('id',$id)->find();
        if ($arr['whether'] == 1){
            $as = Db::name('system')->where('id',$id)->delete();
            if ($as){
                return $this->redirect('admin/list');
            }
        }
    }
}
