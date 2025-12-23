<template>
    <w-loading :show="isFirstLoading" />
    <view class="pt-8 mx-8">
        <view class="pt-7 h-[900rpx] rounded-xl bg-page">
            <view class="flex flex-col items-center">
                <wd-img
                    width="390rpx"
                    height="390rpx"
                    :src="diyData.image"
                />
                <view class="mt-3 text-xl font-bold">
                    {{ diyData.title }}
                </view>
                <view class="mt-5 text-base text-tx-primary leading-7">
                    <view v-if="diyData.datetime">
                        服务时间：{{ diyData.datetime }}
                    </view>
                    <view v-if="diyData.datetime">
                        服务电话：{{ diyData.mobile }}
                    </view>
                    <view v-if="diyData.datetime">
                        服务Q Q：{{ diyData.qq }}
                    </view>
                </view>
            </view>

            <view class="pt-5 px-7">
                <wd-button
                    type="primary"
                    size="large"
                    block
                    @click="handleSaveQrcode"
                >
                    保存二维码
                </wd-button>
            </view>
        </view>
    </view>
</template>

<script setup lang="ts">
import useAppStore from '@/stores/app'
import imageUtil from '@/utils/image'

const appStore = useAppStore()

// 首次加载
const isFirstLoading = ref<boolean>(false)

// 装修数据
const diyData = computed(() => appStore.diyDto.tie)

// 保存二维码
const handleSaveQrcode = () => {
    if (!diyData.value?.image) {
        uni.showToast({ title: '二维码图片不存在', icon: 'none' })
        return
    }
    imageUtil.saveToAlbum(diyData.value.image)
}
</script>
