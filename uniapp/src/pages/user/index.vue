<template>
    <w-loading :show="isFirstLoading" />
    <view>
        <view class="flex flex-col justify-between bg-linear-fade">
            <!-- 顶部占位 -->
            <w-widget-bar background-color="var(--color-primary)" />

            <!-- 设置按钮 -->
            <view class="flex justify-end gap-2 mx-4 mt-4 text-white">
                <wd-icon
                    name="setting"
                    size="38rpx"
                    @click="$go('/bundle/pages/user/setup')"
                />
            </view>

            <!-- 用户信息 -->
            <view class="flex items-center gap-2 mx-3 mb-4 pt-0">
                <wd-img
                    width="120rpx"
                    height="120rpx"
                    mode="aspectFill"
                    :round="true"
                    :src="isLogin ? userInfo.avatar : defaultAvatar"
                />
                <view v-if="isLogin">
                    <view class="text-white text-2xl font-semibold">{{ userInfo.nickname }}</view>
                    <view class="text-white opacity-60 text-xs mt-1">ID: {{ userInfo.sn }}</view>
                </view>
                <view v-else @click="$go('/pages/login/index')">
                    <view class="text-white text-2xl font-semibold">立即登录</view>
                    <view class="text-white opacity-60 text-xs mt-1">登录解锁更多精彩内容</view>
                </view>
            </view>

            <!-- 用户功能 -->
            <w-widget-srv
                mode="row"
                :grid="5"
                :list="diyHeaders"
                text-size="24rpx"
                image-size="54rpx"
            />
        </view>

        <!-- 我的服务 -->
        <w-widget-srv
            mode="col"
            :list="diyServices"
            title="我的服务"
            margin-t="22rpx"
        />

        <!-- 轮播广告 -->
        <view class="p-3">
            <wd-swiper
                :list="diyAdv"
                :autoplay="true"
                height="220rpx"
            />
        </view>

        <!-- 版本信息 -->
        <view class="pb-3 text-center text-tx-placeholder">
            www.waitadmin.cn
        </view>
    </view>
</template>

<script setup lang="ts">
import defaultAvatar from '@/assets/images/default_avatar.png'
import clientUtil from '@/utils/client'
import useAppStore from '@/stores/app'
import useUserStore from '@/stores/user'

const appStore = useAppStore()
const userStore = useUserStore()
const { isLogin, userInfo } = storeToRefs(userStore)

// 支付宝端处理 (因为不支持自定义导航)
clientUtil.callback({
    'mp-alipay': () => {
        uni.setNavigationBarTitle({
            title: ' '
        })
        uni.setNavigationBarColor({
            frontColor: '#ffffff',
            backgroundColor: '#4d80f0'
        })
    }
})

// 首次加载
const isFirstLoading = ref<boolean>(true)

// 装修数据
const diyHeaders = ref<any[]>([])
const diyServices = ref<any[]>([])
const diyAdv = ref<any[]>([])

watch(
    () => appStore.ossDomain,
    (val: string) => {
        // 以下只是演示数据,你可以改成从接口获取,直接编写自己的页面
        if (val) {
            // 页头装修
            diyHeaders.value = [
                {
                    name: '待付款',
                    link: '/bundle/pages/example/order',
                    value: '0',
                    image: appStore.toAbsoluteUrl('/static/common/images/init/to_be_paid.png')
                },
                {
                    name: '待发货',
                    link: '/bundle/pages/example/order',
                    value: '0',
                    image: appStore.toAbsoluteUrl('/static/common/images/init/to_be_delivered.png')
                },
                {
                    name: '待收货',
                    link: '/bundle/pages/example/order',
                    value: '0',
                    image: appStore.toAbsoluteUrl('/static/common/images/init/to_be_received.png')
                },
                {
                    name: '待评价',
                    link: '/bundle/pages/example/order',
                    value: '0',
                    image: appStore.toAbsoluteUrl('/static/common/images/init/to_be_evaluate.png')
                },
                {
                    name: '售后',
                    link: '/bundle/pages/example/order',
                    value: '0',
                    image: appStore.toAbsoluteUrl('/static/common/images/init/to_be_after_sales.png')
                }
            ]

            // 服务装修
            diyServices.value = [
                {
                    name: '地址信息',
                    link: '/bundle/pages/address/index',
                    image: appStore.toAbsoluteUrl('/static/common/images/init/me_address.png')
                },
                {
                    name: '我的钱包',
                    link: '/bundle/pages/user/wallet',
                    image: appStore.toAbsoluteUrl('/static/common/images/init/me_wallet.png')
                },
                {
                    name: '联系客服',
                    link: '/bundle/pages/index/kefu',
                    value: '0',
                    image: appStore.toAbsoluteUrl('/static/common/images/init/me_kefu.png')
                },
                {
                    name: '用户协议',
                    link: '/bundle/pages/index/policy',
                    image: appStore.toAbsoluteUrl('/static/common/images/init/me_policy.png')
                },
                {
                    name: '关于我们',
                    link: '/bundle/pages/index/about',
                    image: appStore.toAbsoluteUrl('/static/common/images/init/me_about.png')
                }
            ]

            // 广告装修
            diyAdv.value = [
                appStore.toAbsoluteUrl('/static/common/images/init/adv01.jpg'),
                appStore.toAbsoluteUrl('/static/common/images/init/adv02.jpg')
            ]

            setTimeout(() => {
                isFirstLoading.value = false
            }, 500)
        }
    }, { immediate: true }
)
</script>

<style lang="scss" scoped>
.wot-theme-light .bg-linear-fade {
    background: linear-gradient(
            var(--color-primary) 0%,
            var(--color-primary) 70%,
            var(--bg-color) 100%);
}

.wot-theme-dark .bg-linear-fade {
    background: linear-gradient(
            var(--bg-color-lighter) 0%,
            var(--bg-color-lighter) 70%,
            var(--bg-color) 100%);
}
</style>
