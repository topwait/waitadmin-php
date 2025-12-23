// +----------------------------------------------------------------------
// | 图标选择器
// +----------------------------------------------------------------------

layui.define(['laypage', 'form'], function (exports) {
    'use strict';

    let IconPicker = function () { this.v = '0.2.beta'; };
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
            limit = opts.limit == null ? 24 : opts.limit,
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
        console.log(limit)
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
                    const layIcon = [
                        'layui-icon-circle-dot',
                        'layui-icon-bot', 'layui-icon-leaf', 'layui-icon-folder', 'layui-icon-folder-open',
                        'layui-icon-gitee', 'layui-icon-github', 'layui-icon-light', 'layui-icon-moon',
                        'layui-icon-error', 'layui-icon-success', 'layui-icon-question', 'layui-icon-lock',
                        'layui-icon-eye', 'layui-icon-eye-invisible', 'layui-icon-clear', 'layui-icon-backspace',
                        'layui-icon-disabled', 'layui-icon-tips-fill', 'layui-icon-test', 'layui-icon-music',
                        'layui-icon-chrome', 'layui-icon-firefox', 'layui-icon-edge', 'layui-icon-ie',
                        'layui-icon-heart-fill', 'layui-icon-heart', 'layui-icon-time', 'layui-icon-at',
                        'layui-icon-email', 'layui-icon-rss', 'layui-icon-sound', 'layui-icon-mute',
                        'layui-icon-mike', 'layui-icon-key', 'layui-icon-gift', 'layui-icon-bluetooth',
                        'layui-icon-wifi', 'layui-icon-logout', 'layui-icon-android', 'layui-icon-ios',
                        'layui-icon-windows', 'layui-icon-transfer', 'layui-icon-service', 'layui-icon-subtraction',
                        'layui-icon-addition', 'layui-icon-slider', 'layui-icon-print', 'layui-icon-export',
                        'layui-icon-cols', 'layui-icon-screen-restore', 'layui-icon-screen-full',
                        'layui-icon-rate-half', 'layui-icon-rate', 'layui-icon-rate-solid', 'layui-icon-cellphone',
                        'layui-icon-vercode', 'layui-icon-login-wechat', 'layui-icon-login-qq',
                        'layui-icon-login-weibo', 'layui-icon-password', 'layui-icon-username', 'layui-icon-refresh-3',
                        'layui-icon-auz', 'layui-icon-spread-left', 'layui-icon-shrink-right',
                        'layui-icon-snowflake', 'layui-icon-tips', 'layui-icon-note', 'layui-icon-home',
                        'layui-icon-senior', 'layui-icon-refresh', 'layui-icon-refresh-1', 'layui-icon-flag',
                        'layui-icon-theme', 'layui-icon-notice', 'layui-icon-website', 'layui-icon-console',
                        'layui-icon-face-surprised', 'layui-icon-set', 'layui-icon-template-1', 'layui-icon-app',
                        'layui-icon-template', 'layui-icon-praise', 'layui-icon-tread', 'layui-icon-male',
                        'layui-icon-female', 'layui-icon-camera', 'layui-icon-camera-fill', 'layui-icon-more',
                        'layui-icon-more-vertical', 'layui-icon-rmb', 'layui-icon-dollar',
                        'layui-icon-diamond', 'layui-icon-fire', 'layui-icon-return', 'layui-icon-location',
                        'layui-icon-read', 'layui-icon-survey', 'layui-icon-face-smile', 'layui-icon-face-cry',
                        'layui-icon-cart-simple', 'layui-icon-cart', 'layui-icon-next', 'layui-icon-prev',
                        'layui-icon-upload-drag', 'layui-icon-upload', 'layui-icon-download-circle',
                        'layui-icon-component', 'layui-icon-file-b', 'layui-icon-user', 'layui-icon-find-fill',
                        'layui-icon-loading', 'layui-icon-loading-1', 'layui-icon-add-1',
                        'layui-icon-play', 'layui-icon-pause', 'layui-icon-headset', 'layui-icon-video',
                        'layui-icon-voice', 'layui-icon-speaker', 'layui-icon-fonts-del', 'layui-icon-fonts-code',
                        'layui-icon-fonts-html', 'layui-icon-fonts-strong', 'layui-icon-unlink', 'layui-icon-picture',
                        'layui-icon-link', 'layui-icon-face-smile-b', 'layui-icon-align-left', 'layui-icon-align-right',
                        'layui-icon-align-center', 'layui-icon-fonts-u', 'layui-icon-fonts-i', 'layui-icon-tabs',
                        'layui-icon-radio', 'layui-icon-circle', 'layui-icon-edit',
                        'layui-icon-share', 'layui-icon-delete', 'layui-icon-form', 'layui-icon-cellphone-fine',
                        'layui-icon-dialogue', 'layui-icon-fonts-clear', 'layui-icon-layer', 'layui-icon-date',
                        'layui-icon-water', 'layui-icon-code-circle', 'layui-icon-carousel', 'layui-icon-prev-circle',
                        'layui-icon-layouts', 'layui-icon-util', 'layui-icon-templeate-1', 'layui-icon-upload-circle',
                        'layui-icon-tree', 'layui-icon-table', 'layui-icon-chart', 'layui-icon-chart-screen',
                        'layui-icon-engine', 'layui-icon-triangle-d', 'layui-icon-triangle-r', 'layui-icon-file',
                        'layui-icon-set-sm', 'layui-icon-reduce-circle', 'layui-icon-add-circle', 'layui-icon-404',
                        'layui-icon-about', 'layui-icon-up', 'layui-icon-down', 'layui-icon-left', 'layui-icon-right',
                        'layui-icon-search', 'layui-icon-set-fill', 'layui-icon-group',
                        'layui-icon-friends', 'layui-icon-reply-fill', 'layui-icon-menu-fill', 'layui-icon-log',
                        'layui-icon-picture-fine', 'layui-icon-face-smile-fine', 'layui-icon-list',
                        'layui-icon-release', 'layui-icon-ok', 'layui-icon-help', 'layui-icon-chat', 'layui-icon-top',
                        'layui-icon-star', 'layui-icon-star-fill', 'layui-icon-close-fill', 'layui-icon-close',
                        'layui-icon-ok-circle', 'layui-icon-add-circle-fine'
                    ];

                    const customIcon = [
                        'icon-helping', 'icon-run', 'icon-robot', 'icon-deposit',
                        'icon-refund', 'icon-lottery', 'icon-box', 'icon-marketing',
                        'icon-integral', 'icon-invoice', 'icon-voucher', 'icon-clock-in',
                        'icon-be-payment', 'icon-be-received', 'icon-be-shipped', 'icon-classify',
                        'icon-menu', 'icon-apply', 'icon-sort', 'icon-remove',
                        'icon-disable', 'icon-forbid-view', 'icon-allow-view', 'icon-enter',
                        'icon-leave', 'icon-retrieve', 'icon-mix', 'icon-semantics',
                        'icon-terminal', 'icon-code', 'icon-detail', 'icon-copy',
                        'icon-drag', 'icon-server', 'icon-equalizer', 'icon-maintain',
                        'icon-fingerprint', 'icon-safety', 'icon-shutdown', 'icon-font',
                        'icon-character', 'icon-row', 'icon-col', 'icon-modify',
                        'icon-editor', 'icon-edit', 'icon-true', 'icon-correct',
                        'icon-append', 'icon-add', 'icon-suspend', 'icon-stop',
                        'icon-wrong', 'icon-btn-cut', 'icon-btn-add', 'icon-douyin',
                        'icon-weibo', 'icon-alipay', 'icon-qq', 'icon-wechat',
                        'icon-logo-account', 'icon-logo-phone', 'icon-logo-google', 'icon-logo-douyin',
                        'icon-logo-gitee', 'icon-logo-github', 'icon-logo-mp', 'icon-logo-alipay',
                        'icon-logo-qq', 'icon-logo-wechat', 'icon-download-cloud', 'icon-download-cloud-fill',
                        'icon-download-folder', 'icon-download-batch', 'icon-download-arrow', 'icon-unlock',
                        'icon-unlocking', 'icon-locking', 'icon-lockout', 'icon-lock',
                        'icon-lock-fill', 'icon-window-close', 'icon-window-max', 'icon-window-min',
                        'icon-screen-enlarge', 'icon-screen-narrow', 'icon-screen-large', 'icon-screen-small',
                        'icon-arrow-down', 'icon-arrow-up', 'icon-arrow-right', 'icon-arrow-left',
                        'icon-notepad', 'icon-notepad-fill', 'icon-backlog', 'icon-backlog-fill',
                        'icon-order', 'icon-order-fill', 'icon-text', 'icon-text-fill',
                        'icon-report', 'icon-report-fill', 'icon-catalogue', 'icon-catalogue-fill',
                        'icon-folder', 'icon-folder-fill', 'icon-homed', 'icon-homed-fill',
                        'icon-family', 'icon-family-fill', 'icon-seckill', 'icon-seckill-fill',
                        'icon-shopping-cart', 'icon-shopping-cart-fill', 'icon-store', 'icon-store-fill',
                        'icon-bargain', 'icon-bargain-fill', 'icon-present', 'icon-present-fill',
                        'icon-purse', 'icon-purse-fill', 'icon-function', 'icon-function-fill',
                        'icon-chart', 'icon-chart-fill', 'icon-masonry', 'icon-masonry-fill',
                        'icon-badge', 'icon-badge-fill', 'icon-evaluate', 'icon-evaluate-fill',
                        'icon-message', 'icon-message-fill', 'icon-chats', 'icon-chats-fill',
                        'icon-play-history', 'icon-play-history-fill', 'icon-wait-history', 'icon-wait-history-fill',
                        'icon-flash', 'icon-flash-fill', 'icon-trample', 'icon-trample-fill',
                        'icon-fabulous', 'icon-fabulous-fill', 'icon-look', 'icon-look-fill',
                        'icon-forward', 'icon-forward-fill', 'icon-sharing', 'icon-sharing-fill',
                        'icon-shared', 'icon-shared-fill', 'icon-speaker-close', 'icon-speaker-open',
                        'icon-horn', 'icon-horn-fill', 'icon-mike', 'icon-mike-fill',
                        'icon-camera', 'icon-camera-fill', 'icon-reverse-lens', 'icon-reverse-lens-fill',
                        'icon-tips', 'icon-tips-fill', 'icon-doubt', 'icon-doubt-fill',
                        'icon-playing', 'icon-playing-fill', 'icon-images', 'icon-images-fill',
                        'icon-image', 'icon-image-fill', 'icon-phone', 'icon-phone-fill',
                        'icon-telephony', 'icon-telephony-fill', 'icon-telephone', 'icon-telephone-fill',
                        'icon-mail', 'icon-mail-fill', 'icon-loupe', 'icon-find-fill',
                        'icon-sex-girl', 'icon-sex-girl-fill', 'icon-sex-male', 'icon-sex-male-fill',
                        'icon-positioning', 'icon-positioning-fill', 'icon-locate', 'icon-locate-fill',
                        'icon-position', 'icon-position-fill', 'icon-broom', 'icon-broom-fill',
                        'icon-clear', 'icon-clear-fill', 'icon-del', 'icon-del-fill',
                        'icon-setting', 'icon-setting-fill', 'icon-setup', 'icon-setup-fill',
                        'icon-specialist', 'icon-specialist-fill', 'icon-customer-serv', 'icon-customer-serv-fill',
                        'icon-team', 'icon-team-fill', 'icon-crowd', 'icon-crowd-fill',
                        'icon-administrator', 'icon-administrator-fill', 'icon-admin-unlock', 'icon-admin-lock-fill',
                        'icon-admin-setting', 'icon-admin-setting-fill', 'icon-people', 'icon-people-fill',
                        'icon-friend', 'icon-friend-fill', 'icon-users', 'icon-users-fill',
                        'icon-member', 'icon-member-fill'
                    ];

                    return [...layIcon, ...customIcon];
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