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
            @click="onBindMobile()"
        >确定</u-button>
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { useLock } from '@/hooks/useLock'
import indexApi from '@/api/indexApi'
import userApi from '@/api/userApi.js'
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

// 发送短信
const onSendSms = async () => {
    if (checkUtil.isEmpty(form.value.mobile)) {
        return uni.$u.toast('请输入手机号')
    }

    if (!checkUtil.isMobile(form.value.mobile)) {
        return uni.$u.toast('非法的手机号')
    }

    if (uCodeRef.value?.canGetCode) {
        await indexApi.sendSms({
            scene: smsEnum.BIND_MOBILE,
            mobile: form.value.mobile
        })
        uCodeRef.value?.start()
    }
}

// 绑定手机
const { loading, methodAPI:$bindMobileApi } = useLock(userApi.bindMobile)
const onBindMobile = async (e) => {
    if (e === undefined || !e.detail.code) {
        if (checkUtil.isEmpty(form.value.mobile)) {
            return uni.$u.toast('请输入手机号')
        }

        if (!checkUtil.isMobile(form.value.mobile)) {
            return uni.$u.toast('非法的手机号')
        }

        if (checkUtil.isEmpty(form.value.code)) {
            return uni.$u.toast('请输入验证码')
        }

        form.value.type = props.value ? 'change' : 'bind'
        await $bindMobileApi(form.value).then(() => {
            emit('close')
            setTimeout(() => {
                uni.$u.toast('绑定成功')
            }, 100)
        }).catch(() => {})
    } else {
        await $bindMobileApi({
            type: form.value.type,
            code: e.detail.code
        }).then(() => {
            emit('close')
            setTimeout(() => {
                uni.$u.toast('绑定成功')
            }, 100)
        }).catch(() => {})
    }
}
</script>
