<?php

namespace app\common\service\security;

class SecurityEnum
{
    /**
     * 会话模式
     */
    public static string $ASYNC_MODEL = 'async';
    public static string $SESSION_MODEL = 'session';

    /**
     * 会话标记
     */
    public static string $API_SIGN = 'api';
    public static string $BACKEND_SIGN = 'backend';
    public static string $FRONTEND_SIGN = 'frontend';

    /**
     * 设备名称
     */
    public static string $WX_DEVICE = 'wx';
    public static string $OA_DEVICE = 'oa';
    public static string $H5_DEVICE = 'h5';
    public static string $AND_DEVICE = 'android';
    public static string $IOS_DEVICE = 'ios';

}