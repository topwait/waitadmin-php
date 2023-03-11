import { ref } from 'vue'

export function useLock(callback) {
    let loading = ref(false)
    const methodAPI = async (params) => {
        if (loading.value) {
            throw new Error('请勿重新请求~')
        }

        loading.value = true
        const reponse = await callback(params)
        loading.value = false
        return reponse
    }

    return {
        loading,
        methodAPI
    }
}
