<template>

    <view class="layout-regist-widget">
        <view class="head"></view>
        <view class="form">
            <u-form ref="uForm" :model="form">
                <u-form-item left-icon="account" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input v-model="form.account" type="text" placeholder="请输入登录账号" />
                </u-form-item>
                <u-form-item left-icon="fingerprint" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input v-model="form.password" type="password" placeholder="请输入登录密码" />
                </u-form-item>
                <u-form-item left-icon="hourglass" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input v-model="form.againPwd" type="password" placeholder="请再次确认密码" />
                </u-form-item>
                <u-form-item left-icon="phone" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input v-model="form.mobile" type="number" placeholder="请输入手机号" />
                </u-form-item>
                <u-form-item left-icon="lock" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input v-model="form.code" type="number" placeholder="请输入验证码" />
                    <template #right>
                        <u-verification-code ref="uCode" seconds="60" />
                        <u-button
                            :plain="true"
                            type="primary"
                            hover-class="none"
                            size="mini"
                            shape="circle"
                        >{{ '获取验证码' }}
                        </u-button>
                    </template>
                </u-form-item>
            </u-form>
            <w-button mt="40" @on-click="onRegister()">注册账号</w-button>
        </view>
    </view>

</template>

<script setup>
import { registerApi } from '@/api/usersApi'
    
// 表单参数
const form = {
    code: '',
    mobile: '',
    account: '',
    password: '',
    againPwd: ''
}

// 注册账号
const onRegister = () => {
    registerApi(form).then(result => {
        
    })
}
</script>

<style lang="scss">
.layout-regist-widget {
    .head {
        height: 200rpx;
        background-color: #2979ff;
    }
    .form {
        margin: 20px;
        margin-top: -100rpx;
        padding: 60rpx;
        border-radius: 14rpx;
        background-color: #ffffff;
        box-shadow: 0 2px 14px 0 rgb(0 0 0 / 8%);
    }
}
</style>
