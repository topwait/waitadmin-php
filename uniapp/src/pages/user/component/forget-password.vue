<template>
    <view class="title">忘记密码</view>
    <u-form ref="uForm" :model="form">
        <u-form-item>
            <u-input v-model="form.newPassword" placeholder="请输入新的密码" :border="false" />
        </u-form-item>
        <u-form-item>
            <u-input v-model="form.ackPassword" placeholder="请再次确认密码" :border="false" />
        </u-form-item>
        <u-form-item>
            <u-input v-model="form.mobile" placeholder="绑定的手机号" :border="false" />
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
                    @click="onSendSms()"
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
            @click="onPwdEdit()"
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
    newPassword: '',
    ackPassword: '',
    mobile: '',
    code: ''
})

// 监听属性
watch(() => props.value,
    (val) => {
        form.value.mobile = val
    }, { immediate: true }
)

// 验证码值
const codeTips = ref('')
const uCodeRef = ref()
const codeChange = (text) => {
    codeTips.value = text
}

// 发送短信
const onSendSms = async () => {
    if (checkUtil.isEmpty(form.value.mobile)) {
        return uni.$u.toast('请输入手机号')
    }

    if (!checkUtil.isMobile(form.value.mobile)) {
        return uni.$u.toast('非法的手机号')
    }

    if (form.value?.canGetCode) {
        await indexApi.sendSms({
            scene: smsEnum.FORGET_PWD,
            mobile: form.value.mobile
        }).then(() => {
            uCodeRef.value?.start()
        }).catch(() => {})
    }
}

// 密码修改
const { loading, methodAPI:$forgetPwdApi } = useLock(userApi.forgetPwd)
const onPwdEdit = async () => {
    if (checkUtil.isEmpty(form.value.newPassword)) {
        return uni.$u.toast('请输入新的密码')
    }

    if (checkUtil.isEmpty(form.value.ackPassword)) {
        return uni.$u.toast('请输入确认密码')
    }

    if (form.value.newPassword !== form.value.ackPassword) {
        return uni.$u.toast('两次不密码不一致')
    }

    await $forgetPwdApi(form.value).then(() => {
        emit('close')
        setTimeout(() => {
            uni.$u.toast('修改成功')
        }, 100)
    }).catch(() => {})
}
</script>
