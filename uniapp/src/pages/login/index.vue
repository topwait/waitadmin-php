<template>
    <view v-if="isClosed" class="flex-1 flex items-center justify-center">
        <close-channel message="登录渠道尚未开放" />
    </view>

    <view v-else class="login flex-1 flex flex-col bg-page">
        <w-widget-bar />

        <view class="flex-1 flex flex-col items-center mt-10">
            <wd-img width="180rpx" height="180rpx" :src="logo" />
            <view class="text-xl text-tx-secondary tracking-[0.3em] mt-6">
                {{ tips }}
            </view>
            <view class="w-full px-10 mt-10">
                <login-account
                    v-if="config.default_method === 'account'"
                    :show-register="config.usable_register.includes('account')"
                    :show-protocol="showProtocol"
                />
                <login-mobile
                    v-if="config.default_method === 'mobile'"
                    :show-register="config.usable_register.includes('mobile')"
                    :show-protocol="showProtocol"
                />
                <login-wechat
                    v-if="config.default_method === 'wx'"
                    :show-protocol="showProtocol"
                />
            </view>
        </view>

        <view v-if="config.usable_channel?.length >= 2" class="text-center mt-4 mb-8">
            <view class="px-12 mb-4">
                <wd-divider
                    color="var(--text-color-placeholder)"
                    custom-class="!text-xs"
                >
                    其它登录方式
                </wd-divider>
            </view>
            <view class="flex justify-center gap-x-8">
                <view
                    v-if="config.usable_channel.includes('wx') && config.default_method !== 'wx'"
                    class="w-[80rpx] h-[80rpx] rounded-full bg-lighter"
                    @click="config.default_method = 'wx'"
                >
                    <wd-img :src="logoWechat" width="100%" height="100%" />
                </view>

                <view
                    v-if="config.usable_channel.includes('mobile') && config.default_method !== 'mobile'"
                    class="w-[80rpx] h-[80rpx] rounded-full bg-lighter"
                    @click="config.default_method = 'mobile'"
                >
                    <wd-img :src="logoMobile" width="100%" height="100%" />
                </view>

                <view
                    v-if="config.usable_channel.includes('account') && config.default_method !== 'account'"
                    class="w-[80rpx] h-[80rpx] rounded-full bg-lighter"
                    @click="config.default_method = 'account'"
                >
                    <wd-img :src="logoAccount" width="100%" height="100%" />
                </view>
            </view>
        </view>
    </view>
</template>

<script setup lang="ts">
import logoWechat from '@/assets/images/logo_wechat.png'
import logoMobile from '@/assets/images/logo_mobile.png'
import logoAccount from '@/assets/images/logo_account.png'
import CloseChannel from './_components/close-channel.vue'
import LoginAccount from './_components/login-account.vue'
import LoginMobile from './_components/login-mobile.vue'
import LoginWechat from './_components/login-wechat.vue'
import { ClientEnum } from '@/enums/client'
import clientUtil from '@/utils/client'
import useAppStore from '@/stores/app'
import useUserStore from '@/stores/user'

const appStore = useAppStore()
const userStore = useUserStore()

// Logo
const logo = computed(() => appStore.getLoginConfig.basis?.logo)

// 登录描述
const tips = computed(() => appStore.getLoginConfig.basis?.tips)

// 关闭登录
const isClosed = ref<boolean>(false)

// 显示协议
const showProtocol = ref<boolean>(true)

/**
 * 获取配置
 */
const config = computed(() => {
    const conf = appStore.getLoginConfig
    const client = clientUtil.fetchClient()
    switch (client) {
        case ClientEnum.H5:
            showProtocol.value = conf.h5?.is_agreement
            isClosed.value = conf.h5?.usable_channel.length === 0
            return conf.h5 || {}
        case ClientEnum.MP_WEIXIN:
        case ClientEnum.OA_WEIXIN:
            showProtocol.value = conf.wx?.is_agreement
            isClosed.value = conf.wx?.usable_channel.length === 0
            return conf.wx || {}
        default:
            showProtocol.value = conf.other?.is_agreement
            isClosed.value = conf.other?.usable_channel.length === 0
            return conf.other || {}
    }
})

onShow(() => {
    if (userStore.isLogin) {
        uni.reLaunch({
            url: '/pages/index/index'
        })
    }
})
</script>

<style scoped lang="scss">
.login {
    :deep(.wd-input) {
        flex: 1;
        background: transparent !important;
        &::after {
            height: 0;
        }
        .wd-input__clear,
        .wd-input__icon {
            background: transparent;
        }
    }
}
</style>
