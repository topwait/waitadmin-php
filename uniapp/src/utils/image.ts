import clientUtil from '@/utils/client'

const imageUtil = {
    /**
     * 保存图片到相册（多端兼容）
     *
     * @param {string} imageUrl - 图片地址（网络地址或本地地址）
     * @returns {Promise<void>}
     * @author zero
     */
    async saveToAlbum(imageUrl: string): Promise<void> {
        if (!imageUrl) {
            await uni.showToast({title: '图片地址不能为空', icon: 'none'})
            return
        }

        await uni.showLoading({title: '保存中...'})

        try {
            // #ifdef MP-WEIXIN
            await this.saveInMpWeixin(imageUrl)
            // #endif

            // #ifdef H5
            if (clientUtil.isWeixin()) {
                await this.saveInOaWeixin(imageUrl)
            } else {
                await this.saveInH5(imageUrl)
            }
            // #endif

            // #ifdef APP-PLUS
            await this.saveInApp(imageUrl)
            // #endif

            uni.hideLoading()
            await uni.showToast({title: '保存成功', icon: 'success'})
        } catch (error: any) {
            uni.hideLoading()
            const errMsg = error?.errMsg || error?.message || '保存失败'
            if (errMsg.includes('auth deny') || errMsg.includes('authorize')) {
                uni.showModal({
                    title: '提示',
                    content: '需要您授权保存图片到相册',
                    confirmText: '去设置',
                    success: (res: UniApp.ShowModalRes): void => {
                        if (res.confirm) {
                            uni.openSetting({})
                        }
                    }
                })
            } else {
                await uni.showToast({title: errMsg, icon: 'none'})
            }
        }
    },

    /**
     * 微信小程序保存图片
     */
    async saveInMpWeixin(imageUrl: string): Promise<void> {
        return new Promise((resolve, reject): void => {
            // 先下载图片到本地
            const downloadTask = (): void => {
                if (imageUrl.startsWith('http')) {
                    uni.downloadFile({
                        url: imageUrl,
                        success: (res: UniApp.DownloadSuccessData): void => {
                            if (res.statusCode === 200) {
                                this.saveImageToAlbum(res.tempFilePath)
                                    .then(resolve)
                                    .catch(reject)
                            } else {
                                reject(new Error('下载图片失败'))
                            }
                        },
                        fail: reject
                    })
                } else {
                    this.saveImageToAlbum(imageUrl).then(resolve).catch(reject)
                }
            }

            // 检查权限
            uni.getSetting({
                success: (res: UniApp.GetSettingSuccessResult): void => {
                    if (!res.authSetting['scope.writePhotosAlbum']) {
                        reject({ errMsg: 'auth deny' })
                    } else if (res.authSetting['scope.writePhotosAlbum'] === undefined) {
                        uni.authorize({
                            scope: 'scope.writePhotosAlbum',
                            success: () => downloadTask(),
                            fail: reject
                        })
                    } else {
                        downloadTask()
                    }
                },
                fail: reject
            })
        })
    },

    /**
     * 微信公众号保存图片（提示长按保存）
     */
    async saveInOaWeixin(imageUrl: string): Promise<void> {
        return new Promise((resolve): void => {
            uni.previewImage({
                urls: [imageUrl],
                current: imageUrl,
                success: (): void => {
                    uni.showToast({
                        title: '请长按图片保存',
                        icon: 'none',
                        duration: 2000
                    })
                    resolve()
                }
            })
        })
    },

    /**
     * H5端保存图片（使用 a 标签下载）
     */
    async saveInH5(imageUrl: string): Promise<void> {
        return new Promise((resolve, reject): void => {
            try {
                // 如果是网络图片，需要先转换为 blob
                if (imageUrl.startsWith('http')) {
                    fetch(imageUrl)
                        .then(response => response.blob())
                        .then(blob => {
                            const url: string = URL.createObjectURL(blob)
                            this.downloadByLink(url, 'qrcode.png')
                            URL.revokeObjectURL(url)
                            resolve()
                        }).catch((): void => {
                            // 如果跨域失败，打开新窗口
                            window.open(imageUrl, '_blank')
                            resolve()
                        })
                } else {
                    this.downloadByLink(imageUrl, 'qrcode.png')
                    resolve()
                }
            } catch (error) {
                reject(error)
            }
        })
    },

    /**
     * 通过 a 标签下载
     */
    downloadByLink(url: string, filename: string): void {
        const link: HTMLAnchorElement = document.createElement('a')
        link.href = url
        link.download = filename
        link.style.display = 'none'
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)
    },

    /**
     * APP端保存图片
     */
    async saveInApp(imageUrl: string): Promise<void> {
        return new Promise((resolve, reject): void => {
            if (imageUrl.startsWith('http')) {
                uni.downloadFile({
                    url: imageUrl,
                    success: (res: UniApp.DownloadSuccessData): void => {
                        if (200 === res.statusCode) {
                            this.saveImageToAlbum(res.tempFilePath)
                                .then(resolve)
                                .catch(reject)
                        } else {
                            reject(new Error('下载图片失败'))
                        }
                    },
                    fail: reject
                })
            } else {
                this.saveImageToAlbum(imageUrl).then(resolve).catch(reject)
            }
        })
    },

    /**
     * 保存图片到相册（通用）
     */
    saveImageToAlbum(filePath: string): Promise<void> {
        return new Promise((resolve, reject): void => {
            uni.saveImageToPhotosAlbum({
                filePath,
                success: () => resolve(),
                fail: reject
            })
        })
    }
}

export default imageUtil
