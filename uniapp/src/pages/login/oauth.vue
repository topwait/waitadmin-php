

<template>
    <wd-toast />
    <view class="flex-1 flex items-center justify-center bg-page">
        <view class="text-center">
            <wd-loading type="outline" size="100rpx" />
            <view class="mt-3 text-tx-secondary">
                {{ msg }}
            </view>
        </view>

        <login-wechat
            scene="bind"
            :show-popup="show"
            :sign="sign"
        />
    </view>
</template>

<script setup lang="ts">
import { useToast } from 'wot-design-uni'
import useUserStore from '@/stores/user'
import LoginWechat from './_components/login-wechat.vue'

// #ifdef H5
import wechatOa from '@/utils/wechat'
// #endif

const toast = useToast()
const userStore = useUserStore()

// 绑定手机控制参数
const show = ref<boolean>(false)
const sign = ref<string>('')
const msg = ref<string>('正在登录中...')

/**
 * 公众号登录
 *
 * @param code
 * @param state
 */
const oaLogin = async (code: string, state: string) => {
    // #ifdef H5
    if (code && state) {
        wechatOa.authLogin(code, state)
            .then(async (result: any) => {
                if (result.code === 1) {
                    msg.value = '请完成手机号绑定...'
                    sign.value = result.data.sign
                    show.value = true
                } else {
                    await userStore.login(result.token)
                    toast.show('登录成功')
                    setTimeout(() => {
                        uni.reLaunch({
                            url: '/pages/user/index'
                        })
                    }, 1000)
                }
            })
    }
    // #endif
}

onLoad(async (options) => {
    if (userStore.isLogin) {
        await uni.reLaunch({
            url: '/pages/index/index'
        })
        return
    }

    if (!options?.code || !options?.state) {
        toast.error('授权异常，为您跳转首页~')
        return setTimeout(() => {
            uni.reLaunch({
                url: '/pages/index/index'
            })
        }, 1500)
    }

    await oaLogin(options.code, options.state)
})
</script>
