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
                    type="primary"
                    hover-class="none"
                    size="mini"
                    shape="circle"
                    @tap="onSendEmail()"
                >{{ codeTips }}
                </u-button>
            </template>
        </u-form-item>
    </u-form>
    <w-button pt="30" pb="30" @on-click="onBindEmail()">确定</w-button>
</template>

<script setup>
import { ref, watch, defineEmits } from 'vue'
import { sendEmailApi } from '@/api/indexApi'
import { bindEmailApi } from '@/api/userApi'
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

    if (uCodeRef.value?.canGetCode) {
        await sendEmailApi({
            scene: smsEnum.BIND_EMAIL,
            email: form.value.email
        })
        uCodeRef.value?.start()
    }
}

// 绑定邮箱
const onBindEmail = async () => {
    if (checkUtil.isEmpty(form.value.mobile)) {
        return uni.$u.toast('请输入邮箱号')
    }

    if (checkUtil.isEmpty(form.value.code)) {
        return uni.$u.toast('请输入验证码')
    }

    try {
        await bindEmailApi(form.value)
    } catch (e) {
        return
    }

    emit('close')
    setTimeout(() => {
        uni.$u.toast('绑定成功')
    }, 100)
}

</script>
