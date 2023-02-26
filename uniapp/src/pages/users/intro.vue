<template>
    <!-- 基础信息 -->
    <view class="mt-20">
        <u-cell-group>
            <u-cell-item :arrow="false">
                <view class="flex flex-col items-center justify-center">
                    <u-avatar :src="userInfo?.avatar" mode="circle" size="100" class="h-100" />
                    <view class="mt-6 text-xs color-muted">点击修改头像</view>
                </view>
            </u-cell-item>
            <u-cell-item title="ID" :arrow="false">
                <text class="u-margin-right-10">{{ userInfo?.sn }}</text>
                <u-icon name="lock" size="28" color="#999" />
            </u-cell-item>
            <u-cell-item title="账号" :value="userInfo?.account" @click="onShowPopup('account')"/>
            <u-cell-item title="昵称" :value="userInfo?.nickname" @click="onShowPopup('nickname')" />
            <u-cell-item title="性别" :value="genderEnumer[userInfo?.gender]" @click="onShowPopup('gender')" />
        </u-cell-group>
    </view>

    <!-- 绑定信息 -->
    <view class="mt-20">
        <u-cell-group>
            <u-cell-item title="登录密码" @click="onShowPopup('password')" />
            <u-cell-item title="绑定微信">
                <u-button
                     :plain="true"
                     type="primary"
                     hover-class="none"
                     size="mini"
                     shape="circle"
                     @click="onBindWeChat()"
                 >{{ '绑定微信' }}
                 </u-button>
            </u-cell-item>
            <u-cell-item title="绑定邮箱" >
                <u-button
                     :plain="true"
                     type="primary"
                     hover-class="none"
                     size="mini"
                     shape="circle"
                     @click="onShowPopup('email')"
                 >{{ '绑定邮箱' }}
                 </u-button>
            </u-cell-item>
            <u-cell-item title="绑定手机">
               <u-button v-if="!userInfo?.mobile"
                    :plain="true"
                    type="primary"
                    hover-class="none"
                    size="mini"
                    shape="circle"
                    @click="onShowPopup('mobile')"
                >{{ '绑定手机' }}
                </u-button>
                <button v-else class="text-right color-muted">18927154977</button>
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
    <view class="mx-30 py-40">
        <w-button @on-click="onLogout()">退出登录</w-button>
    </view>
    
    <!-- 性别修改 -->
    <u-picker
        mode="selector"
        v-model="genderPicker"
        confirm-color="#4173FF"
        :default-selector="[0]"
        :range="genderListed"
        @confirm="onGenderEdit"
    />

    <!-- 密码修改 -->
    <u-action-sheet
        :list="pwdListed"
        v-model="pwdPicker"
        @click="onPwdPopup"
        :safe-area-inset-bottom="true"
    ></u-action-sheet>
    
    <!-- 弹窗部件 -->
    <u-popup v-model="popupShow" mode="center" border-radius="12" :closeable="true">
        <!-- 修改昵称 -->
        <view class="popup-form-widget" v-if="popupType === 'account'">
            <view class="title">修改账号</view>
            <u-form-item>
                <u-input v-model="formValue" placeholder="请输入账号" :border="false" />
            </u-form-item>
            <view class="py-40">
                <u-button type="primary" shape="circle" size="medium" :custom-style="{width: '100%'}" @click="onUpdateUser()">确定</u-button>
            </view>
        </view>
        <!-- 修改昵称 -->
        <view class="popup-form-widget" v-if="popupType === 'nickname'">
            <view class="title">修改昵称</view>
            <u-form-item>
                <u-input v-model="formValue" placeholder="请输入昵称" :border="false" />
            </u-form-item>
            <view class="py-40">
                <u-button type="primary" shape="circle" size="medium" :custom-style="{width: '100%'}" @click="onUpdateUser()">确定</u-button>
            </view>
        </view>
        <!-- 绑定邮箱 -->
        <view class="popup-form-widget" v-if="popupType === 'email'">
            <view class="title">绑定手机</view>
            <u-form-item>
                <u-input v-model="formValue" placeholder="请输入邮箱号" :border="false" />
            </u-form-item>
            <u-form-item>
                <u-input v-model="formValue" placeholder="验证码" :border="false" />
                <template #right>
                    <u-verification-code ref="uCodeRefByForgetPwd" seconds="60" @change="codeChangeByForgetPwd" />
                    <u-button
                        :plain="true"
                        type="primary"
                        hover-class="none"
                        size="mini"
                        shape="circle"
                        @click="onSendSms('forgetPwd')"
                    >{{ codeTipsByForgetPwd }}
                    </u-button>
                </template>
            </u-form-item>
            <view class="py-40">
                <u-button type="primary" shape="circle" size="medium" :custom-style="{width: '100%'}" @click="onUpdateUser()">确定</u-button>
            </view>
        </view>
        <!-- 绑定手机 -->
        <view class="popup-form-widget" v-if="popupType === 'mobile'">
            <view class="title">绑定手机</view>
            <u-form-item>
                <u-input v-model="formValue" placeholder="请输入手机号" :border="false" />
            </u-form-item>
            <u-form-item>
                <u-input v-model="formValue" placeholder="验证码" :border="false" />
                <template #right>
                    <u-verification-code ref="uCodeRefByForgetPwd" seconds="60" @change="codeChangeByForgetPwd" />
                    <u-button
                        :plain="true"
                        type="primary"
                        hover-class="none"
                        size="mini"
                        shape="circle"
                        @click="onSendSms('forgetPwd')"
                    >{{ codeTipsByForgetPwd }}
                    </u-button>
                </template>
            </u-form-item>
            <view class="py-40">
                <u-button type="primary" shape="circle" size="medium" :custom-style="{width: '100%'}" @click="onUpdateUser()">确定</u-button>
            </view>
        </view>
        <!-- 修改密码 -->
        <view class="popup-form-widget" v-if="popupType === 'changePwd'">
            <view class="title">修改密码</view>
            <u-form-item>
                <u-input v-model="changePwdForm.oldPassword" placeholder="请输入原始密码" :border="false" />
            </u-form-item>
            <u-form-item>
                <u-input v-model="changePwdForm.newPassword" placeholder="请输入新的密码" :border="false" />
            </u-form-item>
            <u-form-item>
                <u-input v-model="changePwdForm.ackPassword" placeholder="请再次确认密码" :border="false" />
            </u-form-item>
            <view class="py-40">
                <u-button type="primary" shape="circle" size="medium" :custom-style="{width: '100%'}" @click="onPwdEdit()">确定</u-button>
            </view>
        </view>
        <!-- 忘记密码 -->
        <view class="popup-form-widget" v-if="popupType === 'forgetPwd'">
            <view class="title">忘记密码</view>
            <u-form-item>
                <u-input v-model="formValue" placeholder="请输入新的密码" :border="false" />
            </u-form-item>
            <u-form-item>
                <u-input v-model="formValue" placeholder="请再次确认密码" :border="false" />
            </u-form-item>
            <u-form-item>
                <u-input v-model="formValue" placeholder="绑定的手机号" :border="false" />
            </u-form-item>
            <u-form-item>
                <u-input v-model="formValue" placeholder="验证码" :border="false" />
                <template #right>
                    <u-verification-code ref="uCodeRefByForgetPwd" seconds="60" @change="codeChangeByForgetPwd" />
                    <u-button
                        :plain="true"
                        type="primary"
                        hover-class="none"
                        size="mini"
                        shape="circle"
                        @click="onSendSms('forgetPwd')"
                    >{{ codeTipsByForgetPwd }}
                    </u-button>
                </template>
            </u-form-item>
            <view class="py-40">
                <u-button type="primary" shape="circle" size="medium" :custom-style="{width: '100%'}" @click="onUpdateUser()">确定</u-button>
            </view>
        </view>
    </u-popup>
