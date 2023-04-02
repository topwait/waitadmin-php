// +----------------------------------------------------------------------
// | 图标选择器
// +----------------------------------------------------------------------

layui.define(['laypage', 'form'], function (exports) {
    'use strict';

    let IconPicker = function () { this.v = '0.1.beta'; };
    let $    = layui.jquery;
    let _MOD = 'iconPicker';
    let BODY = 'body';

    /**
     * 渲染组件
     */
    IconPicker.prototype.render = function(options) {
        let opts = options,
            // DOM选择器
            elem = opts.elem,
            // 是否分页: true/false
            page = opts.page,
            // 每页显示数量
            limit = opts.limit == null ? 12 : opts.limit,
            // 是否开启搜索：true/false
            search = opts.search == null ? true : opts.search,
            // 点击回调
            click = opts.click,
            // json数据
            data = {},
            // 唯一标识
            tmp = new Date().getTime(),
            // 定义类名
            TITLE        = 'layui-select-title',
            TITLE_ID     = 'layui-select-title-' + tmp,
            ICON_BODY    = 'layui-iconPicker-' + tmp,
            PICKER_BODY  = 'layui-iconPicker-body-' + tmp,
            PAGE_ID      = 'layui-iconPicker-page-' + tmp,
            LIST_BOX     = 'layui-iconPicker-list-box',
            selected     = 'layui-form-selected',
            unselect     = 'layui-unselect';

        let a = {
            /**
             * 初始化
             */
            init: function () {
                data = common.getData['fontClass']();
                a.hideElem().createSelect().createBody().toggleSelect();
                return a;
            },

            /**
             * 隐藏elem
             */
            hideElem: function () {
                $(elem).hide();
                return a;
            },

            /**
             * 绘制下拉选择框
             */
            createSelect: function () {
                let selectHtml = '<div class="layui-iconPicker layui-unselect layui-form-select" id="'+ ICON_BODY +'">' +
                    '<div class="'+ TITLE +'" id="'+ TITLE_ID +'">' +
                        '<div class="layui-iconPicker-item">'+
                            '<span class="layui-iconPicker-icon layui-unselect">' +
                                '<i class="layui-icon layui-icon-circle-dot"></i>' +
                            '</span>'+
                            '<i class="layui-edge"></i>' +
                        '</div>'+
                    '</div>' +
                    '<div class="layui-anim layui-anim-upbit"></div>';
                $(elem).after(selectHtml);
                return a;
            },

            /**
             * 展开/折叠下拉
             */
            toggleSelect: function () {
                let item = '#' + TITLE_ID + ' .layui-iconPicker-item,#' + TITLE_ID + ' .layui-iconPicker-item .layui-edge';
                a.event('click', item, function (e) {
                    let $icon = $('#' + ICON_BODY);
                    if ($icon.hasClass(selected)) {
                        $icon.removeClass(selected).addClass(unselect);
                    } else {
                        $icon.addClass(selected).removeClass(unselect);
                    }
                    e.stopPropagation();
                });
                return a;
            },

            /**
             * 绘制主体部分
             */
            createBody: function () {
                // 获取数据
                let searchHtml = '';
                if (search) {
                    searchHtml = '<div class="layui-iconPicker-search">' +
                        '<input class="layui-input">' +
                        '<i class="layui-icon">&#xe615;</i>' +
                        '</div>';
                }

                // 组合dom
                let bodyHtml = '<div class="layui-iconPicker-body" id="'+ PICKER_BODY +'">' +
                    searchHtml +
                        '<div class="'+ LIST_BOX +'"></div> '+
                     '</div>';
                $('#' + ICON_BODY).find('.layui-anim').eq(0).html(bodyHtml);
                a.search().createList().check().page();

                return a;
            },

            /**
             * 绘制图标列表
             */
            createList: function (text) {
                let d        = data,       //图标数组集合
                    l        = d.length,   //图标数组长度
                    _limit   = limit,      //每页显图标数
                    pageHtml = '',         //分页HTML内容
                    icons    = [],         //图标DOM数组
                    listHtml = $('<div class="layui-iconPicker-list">'); //图标列表HTML

                // 模糊搜索
                for (let i = 0; i < l; i++) {
                    let obj = d[i];
                    if (text && obj.indexOf(text) === -1) continue;
                    let icon = '<div class="layui-iconPicker-icon-item" title="'+ obj +'">';
                    icon += '<i class="layui-icon '+ obj +'"></i>';
                    icon += '</div>';
                    icons.push(icon);
                }

                // 查询图标
                l = icons.length;
                let _pages = l % _limit === 0 ? l / _limit : parseInt((l / _limit + 1).toString());
                for (let i = 0; i < _pages; i++) {
                    let lm = $('<div class="layui-iconPicker-icon-limit" id="layui-iconPicker-icon-limit-'+ (i+1) +'">');
                    for (let j = i * _limit; j < (i+1) * _limit && j < l; j++) {
                        lm.append(icons[j]);
                    }
                    listHtml.append(lm);
                }

                // 没有数据
                if (l === 0) {
                    listHtml.append('<p class="layui-iconPicker-tips">无数据</p>');
                }

                // 是否分页
                if (page){
                    $('#' + PICKER_BODY).addClass('layui-iconPicker-body-page');
                    pageHtml = '<div class="layui-iconPicker-page" id="'+ PAGE_ID +'">' +
                        '<div class="layui-iconPicker-page-count">' +
                        '<span id="'+ PAGE_ID +'-current">1</span>/' +
                        '<span id="'+ PAGE_ID +'-pages">'+ _pages +'</span>' +
                        '(<span id="'+ PAGE_ID +'-length">'+ l +'</span>)' +
                        '</div>' +
                        '<div class="layui-iconPicker-page-operate">' +
                        '<i class="layui-icon" id="'+ PAGE_ID +'-prev" data-index="0" prev>&#xe603;</i> ' +
                        '<i class="layui-icon" id="'+ PAGE_ID +'-next" data-index="2" next>&#xe602;</i> ' +
                        '</div>' +
                        '</div>';
                }

                $('#' + ICON_BODY).find('.layui-anim').find('.' + LIST_BOX).html('').append(listHtml).append(pageHtml);
                return a;
            },

            /**
             * 分页
             */
            page: function () {
                let icon = '#' + PAGE_ID + ' .layui-iconPicker-page-operate .layui-icon';

                $(icon).unbind('click');
                a.event('click', icon, function (e) {
                    let elem = e.currentTarget;
                    let total = parseInt($('#' +PAGE_ID + '-pages').html());
                    let isPrev = $(elem).attr('prev') !== undefined;
                    let $cur = $('#' +PAGE_ID + '-current');
                    let current = parseInt($cur.html());

                    // 分页数据
                    if (isPrev && current > 1) {
                        current=current-1;
                        $(icon + '[prev]').attr('data-index', current);
                    } else if (!isPrev && current < total){
                        current=current+1;
                        $(icon + '[next]').attr('data-index', current);
                    }
                    $cur.html(current);

                    // 图标数据
                    $('.layui-iconPicker-icon-limit').hide();
                    $('#layui-iconPicker-icon-limit-' + current).show();
                    e.stopPropagation();
                });
                return a;
            },

            /**
             * 搜索
             */
            search: function () {
                let item = '#' + PICKER_BODY + ' .layui-iconPicker-search .layui-input';
                a.event('input propertychange', item, function (e) {
                    let elem = e.target;
                    let t = $(elem).val();
                    a.createList(t);
                });
                a.event('click', item, function (e) {
                    e.stopPropagation();
                });
                return a;
            },

            /**
             * 选择
             */
            check: function () {
                let item = '#' + PICKER_BODY + ' .layui-iconPicker-icon-item';
                a.event('click', item, function (e) {
                    let el = $(e.currentTarget).find('i');
                    let icon =  el.attr('class');
                            
                    $('#' + TITLE_ID).find('.layui-iconPicker-item .layui-iconPicker-icon i').html('').attr('class', icon);
                    $('#' + ICON_BODY).removeClass(selected).addClass(unselect);
                    $(elem).attr('value', icon);

                    // 回调
                    if (click) {
                        click({ icon: icon  });
                    }

                });

                return a;
            },

            /**
             * 事件
             */
            event: function (evt, el, fn) {
                $(BODY).on(evt, el, fn);
            }
        };

        let common = {
            /**
             * 获取数据
             */
            getData: {
                fontClass: function () {
                    return [
                        'layui-icon-circle-dot',

                        'icon-run', 'icon-stop', 'icon-window-min', 'icon-window-max', 'icon-window-close',
                        'icon-detail', 'icon-drag', 'icon-del', 'icon-edit', 'icon-add', 'icon-text-stay', 'icon-text-sign',
                        'icon-text-order', 'icon-text-invoice', 'icon-text-file', 'icon-text-doc', 'icon-text-catalogue',
                        'icon-telephone-fixed', 'icon-telephone-fill', 'icon-telephone', 'icon-team-fill', 'icon-team', 'icon-system',
                        'icon-shutdown', 'icon-shopping-cart-fill', 'icon-shopping-cart', 'icon-shop-fill', 'icon-shop', 'icon-share-ring-fill',
                        'icon-share-ring', 'icon-share-plain', 'icon-share-frame', 'icon-sex-male-fill', 'icon-sex-male', 'icon-sex-girl-fill',
                        'icon-sex-girl', 'icon-setup-fill', 'icon-setup', 'icon-seckill-plain', 'icon-seckill-hollow', 'icon-seckill-flash',
                        'icon-seckill-clock', 'icon-screen-small', 'icon-screen-narrow', 'icon-screen-large', 'icon-screen-enlarge',
                        'icon-reverse-lens-fill', 'icon-reverse-lens', 'icon-purse-fill', 'icon-purse', 'icon-present-fill', 'icon-present',
                        'icon-play-history-fill', 'icon-play-history', 'icon-mike-fill', 'icon-mike', 'icon-message-fill', 'icon-message',
                        'icon-member-user', 'icon-member-staff', 'icon-member-manage', 'icon-member-fill', 'icon-member', 'icon-masonry-fill',
                        'icon-masonry', 'icon-marketing', 'icon-map-marker-fill', 'icon-map-marker', 'icon-maintain', 'icon-maintain',
                        'icon-mailbox-fill', 'icon-mailbox', 'icon-luckdraw', 'icon-loupe-fill', 'icon-loupe', 'icon-logo-weibo', 'icon-logo-wechat',
                        'icon-logo-qq', 'icon-logo-mp', 'icon-logo-alipay', 'icon-lock-fill', 'icon-lock', 'icon-location-fill', 'icon-location',
                        'icon-integral', 'icon-image-fill', 'icon-image', 'icon-horn-fill', 'icon-horn', 'icon-home-hollow', 'icon-home-fill', 'icon-history-fill',
                        'icon-history', 'icon-help-sigh', 'icon-help-lack', 'icon-help-fill', 'icon-gift-bag-fill', 'icon-gift-bag', 'icon-function-fill',
                        'icon-function-case', 'icon-function-apply', 'icon-function', 'icon-fabulous-fill', 'icon-fabulous', 'icon-eye-fill', 'icon-evaluate-fill',
                        'icon-evaluate', 'icon-download-folder', 'icon-download-cloud-fill', 'icon-download-cloud', 'icon-download-batch', 'icon-download-arrow',
                        'icon-delete-fill', 'icon-delete', 'icon-customer-service-fill', 'icon-customer-service', 'icon-clean-fill', 'icon-clean', 'icon-chart-fill',
                        'icon-chart', 'icon-camera-fill', 'icon-camera', 'icon-btn-play', 'icon-btn-pause', 'icon-btn-cut', 'icon-btn-cut', 'icon-btn-cut',
                        'icon-btn-add', 'icon-beauty-fill', 'icon-beauty', 'icon-beauty', 'icon-bargain', 'icon-badge-fill', 'icon-badge', 'icon-audio-quiet',
                        'icon-audio-go',

                        'layui-icon-heart-fill', 'layui-icon-heart', 'layui-icon-light', 'layui-icon-time', 'layui-icon-bluetooth', 'layui-icon-at',
                        'layui-icon-mute', 'layui-icon-mike', 'layui-icon-key', 'layui-icon-gift', 'layui-icon-email', 'layui-icon-rss',
                        'layui-icon-wifi', 'layui-icon-logout', 'layui-icon-android', 'layui-icon-ios', 'layui-icon-windows', 'layui-icon-transfer',
                        'layui-icon-service', 'layui-icon-subtraction', 'layui-icon-addition', 'layui-icon-slider', 'layui-icon-print', 'layui-icon-export',
                        'layui-icon-cols', 'layui-icon-screen-restore', 'layui-icon-screen-full', 'layui-icon-rate-half', 'layui-icon-rate', 'layui-icon-rate-solid',
                        'layui-icon-cellphone', 'layui-icon-vercode', 'layui-icon-login-wechat', 'layui-icon-login-qq', 'layui-icon-login-weibo', 'layui-icon-password',
                        'layui-icon-username', 'layui-icon-refresh-3', 'layui-icon-auz', 'layui-icon-spread-left', 'layui-icon-shrink-right', 'layui-icon-snowflake',
                        'layui-icon-tips', 'layui-icon-note', 'layui-icon-home', 'layui-icon-senior', 'layui-icon-refresh', 'layui-icon-refresh-1',
                        'layui-icon-flag', 'layui-icon-theme', 'layui-icon-notice', 'layui-icon-website', 'layui-icon-console', 'layui-icon-face-surprised',
                        'layui-icon-set', 'layui-icon-template-1', 'layui-icon-app', 'layui-icon-template', 'layui-icon-praise', 'layui-icon-tread',
                        'layui-icon-male', 'layui-icon-female', 'layui-icon-camera', 'layui-icon-camera-fill', 'layui-icon-more', 'layui-icon-more-vertical',
                        'layui-icon-rmb', 'layui-icon-dollar', 'layui-icon-diamond', 'layui-icon-fire', 'layui-icon-return', 'layui-icon-location',
                        'layui-icon-read', 'layui-icon-survey', 'layui-icon-face-smile', 'layui-icon-face-cry', 'layui-icon-cart-simple', 'layui-icon-cart',
                        'layui-icon-next', 'layui-icon-prev', 'layui-icon-upload-drag', 'layui-icon-upload', 'layui-icon-download-circle', 'layui-icon-component',
                        'layui-icon-file-b', 'layui-icon-user', 'layui-icon-find-fill', 'layui-icon-add-1', 'layui-icon-play', 'layui-icon-pause', 'layui-icon-headset',
                        'layui-icon-video', 'layui-icon-voice', 'layui-icon-speaker', 'layui-icon-fonts-del', 'layui-icon-fonts-code', 'layui-icon-unlink',
                        'layui-icon-picture', 'layui-icon-link', 'layui-icon-tabs', 'layui-icon-radio', 'layui-icon-circle', 'layui-icon-edit', 'layui-icon-share',
                        'layui-icon-delete', 'layui-icon-form', 'layui-icon-cellphone-fine', 'layui-icon-dialogue', 'layui-icon-fonts-clearv', 'layui-icon-layer',
                        'layui-icon-date', 'layui-icon-water', 'layui-icon-code-circle', 'layui-icon-carousel', 'layui-icon-prev-circle', 'layui-icon-layouts',
                        'layui-icon-util', 'layui-icon-templeate-1', 'layui-icon-upload-circle', 'layui-icon-tree', 'layui-icon-table', 'layui-icon-chart',
                        'layui-icon-chart-screen', 'layui-icon-engine', 'layui-icon-file', 'layui-icon-set-sm', 'layui-icon-reduce-circle', 'layui-icon-add-circle',
                        'layui-icon-404', 'layui-icon-about', 'layui-icon-search', 'layui-icon-set-fill', 'layui-icon-group', 'layui-icon-friends',
                        'layui-icon-reply-fill', 'layui-icon-menu-fill', 'layui-icon-log', 'layui-icon-picture-fine', 'layui-icon-face-smile-fine',
                        'layui-icon-list', 'layui-icon-release', 'layui-icon-ok', 'layui-icon-help', 'layui-icon-chat', 'layui-icon-top', 'layui-icon-star',
                        'layui-icon-star-fill', 'layui-icon-close-fill', 'layui-icon-close', 'layui-icon-ok-circle', 'layui-icon-add-circle-fine'
                    ];
                }
            }
        };

        a.init();
        return new IconPicker();
    };

    /**
     * 选中图标
     */
    IconPicker.prototype.checkIcon = function (filter, iconName){
        let p = $('*[lay-filter='+ filter +']').next().find('.layui-iconPicker-item .layui-icon');
            p.html('').attr('class', iconName);
    };

    layui.link(layui.cache.base + 'iconPicker/iconPicker.css');
    exports(_MOD, new IconPicker());
});