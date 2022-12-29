<script setup>
import { onLaunch, onShow, onHide } from '@dcloudio/uni-app'

onLaunch(async () => {
    console.log('App Launch')
})

onShow(async () => {
    console.log('App Show')
})

onHide(async () => {
    console.log('App onHide')
})
</script>

<style>
/* 每个页面公共css */
</style>
