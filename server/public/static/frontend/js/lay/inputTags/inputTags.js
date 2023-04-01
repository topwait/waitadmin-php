// +----------------------------------------------------------------------
// | 标签生成器
// +----------------------------------------------------------------------

layui.define(['jquery', 'layer'], function (exports) {
    'use strict';
    let $ = layui.jquery;
    let MOD_NAME = 'inputTags';

    /**
     * 外部接口
     */
    let inputTags = {
        config: {flatContent: []}
        ,on: function (events, callback) {
            return layui.onevent.call(this, MOD_NAME, events, callback)
        }
        ,set: function (options) {
            let that = this;
            that.config = $.extend({}, that.config, options);
            return that;
        }
    };

    /**
     * 当前实例
     */
    let thisInputTags = function () {
        let that = this;
        return {
            config: that.config
        }
    };

    /**
     * 补全标签
     */
    let fillTags = function (val, spans, options, that) {
        if (options.openFlatContent) {
            options.flatContent.push(val);
        }
        options.content.push(val);
        that.render();
        if (options.pinArray.indexOf(val) !== -1) {
            spans = '<span><em>' + val + '</em></span>';
        } else {
            spans = '<span><em>' + val + '</em><button type="button" class="close">×</button></span>';
        }
        options.elem.before(spans)
    };

    /**
     * 取构造器
     */
    let Class = function (options) {
        let that = this;
        that.config = $.extend({}, that.config, inputTags.config, options);
        that.render();
    };

    /**
     * 默认配置
     */
    Class.prototype.config = {
        close: false            // 关闭按钮
        ,openFlatContent: false // 是否开启
        ,theme: ''              // 背景颜色
        ,content: []            // 默认标签
        ,pinArray: []           // 需要取消删除按钮的元素
    };

    /**
     * 初始对象
     */
    Class.prototype.init = function () {
        let that = this;
        let spans = '';
        let options = that.config;

        $.each(options.content, function (index, item) {
            if (options.openFlatContent && options.flatContent.indexOf(item) === -1) {
                options.flatContent.push(item)
            }
            if (options.pinArray.indexOf(item) !== -1) {
                spans += '<span><em>' + item + '</em></span>';
            } else {
                spans += '<span><em>' + item + '</em><button type="button" class="close">×</button></span>';
            }
        });

        options.elem.before(spans);
        that.events()
    };

    /**
     * 渲染对象
     */
    Class.prototype.render = function () {
        let that = this;
        let options = that.config;
        options.elem = $(options.elem);
        that.enter()
    };

    /**
     * 生成标签
     */
    Class.prototype.enter = function () {
        let that  = this;
        let spans = '';
        let options = that.config;

        options.elem.keyup(function (event) {
            let keyNum = (event.keyCode ? event.keyCode : event.which);
            if (keyNum === 13) {
                let $val = options.elem.val().trim();
                if (!$val) return false;
                if (options.openFlatContent) {
                    if (-1 === options.content.indexOf($val)) {
                        if (options.flatContent.indexOf($val) === -1) {
                            fillTags($val, spans, options, that)
                        }
                    }
                } else {
                    if (options.content.indexOf($val) === -1) {
                        fillTags($val, spans, options, that)
                    }
                }
                options.done && typeof options.done === "function" && options.done($val);
                options.elem.val('');

                let content = '';
                $.each(options.content, function (index, val) {
                    content += val + ","
                });
                options.elem.siblings('input[type="hidden"]').val($.trim(content, ","));
            }
        })
    };

    /**
     * 事件处理
     */
    Class.prototype.events = function () {
        let that = this;
        let options = that.config;
        $(options.elem.parent(".tags")).on("click", '.close', function () {
            let ThisRemove = $(this).parent('span').remove();
            let ThisText   = $(ThisRemove).find("em").text();
            options.content.splice($.inArray(ThisText, options.content), 1);
            if (options.openFlatContent) {
                options.flatContent.splice($.inArray(ThisText, options.flatContent), 1)
            }
        })
    };

    /**
     * 核心入口
     */
    inputTags.render = function (options) {
        let inst = new Class(options);
        inst.init();
        return thisInputTags.call(inst);
    };

    /**
     * 加载样式
     */
    layui.link(layui.cache.base + 'inputTags/inputTags.css');

    exports('inputTags', inputTags);
});