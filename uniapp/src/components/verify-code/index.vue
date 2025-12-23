<template>
    <wd-button
        :type="btnType"
        :size="btnSize"
        :plain="btnPlain"
        :block="btnBlock"
        :round="btnRound"
        :hairline="btnHairline"
        :disabled="sendLoading || !!current.seconds || isDisabled"
        custom-class="!w-[186rpx]"
        :style="customStyle"
        @click="submitSend"
    >
        <text v-if="!current.seconds">
            {{ sendLoading ? btnIngText : btnGetText }}
        </text>
        <text v-else>
            {{ current.seconds }}s后可重发
        </text>
    </wd-button>
</template>

<script setup lang="ts">
import { useToast, useCountDown } from 'wot-design-uni'
import { debounce } from 'lodash-es'
import cacheUtil from '@/utils/cache'
import indexApi from '@/api/index'

const props = withDefaults(
    defineProps<{
        // 类型
        type: 'mobile' | 'email',
        // 场景码
        scene: number|string;
        // 邮箱号
        email?: string;
        // 手机号
        mobile?: string;
        // 总倒计秒数
        second?: number;
        // 待获取按钮文本
        btnGetText?: string;
        // 发送中按钮文本
        btnIngText?: string;
        // 细边框按钮
        btnHairline?: boolean;
        // 幽灵按钮
        btnPlain?: boolean;
        // 块状按钮
        btnBlock?: boolean;
        // 圆角按钮
        btnRound?: boolean;
        // 按钮尺寸
        btnSize?: 'small' | 'medium' | 'large';
        // 按钮类型
        btnType?: 'primary' | 'success' | 'info' | 'warning' | 'error' | 'text' | 'icon';
        // 按钮样式
        customStyle?: Object;
    }>(),
    {
        second: 60,
        btnGetText: '获取验证码',
        btnIngText: '正在发送中',
        btnHairline: true,
        btnPlain: true,
        btnBlock: true,
        btnRound: true,
        btnSize: 'small',
        btnType: 'primary'
    }
)

const toast = useToast()

// 键
const cacheKey: string = `countdown_v${props.scene}`

// 发送中
const sendLoading = ref<boolean>(false)

// 倒计时
const { start, reset, current } = useCountDown({
    time: props.second * 1000
})

// 剩余时长
const time: number = parseInt(cacheUtil.get(cacheKey) || 0)
if (time) {
    const timestamp: number = Math.floor(Date.now() / 1000)
    const surplus: number = time + 60 - timestamp
    if (surplus > 0) {
        reset(surplus * 1000)
        start()
    }
}

// 是否禁用
const isDisabled = computed<boolean>(() => {
    if (props.type === 'mobile') {
        return !props.mobile
    } else if (props.type === 'email') {
        return !props.email
    }
    return false
})

/**
 * 发送短信
 */
const submitSend = debounce(() => {
    if (props.type === 'mobile') {
        if (!props.mobile) {
            toast.show('请填写手机号码')
            return false
        }

        sendLoading.value = true
        indexApi.sendSms({
            scene: parseInt(String(props.scene)),
            mobile: props.mobile
        }).then(() => {
            start()
            const timestamp = Math.floor(Date.now() / 1000)
            cacheUtil.set(cacheKey, String(timestamp), 60)
        }).catch(() => {
            sendLoading.value = false
        })
    }

    if (props.type === 'email') {
        if (!props.email) {
            toast.show('请填写邮箱号')
            return false
        }

        sendLoading.value = true
        indexApi.sendEmail({
            scene: parseInt(String(props.scene)),
            email: props.email
        }).then(() => {
            start()
            const timestamp = Math.floor(Date.now() / 1000)
            cacheUtil.set(cacheKey, String(timestamp), 60)
        }).catch(() => {
            sendLoading.value = false
        })
    }
}, 500)
</script>
