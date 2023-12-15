<template>
    <view :class="themeName">
        <view class="layout-login-widget">
            <!-- 图标 -->
            <view class="logo">
                <image lazy-load="true" class="w-h-full rounded-c50" :src="loginLogo" />
            </view>

            <!-- 表单 -->
            <view class="form">
                <!-- 登录方式 -->
                <view class="mt-30 mb-20">
                    <u-tabs
                        :list="loginTabs"
                        :current="tabsIndex"
                        :font-size="34"
                        :active-color="themeColor"
                        inactive-color="#999999"
                        @change="tabChange"
                    />
                </view>

                <!-- 短信登录 -->
                <u-form v-if="(tabMethod || loginWays) === 'mobile'" ref="uForm" :model="form">
                    <u-form-item left-icon="phone" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="form.mobile" type="number" placeholder="请输入手机号" />
                    </u-form-item>
                    <u-form-item left-icon="lock" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="form.code" type="number" placeholder="请输入验证码" />
                        <template #right>
                            <u-verification-code ref="uCodeRefByLogin" seconds="60" @change="codeChangeByLogin" />
                            <u-button
                                :loading="loadingSms"
                                :plain="true"
                                type="theme"
                                hover-class="none"
                                size="mini"
                                shape="circle"
                                @click="sendSmsByLogin()"
                            >{{ codeTipsByLogin }}
                            </u-button>
                        </template>
                    </u-form-item>
                </u-form>

                <!-- 账号登录 -->
                <u-form v-if="(tabMethod || loginWays) === 'account'" ref="uForm" :model="form">
                    <u-form-item left-icon="phone" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="form.account" type="text" placeholder="请输入登录账号" />
                    </u-form-item>
                    <u-form-item left-icon="lock" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                        <u-input v-model="form.password" type="password" placeholder="请输入登录密码" />
                    </u-form-item>
                </u-form>

                <!-- 注册重置 -->
                <view v-if="(tabMethod || loginWays) === 'account'" class="flex justify-between mt-30">
                    <view class="font-sm color-muted" @click="$go('/pages/login/regist')">注册账号</view>
                    <view class="font-sm color-muted" @click="$go('/pages/login/forget')">忘记密码?</view>
                </view>

                <!-- 登录按钮 -->
                <view class="pt-40">
                    <u-button
                        :loading="loading"
                        type="theme"
                        shape="circle"
                        @click="onSaLogin((tabMethod || loginWays))"
                    >登录</u-button>
                </view>

                <!-- 登录协议 -->
                <view v-if="isOpenAgreement" class="treaty">
                    <u-checkbox v-model="isCheckAgreement" shape="circle" :active-color="themeColor" />
                    <text class="ml-10">已阅读并同意</text>
                    <text class="color-theme" @click="$go('/pages/other/policy?type=service')">《用户协议》</text>与
                    <text class="color-theme" @click="$go('/pages/other/policy?type=privacy')">《隐私协议》</text>
                </view>

                <!-- 其它登录 -->
                <!-- #ifdef MP-WEIXIN || H5 -->
                <view v-if="isOpenOtherAuth && isWeixin" class="others mt-50">
                    <u-divider>其它登录方式</u-divider>
                    <view class="flex justify-center mt-40">
                        <button
                            v-if="wayInclude(LoginAuthEnum.WX) && isWeixin && isAuthsMobile"
                            open-type="getPhoneNumber"
                            style="background-color: #ffffff;"
                            @getphonenumber="onWxLogin"
                        >
                            <u-icon name="weixin-circle-fill" color="#19d46b" size="80" />
                        </button>
                        <button
                            v-else-if="wayInclude(LoginAuthEnum.WX) && isWeixin && !isAuthsMobile"
                            style="background-color: #ffffff;"
                            @click="onWxLogin"
                        >
                            <u-icon name="weixin-circle-fill" color="#19d46b" size="80" />
                        </button>
                    </view>
                </view>
                <!-- #endif -->
            </view>

            <!-- 弹窗 -->
            <u-popup v-model="showPopup" mode="bottom" border-radius="20">
                <view class="py-30 text-align-center font-bm font-weight-bold">绑定手机</view>
                <view class="text-align-center font-xs color-muted">您需绑定手机号完成登录操作</view>
                <view class="flex items-center px-20 pt-20 pb-50" style="height: 100%; box-sizing: border-box;">
                    <u-form ref="uForm" :model="phoneForm" style="width: 100%;">
                        <u-form-item left-icon="phone" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                            <u-input v-model="phoneForm.mobile" type="number" placeholder="请输入手机号" />
                        </u-form-item>
                        <u-form-item left-icon="lock" :left-icon-style="{'color': '#999999', 'font-size': '36rpx'}">
                            <u-input v-model="phoneForm.code" type="number" placeholder="请输入验证码" />
                            <template #right>
                                <u-verification-code ref="uCodeRefByPhone" seconds="60" @change="codeChangeByPhone" />
                                <u-button
                                    :loading="loadingSms"
                                    :plain="true"
                                    type="primary"
                                    hover-class="none"
                                    size="mini"
                                    shape="circle"
                                    @click="sendSmsByPhone"
                                >{{ codeTipsByPhone }}
                                </u-button>
                            </template>
                        </u-form-item>
                        <view class="pt-60">
                            <u-button
                                type="theme"
                                shape="circle"
                                @click="onUpLogin()"
                            >确认</u-button>
                        </view>
                    </u-form>
                </view>
            </u-popup>
        </view>
    </view>
