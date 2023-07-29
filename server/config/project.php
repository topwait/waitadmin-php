<?php
// +----------------------------------------------------------------------
// | 项目设置
// +----------------------------------------------------------------------

return [
    // 后台入口
    'backend_entrance' => env('project.backend', '/admin.php'),

    // 版本号
    'version'       => '1.2.3',

    // 上传器
    'uploader' => [
        'image'    => ['size'=>(10 * 1024 * 1024), 'ext'=>['png','jpg','jpeg','gif','ico','bmp']],
        'video'    => ['size'=>(30 * 1024 * 1024), 'ext'=>['mp4','mp3','avi','flv','rmvb','mov']],
        'package'  => ['size'=>(30 * 1024 * 1024), 'ext'=>['zip','rar','iso','7z','tar','gz','arj','bz2']],
        'document' => ['size'=>(30 * 1024 * 1024), 'ext'=>['txt','doc','docx','xls','xlsx','ppt','pptx','pdf','pem']]
    ]
];