export default {
    /**
     * 提取微信Code
     */
    obtainWxCode() {
    	return new Promise((resolve, reject) => {
    		uni.login({
    			success(response) {
    				if ("login:ok" === response.errMsg) {
                        resolve(response.code);
                    } else {
                        reject(response.errMsg);
                    }
    			},
    			fail(error) {
    				reject(error)
    			}
    		})
    	})
    },
    /**
     * 提取微信位置
     */
    obtainWxLocation() {
    	return new Promise((resolve, reject) => {
    		uni.getLocation({
    			type: 'wgs84',
    			success: function (response) {
    				resolve(response)
    			},
                fail(error) {
                	reject(error)
                }
    		})
    	})
    }
}