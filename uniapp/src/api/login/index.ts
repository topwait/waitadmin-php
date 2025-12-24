import request from '@/utils/request'

const loginApi = {
    /**
     * 获取公众号授权URL
     *
     * @return Promise<LoginOaUrlResponse>
     */
    oaCodeUrl(): Promise<LoginOaUrlResponse> {
        const url: URL = new URL(location.href)
        url.pathname = url.pathname.replace(/index$/, 'oauth')
        return request.get({
            url: '/login/oaCodeUrl',
            data: {
                url: url.href
            }
        })
    },

    /**
     * UniApp微信登录(App端用)
     * 注意: 需要配置 manifest.json/sdkConfigs 微信配置
     * 配置来源: 到微信开放平台创建移动端应用获取
     *
     * @param params
     * @param params.openid
     * @param params.access_token
     */
    uniWxLogin(params: {
        openid: string;
        access_token: string;
    }): Promise<LoginResultResponse> {
        return request.post<LoginResultResponse>({
            url: '/login/uniWxLogin',
            params
        })
    },

    /**
     * 微信登录
     *
     * @param {string} code
     * @return Promise<LoginResultResponse>
     */
    wechatLogin(code: string): Promise<LoginResultResponse> {
        return request.post({
            url: '/login/login',
            params: {
                scene: 'wx',
                code: code
            }
        })
    },

    /**
     * 微信公众号登录
     *
     * @param {string} code
     * @param {state} state
     * @return Promise<LoginResultResponse>
     */
    oaLogin(code: string, state: string): Promise<LoginResultResponse> {
        return request.post({
            url: '/login/login',
            params: {
                scene: 'oa',
                code: code,
                state: state
            }
        })
    },

    /**
     * 账号登录
     *
     * @param params
     * @return Promise<LoginResultResponse>
     */
    accountLogin(params: {
        account: string;
        password: string;
    }): Promise<LoginResultResponse> {
        return request.post({
            url: '/login/login',
            params: {
                scene: 'account',
                account: params.account,
                password: params.password
            }
        })
    },

    /**
     * 手机登录
     *
     * @param params
     * @return Promise<LoginResultResponse>
     */
    mobileLogin(params: {
        mobile: string;
        code: string;
    }): Promise<LoginResultResponse> {
        return request.post({
            url: '/login/login',
            params: {
                scene: 'mobile',
                mobile: params.mobile,
                code: params.code
            }
        })
    },

    /**
     * 绑定登录
     *
     * @param params
     * @return Promise<LoginResultResponse>
     */
    bindLogin(params: {
        code: string;
        sign: string;
        mobile: string;
    }): Promise<LoginResultResponse> {
        return request.post({
            url: '/login/login',
            params: {
                scene: 'ba',
                mobile: params.mobile,
                code: params.code,
                sign: params.sign
            }
        })
    },

    /**
     * 注册账号
     *
     * @param {object} params
     * @param {string} params.account
     * @param {string} params.password
     * @param {string} [params.mobile]
     * @param {string} [params.code]
     * @return Promise<LoginResultResponse>
     */
    register(params: {
        account: string;
        password: string;
        mobile?: string;
        code?: string;
    }): Promise<LoginResultResponse> {
        return request.post({
            url: '/login/register',
            params
        })
    },

    /**
     * 退出登录
     */
    logout(): Promise<void> {
        return request.post({
            url: '/login/logout'
        })
    }
}

export default loginApi
