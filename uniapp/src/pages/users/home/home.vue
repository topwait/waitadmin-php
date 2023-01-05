<template>
    <view>
        <view class="layout-header-widget">
            <view class="grid-skinny">
                <view class="userinfo">
                    <view class="avatar">
                        <image class="config" src="http://pic2.sc.chinaz.com/Files/pic/pic9/202002/hpic2119_s.jpg" />
                    </view>
                    <view class="synopsis">
                        <view class="username">Ants</view>
                        <view class="identity">ID: u7456565</view>
                    </view>
                </view>
                <view class="settings">
                    <image class="config" src="/static/ic_set.png" />
                    <image class="config" src="/static/ic_set.png" />
                    <image class="config" src="/static/ic_set.png" />
                </view>
            </view>
        </view>
    </view>
</template>

<script setup>

</script>

<style lang="scss">
.layout-header-widget {
	background-color: #1970F3;
	.grid-skinny {
		display: flex;
        justify-content: space-between;
        padding: 30rpx;
        .userinfo {
            display: flex;
            align-items: center;
        }
        .avatar {
			width: 120rpx;
			height: 120rpx;
            border-radius: 50%;
            background-color: #FFFFFF;
		}
        .synopsis {
            color: #FFFFFF;
            font-size: 28rpx;
            margin-left: 20rpx;
            height: 100%;
            padding: 7px 0;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .settings {
            .config {
                width: 50rpx;
                height: 50rpx;
                margin-top: 7px;
                margin-right: 10px;
            }
        }
	}
}
</style>