</template>

<script setup>
import { onLoad, onShow } from '@dcloudio/uni-app'
import { ref, computed, shallowRef } from 'vue'
import { useAppStore } from '@/stores/appStore'
import { useUserStore } from '@/stores/userStore'
import { useLock } from '@/hooks/useLock'
import loginApi from '@/api/loginApi'
import indexApi from '@/api/indexApi'
import smsEnum from '@/enums/smsEnum'
import checkUtil from '@/utils/checkUtil'
import clientUtil from '@/utils/clientUtil'
import toolUtil from '@/utils/toolUtil'
// #ifdef H5
import wechatOa from '@/utils/wechat'
// #endif

// 基础参数
const appStore = useAppStore()
const userStore = useUserStore()
const isWeixin = clientUtil.isWeixin()

// 授权枚举
const LoginAuthEnum = {
    WX: 1,
    QQ: 2
}

// 场景枚举
const LoginSceneEnum = {
    WX: 'wx',
    OA: 'oa',
    BIND: 'ba',
    MOBILE: 'mobile',
    ACCOUNT: 'account'
}

// 登录配置
const tabsIndex = ref(0)
const tabMethod = ref(null)
const isAuthsMobile = ref(false)
const isCheckAgreement = ref(false)
const loginLogo = computed(() => appStore.h5ConfigVal.logo)
const loginTabs = computed(() => appStore.loginConfigVal.login_modes)
const loginAuth = computed(() => appStore.loginConfigVal.login_other)
const loginWays = computed(() => loginTabs.value ? loginTabs.value[0].alias : '')
const isForceMobileUa = computed(() => appStore.loginConfigVal.force_mobile === 1)
const isOpenAgreement = computed(() => appStore.loginConfigVal.is_agreement === 1)
const isOpenOtherAuth = computed(() => appStore.loginConfigVal.login_other?.length)
const loadingSms = ref(false)

// #ifdef MP-WEIXIN
const authsMobile = computed(() => appStore.loginConfigVal.auths_mobile)
if (authsMobile.value === 1) {
    isAuthsMobile.value = true
}
// #endif

// 绑定手机
const showPopup = ref(false)
const phoneForm = {
    code: '',
    sign: '',
    mobile: ''
}

// 表单参数
const form = {
    code: '',
    mobile: '',
    account: '',
    password: ''
}

// 监听加载
onLoad(async (options) => {
    if (userStore.isLogin) {
        return uni.reLaunch({
            url: '/pages/index/index'
        })
    }

    onOaLogin(options.code)
})

// 监听显示
onShow(async () => {
    try {
        if (userStore.isLogin) {
            uni.navigateBack()
        }
    } catch (e) { }
})

// 验证码(登录)
const codeTipsByLogin = ref('')
const uCodeRefByLogin = shallowRef()
const codeChangeByLogin = (text) => {
    codeTipsByLogin.value = text
}

// 验证码(绑定)
const codeTipsByPhone = ref('')
const uCodeRefByPhone = shallowRef()
const codeChangeByPhone = (text) => {
    codeTipsByPhone.value = text
}

