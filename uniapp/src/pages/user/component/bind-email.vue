<template>
    <view class="title">{{ value ? '变更邮箱' : '绑定邮箱' }}</view>
    <u-form ref="uForm" :model="form">
        <u-form-item>
            <u-input v-model="form.email" placeholder="请输入邮箱号" :border="false" />
        </u-form-item>
        <u-form-item>
            <u-input v-model="form.code" placeholder="验证码" :border="false" />
            <template #right>
                <u-verification-code ref="uCodeRef" seconds="60" @change="codeChange" />
                <u-button
                    :plain="true"
                    type="theme"
                    hover-class="none"
                    size="mini"
                    shape="circle"
                    @click="onSendEmail()"
                >{{ codeTips }}
                </u-button>
            </template>
        </u-form-item>
    </u-form>
    <view class="py-30">
        <u-button
            :loading="loading"
            type="theme"
            shape="circle"
            @click="onBindEmail()"
        >确定</u-button>
    </view>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useLock } from '@/hooks/useLock'
import indexApi from '@/api/indexApi'
import userApi from '@/api/userApi'
import smsEnum from '@/enums/smsEnum'
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
    email: '',
    code: ''
})

// 监听属性
watch(() => props.value,
    (val) => {
        form.value.email = val
    }, { immediate: true }
)

// 验证码值
const codeTips = ref('')
const uCodeRef = ref()
const codeChange = (text) => {
    codeTips.value = text
}

// 发送邮件
const onSendEmail = async () => {
    if (checkUtil.isEmpty(form.value.email)) {
        return uni.$u.toast('请输入邮箱号')
    }

    if (!checkUtil.isEmail(form.value.email)) {
        return uni.$u.toast('非法的邮箱号')
    }

    if (uCodeRef.value?.canGetCode) {
        await indexApi.sendEmail({
            scene: smsEnum.BIND_EMAIL,
            email: form.value.email
        }).then(() => {
            uCodeRef.value?.start()
        }).catch(() => {})
    }
}

// 绑定邮箱
const { loading, methodAPI:$bindEmailApi } = useLock(userApi.bindEmail)
const onBindEmail = async () => {
    if (checkUtil.isEmpty(form.value.email)) {
        return uni.$u.toast('请输入邮箱号')
    }

    if (!checkUtil.isEmail(form.value.email)) {
        return uni.$u.toast('非法的邮箱号')
    }

    if (checkUtil.isEmpty(form.value.code)) {
        return uni.$u.toast('请输入验证码')
    }

    await $bindEmailApi(form.value).then(() => {
        emit('close')
        setTimeout(() => {
            uni.$u.toast('绑定成功')
        }, 100)
    }).catch(() => {})
}
</script>
