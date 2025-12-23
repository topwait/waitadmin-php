<template>
    <view class="flex-1 p-6 pt-10 border-t border-br-thinned bg-page">
        <wd-form v-if="nextStep === 1" :model="formData" class="w-full">
            <view
                class="flex items-center p-3.5 rounded-xl bg-lighter border-2"
                :class="errorOld
                    ? 'border-danger'
                    : isFocusOld ? 'border-primary' : 'border-transparent'"
            >
                <wd-input
                    v-model="formData.oldPassword"
                    :disabled="locking"
                    :no-border="true"
                    prop="password"
                    show-password
                    placeholder="请输入登录密码"
                    custom-class="flex-1 !bg-transparent"
                    @focus="isFocusOld = true"
                    @blur="isFocusOld = false"
                />
            </view>

            <!-- 错误提示 -->
            <view class="pt-2 pl-3 text-sm text-danger h-4.5">
                <view v-if="errorOld">{{ errorOld }}</view>
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
                    :disabled="locking || !formData.oldPassword"
                    @tap="checkPassword"
                >
                    开始校验
                </wd-button>
            </view>
        </wd-form>

        <wd-form v-if="nextStep === 2" :model="formData" class="w-full">
            <!-- 输入新密码 -->
            <view>
                <view class="mb-1.5 text-tx-primary">输入新密码</view>
                <view
                    class="p-3.5 rounded-xl bg-lighter border-2"
                    :class="errorNew
                        ? 'border-danger'
                        : isFocusNew ? 'border-primary' : 'border-transparent'"
                >
                    <wd-input
                        v-model="formData.newPassword"
                        :disabled="locking"
                        :no-border="true"
                        prop="nickname"
                        type="text"
                        show-password
                        placeholder="请输入6-20位新密码"
                        custom-class="!bg-transparent"
                        @focus="isFocusNew = true"
                        @blur="isFocusNew = false"
                    />
                </view>
                <view class="pt-2 pl-3 text-sm text-danger h-4.5">
                    <view v-if="errorNew">{{ errorNew }}</view>
                </view>
            </view>

            <!-- 确认新密码 -->
            <view class="mt-4">
                <view class="mb-1.5 text-tx-primary">确认新密码</view>
                <view
                    class="p-3.5 rounded-xl bg-lighter border-2"
                    :class="errorAck
                        ? 'border-danger'
                        : isFocusAck ? 'border-primary' : 'border-transparent'"
                >
                    <wd-input
                        v-model="formData.ackPassword"
                        :disabled="locking"
                        :no-border="true"
                        prop="nickname"
                        type="text"
                        show-password
                        placeholder="请再次输入6-20位新密码"
                        custom-class="!bg-transparent"
                        @focus="isFocusAck = true"
                        @blur="isFocusAck = false"
                    />
                </view>
                <view class="pt-2 pl-3 text-sm text-danger h-4.5">
                    <view v-if="errorAck">{{ errorAck }}</view>
                </view>
            </view>

            <!-- 密码规则 -->
            <view class="mt-4 px-2 text-sm text-tx-placeholder">
                密码必须是6-20个英文字符、数字或符(除空格)，
                且字母、数字和标点符号至少包含两种。
            </view>

            <!-- 提交按钮 -->
            <view class="mt-8">
                <wd-button
                    type="primary"
                    size="large"
                    :block="true"
                    :loading="locking"
                    :disabled="isDisabled"
                    @tap="handleSubmit"
                >
                    确定
                </wd-button>
            </view>
        </wd-form>
    </view>
</template>

<script setup lang="ts">
import { useToast } from 'wot-design-uni'
import useUserStore from '@/stores/user'
import userApi from '@/api/user'

const toast = useToast()
const userStore = useUserStore()

// 表单参数
const locking = ref<boolean>(false)
const formData = reactive<{
    oldPassword: string;
    newPassword: string;
    ackPassword: string;
}>({
    oldPassword: '',
    newPassword: '',
    ackPassword: ''
})

// 是否聚焦
const nextStep = ref<number>(userStore.userInfo.is_pwd ? 1 : 2)
const isFocusOld = ref<boolean>(false)
const isFocusNew = ref<boolean>(false)
const isFocusAck = ref<boolean>(false)

// 错误提示
const errorOld = ref<string>('')
const errorNew = ref<string>('')
const errorAck = ref<string>('')

// 是否禁用
const isDisabled = computed(() => {
    return locking.value
        || !!errorNew.value
        || !!errorAck.value
        || !formData.newPassword
        || !formData.ackPassword
})

/**
 * 验证密码
 */
const checkPassword = async () => {
    locking.value = true
    await userApi.checkPwd(formData.oldPassword)
        .then(() => {
            nextStep.value = 2
        }).catch(() => {

        }).finally(() => {
            setTimeout(() => {
                locking.value = false
            }, 800)
        })
}

/**
 * 发起提交
 */
const handleSubmit = async () => {
    locking.value = true
    await userApi.changePwd({
        oldPassword: formData.ackPassword,
        newPassword: formData.newPassword
    }).then(() => {
        toast.show('修改成功')
        setTimeout(() => {
            uni.navigateBack()
        }, 500)
    }).catch(() => {

    }).finally(() => {
        setTimeout(() => {
            locking.value = false
        }, 800)
    })
}

/**
 * 监听新的密码
 */
watch(
    () => formData.newPassword,
    (val: string) => {
        if (!val) {
            errorNew.value = '新的密码不能为空'
        } else if (val.length <= 1 || val.length > 20) {
            errorNew.value = '新的密码长度不合法，因为6-20个字符'
        } else {
            errorNew.value = ''
        }
    }
)

/**
 * 监听确认密码
 */
watch(
    () => formData.ackPassword,
    (val: string) => {
        if (!val) {
            errorAck.value = '确认密码不能为空'
        } else if (val !== formData.newPassword) {
            errorAck.value = '两次密码不一致'
        } else {
            errorAck.value = ''
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
