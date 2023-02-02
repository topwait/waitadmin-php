export default {
    init(url) {
        console.log(url)
        return uni.navigateTo({url: url})
    }
}

