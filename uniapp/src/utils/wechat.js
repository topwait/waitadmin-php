import weixin from 'weixin-js-sdk'
import clientUtil from '@/utils/clientUtil'
import { oaCodeUrlApi, loginApi } from '@/api/usersApi'

const wechatOa = {
    getSignLink() {
        if (typeof window.signLink === 'undefined' || window.signLink === '') {
            window.signLink = location.href.split('#')[0]
        }
        return clientUtil.isAndroid() ? location.href.split('#')[0] : window.signLink
    },
    authUrl() {
        oaCodeUrlApi().then((res) => {
            location.href = res.data.url
        })
    },
    authLogin(code) {
        return new Promise((resolve, reject) => {
            loginApi({scene: 'oa', code}).then((res) => {
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
