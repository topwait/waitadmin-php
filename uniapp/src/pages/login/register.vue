<template>
    <view v-if="isClosed" class="flex-1 flex items-center justify-center">
        <close-channel message="注册渠道尚未开放" />
    </view>

    <view v-else class="register flex-1 flex flex-col bg-page">
        <w-widget-bar />

        <view class="text-6xl text-tx-primary font-bold px-10 mt-10">
            注册账号
        </view>

        <view class="text-sm text-tx-placeholder px-10 mt-2">
            别看了，就差你了！快注册！
        </view>

        <view class="w-full px-10 mt-10">
            <wd-form ref="form" :model="formData">
                <!-- 账号输入 -->
                <view class="flex items-center py-1.5 px-4 rounded-[60rpx] bg-lighter">
                    <wd-icon name="user" size="38rpx" />
                    <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
                    <wd-input
                        v-model="formData.account"
                        :maxlength="20"
                        prop="account"
                        clearable
                        clear-trigger="focus"
                        placeholder="请输入账号"
                        placeholder-class="!text-tx-placeholder"
                    />
                </view>
                <!-- 密码输入 -->
                <view class="flex items-center py-1.5 px-4 mt-4 rounded-[60rpx] bg-lighter">
                    <wd-icon name="lock-on" size="38rpx" />
                    <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
                    <wd-input
                        v-model="formData.password"
                        :maxlength="50"
                        prop="password"
                        clearable
                        show-password
                        clear-trigger="focus"
                        placeholder="请输入密码"
                        placeholder-class="!text-tx-placeholder"
                    />
                </view>
                <!-- 确认密码 -->
                <view class="flex items-center py-1.5 px-4 mt-4 rounded-[60rpx] bg-lighter">
                    <wd-icon name="lock-on" size="38rpx" />
                    <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
                    <wd-input
                        v-model="formData.confirm_pwd"
                        :maxlength="50"
                        prop="password"
                        clearable
                        show-password
                        clear-trigger="focus"
                        placeholder="请确认密码"
                        placeholder-class="!text-tx-placeholder"
                    />
                </view>
                <!-- 强制手机 -->
                <template v-if="config.force_mobile">
                    <!-- 手机号码 -->
                    <view class="flex items-center py-1.5 px-4 mt-4 rounded-[60rpx] bg-lighter">
                        <wd-icon name="mobile" size="38rpx" />
                        <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
                        <wd-input
                            v-model="formData.mobile"
                            :maxlength="11"
                            prop="mobile"
                            clearable
                            clear-trigger="focus"
                            placeholder="请输入手机号"
                            placeholder-class="!text-tx-placeholder"
                        />
                    </view>
                    <!-- 短信验证 -->
                    <view class="flex items-center py-1.5 px-4 mt-4 rounded-[60rpx] bg-lighter">
                        <wd-icon name="secured" size="38rpx" />
                        <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
                        <wd-input
                            v-model="formData.code"
                            :maxlength="15"
                            prop="code"
                            type="number"
                            placeholder="请输入验证码"
                            placeholder-class="!text-tx-placeholder"
                        />
                        <w-verify-code
                            type="mobile"
                            :mobile="formData.mobile"
                            :scene="NoticeEnum.REGISTER"
                        />
                    </view>
                </template>
                <!-- 服务协议 -->
                <view v-if="config.is_agreement" class="mt-4 ml-2">
                    <auth-protocol v-model="isProtocol" />
                </view>
                <!-- 提交按钮 -->
                <view class="mt-6">
                    <wd-button
                        type="primary"
                        size="large"
                        :disabled="locking"
                        :loading="locking"
                        block
                        @click="handleRegister"
                    >
                        注册
                    </wd-button>
                </view>
                <!-- 已有账号 -->
                <view class="text-center text-sm mt-4">
                    <text class="text-tx-placeholder">已有账号?</text>
                    <text
                        class="ml-1 text-primary"
                        @click="$go('/pages/login/index')"
                    >
                        立即登录
                    </text>
                </view>
            </wd-form>
        </view>
    </view>
</template>

<script setup lang="ts">
import { useToast } from 'wot-design-uni'
import { ClientEnum } from '@/enums/client'
import { NoticeEnum } from '@/enums/notice'
import clientUtil from '@/utils/client'
import useUserStore from '@/stores/user'
import useAppStore from '@/stores/app'
import loginApi from '@/api/login'
import CloseChannel from './_components/close-channel.vue'
import AuthProtocol from './_components/auth-protocol.vue'

const toast = useToast()
const appStore = useAppStore()
const userStore = useUserStore()

// 关闭登录
const isClosed = ref<boolean>(false)

// 协议参数
const showProtocol = ref<boolean>(true)
const isProtocol = ref<boolean>(false)

// 表单参数
const locking = ref<boolean>(false)
const formData = reactive<{
    account: string;
    password: string;
    confirm_pwd: string;
    mobile: string;
    code: string;
}>({
    account: '',
    password: '',
    confirm_pwd: '',
    mobile: '',
    code: ''
})

/**
 * 读取配置
 */
const config = computed(() => {
    const conf = appStore.getLoginConfig
    const client = clientUtil.fetchClient()
    switch (client) {
        case ClientEnum.H5:
            showProtocol.value = conf.h5?.is_agreement
            isClosed.value = !conf.h5?.usable_register?.includes('account')
            return conf.h5 || {}
        case ClientEnum.MP_WEIXIN:
        case ClientEnum.OA_WEIXIN:
            showProtocol.value = conf.wx?.is_agreement
            isClosed.value = !conf.wx?.usable_register?.includes('account')
            return conf.wx || {}
        default:
            showProtocol.value = conf.other?.is_agreement
            isClosed.value = !conf.other?.usable_register?.includes('account')
            return conf.other || {}
    }
})

/**
 * 发起注册
 */
const handleRegister = async () => {
    if (formData.code) {
        toast.show('请输入登录账号')
        return false
    }

    if (!formData.password) {
        toast.show('请输入登录密码')
        return false
    }

    if (formData.password !== formData.confirm_pwd) {
        toast.show('两次输入密码不一致')
        return false
    }

    if (showProtocol.value && !isProtocol.value) {
        toast.show('请阅读《服务协议》与《隐私协议》')
        return false
    }

    if (config.value.force_mobile) {
        if (!formData.mobile) {
            toast.show('请填写手机号码')
            return false
        }

        if (!formData.mobile) {
            toast.show('请填写验证码')
            return false
        }
    }

    locking.value = true
    await loginApi.register({
        account: formData.account,
        password: formData.password,
        mobile: formData.mobile,
        code: formData.code
    }).then(async (res) => {
        toast.show('注册成功')
        await userStore.login(res.token)
        setTimeout(() => {
            uni.reLaunch({
                url: '/pages/user/index'
            })
            locking.value = false
        }, 1000)
    }).finally(() => {
        setTimeout(() => {
            locking.value = false
        }, 1500)
    })
}

onShow(() => {
    if (userStore.isLogin) {
        uni.reLaunch({
            url: '/pages/index/index'
        })
    }
})
</script>

<style scoped lang="scss">
.register {
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
