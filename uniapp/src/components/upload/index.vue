<template>
    <view class="uploader">
        <wd-toast />
        <view class="flex flex-wrap gap-2.5">
            <!-- 上传预览 -->
            <view v-if="showFileList" class="flex flex-wrap gap-2.5">
                <view
                    v-for="(item, index) in fileList"
                    :key="index"
                    class="relative overflow-hidden rounded-md border border-br bg-lighter"
                    :style="{ width: uploadItemSize, height: uploadItemSize }"
                >
                    <!-- 预览:图片 -->
                    <image
                        v-if="item.fileType === 'image'"
                        :src="item.url"
                        mode="aspectFill"
                        class="block w-full h-full"
                        @click="handlePreview(index)"
                    />
                    <!-- 预览:视频 -->
                    <video
                        v-else-if="item.fileType === 'video'"
                        :src="item.url"
                        class="block w-full h-full"
                        @click="handlePreview(index)"
                    />
                    <!-- 预览:其它 -->
                    <view v-else class="flex flex-col align-center justify-center text-center p-2">
                        <wd-icon name="a-rootlist" size="38rpx" color="var(--text-color-secondary)" />
                        <text class="text-xs text-center text-tx-secondary break-all">
                            {{ item.name }}
                        </text>
                    </view>

                    <!-- 上传中 -->
                    <view v-if="item.status === 'uploading'" class="uploader__loading">
                        <wd-loading :size="24" />
                        <text v-if="showProgress" class="mt-1 text-xs text-white">
                            {{ item.progress }}%
                        </text>
                    </view>

                    <!-- 上传失败 -->
                    <view v-if="item.status === 'failed'" class="uploader__failed">
                        <text class="font-bold text-xs text-danger">上传失败</text>
                    </view>

                    <!-- 删除按钮 -->
                    <view
                        v-if="!disabled && deletable"
                        class="uploader__delete"
                        @click="handleDelete(index)"
                    >
                        <wd-icon name="close" size="32rpx" color="#fff" />
                    </view>
                </view>
            </view>
            <!-- 上传按钮 -->
            <view class="file-picker" @click="handleChoose">
                <slot>
                    <view
                        v-if="!disabled && fileList.length < limit"
                        class="flex flex-col items-center justify-center rounded-md border border-br bg-lighter"
                        :style="{ width: uploadItemSize, height: uploadItemSize }"
                    >
                        <wd-icon
                            v-if="uploadIcon"
                            :name="uploadIcon"
                            :size="uploadIconSize"
                            :color="uploadIconColor"
                        />
                        <wd-icon
                            v-else-if="type === 'picture'"
                            name="camera"
                            :size="uploadIconSize"
                            :color="uploadIconColor"
                        />
                        <wd-icon
                            v-else-if="type === 'video'"
                            name="video1"
                            :size="uploadIconSize"
                            :color="uploadIconColor"
                        />
                        <wd-icon
                            v-else
                            name="cloud-upload"
                            :size="uploadIconSize"
                            :color="uploadIconColor"
                        />
                        <text v-if="uploadText" class="mt-2 text-xs text-tx-secondary">
                            {{ uploadText }}
                        </text>
                    </view>
                </slot>
            </view>

        </view>
    </view>
</template>

<script setup lang="ts">
import { useToast } from 'wot-design-uni'
import useUserStore from '@/stores/user'
import indexApi from '@/api/index'

import {
    type ChooseResult,
    type FileData,
    type FileType,
    getFileType,
    getFileName,
    getFileExt,
    chooseFile,
    handleFiles
} from './hooks'

interface UploaderProps {
    // 双向绑定值
    modelValue?: string | string[] | object;

    // 文件上传接口地址
    url?: string;
    // 上传文件的字段名
    name?: string;
    // 上传到临时的目录
    temp?: boolean;
    // 附加的上传参数
    data?: Record<string, any>;
    // 上传请求的HTTP头
    headers?: Record<string, string>;

