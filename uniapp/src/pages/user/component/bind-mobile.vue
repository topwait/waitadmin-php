<template>
    <view class="title">{{ value ? '变更手机' : '绑定手机' }}</view>
    <u-form ref="uForm" :model="form">
        <u-form-item>
            <u-input v-model="form.mobile" placeholder="请输入新手机号" :border="false" />
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
                    @click="onSendSms()"
                >{{ codeTips }}
                </u-button>
            </template>
        </u-form-item>
    </u-form>
    <w-button pt="30" pb="30" @on-click="onBindMobile">确定</w-button>
</template>

<script setup>
import { ref, defineEmits } from 'vue'
import { sendSmsApi } from '@/api/indexApi'
import { bindMobileApi } from '@/api/userApi'
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
    type: '',
    mobile: '',
    code: ''
})

// 验证码值
const codeTips = ref('')
const uCodeRef = ref()
const codeChange = (text) => {
    codeTips.value = text
}

// 发送邮件
const onSendSms = async () => {
    if (checkUtil.isEmpty(form.value.email)) {
        return uni.$u.toast('请输入邮箱号')
    }

    if (uCodeRef.value?.canGetCode) {
        await sendSmsApi({
            scene: smsEnum.BIND_EMAIL,
            email: form.value.email
        })
        uCodeRef.value?.start()
    }
}

// 绑定手机
const onBindMobile = async (e) => {
    if (e === undefined || !e.detail.code) {
        if (checkUtil.isEmpty(form.value.mobile)) {
            return uni.$u.toast('请输入手机号')
        }

        if (checkUtil.isEmpty(form.value.code)) {
            return uni.$u.toast('请输入验证码')
        }

        form.value.type = props.value ? 'change' : 'bind'
        try {
            await bindMobileApi(form.value)
        } catch (e) { return }

        emit('close')
        setTimeout(() => {
            uni.$u.toast('绑定成功')
        }, 100)
    } else {
        try {
            await bindMobileApi({
                type: form.value.type,
                code: e.detail.code
            })
        } catch (e) { return }

        emit('close')
        setTimeout(() => {
            uni.$u.toast('绑定成功')
        }, 100)
    }
}
</script>
