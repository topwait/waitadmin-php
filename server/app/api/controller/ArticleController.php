<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/WaitAdmin
// | github:  https://github.com/topwait/waitadmin
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\ArticleService;
use app\api\validate\ArticleValidate;
use app\api\validate\IDMustValidate;
use app\common\basics\Api;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;

/**
 * 文章管理
 */
class ArticleController extends Api
{
    protected array $notNeedLogin = ['category', 'lists', 'detail'];

    /**
     * 文章分类
     *
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @method [GET]
     * @author windy
     */
    public function category(): Json
    {
        $list = ArticleService::category();
        return AjaxUtils::success($list);
    }

    /**
     * 文章列表
     *
     * @return Json
     * @throws DbException
     * @method [GET]
     * @author windy
     */
    public function lists(): Json
    {
        (new ArticleValidate())->goCheck();

        $list = ArticleService::lists($this->request->get());
        return AjaxUtils::success($list);
    }

    /**
     * 文章详情
     *
     * @return Json
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @method [GET]
     * @author windy
     */
    public function detail(): Json
    {
        (new IDMustValidate())->goCheck();

        $id = intval($this->request->get('id'));
        $detail = ArticleService::detail($id);
        return AjaxUtils::success($detail);
    }
}