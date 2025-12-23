<template>
    <wd-toast />
    <wd-form v-if="scene === 'normal'" ref="form" :model="formData">
        <view class="mt-4">
            <wd-button
                type="primary"
                size="large"
                block
                :loading="wxLocking"
                :disabled="wxLocking"
                @click="mnpLogin"
            >
                微信登录
            </wd-button>
        </view>
        <view class="mt-4">
            <wd-button
                type="info"
                size="large"
                plain
                block
                :disabled="wxLocking"
                custom-class="!text-tx-placeholder !border-br-light"
                @click="$go('/pages/index/index', 'reLaunch')"
            >
                暂不登录
            </wd-button>
        </view>
        <view v-if="showProtocol" class="mt-4 ml-2">
            <auth-protocol v-model="isProtocol" />
        </view>
    </wd-form>

    <wd-popup
        :model-value="formData.show"
        position="bottom"
        custom-style="border-radius:32rpx;"
        @close="handleClose"
    >
        <view class="p-4 text-center">
            <view class="text-xl text-tx-primary font-bold">绑定手机</view>
            <view class="text-xs text-tx-placeholder mt-2">您需要绑定手机完成登录操作</view>
        </view>

        <view class="p-4 pt-2">
            <!-- 手机号输入 -->
            <view class="flex items-center py-1.5 px-4 rounded-[60rpx] bg-lighter">
                <view>+86</view>
                <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
                <wd-input
                    v-model="formData.mobile"
                    prop="mobile"
                    type="number"
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
                    placeholder="请输入验证码"
                    placeholder-class="!text-tx-placeholder"
                />
                <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
                <w-verify-code
                    type="mobile"
                    :mobile="formData.mobile"
                    :scene="NoticeEnum.BIND_MOBILE"
                />
            </view>
            <!-- 确认按钮 -->
            <view class="mt-6">
                <wd-button
                    type="primary"
                    size="large"
                    block
                    :loading="bdLocking"
                    :disabled="bdLocking || !formData.mobile || !formData.code"
                    @click="handleBind"
                >
                    确认
                </wd-button>
            </view>
        </view>
    </wd-popup>
</template>

<script setup lang="ts">
import { useToast } from 'wot-design-uni'
import { NoticeEnum } from '@/enums/notice'
import useUserStore from '@/stores/user'
import clientUtil from '@/utils/client'
import toolsUtil from '@/utils/tools'
import loginApi from '@/api/login'
import AuthProtocol from './auth-protocol.vue'

// #ifdef H5
import wechatOa from '@/utils/wechat'
// #endif

const props = defineProps({
    // 来源显示场景
    scene: {
        type: String,
        default: 'normal',
        validator: (value: string) => ['normal', 'bind'].includes(value)
    },
    // 绑定手机盐值
    sign: {
        type: String,
        default: ''
    },
    // 是否显示弹窗
    showPopup: {
        type: Boolean,
        default: false
    },
    // 是否显示协议
    showProtocol: {
        type: Boolean,
        default: true
    }
})

const toast = useToast()
const userStore = useUserStore()

// 锁定按钮
const wxLocking = ref<boolean>(false)
const bdLocking = ref<boolean>(false)

// 协议参数
const isProtocol = ref<boolean>(false)

// 表单参数
const formData = reactive<{
    show: boolean;
    sign: string;
    code: string;
    mobile: string;
}>({
    show: false,
    sign: '',
    code: '',
    mobile: ''
})

/**
 * 微信登录
 */
const mnpLogin = async () => {
    if (props.showProtocol && !isProtocol.value) {
        toast.show('请阅读《服务协议》与《隐私协议》')
        return false
    }

    // #ifdef MP-WEIXIN
    try {
        wxLocking.value = true
        const code: string = await toolsUtil.obtainWxCode()
        const result = await loginApi.wechatLogin(code)
        if (result?.sign) {
            formData.sign = result.sign
            formData.show = true
        } else {
            await __handleSuccess(result.token)
        }
    } finally {
        setTimeout(() => {
            wxLocking.value = false
        }, 1500)
    }
    // #endif

    // #ifdef H5
    if (clientUtil.isWeixin()) {
        wechatOa.authUrl()
    }
    // #endif

    // #ifdef APP-PLUS
    await appLogin()
    // #endif
}

const appLogin = async () => {
    uni.login({
        provider: "weixin",
        success: (res: UniApp.LoginRes) => {
            const data: any = res.authResult || {}
            console.log(data?.openid)
            console.log(data?.access_token)
            // const { openid, access_token } = res.authResult;
        }
    })
}

/**
 * 绑定手机
 */
const handleBind = async () => {
    try {
        bdLocking.value = true
        const result = await loginApi.bindLogin({
            sign: formData.sign,
            code: formData.code,
            mobile: formData.mobile
        })
        await __handleSuccess(result.token)
    } finally {
        setTimeout(() => {
            bdLocking.value = false
        }, 1500)
    }
}

/**
 * 关闭弹窗
 */
const handleClose = () => {
    formData.show = true
    formData.mobile = ''
    formData.code = ''
    formData.sign = ''
}

/**
 * 登录成功处理
 *
 * @param {string} token
 * @param {number} delay
 */
const __handleSuccess = async (token: string, delay: number = 1000) => {
    await userStore.login(token)
    toast.show('登录成功')
    setTimeout(() => {
        uni.reLaunch({
            url: '/pages/user/index'
        })
    }, delay)
}

watch(
    () => props.showPopup,
    (val: boolean) => {
        formData.show = val
        formData.sign = props.sign
    }, {
        immediate: true
    }
)
</script>
