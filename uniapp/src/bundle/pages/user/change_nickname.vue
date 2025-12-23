<template>
    <view class="flex-1 p-6 pt-10 border-t border-br-thinned bg-page">
        <wd-form ref="form" :model="formData" class="w-full">
            <!-- 昵称输入 -->
            <view
                class="p-3.5 rounded-xl bg-lighter border-2"
                :class="errorTips
                    ? 'border-danger'
                    : isFocus ? 'border-primary' : 'border-transparent'"
            >
                <wd-input
                    v-model="formData.nickname"
                    :disabled="locking"
                    :no-border="true"
                    prop="nickname"
                    type="text"
                    placeholder="请输入昵称"
                    custom-class="!bg-transparent"
                    @focus="isFocus = true"
                    @blur="isFocus = false"
                />
            </view>

            <!-- 错误提示 -->
            <view class="pt-2 pl-3 text-sm text-danger h-4.5">
                <view v-if="errorTips">{{ errorTips }}</view>
            </view>

            <!-- 账号规则 -->
            <view class="mt-8 px-2 text-sm text-tx-placeholder">
                昵称必须符合国家法律法规，并且长度2~20个字符内容。
            </view>

            <!-- 提交按钮 -->
            <view class="mt-4">
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
import useUserStore from '@/stores/user.ts'
import userApi from '@/api/user'

const toast = useToast()
const userStore = useUserStore()

// 表单参数
const locking = ref<boolean>(false)
const formData = reactive<{
    nickname: string;
}>({
    nickname: userStore.userInfo.nickname || ''
})

// 是否聚焦
const isFocus = ref<boolean>(false)

// 错误提示
const errorTips = ref<string>('')

// 是否禁用
const isDisabled = computed(() => {
    return locking.value || !!errorTips.value || !formData.nickname
})

/**
 * 发起提交
 */
const handleSubmit = async () => {
    const nickname: string = userStore.userInfo.nickname
    if (formData.nickname === nickname) {
        toast.show('与原昵称一致,无需修改')
        return false
    }

    locking.value = true
    await userApi.edit({
        scene: 'nickname',
        value: formData.nickname
    }).then(async () => {
        toast.show('修改成功')
        await userStore.getUser()
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
 * 监听昵称
 */
watch(
    () => formData.nickname,
    (val: string) => {
        if (!val) {
            errorTips.value = '昵称不能为空'
        } else if (val.length <= 1 || val.length > 20) {
            errorTips.value = '昵称长度不合法，应为2-20个字符'
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
