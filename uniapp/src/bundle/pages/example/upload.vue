<template>
    <view class="p-3">
        <view class="text-tx-placeholder mb-2">当前是上传示例代码</view>

        <!-- 基础用法 -->
        <view class="mb-2 p-3 rounded-md bg-page">
            <view class="mb-2 text-xl font-bold text-tx-primary">单图上传</view>
            <w-upload
                v-model="imageUrl"
                :limit="1"
                type="picture"
                upload-text="单图"
                @success="handleSuccess"
            />
        </view>

        <!-- 多图上传 -->
        <view class="mb-2 p-3 rounded-md bg-page">
            <view class="mb-2 text-xl font-bold text-tx-primary">多图上传</view>
            <w-upload
                v-model="images"
                type="picture"
                :multiple="true"
                :limit="3"
                upload-text="多图"
            />
        </view>

        <!-- 视频上传 -->
        <view class="mb-2 p-3 rounded-md bg-page">
            <view class="mb-2 text-xl font-bold text-tx-primary">视频上传</view>
            <w-upload
                v-model="videoUrl"
                type="video"
                upload-text="视频"
            />
        </view>

        <!-- 文件上传 -->
        <view class="mb-2 p-3 rounded-md bg-page">
            <view class="mb-2 text-xl font-bold text-tx-primary">文件上传</view>
            <w-upload
                v-model="fileUrl"
                type="document"
                upload-text="文件"
            />
        </view>

        <!-- 带上传接口 -->
        <view class="mb-2 p-3 rounded-md bg-page">
            <view class="mb-2 text-xl font-bold text-tx-primary">自定义上传地址</view>
            <w-upload
                v-model="fileUrl"
                type="all"
                url="https://www.x.cn/api/upload"
                :headers="{ token: 'xxx' }"
                :data="{ dir: 'xxx' }"
                @success="handleSuccess"
            />
        </view>
    </view>
</template>

<script setup lang="ts">
// 支付宝端适配: 最好主动导入组件,因为主包没调用时,编译会报错
import WUpload from '@/components/upload/index.vue'

// 单图
const imageUrl = ref<string>('')
// 多图
const images = ref<string[]>([])
// 视频
const videoUrl = ref<string>('')
// 文件
const fileUrl = ref<string>('')

/**
 * 上传成功回调
 *
 * @param file
 * @param response
 */
function handleSuccess(file: any, response: any) {
    /* eslint-disable no-console */
    console.log('上传成功', file, response)
}
</script>
