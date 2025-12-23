/** 首页数据响应 */
interface IndexDataResponse {
    article: {
        // ID
        id: number;
        // 封面
        image: string;
        // 标题
        title: string;
        // 简介
        intro: string;
        // 浏览量
        browse: string;
        // 创建时间
        create_time: string;
    }[]
}

/** 系统配置响应 */
interface SysConfigResponse {
    oss_domain: string;
    h5: {
        // 网站图标
        logo: string;
        // 网站标题
        title: string;
        // 网站关闭状态
        status: boolean;
        // 关闭跳转地址
        close_url: string;
    };
    login: {
        // 微信端
        wx: {
            // 显示协议
            is_agreement: boolean;
            // 强制绑定手机
            force_mobile: boolean;
            // 默认登录方式
            default_method: string;
            // 可用登录渠道
            usable_channel: string[];
            // 可用注册渠道
            usable_register: string[];
        },
        // PC端
        pc: {
            is_agreement: boolean;
            force_mobile: boolean;
            default_method: string;
            usable_channel: string[];
            usable_register: string[];
        },
        // H5端
        h5: {
            is_agreement: boolean;
            force_mobile: boolean;
            default_method: string;
            usable_channel: string[];
            usable_register: string[];
        },
        // 其它端
        other: {
            is_agreement: boolean;
            force_mobile: boolean;
            default_method: string;
            usable_channel: string[];
            usable_register: string[];
        },
        // 基础配置
        basis: {
            // logo图标
            logo: string;
            // 提示描述
            tips: string;
        }
    }
}

/** 页面装修响应 */
interface DecorateResponse {
    // 主题风格
    theme: string;
    // 主题颜色
    color: string;
    // 底部导航
    tabbar: {
        style: {
            effect: string;
            shape: 'default' | 'round';
            color: string;
            selectedColor: string;
            backgroundColor: string;
        },
        list: {
            text: string;
            pagePath: string;
            iconPath: string;
            selectedIconPath: string;
        }[],
        routes: string[]
    };
    // 客服页面
    tie: {
        title: string;
        image: string;
        datetime: string;
        mobile: string;
        qq: string;
    }
}

/** 政策协议响应 */
interface PolicyResponse {
    // 协议内容
    content: string;
}
