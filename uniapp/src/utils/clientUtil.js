import { ClientEnum } from '@/enums/clientEnum'

/**
 * 是否为微信环境
 */
export function isWeixin() {
    try {
        let ua = navigator.userAgent.toLowerCase()
        if (ua.match(/MicroMessenger/i) == 'micromessenger') {
            return true
        } else {
            return false
        }
    } catch (e) {
        return false
    }
}

/**
 * 是否为安卓环境
 */
export function isAndroid() {
	let u = navigator.userAgent
	return u.indexOf('Android') > -1 || u.indexOf('Adr') > -1
}

/**
 * 取当前的客户端
 */
export function fetchClient() {
	// #ifdef MP-WEIXIN
		return ClientEnum.MP_WEIXIN
	// #endif
	
	// #ifdef H5
		return isWeixin() ? ClientEnum.OA_WEIXIN : ClientEnum.H5
	// #endif
	
	// #ifdef APP-PLUS
        return isAndroid() ? ClientEnum.ANDROID : ClientEnum.IOS
	// #endif
	
	return null
}
