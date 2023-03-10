<template>
    <view class="layout-adv-widget">
        <u-swiper 
            :list="advList" 
            :height="200"
            :title="false"
            :border-radius="14"
            indicator-pos="none"
        />
    </view>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    list: {
        type: Array,
        default: () => []
    }
})

const advList = computed(() => {
    let data = []
    props.list.forEach((item) => {
        let title = ''
        if (item.title !== undefined) {
            title = item.title
        } else if (item.name !== undefined) {
            title = item.name
        } else if (item.text !== undefined) {
            title = item.text
        }
        data.push({
            title: title, 
            image: item.image,
            link: item.link
        })
    })
    return data
})
</script>

<style lang="scss">
.layout-adv-widget {
    padding: 0 20rpx;
    box-sizing: border-box;
}
</style>
