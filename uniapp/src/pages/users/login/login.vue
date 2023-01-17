<template>
    <view>

        <view class="layout-login-widget">
            <view class="logo">
                <image class="w-h-full rounded-c50" src="../../../static/logo.png" />
            </view>
            <view class="form">
                <!-- 登录方式 -->
                <view class="mt-30 mb-20">
                    <u-tabs
                        :list="tabLists"
                        :current="tabIndex"
                        :font-size="34"
                        inactive-color="#999999"
                        @change="changeTab"
                    />
                </view>
                <!-- 短信登录 -->
                <u-form v-if="tabIndex === 0" ref="uForm" :model="signForm">
                    <u-form-item left-icon="phone" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="signForm.mobile" type="number" placeholder="请输入手机号" />
                    </u-form-item>
                    <u-form-item left-icon="lock" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input type="number" placeholder="请输入验证码" />
                        <template #right>
                            <u-verification-code ref="uCode" seconds="60" />
                            <u-button
                                :plain="true"
                                type="primary"
                                hover-class="none"
                                size="mini"
                                shape="circle"
                            >{{ '发送短信' }}
                            </u-button>
                        </template>
                    </u-form-item>
                </u-form>
                <!-- 账号登录 -->
                <u-form v-if="tabIndex === 1" ref="uForm" :model="signForm">
                    <u-form-item left-icon="phone" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="signForm.mobile" type="number" placeholder="请输入登录账号" />
                    </u-form-item>
                    <u-form-item left-icon="lock" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input type="number" placeholder="请输入登录密码" />
                    </u-form-item>
                </u-form>
                <!-- 忘记密码 -->
                <view v-if="tabIndex === 1" class="flex justify-between mt-30">
                    <view class="text-sm color-muted" @click="onJumpReg()">注册账号</view>
                    <view class="text-sm color-muted" @click="onJumpFor()">忘记密码?</view>
                </view>
                <!-- 登录安装 -->
                <button class="button">登录</button>
                <!-- 登录协议 -->
                <view class="treaty">
                    <u-checkbox shape="circle" active-color="#2979ff" />
                    <text class="ml-10">已阅读并同意</text>
                    <text class="color-theme">《用户协议》</text>与
                    <text class="color-theme">《隐私协议》</text>
                </view>
                <!-- 其它登录 -->
                <view class="others mt-80">
                    <u-divider>其它登录方式</u-divider>
                    <view class="flex justify-center mt-40">
                        <u-icon name="weixin-circle-fill" color="#19d46b" size="80" />
                    </view>
                </view>
            </view>
        </view>

    </view>
</template>

<script setup>
import { ref, reactive } from 'vue'

const tabIndex = ref(0)
const tabLists = reactive([{'name': '短信登录'}, {'name': '账号登录'}])
const signForm = reactive({'mobile': ''})

const changeTab = () => {
    tabIndex.value = tabIndex.value ? 0 : 1
}

const onJumpReg = () => {
    uni.navigateTo({
        url: '/pages/users/regist/regist'
    })
}

const onJumpFor = () => {
    uni.navigateTo({
        url: '/pages/users/forget/forget'
    })
}
</script>

<style lang="scss">
.layout-login-widget {
    padding-top: 50rpx;
    .logo {
        margin: 0 auto;
        padding: 6rpx;
        width: 180rpx;
        height: 180rpx;
        border-radius: 50%;
        background-color: #ffffff;
    }
    .form {
        margin: -40px 20px 0;
        padding: 60rpx;
        border-radius: 14rpx;
        background-color: #ffffff;
        box-shadow: 0 2px 14px 0 rgb(0 0 0 / 8%);
    }
    .button {
        margin-top: 40rpx;
        padding: 2rpx 0;
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
        :deep(.u-checkbox__label) {
            display: none;
        }
    }
}
</style>
