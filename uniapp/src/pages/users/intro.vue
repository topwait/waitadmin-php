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
            <u-cell-item title="绑定微信" />
            <u-cell-item title="绑定邮箱" />
            <u-cell-item title="绑定手机" :arrow="false">
                <u-button
                    :plain="true"
                    type="primary"
                    hover-class="none"
                    size="mini"
                    shape="circle"
                    @click="onSendSms()"
                >{{ '绑定手机号' }}
                </u-button>
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
        @click="onPasswordEdit"
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
        <!-- 修改密码 -->
        <view class="popup-form-widget" v-if="popupType === 'changePwd'">
            <view class="title">修改密码</view>
            <u-form-item>
                <u-input v-model="formValue" placeholder="请输入原始密码" :border="false" />
            </u-form-item>
            <u-form-item>
                <u-input v-model="formValue" placeholder="请输入新的密码" :border="false" />
            </u-form-item>
            <u-form-item>
                <u-input v-model="formValue" placeholder="请再次确认密码" :border="false" />
            </u-form-item>
            <view class="py-40">
                <u-button type="primary" shape="circle" size="medium" :custom-style="{width: '100%'}" @click="onUpdateUser()">确定</u-button>
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
            </u-form-item>
            <view class="py-40">
                <u-button type="primary" shape="circle" size="medium" :custom-style="{width: '100%'}" @click="onUpdateUser()">确定</u-button>
            </view>
        </view>
    </u-popup>
</template>

<script setup>
import { ref } from 'vue'
import { onShow } from '@dcloudio/uni-app'
import { useUserStore } from '@/stores/userStore'
import { getUserInfoApi, postUserEditApi } from '@/api/usersApi.js'

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

const formValue = ref(null)
const popupType = ref(null)
const popupShow = ref(false)

const genderPicker = ref(false)
const genderListed = ref(['男', '女'])
const genderEnumer = ref(['未知', '男', '女'])

const pwdPicker = ref(false)
const pwdListed = ref([{text: '修改密码'}, {text: '忘记密码'}])

// 显示监听
onShow(() => {
    queryUserInfo()
})

// 查询信息
const queryUserInfo = async () => {
    const res = await getUserInfoApi()
    userInfo.value = res.data
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

// 更新用户
const onUpdateUser = async () => {
    await postUserEditApi({
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
const onPasswordEdit = (index) => {
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
