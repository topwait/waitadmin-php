export default {
    /**
     * 提取微信编码
     */
    obtainWxCode() {
        return new Promise((resolve, reject) => {
            uni.login({
                success(response) {
                    if ('login:ok' === response.errMsg) {
                        resolve(response.code)
                    } else {
                        reject(response.errMsg)
                    }
                },
                fail(error) {
                    reject(error)
                }
            })
        })
    },
    /**
     * 提取微信位置
     */
    obtainWxLocation() {
        return new Promise((resolve, reject) => {
            uni.getLocation({
                type: 'wgs84',
                success: function (response) {
                    resolve(response)
                },
                fail(error) {
                    reject(error)
                }
            })
        })
    },
    /**
     * 获取当前页面
     */
    currentPage() {
        const pages = getCurrentPages()
        const currentPage = pages[pages.length - 1]
        return currentPage || {}
    },
    /**
     * 上传文件资源
     *
     * @param {String} path
     * @param {String} dir
     */
    uploadFile(path, dir) {
        return new Promise((resolve, reject) => {
            uni.uploadFile({
                name: 'iFile',
                url: `${import.meta.env.VITE_APP_BASE_URL}/upload/image`,
                filePath: path,
                header: {token: ''},
                formData: {
                    'dir': dir
                },
                fileType: 'image',
                success: res => {
                    let data = JSON.parse(res.data);
                    if (data.code == 0) {
                        resolve(data);
                    } else {
                        reject()
                    }
                },
                fail: (err) => {
                    reject(err)
                }
            })
        })
    }
}
