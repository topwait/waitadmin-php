<?php

namespace app\api\controller;

use app\api\service\ArticleService;
use app\common\basics\Api;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;

class ArticleController extends Api
{
    protected array $notNeedLogin = ['category', 'lists'];

    /**
     * 文章分类
     *
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function category(): Json
    {
        $list = ArticleService::category();
        return AjaxUtils::success($list);
    }

    public function lists()
    {
        $list = ArticleService::lists();
        return AjaxUtils::success($list);
    }

    public function detail()
    {

    }
}