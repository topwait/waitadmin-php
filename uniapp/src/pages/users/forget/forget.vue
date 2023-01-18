<template>

    <view class="layout-regist-widget">
        <view class="head"></view>
        <view class="form">
            <u-form ref="uForm">
                <u-form-item left-icon="phone" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input type="number" placeholder="请输入手机号" />
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
                        >{{ '获取验证码' }}
                        </u-button>
                    </template>
                </u-form-item>
                <u-form-item left-icon="account" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input type="number" placeholder="6~20位数字+字母或符号组合" />
                </u-form-item>
                <u-form-item left-icon="account" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input type="number" placeholder="请再次确认新密码" />
                </u-form-item>
            </u-form>
            <button class="button">重设密码</button>
        </view>
    </view>

</template>

<script>
    export default {
        data() {
            return {
                
            }
        },
        methods: {
            
        }
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
