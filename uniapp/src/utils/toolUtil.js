import { useUserStore } from '@/stores/userStore'
import clientUtil from '@/utils/clientUtil'

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
     * @param {String} path (本地文件路径)
     * @param {String} type (类型: image/video)
     * @param {String} dir  (要上传到那个目录)
     */
    uploadFile(path, type, dir) {
        const userStore = useUserStore()
        const token = userStore.$state.token
        const terminal = clientUtil.fetchClient()
        
        return new Promise((resolve, reject) => {
            uni.uploadFile({
                name: 'file',
                url: `${import.meta.env.VITE_APP_BASE_URL}/upload/file`,
                filePath: path,
                header: {token, terminal},
                formData: {'type': type, 'dir': dir},
                fileType: type,
                success: res => {
                    let result = JSON.parse(res.data)
                    if (result.code == 0) {
                        resolve(result.data);
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
