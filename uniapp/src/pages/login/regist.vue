<template>
    <view :class="themeName">
        <view class="layout-regist-widget">
            <view class="head">
                <view class="backdrop" />
                <view class="title">注册账号</view>
            </view>
            <view class="form">
                <u-form ref="uForm" :model="form">
                    <u-form-item left-icon="account" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="form.account" type="text" placeholder="请输入登录账号" />
                    </u-form-item>
                    <u-form-item left-icon="fingerprint" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="form.password" type="password" placeholder="请输入登录密码" />
                    </u-form-item>
                    <u-form-item left-icon="fingerprint" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="form.againPwd" type="password" placeholder="请再次确认密码" />
                    </u-form-item>
                    <u-form-item left-icon="phone" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="form.mobile" type="number" placeholder="请输入手机号" />
                    </u-form-item>
                    <u-form-item left-icon="lock" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="form.code" type="number" placeholder="请输入验证码" />
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
                <view class="pt-40">
                    <u-button
                        :loading="loading"
                        type="theme"
                        shape="circle"
                        @click="onRegister()"
                    >注册账号</u-button>
                </view>
            </view>
        </view>
    </view>
</template>

<script setup>
import { ref } from 'vue'
import { useLock } from '@/hooks/useLock'
import IndexApi from '@/api/IndexApi'
import LoginApi from '@/api/LoginApi'
import smsEnum from '@/enums/smsEnum'
import checkUtil from '@/utils/checkUtil'

// 设置标题
uni.setNavigationBarTitle({title: ''})

// 表单参数
const form = ref({
    code: '',
    mobile: '',
    account: '',
    password: '',
    againPwd: ''
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
        await IndexApi.sendSms({
            scene: smsEnum.REGISTER,
            mobile: form.value.mobile
        }).then(() => {
            uCodeRef.value?.start()
        }).catch(() => {})
    }
}

// 注册账号
const { loading, methodAPI:$registerApi } = useLock(LoginApi.register)
const onRegister = async () => {
    if (checkUtil.isEmpty(form.value.account)) {
        return uni.$u.toast('请输登录账号')
    }

    if (checkUtil.isEmpty(form.value.password)) {
        return uni.$u.toast('请输登录密码')
    }

    if (checkUtil.isEmpty(form.value.againPwd)) {
        return uni.$u.toast('请输确认密码')
    }

    if (checkUtil.isEmpty(form.value.code)) {
        return uni.$u.toast('请输入验证码')
    }

    if (form.value.password !== form.value.againPwd) {
        return uni.$u.toast('两次密码不一致')
    }

    await $registerApi(form.value).then(() => {
        uni.$u.toast('注册成功')
        uni.navigateBack()
    }).catch(() => {})
}
</script>

<style lang="scss">
.layout-regist-widget {
    .head {
        height: 300rpx;
        background-color: var(--theme-background);
        .backdrop {
            height: 300rpx;
            background-repeat: no-repeat;
            background-size: cover;
            background-color: var(--theme-background);
            background-image: url("/static/bg_head_honour.png");
        }
        .title {
            position: absolute;
            top: 10rpx;
            left: 35%;
            font-size: 48rpx;
            color: #ffffff;
            text-align: center;
        }
    }
    .form {
        margin: 20px;
        margin-top: -180rpx;
        padding: 60rpx;
        border-radius: 14rpx;
        background-color: #ffffff;
        box-shadow: 0 2px 14px 0 rgb(0 0 0 / 8%);
    }
}
</style>
