<template>
    <!-- 基础信息 -->
    <view class="mt-20">
        <u-cell-group>
            <u-cell-item :arrow="false">
                <view class="flex flex-col items-center justify-center">
                    <u-avatar :src="userInfo.avatar" mode="circle" size="100" class="h-100" />
                    <view class="mt-6 text-xs color-muted">点击修改头像</view>
                </view>
            </u-cell-item>
            <u-cell-item title="ID" :arrow="false">
                <text class="u-margin-right-10">{{ userInfo?.sn }}</text>
                <u-icon name="lock" size="28" color="#999" />
            </u-cell-item>
            <u-cell-item title="账号" :value="userInfo.account" @click="onShowPopup('account')" />
            <u-cell-item title="昵称" :value="userInfo.nickname" @click="onShowPopup('nickname')" />
            <u-cell-item title="性别" :value="genderEnumer[userInfo?.gender]" @click="onShowPopup('gender')" />
        </u-cell-group>
    </view>

    <!-- 绑定信息 -->
    <view class="mt-20">
        <u-cell-group>
            <u-cell-item title="登录密码" @click="onShowPopup('password')" />
            <u-cell-item title="绑定微信">
                <button
                    class="text-right color-muted button-hover"
                    @click="onBindWeChat"
                >{{ userInfo?.isWeiChat ? '已绑定' : '未绑定' }}
                </button>
            </u-cell-item>
            <u-cell-item title="绑定邮箱">
                <button
                    class="text-right color-muted button-hover"
                    @click="onShowPopup('email')"
                >{{ userInfo?.email ? userInfo?.email : '未绑定' }}
                </button>
            </u-cell-item>
            <u-cell-item title="绑定手机">
                <button
                    class="text-right color-muted button-hover"
                    open-type="getPhoneNumber"
                    @getphonenumber="onBindMobile"
                    @click="onBindMobile"
                >{{ userInfo.mobile ? userInfo?.mobile : '未绑定' }}
                </button>
            </u-cell-item>
        </u-cell-group>
    </view>

    <!-- 协议信息 -->
    <view class="mt-20">
        <u-cell-group>
            <u-cell-item title="隐私政策" />
            <u-cell-item title="服务协议" />
            <u-cell-item title="关于我们" :value="'v1.2.4'" />
        </u-cell-group>
    </view>

    <!-- 退出登录 -->
    <view class="logout">
        <w-button @on-click="onLogout()">退出登录</w-button>
    </view>

    <!-- 性别修改 -->
    <u-picker
        v-model="genderPicker"
        mode="selector"
        confirm-color="#4173FF"
        :default-selector="[0]"
        :range="genderListed"
        @confirm="onGenderEdit"
    />

    <!-- 密码修改 -->
    <u-action-sheet
        v-model="pwdPicker"
        :list="pwdListed"
        :safe-area-inset-bottom="true"
        @click="onPwdPopup"
    />

    <!-- 弹窗部件 -->
    <u-popup v-model="popupShow" mode="center" border-radius="12" :closeable="true">
        <view class="popup-form-widget">
            <BindEmail v-if="popupType === 'email'" :value="userInfo.email" @close="onClosePopup" />
            <BindMobile v-if="popupType === 'mobile'" :value="userInfo.mobile" @close="onClosePopup" />
            <ChangeAccount v-if="popupType === 'account'" :value="userInfo.account" @close="onClosePopup" />
            <ChangeNickname v-if="popupType === 'nickname'" :value="userInfo.nickname" @close="onClosePopup" />
            <ChangePassword v-if="popupType === 'changePwd'" :value="userInfo.password" @close="onClosePopup" />
            <ForgetPassword v-if="popupType === 'forgetPwd'" :value="userInfo.password" @close="onClosePopup" />
        </view>
    </u-popup>
</template>

<script setup>
import { ref } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import { useUserStore } from '@/stores/userStore'
import { userInfoApi, userEditApi } from '@/api/usersApi'
import { bindWeChatApi } from '@/api/loginApi'
import toolUtil from '@/utils/toolUtil'

import BindEmail from './component/bind-email'
import BindMobile from './component/bind-mobile'
import ChangeAccount from './component/change-account'
import ChangeNickname from './component/change-nickname'
import ChangePassword from './component/change-password'
import ForgetPassword from './component/forget-password'

// 用户信息数据
const userStore = useUserStore()
const userInfo = ref({
    sn: '',
    avatar: '',
    account: '',
    nickname: '',
    mobile: '',
    email: '',
    gender: 0,
    isWeiChat: false
})

// 弹出参数
const formValue = ref(null)
const popupType = ref(null)
const popupShow = ref(false)

// 性别参数
const genderPicker = ref(false)
const genderListed = ref(['男', '女'])
const genderEnumer = ref(['未知', '男', '女'])

// 密码参数
const pwdPicker = ref(false)
const pwdListed = ref([{text: '修改密码'}, {text: '忘记密码'}])

// 显示监听
onShow(() => {
    queryUserInfo()
})

// 查询信息
const queryUserInfo = async () => {
    userInfo.value = await userInfoApi()
}

// 退出登录
const onLogout = async () => {
    uni.showModal({
        content: '是否退出登录？',
        confirmColor: '#4173FF',
        success: ({ cancel }) => {
            if (!cancel) {
                userStore.logout()
                uni.redirectTo({ url: '/pages/login/enroll' })
            }
        }
    })
}

// 性别修改
const onGenderEdit = async (value) => {
    await userEditApi({
        scene: popupType.value,
        value: value[0] + 1
    })
}

// 绑定微信
const onBindWeChat = async () => {
    const code = await toolUtil.obtainWxCode()
    await bindWeChatApi({code: code})
    queryUserInfo()

}

// 绑定手机
const onBindMobile = (e) => {
    if (!e.detail.code) {
        onShowPopup('mobile')
    }
}

// 密码弹窗
const onPwdPopup = (index) => {
    switch (index) {
    case 0:
        popupType.value = 'changePwd'
        popupShow.value = true
        break
    case 1:
        popupType.value = 'forgetPwd'
        popupShow.value = true
        break
    default:
    }
}

// 弹出窗口
const onShowPopup = (type) => {
    switch (type) {
    case 'gender':
        popupType.value = type
        formValue.value = userInfo.value.gender
        genderPicker.value = true
        break
    case 'password':
        popupType.value = type
        pwdPicker.value = true
        break
    default:
        popupType.value = type
        popupShow.value = true
    }
}

// 关闭窗口
const onClosePopup = () => {
    popupShow.value = false
    popupType.value = null
}
</script>

<style lang="scss">
.logout {
    margin: 0 30px;
    padding: 40rpx 0;
}
.button-hover {
    color: unset;
    background-color: unset;
}
.popup-form-widget {
    padding: 0 30rpx;
    width: 85vw;
    border-radius: 14rpx;
    background-color: #ffffff;
    :deep(.title) {
        padding: 22rpx;
        font-size: 28rpx;
        font-weight: bold;
        text-align: center;
    }
}
</style>
