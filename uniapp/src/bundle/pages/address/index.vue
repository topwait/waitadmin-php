<template>
    <wd-message-box />
    <view class="pb-[120rpx]">
        <!-- 地址信息列表 -->
        <view class="p-3 pb-0">
            <view
                v-for="(item, index) in addressList"
                :key="index"
                class="bg-white rounded-lg mb-3 overflow-hidden"
            >
                <view class="p-4">
                    <view class="flex items-center gap-x-2 mb-2">
                        <text class="font-bold text-lg">{{ item.nickname }}</text>
                        <text class="font-bold text-lg">{{ item.mobile }}</text>
                    </view>
                    <view class="text-sm text-tx-regular">
                        {{ item.region }}{{ item.address }}
                    </view>
                </view>

                <view class="flex items-center justify-between px-4 py-3">
                    <view class="flex items-center">
                        <wd-checkbox
                            v-model="item.is_default"
                            shape="square"
                            checked-color="#999999"
                            custom-label-class="ml-1!"
                            @change="setDefault(item)"
                        >
                            <text class="text-tx-regular text-xs">
                                {{ item.is_default ? '已默认' : '设为默认' }}
                            </text>
                        </wd-checkbox>
                    </view>
                    <view class="flex items-center gap-4">
                        <view
                            class="flex items-center text-tx-regular"
                            @click="$go(`/bundle/pages/address/edit?id=${item.id}`)"
                        >
                            <wd-icon name="edit-outline" size="26rpx" />
                            <text class="ml-1 text-xs">编辑</text>
                        </view>
                        <view
                            class="flex items-center text-tx-regular"
                            @click="handleDelete(index)"
                        >
                            <wd-icon name="delete-thin" size="26rpx" />
                            <text class="ml-1 text-xs">删除</text>
                        </view>
                    </view>
                </view>
            </view>
        </view>

        <!-- 底部添加按钮 -->
        <view class="fixed left-0 right-0 bottom-0 px-4 py-3 bg-white">
            <wd-button
                type="primary"
                block
                @click="$go('/pages/address/edit')"
            >
                添加新地址
            </wd-button>
        </view>
    </view>
</template>

<script setup lang="ts">
import { useToast, useMessage } from 'wot-design-uni'

const toast = useToast()
const message = useMessage()

interface AddressItem {
    id: number;
    nickname: string;
    mobile: string;
    region: string;
    address: string;
    is_default: number;
}

// 地址列表数据
const addressList = ref<AddressItem[]>([
    {
        id: 1,
        nickname: '张三',
        mobile: '13800138000',
        region: '广东省广州市白云区',
        address: '人和镇凤岗路建设大道九州创意园1号楼205室',
        is_default: 1
    },
    {
        id: 2,
        nickname: '李四',
        mobile: '13900139000',
        region: '广东省深圳市南山区',
        address: '科技园高新南一道3号',
        is_default: 0
    },
    {
        id: 3,
        nickname: '王五',
        mobile: '13700137000',
        region: '广东省佛山市禅城区',
        address: '祖庙路15号',
        is_default: 0
    },
    {
        id: 3,
        nickname: '王五',
        mobile: '13700137000',
        region: '广东省佛山市禅城区',
        address: '祖庙路15号',
        is_default: 0
    },
    {
        id: 3,
        nickname: '王五',
        mobile: '13700137000',
        region: '广东省佛山市禅城区',
        address: '祖庙路15号',
        is_default: 0
    },
    {
        id: 3,
        nickname: '王五',
        mobile: '13700137000',
        region: '广东省佛山市禅城区',
        address: '祖庙路15号',
        is_default: 0
    }
])

/**
 * 设为默认地址
 */
const setDefault = (item: AddressItem): void => {
    if (item.is_default) {
        addressList.value.forEach((address: AddressItem) => {
            if (address.id !== item.id) {
                address.is_default = 0
            }
        })

        uni.showToast({
            title: '已设为默认地址',
            icon: 'success'
        })
    }
}

/**
 * 删除地址
 */
const handleDelete = (index: number): void => {
    message
        .confirm({msg: '是否删除该地址'})
        .catch(() => {})
        .then(() => {
            addressList.value.splice(index, 1)
            toast.show('删除成功')
        })
}
</script>
