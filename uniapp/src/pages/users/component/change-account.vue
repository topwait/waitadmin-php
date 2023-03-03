<template>
    <view class="title">修改账号</view>
    <u-form ref="uForm">
        <u-form-item>
            <u-input v-model="formValue" placeholder="请输入账号" :border="false" />
        </u-form-item>
    </u-form>
    <w-button pt="30" pb="30" @on-click="onUpdateUser()">确定</w-button>
</template>

<script setup>
import { ref, watch, defineEmits } from 'vue'
import { userEditApi } from '@/api/usersApi'
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
const onUpdateUser = async () => {
    if (checkUtil.isEmpty(formValue.value)) {
        return uni.$u.toast('账号不允许为空')
    }

    if (props.value === formValue.value) {
        return uni.$u.toast('账号未发生改变')
    }

    await userEditApi({
        scene: 'account',
        value: formValue.value
    })

    uni.$u.toast('修改成功')
    emit('close')
}
</script>
