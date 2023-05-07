<?php
// +----------------------------------------------------------------------
// | 项目设置
// +----------------------------------------------------------------------

return [
    // 版本号
    'version'       => '1.1.4',

    // 上传器
    'uploader' => [
        'image'    => ['size'=>10485760, 'ext'=>['png','jpg','jpeg','gif','ico','bmp']],
        'video'    => ['size'=>31457280, 'ext'=>['mp4','mp3','avi','flv','rmvb','mov']],
        'package'  => ['size'=>31457280, 'ext'=>['zip','rar','iso','7z','tar','gz','arj','bz2']],
        'document' => ['size'=>31457280, 'ext'=>['txt','doc','docx','xls','xlsx','ppt','pptx','pdf','pem']]
    ]

];