export default {
    SUCCESS: 0,                // 成功
    SYSTEM_ERROR: 5000,        // 系统错误异常
    PARAMS_ERROR: 5100,        // 请求参数错误
    METHOD_ERROR: 5200,        // 方法名不存在
    CONTROl_ERROR: 5300,       // 控制器不存在
    REQUEST_ERROR: 5500,       // 请求异常
    OPERATE_ERROR: 5600,       // 操作错误
    UPLOADS_ERROR: 5700,       // 上传错误
    PURVIEW_ERROR: 5800,       // 权限不足,

    LOGIN_EMPTY_ERROR: 8000,   // 登录令牌空值
    LOGIN_EXPIRE_ERROR: 8100,  // 登录令牌失效
}