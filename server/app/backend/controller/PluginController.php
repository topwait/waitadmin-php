<?php

namespace app\backend\controller;

use app\common\basics\Backend;

/**
 * 插件管理
 */
class PluginController extends Backend
{
    public function index()
    {
        return view();
    }

    public function apply()
    {
        $name = $this->request->get('name');
        return view('', [
            'url' => 'addons/curd/gen/index'
        ]);
    }
}