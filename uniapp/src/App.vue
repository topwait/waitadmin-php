<script setup lang="ts">
import { onLaunch } from '@dcloudio/uni-app'
import useUserStore from '@/stores/user'
import useAppStore from '@/stores/app'
import decorUtil from '@/utils/decor'

const appStore = useAppStore()
const userStore = useUserStore()

onLaunch(async () => {
    appStore.getRoutePages()
    await appStore.getSysConfig()
    await appStore.getDecorates()
    await appStore.h5Intercepts()
    await userStore.getUser()
    await decorUtil.setNavBar()
    await decorUtil.setTabBar()
})
</script>
