<template>
    <w-loading :show="isFirstLoading" />
    <view>
        <!-- 轮播图 -->
        <view class="layout-carousel">
            <view class="backdrop" />
            <view class="swiper">
                <wd-swiper
                    v-if="diyHoming?.banner"
                    :list="diyHoming?.banner"
                    :autoplay="true"
                    height="300rpx"
                />
            </view>
        </view>

        <!-- 推荐服务 -->
        <w-widget-srv
            :list="diyHoming?.nav"
            margin-t="46rpx"
        />

        <!-- 最新资讯 -->
        <view class="mt-3 mb-4 mx-3 bg-overlay rounded-md">
            <view class="px-3 pt-4 text-tx-primary text-xl font-bold">最新资讯</view>
            <view class="mt-1">
                <view
                    v-for="(item, index) in articleList"
                    :key="index"
                    class="flex p-2.5 border-b border-br-thinned last:border-b-0"
                    @click="$go('/pages/article/detail?id='+item.id)"
                >
                    <wd-img
                        :lazy-load="true"
                        width="240rpx"
                        height="180rpx"
                        :radius="4"
                        :src="item.image"
                        style="flex-shrink: 0;"
                    />
                    <view class="flex flex-1 flex-col justify-between px-4 overflow-hidden">
                        <view class="truncate text-xl font-medium text-tx-primary">
                            {{ item.title }}
                        </view>
                        <view class="line-clamp-2 text-xs text-tx-regular">
                            {{ item.intro }}
                        </view>
                        <view class="flex justify-between text-tx-placeholder">
                            <view class="text-xs">{{ item.create_time }}</view>
                            <view class="text-xs">{{ item.browse }}人浏览</view>
                        </view>
                    </view>
                </view>
            </view>
        </view>

        <!-- 版本信息 -->
        <view class="pb-3 text-center text-tx-placeholder">
            www.waitadmin.cn
        </view>
    </view>
</template>

<script setup lang="ts">
import indexApi from '@/api/index'
import useAppStore from '@/stores/app'

const appStore = useAppStore()

// 首次加载
const isFirstLoading = ref<boolean>(true)

// 文章列表
const articleList = ref<IndexDataResponse['article']>([])

// 首页装修
const diyHoming = ref()

onShow(async () => {
    const results = await indexApi.index()
    articleList.value = results.article
    setTimeout(() => {
        isFirstLoading.value = false
    }, 50)
})

watch(
    () => appStore.ossDomain,
    (value: string) => {
        if (value) {
            diyHoming.value = {
                banner: [
                    appStore.toAbsoluteUrl('/static/common/images/init/banner01.jpg'),
                    appStore.toAbsoluteUrl('/static/common/images/init/banner02.jpg')
                ],
                nav: [
                    {
                        'name': '多语言',
                        'image': appStore.toAbsoluteUrl('/static/common/images/init/ic_article.png'),
                        'link': '/bundle/pages/example/lang'
                    },
                    {
                        'name': '个人设置',
                        'image': appStore.toAbsoluteUrl('/static/common/images/init/ic_user.png'),
                        'link': '/bundle/pages/user/setup'
                    },
                    {
                        'name': '联系我们',
                        'image': appStore.toAbsoluteUrl('/static/common/images/init/ic_contact.png'),
                        'link': '/bundle/pages/index/kefu'
                    },
                    {
                        'name': '关于我们',
                        'image': appStore.toAbsoluteUrl('/static/common/images/init/ic_about.png'),
                        'link': '/bundle/pages/index/about'
                    }
                ]
            }
        }
    }, { immediate: true }
)
</script>

<style lang="scss" scoped>
.layout-carousel {
    position: relative;
    .backdrop {
        height: 300rpx;
        background-color: var(--color-primary);
        background-image: url("../../assets/images/bg_head_honour.png");
        background-repeat: round;
        background-size: contain;
    }
    .swiper {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        padding: 20rpx 24rpx 0;
    }
}
</style>
