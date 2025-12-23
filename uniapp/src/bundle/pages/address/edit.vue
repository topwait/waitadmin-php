
<template>
    <w-loading v-if="isFirstLoading" />
    <view class="pt-1">
        <wd-form ref="form" :model="formData">
            <wd-cell-group border>
                <wd-input
                    v-model="formData.nickname"
                    prop="nickname"
                    label="姓名"
                    label-width="130rpx"
                    placeholder="请填写姓名"
                />
                <wd-input
                    v-model="formData.mobile"
                    prop="mobile"
                    label="联系电话"
                    label-width="130rpx"
                    placeholder="请填写联系电话"
                />
                <wd-picker
                    v-model="formData.region_ids"
                    :columns="columns"
                    label="所在地区"
                    label-width="130rpx"
                    :column-change="onChangeDistrict"
                    @confirm="onChangeAddress"
                />
                <wd-textarea
                    v-model="formData.address"
                    prop="address"
                    label="详细地址"
                    label-width="130rpx"
                    placeholder="小区、门牌号"
                    style="height: 180rpx;"
                />
            </wd-cell-group>
        </wd-form>

        <view class="mt-2 p-3 bg-page">
            <wd-checkbox v-model="formData.is_default">
                <text class="text-sm">设为默认地址</text>
            </wd-checkbox>
        </view>

        <view class="p-3 pt-5">
            <view>
                <wd-button
                    type="primary"
                    :block="true"
                    @click="handleSubmit"
                >
                    确认
                </wd-button>
            </view>
            <view class="mt-4">
                <wd-button
                    type="primary"
                    :block="true"
                    :plain="true"
                    @click="importWxAddress"
                >
                    导入微信地址
                </wd-button>
            </view>
        </view>
    </view>
</template>

<script setup lang="ts">
import { useToast } from 'wot-design-uni'

const toast = useToast()

interface AddressForm {
    id?: number;
    nickname: string;
    mobile: string;
    region: string;
    address: string;
    region_ids: string[];
    is_default: boolean;
}

// 首次加载
const isFirstLoading = ref<boolean>(true)

// 地区数据
const district = ref<any>({})
const columns = ref<any[]>([])

// 表单数据
const formData = ref<AddressForm>({
    nickname: '',
    mobile: '',
    region: '',
    address: '',
    region_ids: [],
    is_default: false
})

/**
 * 模拟获取地址详情
 */
const getAddressDetail = (id: number) => {
    setTimeout(() => {
        formData.value = {
            id: id,
            nickname: '张三',
            mobile: '13800138000',
            region: '广东省 广州市 白云区',
            address: '人和镇凤岗路建设大道九州创意园1号楼205室',
            region_ids: ['110000', '110100', '110102'],
            is_default: false
        }
    }, 500)
}

/**
 * 模拟获取地区数据
 */
const getRegionData = () => {
    const data: any = {
        '0': [{ label: '北京', value: '110000' }, { label: '广东省', value: '440000' }],
        '110000': [{ label: '北京', value: '110100' }],
        '440000': [{ label: '广州市', value: '440100' }, { label: '韶关市', value: '440200' }, { label: '深圳市', value: '440300' }, { label: '珠海市', value: '440400' }, { label: '汕头市', value: '440500' }],
        '110100': [{ label: '东城区', value: '110101' }, { label: '西城区', value: '110102' }, { label: '朝阳区', value: '110105' }, { label: '丰台区', value: '110106' }, { label: '石景山区', value: '110107' }],
        '440100': [{ label: '荔湾区', value: '440103' }, { label: '越秀区', value: '440104' }, { label: '海珠区', value: '440105'}],
        '440200': [{ label: '武江区', value: '440203'}],
        '440300': [{ label: '罗湖区', value: '440303' }, { label: '福田区', value: '440304' }],
        '440400': [{ label: '香洲区', value: '440402' }, { label: '斗门区', value: '440403' }, { label: '金湾区', value: '440404' }],
        '440500': [{ label: '龙湖区', value: '440507' }, { label: '金平区', value: '440511' }]
    }

    district.value = data
    columns.value = [
        data[0],
        data[data[0][0].value],
        data[data[data[0][0].value][0].value]
    ]
}

/**
 * 监听地区滑动
 */
const onChangeDistrict = (pickerView: any, value: any, columnIndex: number, resolve: any) => {
    const item = value[columnIndex]
    if (columnIndex === 0) {
        pickerView.setColumnData(1, district.value[item.value])
        pickerView.setColumnData(2, district.value[district.value[item.value][0].value])
    } else if (columnIndex === 1) {
        pickerView.setColumnData(2, district.value[item.value])
    }
    resolve()
}

/**
 * 确认选择地址
 */
const onChangeAddress = ({ value }: { value: string[] }) => {
    formData.value.region_ids = value
}

/**
 * 导入微信地址
 */
const importWxAddress = () => {
    const wxAddressJson = uni.getStorageSync('wxAddress')
    if (!wxAddressJson) {
        return
    }

    // eslint-disable-next-line no-console
    console.log(JSON.parse(wxAddressJson))
}

/**
 * 提交保存地址
 */
const handleSubmit = () => {
    toast.show('保存成功')
    setTimeout(() => {
        uni.navigateBack()
    }, 1000)
}

onLoad((options) => {
    try {
        const id = parseInt(options?.id || '0')
        if (id) {
            uni.setNavigationBarTitle({ title: '编辑地址' })
            getAddressDetail(id)
        } else {
            uni.setNavigationBarTitle({ title: '添加地址' })
        }

        getRegionData()
    } finally {
        setTimeout(() => {
            isFirstLoading.value = false
        }, 500)
    }
})
</script>
