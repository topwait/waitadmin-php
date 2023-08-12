import weixin from 'weixin-js-sdk'
import loginApi from '@/api/loginApi'
import clientUtil from '@/utils/clientUtil'

const wechatOa = {
    /**
     * 签名授权链接
     */
    signLink() {
        if (typeof window.signLink === 'undefined' || window.signLink === '') {
            window.signLink = location.href.split('#')[0]
        }
        return clientUtil.isAndroid() ? location.href.split('#')[0] : window.signLink
    },
    /**
     * 公众号授权链接
     */
    authUrl() {
        loginApi.oaCodeUrl().then((res) => {
            location.href = res.url
        })
    },
    /**
     * 公众号授权登录
     */
    authLogin(code) {
        return new Promise((resolve, reject) => {
            loginApi.login({ scene: 'oa', code }).then((res) => {
                resolve(res)
            }).catch((err) => {
                reject(err)
            })
        })
    },
    ready() {
        return new Promise((resolve) => {
            weixin.ready(() => {
                resolve('success')
            })
        })
    },
    share(options) {
        this.ready().then(() => {
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
    getAddress() {
        return new Promise((reslove, reject) => {
            this.ready().then(() => {
                weixin.openAddress({
                    success: (res) => {
                        reslove(res)
                    },
                    fail: (res) => {
                        reject(res)
                    }
                })
            })
        })
    },
    getLocation() {
        return new Promise((reslove, reject) => {
            this.ready().then(() => {
                weixin.getLocation({
                    type: 'gcj02',
                    success: (res) => {
                        reslove(res)
                    },
                    fail: (res) => {
                        reject(res)
                    }
                })
            })
        })
    }
}

export default wechatOa
