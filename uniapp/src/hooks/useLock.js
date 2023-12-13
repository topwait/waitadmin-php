import { ref } from 'vue'

export function useLock(callback, delay = 0) {
    let loading = ref(false)
    const methodAPI = async (params) => {
        if (loading.value) {
            throw new Error('请勿重新请求~')
        }

        loading.value = true
        const reponse = await callback(params).catch(e => {
            if (delay) {
                setTimeout(() => {
                    loading.value = false
                }, delay)
            } else {
                loading.value = false
            }
            return Promise.reject(e)
        })
        if (delay) {
            setTimeout(() => {
                loading.value = false
            }, delay)
        } else {
            loading.value = false
        }
        return reponse
    }

    return {
        loading,
        methodAPI
    }
}