// 发送短信(登录)
const sendSmsByLogin = async () => {
    if (checkUtil.isEmpty(form.mobile)) {
        return uni.$u.toast('请输入手机号')
    }
    loadingSms.value = true
    if (uCodeRefByLogin.value?.canGetCode) {
        await indexApi.sendSms({
            scene: smsEnum.LOGIN,
            mobile: form.mobile
        }).then(() => {
            uCodeRefByLogin.value?.start()
            return uni.$u.toast('发送成功')
        }).catch(e => {
            loadingSms.value = false
            return uni.$u.toast(e.msg)
        })
    }
}

// 发送短信(绑定)
const sendSmsByPhone = async () => {
    if (checkUtil.isEmpty(phoneForm.mobile)) {
        return uni.$u.toast('请输入手机号')
    }
    loadingSms.value = true
    if (uCodeRefByPhone.value?.canGetCode) {
        await indexApi.sendSms({
            scene: smsEnum.BIND_MOBILE,
            mobile: form.mobile
        }).then(() => {
            uCodeRefByPhone.value?.start()
            return uni.$u.toast('发送成功')
        }).catch(e => {
            loadingSms.value = false
            return uni.$u.toast(e.msg)
        })
    }
}

// 切换登录
const tabChange = (index) => {
    tabsIndex.value = index
    tabMethod.value = loginTabs.value[index].alias
}

// 判断登录
const wayInclude = (way) => {
    return loginAuth.value.includes(way)
}

// 绑定登录
const onUpLogin = () => {
    if (checkUtil.isEmpty(phoneForm.mobile)) {
        return uni.$u.toast('请输入手机号')
    }
    if (checkUtil.isEmpty(phoneForm.code)) {
        return uni.$u.toast('请输入验证码')
    }

    loginApi.login({
        scene: LoginSceneEnum.BIND,
        code: phoneForm.code,
        sign: phoneForm.sign,
        mobile: phoneForm.mobile
    }).then(result => {
        showPopup.value = false
        __loginHandle(result)
    })
}

// 普通登录
const { loading, methodAPI:$loginApi } = useLock(loginApi.login, 2000)
const onSaLogin = (scene) => {
    let params = {}
    if (scene === LoginSceneEnum.MOBILE) {
        if (checkUtil.isEmpty(form.mobile)) {
            return uni.$u.toast('请输入手机号')
        }
        if (checkUtil.isEmpty(form.code)) {
            return uni.$u.toast('请输入验证码')
        }
        if (!checkUtil.isNumber(form.code) && form.code.length > 6) {
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

    if (!isCheckAgreement.value) {
        return uni.$u.toast('请勾选已阅读并同意《服务协议》和《隐私协议》')
    }

    $loginApi(params).then(async result => {
        __loginHandle(result)
    }).catch(() => {
        loading.value = false
    })
}

// 微信登录
const onWxLogin = async (e) => {
    if (!isCheckAgreement.value) {
        return uni.$u.toast('请勾选已阅读并同意《服务协议》和《隐私协议》')
    }

    loading.value = true
    // #ifdef MP-WEIXIN
    const wxCode = e.detail.code || ''
    const code = await toolUtil.obtainWxCode()
    const result = await $loginApi({
        scene: LoginSceneEnum.WX,
        code: code,
        wxCode: wxCode
    }).catch(() => {
        loading.value = false
    })

    if (isForceMobileUa.value && result.code === 1) {
        phoneForm.sign = result.data.sign
        showPopup.value = true
    } else {
        __loginHandle(result)
    }
    // #endif

    // #ifdef H5
    if (isWeixin) {
        wechatOa.authUrl()
    }
    // #endif
    loading.value = false
}

// 公众号登录
const onOaLogin = async (code) => {
    // #ifdef H5
    if (code) {
        wechatOa.authLogin(code).then(result => {
            if (result.code === 1) {
                phoneForm.sign = result.data.sign
                showPopup.value = true
            } else {
                __loginHandle(result)
            }
        })
    }
    // #endif
}

// 处理登录
const __loginHandle = (result) => {
    if (result.code !== 0) {
        return uni.$u.toast(result.msg)
    }

    userStore.login(result.data.token)
    uni.showToast({ title: '登陆成功' })

    setTimeout(() => {
        const pages = toolUtil.currentPage()
        if (pages.length > 1) {
            const prevPage = pages.at(-2)
            return uni.navigateBack({
                success: () => {
                    const { onLoad, options } = prevPage
                    onLoad && onLoad(options)
                }
            })
        }
        return uni.reLaunch({
            url: '/pages/index/index'
        })

    }, 1000)
}
</script>

<style lang="scss" scoped>
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
