import { ClientEnum } from '@/enums/clientEnum'

export default {
    /**
     * 是否为微信环境
     */
    isWeixin() {
        let wxBoolean = true
        // #ifndef MP-WEIXIN
        try {
            let ua = navigator.userAgent.toLowerCase()
            if (ua.match(/MicroMessenger/i)[0] === 'micromessenger') {
                wxBoolean = true
            } else {
                wxBoolean = false
            }
        } catch (e) {
            wxBoolean = false
        }
        // #endif
        
        return wxBoolean
    },
    /**
     * 是否为安卓环境
     */
    isAndroid() {
        let u = navigator.userAgent
        return u.indexOf('Android') > -1 || u.indexOf('Adr') > -1
    },
    /**
     * 取当前的客户端
     */
    fetchClient() {
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

        return clientValue
    }
}
