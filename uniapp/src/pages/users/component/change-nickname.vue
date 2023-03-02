<template>
    <view class="title">修改昵称</view>
    <u-form-item>
        <u-input v-model="formValue" placeholder="请输入昵称" :border="false" />
    </u-form-item>
    <w-button pt="30" pb="30" @on-click="onUpdateUser()">确定</w-button>
</template>

<script setup>
import { ref, watch, defineEmits } from 'vue'
import { userEditApi } from '@/api/usersApi'

// 定义事件
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

// 更新昵称
const onUpdateUser = async () => {
    await userEditApi({
        scene: 'nickname',
        value: formValue.value
    })

    emit('close')
}
</script>
