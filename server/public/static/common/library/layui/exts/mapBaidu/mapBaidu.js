// +----------------------------------------------------------------------
// | 百度地图
// +----------------------------------------------------------------------

layui.define(['jquery', 'form'], function (exports) {
    let $ = layui.$;
    let BMap = window.BMap;
    let markersArray = [];
    let map = new BMap.Map('map');
    let geo = new BMap.Geocoder();

    let obj = {
        /**
         * 初始化
         */
        init: function (longitude, latitude) {
            new BMap.Geolocation();
            let local = new BMap.LocalSearch(map, { renderOptions: {map: map} });
            let point = new BMap.Point(longitude, latitude)
            map.centerAndZoom(point, 16);
            map.addEventListener('click', obj.showInfo);
            obj.searchMap(local);
        },

        /**
         * 清除标识
         */
        clearOverlays: function() {
            if (markersArray) {
                for (let i in markersArray) {
                    map.removeOverlay(markersArray[i])
                }
            }
        },

        /**
         * 地图标注
         */
        addMarker: function (point) {
            let marker = new BMap.Marker(point);
            markersArray.push(marker);
            obj.clearOverlays();
            map.addOverlay(marker);
        },

        /**
         * 点击地图
         */
        showInfo: function (e) {
            $('input[name="longitude"]').val(e.point.lng);
            $('input[name="latitude"]').val(e.point.lat);
            geo.getLocation(e.point, function (rs) {
                console.log(rs)
                let addComp = rs['addressComponents'];
                let info_address = addComp['street'] + addComp['streetNumber'];
                if(info_address !== ''){
                    $('input[name="address"]').val(info_address);
                }
            });
            obj.addMarker(e.point);
        },

        /**
         * 搜索地图
         */
        searchMap: function (local) {
            $(document).on('click', '#searchMap', function() {
                let province_id  = $("#province");
                let city_id      = $("#city");
                let district_id  = $("#district");
                let shop_address = $("input[name='address']").val();

                if(province_id.val() === null){
                    layer.open({icon:2, time:2000, content:'请选择省份'});
                    return;
                }

                if(city_id.val() === null){
                    layer.open({icon:2, time:2000, content:'请选择市'});
                    return;
                }

                if(district_id.val() === null){
                    layer.open({icon:2, time:2000, content:'请选择镇/区'});
                    return;
                }

                let address = province_id.find('option:selected').text() + city_id.find('option:selected').text() + district_id.find('option:selected').text() + shop_address;
                local.setSearchCompleteCallback(function (searchResult) {
                    let poi = searchResult['getPoi'](0);
                    $('input[name="longitude"]').val(poi.point.lng);
                    $('input[name="latitude"]').val(poi.point.lat);
                    map.centerAndZoom(poi.point, 13);
                });
                local.search(address);
            });
        }
    };

    exports('mapBaidu', obj);
});