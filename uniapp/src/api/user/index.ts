import request from '@/utils/request'

const userApi = {
    /**
     * 用户信息
     *
     * @return {Promise<UserCenterResponse>}
     * @author zero
     */
    center(): Promise<UserCenterResponse> {
        return request.get<UserCenterResponse>({
            url: '/user/center'
        })
    },

    /**
     * 编辑信息
     *
     * @param {object} params
     * @param {string} params.scene
     * @param {string} params.valute
     * @return {Promise<any>}
     * @author zero
     */
    edit(params: {
        scene: string;
        value: string;
    }): Promise<any> {
        return request.post({
            url: '/user/edit',
            params
        })
    },

    /**
     * 校验密码
     *
     * @param {string} password
     * @return {Promise<any>}
     * @author zero
     */
    checkPwd(password: string): Promise<any> {
        return request.post({
            url: '/user/checkPwd',
            params: {
                password
            }
        })
    },

    /**
     * 忘记密码
     *
     * @param {object} params
     * @param {string} params.code
     * @param {string} params.mobile
     * @param {string} params.password
     * @return {Promise<any>}
     * @author zero
     */
    forgetPwd(params: {
        code: string;
        mobile: string;
        newPassword: string;
    }): Promise<any> {
        return request.post({
            url: '/user/forgetPwd',
            params
        })
    },

    /**
     * 修改密码
     *
     * @param {object} params
     * @param {string} params.code
     * @param {string} params.mobile
     * @param {string} params.password
     * @return {Promise<any>}
     * @author zero
     */
    changePwd(params: {
        newPassword: string;
        oldPassword: string;
    }): Promise<any> {
        return request.post({
            url: '/user/changePwd',
            params
        })
    },

    /**
     * 绑定手机
     *
     * @param {object} params
     * @param {string} params.password
     * @param {string} params.mobile
     * @param {string} params.code
     * @return {Promise<any>}
     * @author zero
     */
    bindMobile(params: {
        password: string;
        mobile: string;
        code: string;
    }): Promise<any> {
        return request.post({
            url: '/user/bindMobile',
            params
        })
    },

    /**
     * 绑定手机
     *
     * @param {object} params
     * @param {string} params.password
     * @param {string} params.email
     * @param {string} params.code
     * @return {Promise<any>}
     * @author zero
     */
    bindEmail(params: {
        password: string;
        email: string;
        code: string;
    }): Promise<any> {
        return request.post({
            url: '/user/bindEmail',
            params
        })
    },

    /**
     * 绑定微信
     *
     * @param {string} code
     * @return {Promise<any>}
     * @author zero
     */
    bindWeChat(code: string): Promise<any> {
        return request.post({
            url: '/user/bindEmail',
            params: {
                code
            }
        })
    }
}

export default userApi
