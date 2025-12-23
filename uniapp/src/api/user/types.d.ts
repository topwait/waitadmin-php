/** 用户信息响应 */
interface UserCenterResponse {
    // ID
    id: string;
    // 编号
    sn: string;
    // 昵称
    nickname: string;
    // 账号
    account: string;
    // 头像
    avatar: string;
    // 性别
    gender: string;
    // 手机号码
    mobile: string;
    // 邮箱号码
    email: string;
    // 是否已设密码
    is_pwd: boolean;
    // 是否已绑微信
    is_wechat: boolean;
}