    // 允许上传文件类型
    type: 'picture' | 'video' | 'document' | 'package' | 'all'
    // 最大可选的文件数
    limit?: number;
    // 单个文件最大体积(B)
    maxSize?: number;
    // 是否支持多选文件
    multiple?: boolean;
    // 是否禁用上传功能
    disabled?: boolean;
    // 是否显示删除按钮
    deletable?: boolean;
    // 是否显示上传进度条
    showProgress?: boolean;
    // 是否显示文件的列表
    showFileList?: boolean;
    // 筛选可选的扩展类型
    acceptMime?: string[];
    // 返回通知的数据结构
    emitsType?: 'string' | 'object';

    // 图片压缩类型(仅对图片有效)
    sizeType?: ('original' | 'compressed')[];
    // 图片|视频的来源类型(相册/相机)
    sourceType?: ('album' | 'camera')[];

    // 上传区域的文字
    uploadText?: string;
    // 上传区域的图标
    uploadIcon?: string;
    // 上传区域图标的大小
    uploadIconSize?: string;
    // 上传区域图标的颜色
    uploadIconColor?: string;
    // 上传区域按钮的尺寸
    uploadItemSize?: string;

    // 上传前的钩子函数
    beforeUpload?: (file: any) => boolean | Promise<boolean>;
    // 删除前的钩子函数
    beforeDelete?: (file: FileData, index: number) => boolean | Promise<boolean>;
}

const props = withDefaults(defineProps<UploaderProps>(), {
    name: 'file',
    limit: 1,
    multiple: false,
    disabled: false,
    deletable: true,
    showProgress: true,
    showFileList: true,
    uploadText: '',
    uploadIcon: '',
    uploadIconSize: '48rpx',
    uploadIconColor: '#999999',
    uploadItemSize: '160rpx',
    sizeType: () => ['original', 'compressed'],
    sourceType: () => ['album', 'camera']
})

const emits = defineEmits<{
    'update:modelValue': [value: string | string[]];
    'progress': [file: FileData, progress: number];
    'change': [value: FileData[]];
    'delete': [file: FileData, index: number];
    'success': [file: FileData, response: any];
    'fail': [file: FileData, error: any];
}>()

const toast = useToast()
const userStore = useUserStore()

