<template>

    <view class="layout-regist-widget">
        <view class="head">
            <view class="title">重置密码</view>
        </view>
        <view class="form">
            <u-form ref="uForm" :model="form">
                <u-form-item left-icon="phone" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input v-model="form.mobile" type="number" placeholder="请输入手机号" />
                </u-form-item>
                <u-form-item left-icon="lock" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input v-model="form.code" type="number" placeholder="请输入验证码" />
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
                <u-form-item left-icon="account" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input v-model="form.password" type="password" placeholder="6~20位数字+字母或符号组合" />
                </u-form-item>
                <u-form-item left-icon="account" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input v-model="form.againPwd" type="password" placeholder="请再次确认新密码" />
                </u-form-item>
            </u-form>
            <button class="button">重设密码</button>
        </view>
    </view>

</template>

<script setup>
import { ref, shallowRef } from 'vue'
import { forgetPwdApi } from '@/api/loginApi'
import checkUtil from '@/utils/checkUtil'

// 设置标题
uni.setNavigationBarTitle({title: ''})

// 表单参数
const form = {
    code: '',
    mobile: '',
    password: '',
    againPwd: ''
}

// 切换提示
const codeTips = ref('')
const uCodeRef = shallowRef()
const codeChange = (text) => {
    codeTips.value = text
}

// 发送短信
const onSendSms = async () => {
    if (checkUtil.isEmpty(form.account)) {
        return uni.$u.toast('请输入手机号')
    }
    if (checkUtil.isMobile(form.account)) {
        return uni.$u.toast('手机号不合规')
    }
    if (uCodeRef.value?.canGetCode) {
        await sendSmsApi({
            scene: smsEnum.LOGIN,
            mobile: form.mobile
        })
        uCodeRef.value?.start()
    }
}

// 重置密码
const onResetPwd = async () => {
    await forgetPwdApi(form)
    uni.$u.toast('操作成功')
    uni.navigateBack()
}
</script>

<style lang="scss">
.layout-regist-widget {
    .head {
        height: 240rpx;
        background-color: #2979ff;
        .title {
            padding-top: 20rpx;
            font-size: 48rpx;
            text-align: center;
            color: #ffffff;
        }
    }
    .form {
        margin: 20px;
        margin-top: -100rpx;
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
}
</style>
