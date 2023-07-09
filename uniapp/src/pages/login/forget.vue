<template>
    <view :class="themeName">
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
                        <u-input v-model="form.newPassword" type="password" placeholder="6~20位数字+字母或符号组合" />
                    </u-form-item>
                    <u-form-item left-icon="account" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="form.ackPassword" type="password" placeholder="请再次确认新密码" />
                    </u-form-item>
                </u-form>
                <w-button pt="60" @on-click="onResetPwd()">重设密码</w-button>
            </view>
        </view>
    </view>
</template>

<script setup>
import { ref } from 'vue'
import IndexApi from '@/api/IndexApi'
import UserApi from '@/api/UserApi'
import smsEnum from '@/enums/smsEnum'
import checkUtil from '@/utils/checkUtil'

// 设置标题
uni.setNavigationBarTitle({title: ''})

// 表单参数
const form = ref({
    newPassword: '',
    ackPassword: '',
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

    if (uCodeRef.value?.canGetCode) {
        await IndexApi.sendSms({
            scene: smsEnum.LOGIN,
            mobile: form.value.mobile
        })
        uCodeRef.value?.start()
    }
}

// 密码修改
const onResetPwd = async () => {
    if (checkUtil.isEmpty(form.value.newPassword)) {
        return uni.$u.toast('请输入新的密码')
    }

    if (checkUtil.isEmpty(form.value.ackPassword)) {
        return uni.$u.toast('请输入确认密码')
    }

    if (form.value.newPassword !== form.value.ackPassword) {
        return uni.$u.toast('两次不密码不一致')
    }

    await UserApi.forgetPwd(form.value)
    uni.$u.toast('修改成功')
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
            color: #ffffff;
            text-align: center;
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
}
</style>
