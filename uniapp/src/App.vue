<script setup>
import { onLaunch } from '@dcloudio/uni-app'
import { useAppStore } from '@/stores/appStore'
import { useUserStore } from '@/stores/userStore'

const appStore = useAppStore()
const userStore = useUserStore()

onLaunch(async () => {
    uni.hideTabBar({animation: false})
    await appStore.getSysConfig()
    await appStore.h5Intercepts()
    await userStore.getUserInfo()
})
</script>

<style lang="scss">
// #ifdef H5
* { touch-action: pan-y; }

// #endif
</style>
