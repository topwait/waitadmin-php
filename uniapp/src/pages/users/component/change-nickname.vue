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
import checkUtil from '@/utils/checkUtil'

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
    if (checkUtil.isEmpty(formValue.value)) {
        return uni.$u.toast('昵称不允许为空')
    }
    
    if (props.value === formValue.value) {
        return uni.$u.toast('昵称未发生改变')
    }

    await userEditApi({
        scene: 'nickname',
        value: formValue.value
    })

    uni.$u.toast('修改成功')
    emit('close')
}
</script>
