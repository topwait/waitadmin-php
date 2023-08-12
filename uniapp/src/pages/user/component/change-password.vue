<template>
    <view class="title">修改密码</view>
    <u-form ref="uForm" :model="form">
        <u-form-item>
            <u-input v-model="form.oldPassword" type="password" placeholder="请输入原始密码" :border="false" />
        </u-form-item>
        <u-form-item>
            <u-input v-model="form.newPassword" placeholder="请输入新的密码" :border="false" />
        </u-form-item>
        <u-form-item>
            <u-input v-model="form.ackPassword" placeholder="请再次确认密码" :border="false" />
        </u-form-item>
    </u-form>
    <view class="py-30">
        <u-button
            :loading="loading"
            type="theme"
            shape="circle"
            @click="onPwdEdit()"
        >确定</u-button>
    </view>
</template>

<script setup>
import { ref, watch, defineEmits } from 'vue'
import { useLock } from '@/hooks/useLock'
import userApi from '@/api/userApi.js'
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
const form = ref({
    oldPassword: '',
    newPassword: '',
    ackPassword: ''
})

// 监听属性
watch(() => props.value,
    (val) => {
        form.value.mobile = val
    }, { immediate: true }
)

// 密码修改
const { loading, methodAPI:$changePwdApi } = useLock(userApi.changePwd)
const onPwdEdit = async () => {
    if (checkUtil.isEmpty(form.value.oldPassword)) {
        return uni.$u.toast('请输入原始密码')
    }

    if (checkUtil.isEmpty(form.value.newPassword)) {
        return uni.$u.toast('请输入新的密码')
    }

    if (checkUtil.isEmpty(form.value.ackPassword)) {
        return uni.$u.toast('请输入确认密码')
    }

    if (form.value.newPassword !== form.value.ackPassword) {
        return uni.$u.toast('两次不密码不一致')
    }

    await $changePwdApi(form.value).then(() => {
        emit('close')
        setTimeout(() => {
            uni.$u.toast('修改成功')
        }, 100)
    }).catch(() => {})
}
</script>
