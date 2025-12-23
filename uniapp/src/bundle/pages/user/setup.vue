<template>
    <w-loading :show="isFirstLoading" :delay="500" />

    <view class="border-t border-br-lighter">
        <!-- 弹框组件 -->
        <wd-message-box />

        <!-- 用户信息 -->
        <wd-cell-group border>
            <wd-cell :is-link="true">
                <view @click="$go('/bundle/pages/user/intro')">
                    <view class="flex items-center">
                        <view class="w-[100rpx] h-[100rpx] rounded-full bg-light">
                            <wd-img :src="userInfo.avatar" width="100%" height="100%" round />
                        </view>
                        <view class="flex-1 text-left" style="margin-left: 30rpx">
                            <view class="text-base text-tx-primary">{{ userInfo.nickname }}</view>
                            <view class="text-xs">ID: {{ userInfo.sn }}</view>
                        </view>
                    </view>
                </view>
            </wd-cell>
            <wd-cell
                title="密保手机"
                :is-link="true"
                :value="userInfo.mobile"
                @click="$go('/bundle/pages/user/adjust_mobile')"
            />
            <wd-cell
                title="地址管理"
                :is-link="true"
                @click="$go('/bundle/pages/address/index')"
            />
        </wd-cell-group>

        <!-- 协议信息 -->
        <view class="mt-3">
            <wd-cell-group border>
                <wd-cell
                    title="服务协议"
                    :is-link="true"
                    @click="$go('/bundle/pages/index/policy?type=service')"
                />
                <wd-cell
                    title="隐私政策"
                    :is-link="true"
                    @click="$go('/bundle/pages/index/policy?type=privacy')"
                />
                <wd-cell
                    title="关于我们"
                    :value="`v${config.version}`"
                    :is-link="true"
                    @click="$go('/bundle/pages/index/about')"
                />
            </wd-cell-group>
        </view>

        <!-- 退出登录 -->
        <view class="mt-3">
            <wd-cell-group border>
                <wd-cell>
                    <view class="text-center text-tx-primary" @click="logout">
                        退出登录
                    </view>
                </wd-cell>
            </wd-cell-group>
        </view>
    </view>
</template>

<script setup lang="ts">
import { useMessage } from 'wot-design-uni'
import config from '@/config/index'
import useUserStore from '@/stores/user'

const message = useMessage()
const userStore = useUserStore()

// 首次加载
const isFirstLoading = ref<boolean>(true)

// 用户信息
const { userInfo } = storeToRefs(userStore)

/**
 * 退出登录
 */
const logout = () => {
    message
        .confirm({
            msg: '您确定要退出登录吗?',
            title: '退出系统'
        })
        .then(() => {
            userStore.logout()
        })
        .catch(() => {})
}

onShow(() => {
    isFirstLoading.value = false
})
</script>

<style lang="scss" scoped>
:deep(.wd-cell) {
    padding-left: 0 !important;
    color: var(--text-color-primary);
    .wd-cell__wrapper {
        padding: 24rpx 30rpx;
    }
    .wd-cell__value {
        font-size: 26rpx;
        color: var(--text-color-placeholder);
    }
    .wd-cell__body {
        align-items: center;
    }
    .wd-cell__arrow-right {
        color: var(--text-color-placeholder) !important;
    }
}
</style>