</template>

<script setup>
import { ref, shallowRef } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import { useUserStore } from '@/stores/userStore'
import { userInfoApi, userEditApi } from '@/api/usersApi'
import { changePwdApi, forgetPwdApi, bindWeChatApi, bindMobileApi, bindEmailApi } from '@/api/loginApi'
import toolUtil from '@/utils/toolUtil'
import checkUtil from '@/utils/checkUtil'

// 用户信息
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

// 修改密码
const changePwdForm = ref({
    oldPassword: '',
    newPassword: '',
    ackPassword: ''
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

// 验证码(忘记密码)
const codeTipsByForgetPwd = ref('')
const uCodeRefByForgetPwd = shallowRef()
const codeChangeByForgetPwd = (text) => {
    codeTipsByForgetPwd.value = text
}

// 查询信息
const queryUserInfo = async () => {
    const res = await userInfoApi()
    userInfo.value = res.data
}

// 发送短信
const onSendSms = async (type) => {
    if (checkUtil.isEmpty(form.mobile)) {
        return uni.$u.toast('请输入手机号')
    }
    if (uCodeRefByForgetPwd.value?.canGetCode) {
        await sendSmsApi({
            scene: smsEnum.LOGIN,
            mobile: form.mobile
        })
        uCodeRefByForgetPwd.value?.start()
    }
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

// 绑定微信
const onBindWeChat = async () => {
    const code = await toolUtil.obtainWxCode()
    await bindWeChatApi({code: code})
    queryUserInfo()
    
}

// 绑定手机
const onBindMobile = async () => {
    
}

// 绑定邮箱
const onBindEmail = async () => {
    
}

// 更新用户
const onUpdateUser = async () => {
    await userEditApi({
        scene: popupType.value,
        value: formValue.value
    })

    queryUserInfo()
    popupShow.value = false
    popupType.value = null
    formValue.value = null
}

// 性别修改
const onGenderEdit = (value) => {
    formValue.value = value[0] + 1
    onUpdateUser()
}

// 密码修改
const onPwdEdit = async () => {
    if (popupType.value === 'changePwd') {
        if (checkUtil.isEmpty(changePwdForm.oldPassword)) {
            return uni.$u.toast('请输入原始密码')
        }
        if (checkUtil.isEmpty(changePwdForm.newPassword)) {
            return uni.$u.toast('请输入新的密码')
        }
        if (checkUtil.isEmpty(changePwdForm.ackPassword)) {
            return uni.$u.toast('请输入确认密码')
        }
        if (changePwdForm.newPassword !== changePwdForm.ackPassword) {
            return uni.$u.toast('两次不密码不一致')
        }
        await changePwdApi(changePwdForm)
    } else {
        await forgetPwdApi(changePwdForm)
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
    }
}

// 弹出窗口
const onShowPopup = (type) => {
    switch (type) {
        case 'gender':
            popupType.value = type
            formValue.value = userInfo.gender
            genderPicker.value = true
            break
        case 'password':
            popupType.value = type
            pwdPicker.value = true
            break
        default:
            popupType.value = type
            popupShow.value = true
            formValue.value = userInfo.value[type]
    }
}
</script>

<style lang="scss">
.popup-form-widget {
    width: 85vw;
    padding: 0 30rpx;
    border-radius: 14rpx;
    background-color: #ffffff;
    .title {
        text-align: center;
        font-size: 28rpx;
        padding: 22rpx;
    }
}
</style>
