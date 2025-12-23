<template>
    <wd-config-provider theme="light" :theme-scheme="themeName">
        <ku-root-view />
        <wd-toast />
        <wd-tabbar
            v-if="diyTabbar?.style.effect === 'custom' && isTabbar"
            :model-value="curTabbar"
            :fixed="true"
            :bordered="true"
            :placeholder="true"
            :safe-area-inset-bottom="true"
            :z-index="9998"
            :shape="diyTabbar?.style.shape"
            :active-color="diyTabbar?.style.selectedColor"
            :inactive-color="diyTabbar?.style.color"
            @change="changeTabbar"
        >
            <wd-tabbar-item
                v-for="(item, index) in diyTabbar.list"
                :key="index"
                :name="index"
                :title="item.text"
            >
                <template #icon>
                    <wd-img
                        height="40rpx"
                        width="40rpx"
                        :src="index === curTabbar ? item.selectedIconPath : item.iconPath"
                    />
                </template>
            </wd-tabbar-item>
        </wd-tabbar>
    </wd-config-provider>
</template>

<script setup lang="ts">
import useAppStore from '@/stores/app'
import toolsUtil from './utils/tools'

const appStore = useAppStore()
const curTabbar = computed(() => appStore.curTabBar)
const diyTabbar = computed(() => appStore.diyDto.tabbar)
const themeName = computed(() => appStore.diyDto.theme)
const isTabbar = ref<boolean>(false)

const changeTabbar = ({ value }: { value: string }) => {
    const index = parseInt(value)
    const pagePath = diyTabbar.value.list[index]?.pagePath
    appStore.setCurrentTabBar(index)
    uni.reLaunch({
        url: pagePath || ''
    })
}

const customTabbar = () => {
    const page = toolsUtil.currentPage()
    const path: string = `/${page.route}`
    const data: string[] = appStore.tabbar
    if (data.includes(path)) {
        const index: number = data.findIndex((item: string) => item === path)
        appStore.setCurrentTabBar(index)
        isTabbar.value = true
    } else {
        isTabbar.value = false
    }
}

onShow(() => {
    customTabbar()
})

watch(
    () => appStore.tabbar,
    () => {
        customTabbar()
    }
)
</script>

<style lang="scss">
page {
    .wd-tabbar--round.is-fixed.is-safe {
        bottom: calc(env(safe-area-inset-bottom) + 12rpx) !important;
    }
}
</style>
