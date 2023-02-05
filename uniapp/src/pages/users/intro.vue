<template>
    <view class="mt-20">
        <u-cell-group>
            <u-cell-item :arrow="false">
                <view class="flex flex-col items-center justify-center">
                    <u-avatar :src="''" mode="circle" size="100" class="h-100" />
                    <view class="mt-6 text-xs color-muted">点击修改头像</view>
                </view>
            </u-cell-item>
            <u-cell-item title="ID" :arrow="false">
                <text class="u-margin-right-10">664875785</text>
                <u-icon name="lock" size="28" color="#999" />
            </u-cell-item>
            <u-cell-item title="账号" :value="'king'" />
            <u-cell-item title="昵称" :value="'小明同学'" />
            <u-cell-item title="性别" :value="'男'" />
        </u-cell-group>
    </view>

    <view class="mt-20">
        <u-cell-group>
            <u-cell-item title="登录密码" />
            <u-cell-item title="绑定微信" />
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

    <view class="mt-20">
        <u-cell-group>
            <u-cell-item title="隐私政策" />
            <u-cell-item title="服务协议" />
            <u-cell-item title="关于我们" :value="'v1.2.4'" />
        </u-cell-group>
    </view>

    <view class="mx-30 py-40">
        <w-button @on-click="onLogout()">退出登录</w-button>
    </view>
</template>

<script setup>
import { useUserStore } from '@/stores/userStore'

const userStore = useUserStore()

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
</script>
