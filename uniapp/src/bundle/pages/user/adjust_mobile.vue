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
            <!-- 手机号输入 -->
            <view
                class="flex items-center p-3.5 rounded-xl bg-lighter border-2"
                :class="isFocusMobile ? 'border-primary' : 'border-transparent'"
            >
                <view class="flex items-center">
                    +86
                    <wd-icon name="caret-down-small" size="22px" />
                </view>
                <view class="w-[1px] h-[40rpx] mx-2.5 ml-1 bg-light" />
                <wd-input
                    v-model="formData.mobile"
                    :disabled="locking"
                    :no-border="true"
                    prop="mobile"
                    type="text"
                    placeholder="请输入手机号"
                    custom-class="!bg-transparent"
                    @focus="isFocusMobile = true"
                    @blur="isFocusMobile = false"
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
                    type="mobile"
                    :mobile="formData.mobile"
                    :scene="NoticeEnum.BIND_MOBILE"
                />
            </view>

            <!-- 提交按钮 -->
            <view class="mt-8">
                <wd-button
                    type="primary"
                    size="large"
                    :block="true"
                    :loading="locking"
                    :disabled="locking || !formData.mobile || !formData.code"
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
    mobile: string;
    code: string;
}>({
    password: '',
    mobile: '',
    code: ''
})

// 是否聚焦
const nextStep = ref<number>(1)
const isFocusPwd = ref<boolean>(false)
const isFocusCode = ref<boolean>(false)
const isFocusMobile = ref<boolean>(false)

// 错误信息
const errorTips = ref<string>('')

/**
 * 验证密码
 */
const checkPassword = async () => {
    locking.value = true
    await userApi.checkPwd(formData.password)
        .then((res) => {
            if (res.status) {
                toast.show('校验通过')
                setTimeout(() => {
                    nextStep.value = 2
                    locking.value = false
                }, 600)
            } else {
                toast.show('密码校验不通过')
            }
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
    if (!formData.mobile) {
        toast.show('请填写手机号')
        return false
    }

    if (!validateUtil.isMobile(formData.mobile)) {
        toast.show('手机号无效')
        return false
    }

    if (!formData.code) {
        toast.show('请输入验证码')
        return false
    }

    locking.value = true
    await userApi.bindMobile({
        password: formData.password,
        mobile: formData.mobile,
        code: formData.code
    }).then(() => {
        toast.show('修改成功')
        setTimeout(() => {
            uni.navigateBack()
        }, 1000)
    }).catch(() => {

    }).finally(() => {
        setTimeout(() => {
            locking.value = false
        }, 1500)
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
