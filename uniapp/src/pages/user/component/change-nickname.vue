<template>
    <view class="title">修改昵称</view>
    <u-form ref="uForm">
        <u-form-item>
            <u-input v-model="formValue" placeholder="请输入昵称" :border="false" />
        </u-form-item>
    </u-form>
    <view class="py-30">
        <u-button type="normal" shape="circle" @click="onUpdateUser()">确定</u-button>
    </view>
</template>

<script setup>
import { ref, watch, defineEmits } from 'vue'
import UserApi from '@/api/UserApi'
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

    try {
        await UserApi.edit({
            scene: 'nickname',
            value: formValue.value
        })
    } catch (e) {
        return
    }

    emit('close')
    setTimeout(() => {
        uni.$u.toast('修改成功')
    }, 100)
}
</script>
