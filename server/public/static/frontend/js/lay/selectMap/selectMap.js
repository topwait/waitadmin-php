
layui.define(["jquery", "form"], function (exports) {
    var markersArray = [];
    var map   = new BMap.Map("map");
    var geoc  = new BMap.Geocoder();

    var obj = {
        /**
         * 初始化
         */
        init: function (longitude, latitude) {
            new BMap.Geolocation();
            var local = new BMap.LocalSearch(map, { renderOptions: {map: map} });
            var point = new BMap.Point(longitude, latitude)
            map.centerAndZoom(point, 16);
            map.addEventListener("click", obj.showInfo);

            obj.searchMap(local);
        },
        /**
         * 清除标识
         */
        clearOverlays: function() {
            if (markersArray) {
                for (i in markersArray) {
                    map.removeOverlay(markersArray[i])
                }
            }
        },
        /**
         * 地图上标注
         */
        addMarker: function (point) {
            var marker = new BMap.Marker(point);
            markersArray.push(marker);
            obj.clearOverlays();
            map.addOverlay(marker);
        },
        /**
         * 点击地图事件处理
         */
        showInfo: function (e) {
            $("input[name='longitude']").val(e.point.lng);
            $("input[name='latitude']").val(e.point.lat);
            geoc.getLocation(e.point, function (rs) {
                var addComp = rs.addressComponents;
                var info_address = addComp.street + addComp.streetNumber;
                if(info_address !== ''){
                    $("input[name='address']").val(info_address);
                }
            });
            obj.addMarker(e.point);
        },
        /**
         * 搜索地图
         * @param local
         */
        searchMap: function (local) {
            $(document).on('click', '#searchMap', function() {
                var province_id  = $("#province");
                var city_id      = $("#city");
                var district_id  = $("#district");
                var shop_address = $("input[name='address']").val();

                if(province_id.val() === null){
                    layer.open({icon:2, time:2000, content:"请选择省份"});
                    return;
                }
                if(city_id.val() === null){
                    layer.open({icon:2, time:2000, content:"请选择市"});
                    return;
                }
                if(district_id.val() === null){
                    layer.open({icon:2,time:2000,content:"请选择镇/区"});
                    return;
                }

                var address = province_id.find("option:selected").text() + city_id.find("option:selected").text() + district_id.find("option:selected").text() + shop_address;

                //找到最符合的标注a点坐标
                local.setSearchCompleteCallback(function (searchResult) {
                    var poi = searchResult.getPoi(0);
                    $("input[name='longitude']").val(poi.point.lng);
                    $("input[name='latitude']").val(poi.point.lat); //获取经度和纬度，将结果显示在文本框中
                    map.centerAndZoom(poi.point, 13);
                });
                local.search(address);
            });
        }
    };


    exports("waitMap", obj);
});