<template>
    <view class="recover flex-1 flex flex-col bg-page">
        <w-widget-bar />

        <view class="text-6xl text-tx-primary font-bold px-10 mt-10">
            找回密码
        </view>

        <view class="text-sm text-tx-placeholder px-10 mt-2">
            请输入您账号绑定的手机号找回
        </view>

        <view class="w-full px-10 mt-10">
            <wd-form v-if="step === 1" ref="form" :model="formData">
                <!-- 手机号码 -->
                <view class="flex items-center py-1.5 px-4 mt-4 rounded-[60rpx] bg-lighter">
                    <wd-icon name="mobile" size="38rpx" />
                    <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
                    <wd-input
                        v-model="formData.mobile"
                        :maxlength="50"
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
                        :maxlength="50"
                        prop="code"
                        clearable
                        clear-trigger="focus"
                        placeholder="请输入验证码"
                    />
                    <w-verify-code
                        type="mobile"
                        :mobile="formData.mobile"
                        :scene="NoticeEnum.FORGET_PWD"
                    />
                </view>
                <!-- 下一步 -->
                <view class="mt-6">
                    <wd-button
                        type="primary"
                        size="large"
                        :disabled="!formData.mobile || !formData.code || locking"
                        :locking="locking"
                        :block="true"
                        @click="verifyMobile"
                    >
                        下一步
                    </wd-button>
                </view>
            </wd-form>

            <wd-form v-if="step === 2" ref="form" :model="formData">
                <!-- 新的密码 -->
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
                        v-model="formData.ackPassword"
                        :maxlength="50"
                        prop="password"
                        clearable
                        show-password
                        clear-trigger="focus"
                        placeholder="请再次密码"
                        placeholder-class="!text-tx-placeholder"
                    />
                </view>
                <!-- 确认 -->
                <view class="mt-6">
                    <wd-button
                        type="primary"
                        size="large"
                        :disabled="locking"
                        :locking="locking"
                        :block="true"
                        @click="handleSubmit"
                    >
                        确认修改
                    </wd-button>
                </view>
            </wd-form>
        </view>
    </view>
</template>

<script setup lang="ts">
import { useToast } from 'wot-design-uni'
import { NoticeEnum } from '@/enums/notice'
import indexApi from '@/api/index'
import userApi from '@/api/user'

uni.setNavigationBarTitle({title: ''})

const toast = useToast()

// 当前步骤
const step = ref<number>(1)

// 表单参数
const locking = ref<boolean>(false)
const formData = reactive<{
    code: string;
    mobile: string;
    password: string;
    ackPassword: string;
}>({
    code: '',
    mobile: '',
    password: '',
    ackPassword: ''
})

/**
 * 验证码手机号码
 */
const verifyMobile = async () => {
    locking.value = true
    toast.loading('正在验证手机号码')

    await indexApi.verifyCode({
        scene: NoticeEnum.FORGET_PWD,
        code: formData.code
    }).then((res) => {
        setTimeout(() => {
            if (res.state) {
                step.value = 2
            } else {
                toast.show('验证失败')
            }
        }, 1800)
    }).finally(() => {
        setTimeout(() => {
            toast.close()
            locking.value = false
        }, 1500)
    })
}

/**
 * 发起重置
 */
const handleSubmit = async () => {
    if (formData.password !== formData.ackPassword) {
        return toast.warning('两次输入密码不一致')
    }

    locking.value = true
    await userApi.forgetPwd({
        code: formData.code,
        mobile: formData.mobile,
        newPassword: formData.password
    }).then(() => {
        toast.success('重置成功')
        setTimeout(() => {
            uni.reLaunch({
                url: '/pages/login/index'
            })
            locking.value = false
        }, 1000)
    }).finally(() => {
        setTimeout(() => {
            locking.value = false
        }, 1500)
    })
}
</script>

<style scoped lang="scss">
.recover {
    :deep(.wd-input) {
        flex: 1;
        background: transparent;
        &::after {
            height: 0;
        }
        .wd-input__clear {
            background: transparent;
        }
    }
}
</style>
