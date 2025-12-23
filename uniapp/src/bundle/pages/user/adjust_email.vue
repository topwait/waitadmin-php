<template>
    <view class="flex-1 p-6 pt-10 border-t border-br-thinned bg-page">
        <wd-form v-if="nextStep === 1" :model="formData" class="w-full">
            <view
                class="flex items-center p-3.5 rounded-xl bg-lighter border-2"
                :class="errorTips
                    ? 'border-danger'
                    : isFocusPwd ? 'border-primary' : 'border-transparent'"
            >
                <wd-input
                    v-model="formData.password"
                    :disabled="locking"
                    :no-border="true"
                    prop="password"
                    show-password
                    placeholder="请输入登录密码"
                    custom-class="flex-1 !bg-transparent"
                    @focus="isFocusPwd = true"
                    @blur="isFocusPwd = false"
                />
            </view>

            <!-- 错误提示 -->
            <view class="pt-2 pl-3 text-sm text-danger h-4.5">
                <view v-if="errorTips">{{ errorTips }}</view>
            </view>

            <!-- 密码规则 -->
            <view class="mt-8 px-2 text-sm text-tx-placeholder">
                为了确保您是账户拥有者，现需要对您进行登录密码安全检验，
                验证通过后方可进行修改。
            </view>

            <!-- 校验按钮 -->
            <view class="mt-6">
                <wd-button
                    type="primary"
                    size="large"
                    :block="true"
                    :loading="locking"
                    :disabled="locking || !formData.password"
                    @tap="checkPassword"
                >
                    开始校验
                </wd-button>
            </view>
        </wd-form>

        <wd-form v-if="nextStep === 2" :model="formData" class="w-full">
            <!-- 邮箱号输入 -->
            <view
                class="flex items-center p-3.5 rounded-xl bg-lighter border-2"
                :class="isFocusEmail ? 'border-primary' : 'border-transparent'"
            >
                <wd-icon name="mail" size="38rpx" class="text-tx-placeholder" />
                <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
                <wd-input
                    v-model="formData.email"
                    :disabled="locking"
                    :no-border="true"
                    prop="mobile"
                    type="text"
                    placeholder="请输邮箱号"
                    custom-class="!bg-transparent"
                    @focus="isFocusEmail = true"
                    @blur="isFocusEmail = false"
                />
            </view>

            <!-- 验证码输入 -->
            <view
                class="flex items-center p-3.5 mt-4 rounded-xl bg-lighter border-2"
                :class="isFocusCode ? 'border-primary' : 'border-transparent'"
            >
                <wd-input
                    v-model="formData.code"
                    :no-border="true"
                    prop="code"
                    type="number"
                    placeholder="请输入验证码"
                    custom-class="flex-1 !bg-transparent"
                    @focus="isFocusCode = true"
                    @blur="isFocusCode = false"
                />
                <view class="w-[1px] h-[40rpx] mx-2.5 bg-light" />
                <w-verify-code
                    type="email"
                    :email="formData.email"
                    :scene="NoticeEnum.BIND_EMAIL"
                />
            </view>

            <!-- 提交按钮 -->
            <view class="mt-8">
                <wd-button
                    type="primary"
                    size="large"
                    :block="true"
                    :loading="locking"
                    :disabled="locking || !formData.email || !formData.code"
                    @tap="handleSubmit"
                >
                    确认修改
                </wd-button>
            </view>
        </wd-form>
    </view>
</template>

<script setup lang="ts">
import { useToast } from 'wot-design-uni'
import { NoticeEnum } from '@/enums/notice'
import validateUtil from '@/utils/validate'
import userApi from '@/api/user'

const toast = useToast()

// 表单参数
const locking = ref<boolean>(false)
const formData = reactive<{
    password: string;
    email: string;
    code: string;
}>({
    password: '',
    email: '',
    code: ''
})

// 是否聚焦
const nextStep = ref<number>(1)
const isFocusPwd = ref<boolean>(false)
const isFocusCode = ref<boolean>(false)
const isFocusEmail = ref<boolean>(false)

// 错误信息
const errorTips = ref<string>('')

/**
 * 验证密码
 */
const checkPassword = async () => {
    locking.value = true
    await userApi.checkPwd(formData.password)
        .then(() => {
            nextStep.value = 2
        }).catch(() => {

        }).finally(() => {
            setTimeout(() => {
                locking.value = false
            }, 1000)
        })
}

/**
 * 发起提交
 */
const handleSubmit = async () => {
    if (!formData.email) {
        toast.show('请填写邮箱号')
        return false
    }

    if (!validateUtil.isEmail(formData.email)) {
        toast.show('电子邮箱无效')
        return false
    }

    if (!formData.code) {
        toast.show('请输入验证码')
        return false
    }

    locking.value = true
    await userApi.bindEmail({
        password: formData.password,
        email: formData.email,
        code: formData.code
    }).then(() => {
        toast.show('修改成功')
        uni.navigateBack()
    }).catch(() => {

    }).finally(() => {
        setTimeout(() => {
            locking.value = false
        }, 800)
    })
}

/**
 * 监听密码
 */
watch(
    () => formData.password,
    (val: string) => {
        if (!val) {
            errorTips.value = '密码不能为空'
        } else if (val.length < 6 || val.length > 20) {
            errorTips.value = '密码长度不合法'
        } else {
            errorTips.value = ''
        }
    }
)
</script>

<style scoped lang="scss">
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
</style>
