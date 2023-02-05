<template>

    <view class="layout-login-widget">
        <view class="logo">
            <image class="w-h-full rounded-c50" src="../../static/logo.png" />
        </view>
        <view class="form">
            <!-- 登录方式 -->
            <view class="mt-30 mb-20">
                <u-tabs
                    :list="loginTabs"
                    :current="tabsIndex"
                    :font-size="34"
                    inactive-color="#999999"
                    @change="tabChange"
                />
            </view>

            <!-- 短信登录 -->
            <u-form v-if="loginWays === 'mobile'" ref="uForm" :model="form">
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
            </u-form>

            <!-- 账号登录 -->
            <u-form v-if="loginWays === 'account'" ref="uForm" :model="form">
                <u-form-item left-icon="phone" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input v-model="form.account" type="text" placeholder="请输入登录账号" />
                </u-form-item>
                <u-form-item left-icon="lock" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                    <u-input v-model="form.password" type="password" placeholder="请输入登录密码" />
                </u-form-item>
            </u-form>

            <!-- 注册重置 -->
            <view v-if="loginWays === 'account'" class="flex justify-between mt-30">
                <view class="text-sm color-muted" @click="$go('/pages/users/regist/regist')">注册账号</view>
                <view class="text-sm color-muted" @click="$go('/pages/users/forget/forget')">忘记密码?</view>
            </view>

            <!-- 登录按钮 -->
            <w-button v-if="loginTabs.length" mt="40" @on-click="onSaLogin(loginWays)">登录</w-button>

            <!-- 登录插图 -->
            <view v-if="!loginTabs.length" class="flex justify-center">
                <u-image width="300rpx" height="300rpx" src="https://img.zcool.cn/community/0108c75972fc5da8012193a369015f.png@1280w_1l_2o_100sh.png" mode="" />
            </view>

            <!-- 登录协议 -->
            <view v-if="isOpenAgreement" class="treaty">
                <u-checkbox v-model="isCheckAgreement" shape="circle" active-color="#2979ff" />
                <text class="ml-10">已阅读并同意</text>
                <text class="color-theme">《用户协议》</text>与
                <text class="color-theme">《隐私协议》</text>
            </view>

            <!-- 其它登录 -->
            <!-- #ifdef MP-WEIXIN || H5 -->
            <view v-if="isOpenOtherAuth && isWeixin" class="others mt-50">
                <u-divider>其它登录方式</u-divider>
                <view class="flex justify-center mt-40">
                    <button
                        v-if="wayInclude(LoginAuthEnum.WX) && isWeixin"
                        open-type="getPhoneNumber"
                        @getphonenumber="onWxLogin"
                    >
                        <u-icon name="weixin-circle-fill" color="#19d46b" size="80" />
                    </button>
                </view>
            </view>
            <!-- #endif -->
        </view>
    </view>

</template>

<script setup>
import { onLoad, onShow } from '@dcloudio/uni-app'
import { ref, computed, shallowRef } from 'vue'
import { useAppStore } from '@/stores/appStore'
import { useUserStore } from '@/stores/userStore'
import { loginApi } from '@/api/usersApi'
import { sendSmsApi } from '@/api/indexApi'
import smsEnum from '@/enums/smsEnum'
import checkUtil from '@/utils/checkUtil'
import clientUtil from '@/utils/clientUtil'
import toolUtil from '@/utils/toolUtil'

const appStore = useAppStore()
const userStore = useUserStore()
const isWeixin = clientUtil.isWeixin()

// 枚举对象
const LoginAuthEnum = {
    WX: 1,
    QQ: 2
}

// 登录配置
const tabsIndex = ref(0)
const loginTabs = appStore.loginConfigVal.login_modes
const loginAuth = appStore.loginConfigVal.login_other
const loginWays = ref(loginTabs.length ? loginTabs[0].alias : '')
const isCheckAgreement = ref(false)

// 计算属性
const isForceMobileUa = computed(() => appStore.loginConfigVal.force_mobile === 1)
const isOpenAgreement = computed(() => appStore.loginConfigVal.is_agreement === 1)
const isOpenOtherAuth = computed(() => appStore.loginConfigVal.login_other.length)

// 表单参数
const form = {
    code: '',
    mobile: '',
    account: '',
    password: ''
}

// 监听加载
onLoad(async () => {
    if (userStore.isLogin) {
        return uni.reLaunch({
            url: '/pages/index/index'
        })
    }
})

// 监听显示
onShow(async () => {
    try {
        if (userStore.isLogin) {
            uni.showLoading({
                title: '请稍后...'
            })
            uni.hideLoading()
            uni.navigateBack()
        }
    } catch (error) {
        uni.hideLoading()
    }
})

// 切换提示
const codeTips = ref('')
const uCodeRef = shallowRef()
const codeChange = (text) => {
    codeTips.value = text
}

// 切换登录
const tabChange = (e) => {
    tabsIndex.value = e
    loginWays.value = loginTabs[e].alias
}

// 判断登录
const wayInclude = (way) => {
    return loginAuth.includes(way)
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

// 普通登录
const onSaLogin = (scene) => {
    let params = {}
    if (scene === 'mobile') {
        if (checkUtil.isEmpty(form.mobile)) {
            return uni.$u.toast('请输入手机号')
        }
        if (checkUtil.isMobile(form.mobile)) {
            return uni.$u.toast('手机号不合规')
        }
        if (checkUtil.isEmpty(form.code)) {
            return uni.$u.toast('请输入验证码')
        }
        if (!checkUtil.isNumber(form.code) && form.code.length > 4) {
            return uni.$u.toast('错误的验证码')
        }
        params = {scene: scene, code: form.code, mobile: form.mobile}
    } else {
        if (checkUtil.isEmpty(form.account)) {
            return uni.$u.toast('请输入登录账号')
        }
        if (checkUtil.isEmpty(form.password)) {
            return uni.$u.toast('请输入登录密码')
        }
        params = {scene: scene, account: form.account, password: form.password}
    }

    if (isForceMobileUa.value && !isCheckAgreement.value) {
        return uni.$u.toast('请勾选已阅读并同意《服务协议》和《隐私协议》')
    }

    loginApi(params).then(result => {
        __loginHandle(result)
    })
}

// 微信登录
const onWxLogin = async (e) => {
    const wxCode = await toolUtil.obtainWxCode()
    loginApi({
        scene: 'wx',
        code: wxCode,
        phoneCode: e.detail.code
    }).then(result => {
        __loginHandle(result)
    })
}

// 处理登录
const __loginHandle = (result) => {
    if (result.code !== 0) {
        return uni.$u.toast(result.msg)
    }

    userStore.login(result.data.token)
    uni.$u.toast('登录成功')

    const pages = toolUtil.currentPage()
    if (pages.length > 1) {
        const prevPage = pages.at(-2)
        uni.navigateBack({
            success: () => {
                const { onLoad, options } = prevPage
                onLoad && onLoad(options)
            }
        })
    } else {
        uni.reLaunch({
            url: '/pages/index/index'
        })
    }
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
