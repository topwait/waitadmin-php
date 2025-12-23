
<template>
    <view class="p-3">
        <view class="mb-3 text-center">
            <wd-img
                :src="logo"
                width="130rpx"
                height="130rpx"
            />
        </view>

        <view class="p-3 bg-page rounded-md">
            <view class="text-xl font-bold text-tx-primary">
                template用法: 不带参数
            </view>
            <view class="mt-2 text-tx-secondary">
                <view>定义: {"pages.account": "你好"}</view>
                <view>用法: $t("pages.account")</view>
            </view>
        </view>

        <view class="mt-3 p-3 bg-page rounded-md">
            <view class="text-xl font-bold text-tx-primary">
                template用法: 带参数用法
            </view>
            <view class="mt-2 text-tx-secondary">
                <view>定义: {"pages.account": "你好，{name}！"}</view>
                <view>用法: $t("pages.account", {name: "小明"})</view>
            </view>
        </view>

        <view class="mt-3 p-3 bg-page rounded-md">
            <view class="text-xl font-bold text-tx-primary">JS中使用一:</view>
            <view class="mt-2 text-tx-secondary">
                <view>import { useI18n } from 'vue-i18n'</view>
                <view>const { t } = useI18n()</view>
                <view>console.log(t('pages.account'))</view>
            </view>
        </view>

        <view class="mt-3 p-3 bg-page rounded-md">
            <view class="text-xl font-bold text-tx-primary">JS中使用二:</view>
            <view class="mt-2 text-tx-secondary">
                <view>import { getCurrentInstance } from 'vue'</view>
                <view>const instance = getCurrentInstance()</view>
                <view>const proxy = instance?.proxy as ComponentPublicInstance</view>
                <view>console.log(proxy.$t('pages.account'))</view>
            </view>
        </view>

    </view>
</template>

<script setup lang="ts">
import logo from '@/bundle/assets/logo.png'

// 方案1：使用 useI18n()（推荐）
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
// eslint-disable-next-line no-console
console.log(t('pages.account', {name: 'Wait'}))

// 方案2：使用 getCurrentInstance()（不推荐）
import { getCurrentInstance } from 'vue'
const instance = getCurrentInstance()
const proxy = instance?.proxy as ComponentPublicInstance
// eslint-disable-next-line no-console
console.log(proxy.$t('pages.account', {name: 'Wait'}))
</script>
