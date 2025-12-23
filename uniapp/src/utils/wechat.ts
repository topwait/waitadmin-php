import weixin from 'weixin-js-sdk'
import loginApi from '@/api/login'
import clientUtil from '@/utils/client'

const wechatOa = {
    /**
     * 签名授权链接
     */
    signLink(): string | undefined {
        if (typeof window.signLink === 'undefined' || window.signLink === '') {
            window.signLink = location.href.split('#')[0] || ''
        }
        return clientUtil.isAndroid() ? location.href.split('#')[0] : window.signLink
    },

    /**
     * 公众号授权链接
     */
    authUrl(): void {
        loginApi.oaCodeUrl().then((res: any): void => {
            location.href = res.url
        })
    },

    /**
     * 公众号授权登录
     */
    authLogin(code: string, state: string): Promise<any> {
        return new Promise((resolve, reject): void => {
            loginApi.oaLogin(code, state).then((res: any): void => {
                resolve(res)
            }).catch((err): void => {
                reject(err)
            })
        })
    },

    /**
     * 准备就绪
     */
    ready(): Promise<any> {
        return new Promise((resolve): void => {
            weixin.ready((): void => {
                resolve('success')
            })
        })
    },

    /**
     * 页面分享
     */
    share(options: any): void {
        this.ready().then((): void => {
            const { shareTitle, shareLink, shareImage, shareDesc } = options
            weixin.updateTimelineShareData({
                title: shareTitle,
                link: shareLink,
                imgUrl: shareImage
            })
            // 发送给好友
            weixin.updateAppMessageShareData({
                title: shareTitle,
                link: shareLink,
                imgUrl: shareImage,
                desc: shareDesc
            })
            // 发送到tx微博
            weixin.onMenuShareWeibo({
                title: shareTitle,
                link: shareLink,
                imgUrl: shareImage,
                desc: shareDesc
            })
        })
    },

    /**
     * 获取地址
     */
    getAddress(): Promise<any> {
        return new Promise((reslove, reject): void => {
            this.ready().then((): void => {
                weixin.openAddress({
                    success: (res: any): void => {
                        reslove(res)
                    },
                    fail: (res: any): void => {
                        reject(res)
                    }
                })
            })
        })
    },

    /**
     * 获取定位
     */
    getLocation(): Promise<any> {
        return new Promise((reslove, reject): void => {
            this.ready().then((): void => {
                weixin.getLocation({
                    type: 'gcj02',
                    success: (res: any): void => {
                        reslove(res)
                    },
                    fail: (res: any): void => {
                        reject(res)
                    }
                })
            })
        })
    }
}

export default wechatOa
