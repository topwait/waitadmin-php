<template>
    <w-loading :show="isFirstLoading" :delay="500" />
    <view>
        <!-- 提示弹框 -->
        <wd-toast />
        <wd-message-box />

        <!-- 基础信息 -->
        <view class="mt-3">
            <wd-cell-group border>
                <wd-cell>
                    <button
                        style="height: 100%; background-color: unset;"
                        open-type="chooseAvatar"
                        @chooseavatar="changeAvatar"
                        @click="chooseAvatar"
                    >
                        <view class="flex flex-col items-center justify-center">
                            <view class="w-[110rpx] h-[110rpx] rounded-full bg-light">
                                <wd-img :src="userInfo.avatar" width="100%" height="100%" round />
                            </view>

                            <view class="text-xs text-tx-placeholder mt-2">点击修改头像</view>
                        </view>
                    </button>
                </wd-cell>
                <wd-cell title="ID">
                    {{ userInfo.sn }}
                    <wd-icon name="lock-on" />
                </wd-cell>
                <wd-cell
                    title="账号"
                    :value="userInfo.account"
                    :is-link="true"
                    @click="$go('/bundle/pages/user/change_account')"
                />
                <wd-cell
                    title="昵称"
                    :value="userInfo.nickname"
                    :is-link="true"
                    @click="$go('/bundle/pages/user/change_nickname')"
                />
                <wd-picker
                    :v-model="userInfo.gender"
                    :columns="genderColumns"
                    use-default-slot
                    @confirm="changeGender"
                >
                    <wd-cell title="性别" is-link :value="userInfo.gender" />
                </wd-picker>
            </wd-cell-group>
        </view>

        <!-- 账号信息 -->
        <view class="my-3">
            <wd-cell-group border>
                <wd-cell
                    title="密保手机"
                    :value="userInfo.mobile || '点击修改'"
                    :is-link="true"
                    @click="$go('/pages/user/adjust_mobile')"
                />
                <wd-cell
                    title="电子邮箱"
                    :is-link="true"
                    :value="userInfo.email || '点击修改'"
                    @click="$go('/pages/user/adjust_email')"
                />
                <wd-cell
                    title="登录密码"
                    :is-link="true"
                    value="点击修改"
                    @click="$go('/pages/user/change_password')"
                />
            </wd-cell-group>
        </view>

        <!-- 头像裁剪 -->
        <wd-img-cropper
            :model-value="cropShow"
            :img-src="cropPath"
            @confirm="changeAvatar"
        />
    </view>
</template>

<script setup lang="ts">
import { useToast } from 'wot-design-uni'
import useUserStore from '@/stores/user'
import indexApi from '@/api/index'
import userApi from '@/api/user'

const toast = useToast()
const userStore = useUserStore()

// 首次加载
const isFirstLoading = ref<boolean>(true)

// 用户信息
const { userInfo } = storeToRefs(userStore)

// 头像裁剪
const cropShow = ref<boolean>(false)
const cropPath = ref<string>('')

// 性别列表
const genderColumns = [
    '未知',
    '男',
    '女'
]

/**
 * 选择头像
 */
const chooseAvatar = () =>  {
    // #ifndef MP-WEIXIN
    uni.chooseImage({
        count: 1,
        success: (res) => {
            cropPath.value = res.tempFilePaths[0] || ''
            cropShow.value = true
        }
    })
    // #endif
}

/**
 * 修改头像
 */
const changeAvatar = async (e: any) => {
    let avatarUrl = e.tempFilePath || ''
    if (e.detail?.avatarUrl) {
        avatarUrl = e.detail.avatarUrl
    }

    const data = await indexApi.upload('picture', 'permanent',{
        filePath: avatarUrl
    })

    cropPath.value = ''
    cropShow.value = false
    toast.loading({
        msg: '上传中...',
        zIndex: 9999,
        cover: true
    })

    await userApi.edit({
        scene: 'avatar',
        value: data.url
    })

    await userStore.getUser()
    toast.close()
}

/**
 * 修改性别
 */
const changeGender = async ({ value }: { value: string }) => {
    const index = genderColumns.indexOf(value)
    await userApi.edit({
        scene: 'gender',
        value: String(index)
    })
    await userStore.getUser()
}

onShow(() => {
    isFirstLoading.value = false
})
</script>

<style lang="scss" scoped>
:deep(.wd-cell) {
    padding-left: 0 !important;
    color: var(--text-color-primary);
    &.is-hover {
        background: unset;
    }
    .wd-cell__wrapper {
        padding: 24rpx 30rpx;
    }
    .wd-cell__value {
        font-size: 26rpx;
        color: var(--text-color-placeholder);
    }
    .wd-cell__arrow-right {
        color: var(--text-color-placeholder) !important;
    }
}
</style>