// 扩展类型
const imageExtArr = ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.ico']
const videoExtArr = ['.mp4', '.avi', '.flv', '.rmvb', '.mov']
const packExtArr = ['.zip', '.rar', '.iso', '.7z', '.tar', '.gz', '.arj', '.bz2']
const docsExtArr = ['.txt', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.pem']

// 文件列表
const fileList = ref<FileData[]>([])

// 默认限制
const defaultMaxSize: Record<string, number> = {
    image: 10 * 1024 * 1024,
    video: 30 * 1024 * 1024,
    package: 30 * 1024 * 1024,
    document: 30 * 1024 * 1024
}

/**
 * 获取接纳类型
 */
const getAcceptType = (): FileType => {
    switch (props.type) {
        case 'picture': return 'image'
        case 'video': return 'video'
        default: return 'all'
    }
}

/**
 * 筛选出文件类型
 */
const getAcceptMime = (): string[] | undefined => {
    if (props.acceptMime) {
        return props.acceptMime
    }

    switch (props.type) {
        case 'picture':
            return imageExtArr
        case 'video':
            return videoExtArr
        case 'document':
            return docsExtArr
        case 'package':
            return packExtArr
        default:
            return undefined
    }
}

/**
 * 选择上传文件
 */
const handleChoose = async () => {
    if (props.disabled) {
        return
    }

    if (!userStore.isLogin) {
        toast.error('请登录后再操作!')
        return setTimeout(() => {
            uni.navigateTo({
                url: '/pages/login/index'
            })
        }, 1800)
    }

    const count: number = props.limit - fileList.value.length
    if (count <= 0) {
        toast.show(`最多只能上传${props.limit}个文件`)
        return
    }

    const filesResult: ChooseResult = await chooseFile({
        type: getAcceptType(),
        count: props.limit,
        compressed: false,
        sizeType: props.sizeType,
        sourceType: props.sourceType,
        extension: getAcceptMime()
    })

    await chooseFileCallback(filesResult)
}

/**
 * 选择文件回调
 */
const chooseFileCallback = async (filesResult: ChooseResult) => {
    for (const file of filesResult.tempFiles) {
        let maxSize: number = props.maxSize || 0
        if (maxSize === -1) {
            maxSize = defaultMaxSize[props.type] || 0
        }

        const size: number = file.size || 0
        if (maxSize > 0 && size > maxSize) {
            const mb: number = maxSize / 1024 / 1024
            toast.warning(`文件大小不能超过${mb.toFixed(0)}MB`)
            continue
        }

        if (props.beforeUpload) {
            const result = await props.beforeUpload(file)
            if (!result) {
                continue
            }
        }

        const fileItem: FileData = handleFiles(file)
        fileList.value.push(fileItem)

        await uploadFile(fileItem, fileList.value.length - 1)
    }
}

/**
 * 上传文件
 */
const uploadFile = async (fileItem: FileData, index: number) => {
    try {
        const scene: 'temporary' | 'permanent' = props.temp ? 'temporary' : 'permanent'
        const result: any = await indexApi.upload(props.type, scene, {
            filePath: fileItem.url,
            formData: props.data
        }, (progress) => {
            fileList.value[index]!.progress = progress
        })

        fileList.value[index]!.status = 'success'
        fileList.value[index]!.url = result.url
        emits('success', fileItem, result)
        updateValue()
    } catch (error) {
        fileList.value[index]!.errMsg = error as string
        fileList.value[index]!.status = 'failed'
        emits('fail', fileItem, error)
    }
}

/**
 * 通知更新
 */
const updateValue = () => {
    const successFiles = fileList.value.filter((item) => item.status === 'success')

    let value: any[] | string[]
    if (props.emitsType === 'object') {
        const array: any = []
        for (const item of successFiles) {
            array.push({
                url: item!.url,
                ext: item!.ext,
                size: item!.size,
                name: item!.name
            })
        }
        value = array
    } else {
        value = successFiles.map((item) => item.url)
    }

    if (!props.multiple || props.limit <= 1) {
        value = value.length ? value[0] : ''
    }

    emits('update:modelValue', value)
    emits('change', successFiles)
}

/**
 * 删除文件
 */
const handleDelete = async (index: number) => {
    const file = fileList.value[index] as FileData
    if (props.beforeDelete) {
        const result = await props.beforeDelete(file, index)
        if (!result) {
            return
        }
    }

    fileList.value.splice(index, 1)
    emits('delete', file, index)
    updateValue()
}

/**
 * 预览文件
 */
const handlePreview = (index: number) => {
    const file: FileData = fileList.value[index] as FileData
    if (file.fileType === 'image') {
        const images = fileList.value.filter((item) => item.fileType === 'image').map((item) => item.url)
        const current = images.indexOf(file.url)
        uni.previewImage({
            urls: images,
            current
        })
    } else if (file.fileType === 'video') {
        if (typeof uni.previewMedia === 'function') {
            uni.previewMedia({
                sources: [{ url: file.url, type: 'video' }]
            })
        } else {
            toast.warning('当前环境不支持视频预览')
        }
    }
}

watch(
    () => props.modelValue,
    (newVal) => {
        if (!newVal) {
            fileList.value = []
            return
        }

        const array: FileData[] = []
        const lists = Array.isArray(newVal) ? newVal : [newVal]
        lists.forEach((item: any) => {
            let isEmpty: boolean = false
            if (typeof item === 'object' && Object.keys(item).length === 0) {
                isEmpty = true
            }

            if (!isEmpty) {
                const url: string = String(item?.url || item || '')
                array.push({
                    url,
                    size: item?.size || 0,
                    ext: item?.ext || getFileExt(url),
                    name: item?.name || getFileName(url),
                    fileType: item?.fileType || getFileType(url),
                    status: 'success'
                })
            }
        })

        fileList.value = array
    },
    { immediate: true }
)
</script>

<style scoped lang="scss">
.uploader {
    &__loading {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: rgb(0 0 0 / 50%);
    }

    &__failed {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgb(0 0 0 / 50%);
    }

    &__delete {
        position: absolute;
        top: 0;
        right: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44rpx;
        height: 44rpx;
        background-color: rgb(0 0 0 / 50%);
        border-radius: 0 0 0 24rpx;
    }
}
</style>
