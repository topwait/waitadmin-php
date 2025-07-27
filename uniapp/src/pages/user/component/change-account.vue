<template>
    <view class="title">修改账号</view>
    <u-form ref="uForm">
        <u-form-item>
            <u-input v-model="formValue" placeholder="请输入账号" :border="false" />
        </u-form-item>
    </u-form>
    <view class="py-30">
        <u-button
            :loading="loading"
            type="theme"
            shape="circle"
            @click="onUpdateUser()"
        >确定</u-button>
    </view>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useLock } from '@/hooks/useLock'
import userApi from '@/api/userApi'
import checkUtil from '@/utils/checkUtil'

// 定义事件s
const emit = defineEmits(['close'])

// 接收参数
const props = defineProps({
    value: {
        type: String,
        default: ''
    }
})

// 表单参数
const formValue = ref('')

// 监听属性
watch(() => props.value,
    (val) => {
        formValue.value = val
    }, { immediate: true }
)

// 更新用户
const { loading, methodAPI:$userEditApi } = useLock(userApi.edit)
const onUpdateUser = async () => {
    if (checkUtil.isEmpty(formValue.value)) {
        return uni.$u.toast('账号不允许为空')
    }

    if (props.value === formValue.value) {
        return uni.$u.toast('账号未发生改变')
    }

    await $userEditApi({
        scene: 'account',
        value: formValue.value
    }).then(() => {
        emit('close')
        setTimeout(() => {
            uni.$u.toast('修改成功')
        }, 100)
    }).catch(() => {})
}
</script>
