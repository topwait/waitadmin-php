<?php

namespace app\api\validate;

use app\common\basics\Validate;

/**
 * 文章参数验证器
 */
class ArticleValidate extends Validate
{
    protected $rule = [
        'cid'     => 'number',
        'keyword' => 'max:100'
    ];

    public function __construct()
    {
        $this->field = [
            'cid'      => '类目',
            'keyword'  => '关键词'
        ];

        parent::__construct();
    }
}