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

namespace app\frontend\controller;

use app\common\basics\Frontend;
use app\common\utils\AjaxUtils;
use app\frontend\service\ArticleService;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 文章管理
 */
class ArticleController extends Frontend
{
    protected array $notNeedLogin = ['lists', 'detail'];

    /**
     * 文章列表
     *
     * @return View
     * @throws DbException
     * @author windy
     */
    public function lists(): View
    {
        $get = $this->request->get();
        return view('', [
            'category' => ArticleService::category(intval($get['cid']??0)),
            'lists'    => ArticleService::lists($get),
            'lately'   => ArticleService::recommend('lately', 8),
            'ranking'  => ArticleService::recommend('ranking', 8)
        ]);
    }

    /**
     * 文章详情
     *
     * @return View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function detail(): View
    {
        $id = intval($this->request->get('id'));
        return view('', [
            'detail'  => ArticleService::detail($id, $this->userId),
            'lately'  => ArticleService::recommend('lately', 8),
            'ranking' => ArticleService::recommend('ranking', 8)
        ]);
    }

    /**
     * 文章收藏
     *
     * @return Json
     * @author windy
     */
    public function collect(): Json
    {
        if ($this->isAjaxPost()) {
            $id = intval($this->request->post('id'));
            $result = ArticleService::collect($id, $this->userId);
            return AjaxUtils::success($result);
        }

        return AjaxUtils::error();
    }
}