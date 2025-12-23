<template>
    <wd-toast />
    <wd-form :model="formData">
        <!-- 账号输入 -->
        <view class="flex items-center py-1.5 px-4 rounded-[60rpx] bg-lighter">
            <wd-icon name="user" size="38rpx" />
            <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
            <wd-input
                v-model="formData.account"
                :maxlength="50"
                :disabled="locking"
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
                :disabled="locking"
                prop="password"
                clearable
                show-password
                clear-trigger="focus"
                placeholder="请输入密码"
                placeholder-class="!text-tx-placeholder"
            />
        </view>

        <!-- 服务协议 -->
        <view v-if="showProtocol" class="mt-4 ml-2">
            <auth-protocol v-model="isProtocol" />
        </view>

        <!-- 提交按钮 -->
        <view class="mt-4">
            <wd-button
                type="primary"
                size="large"
                block
                :loading="locking"
                :disabled="isFormValid"
                @click="handleSubmit"
            >
                登录
            </wd-button>
        </view>

        <!-- 账号服务 -->
        <view class="flex items-center justify-end gap-x-2 mt-4">
            <view
                v-if="showRegister"
                class="ml-1 text-sm text-primary"
                @click="$go('/pages/login/register')"
            >
                立即注册
            </view>
            <view
                v-if="showRegister"
                class="text-tx-placeholder text-xs"
            >
                |
            </view>
            <view class="text-right text-sm text-primary mr-2">
                <text @click="$go('/pages/login/recover')">忘记密码?</text>
            </view>
        </view>
    </wd-form>
</template>

<script setup lang="ts">
import { useToast } from 'wot-design-uni'
import useUserStore from '@/stores/user'
import loginApi from '@/api/login'
import AuthProtocol from './auth-protocol.vue'

const props = defineProps({
    // 是否显示协议
    showProtocol: {
        type: Boolean,
        default: true
    },
    // 显示注册按钮
    showRegister: {
        type: Boolean,
        default: true
    }
})

const toast = useToast()
const userStore = useUserStore()

// 协议参数
const isProtocol = ref<boolean>(false)

// 表单参数
const locking = ref<boolean>(false)
const formData = reactive<{
    account: string;
    password: string;
}>({
    account: '',
    password: ''
})

// 表单检测
const isFormValid = computed<boolean>(() => {
    return !(
        formData.account.trim() &&
        formData.password.trim() &&
        !locking.value
    )
})

/**
 * 账号登录
 */
const handleSubmit = () => {
    if (!formData.account) {
        toast.show('请输入登录账号')
        return false
    }

    if (!formData.password) {
        toast.show('请输入登录密码')
        return false
    }

    if (props.showProtocol && !isProtocol.value) {
        toast.show('请阅读《服务协议》与《隐私协议》')
        return false
    }

    locking.value = true
    loginApi.accountLogin({
        account: formData.account,
        password: formData.password
    }).then(async (res) => {
        await userStore.login(res.token)
        toast.show('登录成功')
        setTimeout(() => {
            uni.reLaunch({
                url: '/pages/user/index'
            })
            locking.value = false
        }, 1000)
    }).catch(() => {
        setTimeout(() => {
            locking.value = false
        }, 1500)
    })
}
</script>
