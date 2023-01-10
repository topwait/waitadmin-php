<template>
    <view>

        <view class="layout-login-widget">
            <view class="logo">
                <image class="w-h-full rounded-c50" src="../../static/logo.png" />
            </view>
            <view class="form">
                <view class="mt-30 mb-20">
                    <u-tabs :list="tabs" :current="0" :font-size="34" inactive-color="#999999" />
                </view>
                <u-form ref="uForm">
                    <u-form-item left-icon="phone" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="form.mobile" type="number" placeholder="请输入手机号" />
                    </u-form-item>
                    <u-form-item left-icon="lock" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input type="number" placeholder="请输入验证码" />
                        <u-form-item slot="right" :border-bottom="false">
                            <u-verification-code ref="uCode" seconds="60" />
                            <u-button
                                :plain="true"
                                type="error"
                                hover-class="none"
                                size="mini"
                                shape="circle"
                            >{{ '发送短信' }}
                            </u-button>
                        </u-form-item>
                    </u-form-item>
                </u-form>
                <button class="login">登录</button>
                <view class="treaty">
                    <u-checkbox shape="circle" active-color="#2979ff" />
                    已阅读并同意
                    <text class="color-theme">《用户协议》</text>与
                    <text class="color-theme">《隐私协议》</text>
                </view>
            </view>
        </view>

    </view>
</template>

<script setup>
import { reactive } from 'vue'

const tabs = reactive([{'name': '短信登录'}, {'name': '账号登录'}])

const form = reactive({
    'mobile': ''
})
</script>

<style lang="scss">
.layout-login-widget {
    margin-top: 50rpx;
    .logo {
        margin: 0 auto;
        padding: 6rpx;
        width: 180rpx;
        height: 180rpx;
        border-radius: 50%;
        background-color: #ffffff;
    }
    .form {
        margin: -40px 28px 0;
        padding: 60rpx;
        border-radius: 14rpx;
        background-color: #ffffff;
        box-shadow: 0 2px 14px 0 rgb(0 0 0 / 8%);
    }
    .login {
        margin-top: 40rpx;
        padding: 6rpx 0;
        width: 100%;
        font-size: 32rpx;
        border-radius: 50rpx;
        text-align: center;
        color: #ffffff;
        background-color: #2979ff;
    }
    .treaty {
        margin-top: 40rpx;
        font-size: 22rpx;
        color: #999999;
        .u-checkbox__label {
            display: none;
            margin-right: 0 !important;
        }
    }
}
</style>
