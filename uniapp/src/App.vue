<script setup>
import { onLaunch } from '@dcloudio/uni-app'
import { useAppStore } from '@/stores/appStore'
import { useUserStore } from '@/stores/userStore'

const appStore = useAppStore()
const userStore = useUserStore()

onLaunch(async () => {
    await appStore.getSysConfig()
    await userStore.getUserInfo()
})
</script>

<style lang="scss">

</style>
