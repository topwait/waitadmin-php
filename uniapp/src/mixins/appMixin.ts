import decorUtil from '@/utils/decor'

export default {
    async onLoad(): Promise<void> {
        await decorUtil.setNavBar()
        await decorUtil.setTabBar()
    }
}
