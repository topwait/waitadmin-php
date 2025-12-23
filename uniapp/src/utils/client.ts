import { ClientEnum } from '@/enums/client'

type PlatformType =
    | 'h5'
    | 'mp-weixin'
    | 'mp-alipay'
    | 'mp-baidu'
    | 'mp-toutiao'
    | 'mp-qq'
    | 'mp-kuaishou'
    | 'mp-jd'
    | 'mp-lark'
    | 'mp-xhs'
    | 'mp-harmony'
    | 'quickapp-webview'
    | 'quickapp-webview-union'
    | 'quickapp-webview-huawei'
    | 'unknown';

interface CallbacksType {
    'h5'?: () => void;
    'mp-weixin'?: () => void;
    'mp-alipay'?: () => void;
    'mp-baidu'?: () => void;
    'mp-toutiao'?: () => void;
    'mp-qq'?: () => void;
    'mp-kuaishou'?: () => void;
    'mp-jd'?: () => void;
    'mp-lark'?: () => void;
    'mp-xhs'?: () => void;
    'mp-harmony'?: () => void;
    'quickapp-webview'?: () => void;
    'quickapp-webview-union'?: () => void;
    'quickapp-webview-huawei'?: () => void;
    'multi'?: () => void;
    'unknown'?: () => void;
}

const clientUtil = {
    /**
     * 是否为微信环境
     *
     * @return {boolean}
     * @author zero
     */
    isWeixin: function (): boolean {
        let wxBoolean: boolean
        // #ifndef MP-WEIXIN
        try {
            const ua: string = navigator.userAgent.toLowerCase()
            const match: RegExpMatchArray | null = ua.match(/MicroMessenger/i)
            wxBoolean = match ? match[0] === 'micromessenger' : false
            // eslint-disable-next-line @typescript-eslint/no-unused-vars
        } catch (e) {
            wxBoolean = false
        }
        // #endif
        return wxBoolean
    },

    /**
     * 是否为安卓环境
     *
     * @return {boolean}
     * @author zero
     */
    isAndroid(): boolean {
        const u: string = navigator?.userAgent
        if (u) {
            return u.indexOf('Android') > -1 || u.indexOf('Adr') > -1
        }
        return uni.getSystemInfoSync().platform === 'android'
    },

    /**
     * 取当前的客户端
     *
     * @returns {ClientEnum}
     * @author zero
     */
    fetchClient(): ClientEnum {
        let clientValue = null

        // #ifdef MP-WEIXIN
        clientValue = ClientEnum.MP_WEIXIN
        // #endif

        // #ifdef H5
        clientValue = this.isWeixin() ? ClientEnum.OA_WEIXIN : ClientEnum.H5
        // #endif

        // #ifdef APP-PLUS
        clientValue = this.isAndroid() ? ClientEnum.ANDROID : ClientEnum.IOS
        // #endif

        // #ifdef MP-ALIPAY
        clientValue = ClientEnum.ALIPAY
        // #endif

        return clientValue
    },

    /**
     * 根据当前平台执行方法
     *
     * @param {CallbacksType} callbacks
     * @param {PlatformType[]} platforms
     * @author zero
     */
    callback(callbacks: CallbacksType, platforms?: PlatformType[]): void {
        // 获取当前平台
        const platform: PlatformType = process.env.UNI_PLATFORM as PlatformType

        // 多平台调用
        if (callbacks?.multi) {
            if (!platforms) {
                throw new Error('使用 multi 时必须指定 platforms 参数')
            }
            if (platforms.includes(platform)) {
                callbacks.multi()
            }
        }

        // 单平台调用
        if (callbacks[platform]) {
            callbacks[platform]!()
        }
    }
}

export default clientUtil
