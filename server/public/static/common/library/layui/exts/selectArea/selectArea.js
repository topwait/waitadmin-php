layui.define(['jquery', 'form'], function (exports) {
    let $    = layui.$;
    let form = layui.form;

    let ojb = {
        /**
         * 初始化入口
         *
         * @param provinceFilter (省过滤器)
         * @param cityFilter     (市过滤器)
         * @param districtFilter (区过滤器)
         * @param provinceName   (省选下拉框名)
         * @param cityName       (市选下拉框名)
         * @param districtName   (区选下拉框名)
         * @param provinceId     (默认省ID)
         * @param cityId         (默认市ID)
         * @param districtId     (默认区ID)
         * @author zero
         */
        init: function (provinceFilter, cityFilter, districtFilter, provinceName, cityName, districtName, provinceId, cityId, districtId) {
            /**
             * 选择地区列出子级地址
             *
             * @param data    (子级地区)
             * @param element (子级节点)
             * @returns {number|*}
             * @author zero
             */
            function areaSelect(data, element) {
                let html = '';
                for (let i in data) {
                    html += "<option value=" + data[i]['id'] + ">" + data[i]['name'] + "</option>";
                }
                $(element).html(html);
                form.render('select');
                return data[0]['id'] === undefined ? 0 : data[0]['id'];
            }

            /**
             * 省市区Input选择
             *
             * @type {*|jQuery|HTMLElement}
             * @author zero
             */
            let elemProvinceName = $("[name='"+provinceName+"']");
            let elemCityName     = $("[name='"+cityName+"']");
            let elemDistrictName = $("[name='"+districtName+"']");

            /**
             * 初次渲染选择省
             *
             * @type {number|*}
             * @author zero
             */
            let firstId = areaSelect(getAllProvince(), elemProvinceName);
            if(provinceId !== undefined){
                elemProvinceName.val(provinceId);
                form.render('select');
                firstId = provinceId;
            }

            /**
             * 初次渲染选择市
             *
             * @type {number|*}
             * @author zero
             */
            firstId = areaSelect(getAreaChildren(firstId), elemCityName);
            if(cityId !== undefined){
                elemCityName.val(cityId);
                form.render('select');
                firstId = cityId;
            }

            /**
             * 初次渲染选择区
             *
             * @type {number|*}
             * @author zero
             */
            areaSelect(getAreaChildren(firstId), elemDistrictName);
            if(districtId !== undefined){
                elemDistrictName.val(districtId);
                form.render('select');
            }

            /**
             * 选择省
             */
            form.on('select('+provinceFilter+')', function (data) {
                let firstId = areaSelect(getAreaChildren(data['value']), $("[name='"+cityName+"']"));
                areaSelect(getAreaChildren(firstId), $("[name='"+districtName+"']"));
            });

            /**
             * 选择市
             */
            form.on('select('+cityFilter+')', function (data) {
                areaSelect(getAreaChildren(data['value']), $("[name='"+districtName+"']"));
            });
        }
    };

    exports('selectArea', ojb);
});