<template>
    <wd-toast />
    <wd-form ref="form" :model="formData">
        <!-- 手机号输入 -->
        <view class="flex items-center py-1.5 px-4 rounded-[60rpx] bg-lighter">
            <view>+86</view>
            <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
            <wd-input
                v-model="formData.mobile"
                prop="mobile"
                type="number"
                clearable
                placeholder="请输入手机号码"
                placeholder-class="!text-tx-placeholder"
            />
        </view>
        <!-- 验证码输入 -->
        <view class="flex items-center py-1.5 px-4 mt-4 rounded-[60rpx] bg-lighter">
            <wd-input
                v-model="formData.code"
                prop="code"
                type="number"
                clearable
                placeholder="请输入验证码"
                placeholder-class="!text-tx-placeholder"
            />
            <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
            <w-verify-code
                type="mobile"
                :mobile="formData.mobile"
                :scene="NoticeEnum.LOGIN"
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
                :disabled="locking || !formData.mobile || !formData.code"
                :loading="locking"
                block
                @click="handleSubmit"
            >
                登录
            </wd-button>
        </view>
        <!-- 提示信息 -->
        <view
            v-if="showRegister"
            class="text-xs text-center text-tx-placeholder mt-4"
        >
            若手机号未注册，登录后会自动注册
        </view>
    </wd-form>
</template>

<script setup lang="ts">
import { useToast } from 'wot-design-uni'
import { NoticeEnum } from '@/enums/notice'
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
    mobile: string;
    code: string;
}>({
    mobile: '',
    code: ''
})

/**
 * 登录提交
 */
const handleSubmit = () => {
    if (!formData.mobile) {
        toast.show('请输入手机号')
        return false
    }

    if (!formData.code) {
        toast.show('请输入验证码')
        return false
    }

    if (props.showProtocol && !isProtocol.value) {
        toast.show('请阅读《服务协议》与《隐私协议》')
        return false
    }

    locking.value = true
    loginApi.mobileLogin({
        mobile: formData.mobile,
        code: formData.code
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
