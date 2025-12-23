import request from '@/utils/request'

const indexApi = {
    /**
     * 首页数据
     *
     * @return {IndexDataResponse}
     * @author zero
     */
    index(): Promise<IndexDataResponse> {
        return request.get<IndexDataResponse>({
            url: '/index/index'
        })
    },

    /**
     * 系统配置
     *
     * @return {SysConfigResponse}
     * @author zero
     */
    config(): Promise<SysConfigResponse> {
        return request.get<SysConfigResponse>({
            url: '/index/config'
        })
    },

    /**
     * 装修数据
     *
     * @return {DecorateResponse}
     * @author zero
     */
    decorate(): Promise<DecorateResponse> {
        return request.get<DecorateResponse>({
            url: '/index/decorate'
        })
    },

    /**
     * 政策协议
     *
     * @param {string} type
     * @return {PolicyResponse}
     * @author zero
     */
    policy(type: string): Promise<PolicyResponse> {
        return request.get<PolicyResponse>({
            url: '/index/policy',
            params: {
                type
            }
        })
    },

    /**
     * 发送短信
     *
     * @param {object} params
     * @param {number} params.scene
     * @param {string} params.mobile
     * @return {any}
     * @author zero
     */
    sendSms(params: {
        scene: number;
        mobile: string;
    }): Promise<any> {
        return request.post({
            url: '/index/sendSms',
            params
        })
    },

    /**
     * 发送邮件
     *
     * @param {object} params
     * @param {number} params.scene
     * @param {string} params.email
     * @return {any}
     * @author zero
     */
    sendEmail(params: {
        scene: number;
        email: string;
    }): Promise<any> {
        return request.post({
            url: '/index/sendEmail',
            params
        })
    },

    /**
     * 验证验证码
     *
     * @param {object} params
     * @param {number} params.scene
     * @param {string} params.code
     * @return {any}
     * @author zero
     */
    verifyCode(params: {
        scene: number;
        code: string;
    }): Promise<any> {
        return request.post({
            url: '/index/verifyCode',
            params
        })
    },

    /**
     * 上传文件
     *
     * @param {string} type
     * @param {string} scene
     * @param {Omit<UniApp.UploadFileOption, 'url'>} options
     * @param onProgress
     * @author zero
     */
    upload(
        type: 'picture' | 'video' | 'document' | 'package' | 'all',
        scene: 'permanent' | 'temporary',
        options: Omit<UniApp.UploadFileOption, 'url'>,
        onProgress?: (progress: number) => void
    ): Promise<any> {
        return request.uploadFile(
            {
                url: `/upload/${scene}?type=${type}`,
                name: 'file',
                ...options
            },
            {
                onProgress
            }
        )
    }
}

export default indexApi
