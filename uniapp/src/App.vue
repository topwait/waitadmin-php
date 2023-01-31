<script setup>
import { onLaunch } from '@dcloudio/uni-app'
import { useAppStore } from '@/stores/appStore'

const appStore = useAppStore()

onLaunch(async () => {
    await appStore.getSysConfig()
})
</script>

<style lang="scss">

</style>
