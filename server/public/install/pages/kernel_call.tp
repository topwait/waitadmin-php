layui.use(['jquery', 'element'], function() {
    let $ = layui.jquery;                      // 加载JQuery
    let element = layui.element;               // 加载Element
    let $mainBodyNode    = $('body')           // 主体应用节点
    let $waitAppNode     = $('#app');          // 主应用的节点
    let $waitTabsNode    = $('.wait-tabs');    // 选项卡的节点
    let $waitBodyNode    = $('.wait-body');    // 主体内容节点
    let $waitMaskNode    = $('.wait-mask');    // 辅助遮罩节点
    let $waitLoadNode    = $('.wait-load');    // 加载动画节点
    let $waitHeaderNode  = $('.wait-header');  // 头部内容节点
    let $waitSidebarNode = $('.wait-sidebar'); // 侧边内容节点

    /** 操作总事件 **/
    let events = {
        // 获取当前标签标识
        tabsPage: { id: 0, index: 0 }
        // 页面动画加载效果
        ,loading: function() {
            let load = '<div class="wait-loading">' +
                '<div class="ball-loader">' +
                    '<span></span><span></span>' +
                    '<span></span><span></span>' +
                '</div>' +
            '</div>';

            $iframe = $waitBodyNode.find('.tab-body-item.layui-show iframe');
            if (!$iframe.next().length) {
                $iframe.parent().append(load);
                let loadNode = $iframe.parent().find('.wait-loading');
                $iframe.on('load', function() {
                    let theme = waitCache.getItem('theme');
                    let $html = $($iframe[0].contentWindow.document).find('html');
                    let $body = $($iframe[0].contentWindow.document).find('body');
                    $html.removeAttr('style');
                    $body.attr('data-theme', theme);

                    loadNode.fadeOut(1000, function() {
                        loadNode.remove();
                    });
                })
            }
        }
        // 记忆标签页面数据
        ,tabMemory: function () {
            if (waitCache.getItem('isTabMemory')) {
                setTimeout(function(){
                    let tabs = [];
                    $waitTabsNode.find('.layui-tab-title li').each(function (i) {
                        if (i > 0) {
                            let layId   = $(this).attr('lay-id');
                            let layAttr = $(this).attr('lay-attr');
                            let layText = $(this).children('span').html();
                            let layThis = $(this).hasClass('layui-this');
                            tabs.push({'id':layId, 'attr':layAttr, 'text':layText, 'this':layThis});
                        }
                    });

                    let data = {
                        'tabs': tabs,
                        'body': $waitBodyNode.html()
                    }
                    sessionStorage.setItem('tabMenus', JSON.stringify(data));
                }, 10);
            }
        }
        // 获取标签主体元素
        ,tabsBody: function (index) {
            return $waitBodyNode.find('.tab-body-item').eq(index || 0);
        }
        // 打开当前页面标签
        ,tabsOpen: function (id, url, text) {
            // 循环查找是否已存在标签栏中
            let matchTo;
            let tabs = $($waitTabsNode).find('ul.layui-tab-title > li');
            tabs.each(function () {
                let layId   = $(this).attr('lay-id');
                let layAttr = $(this).attr('lay-attr');
                if (layAttr === url && layId === id) {
                    matchTo = true;
                }
            });

            // 如果页面标签不存在则新创建
            if (!matchTo) {
                setTimeout(function () {
                    $($waitBodyNode).find('.tab-body-item').removeClass('layui-show');
                    $($waitBodyNode).append([
                        '<div lay-id="'+ id +'" class="tab-body-item layui-show">',
                        '<iframe src="'+ url +'"></iframe>',
                        '</div>'
                    ].join(''));
                    events.loading();
                }, 10);

                element.tabAdd('tab-body-filter', {
                    id: id
                    ,attr: url
                    ,title: '<span>' + (text || '新打开标签') + '</span>'
                });
            } else {
                // 页面已存在直接切换到页面就行
                $($waitBodyNode).find('.tab-body-item').removeClass('layui-show');
                $($waitBodyNode).find('.tab-body-item[lay-id='+id+']').addClass('layui-show');
                // 标签页面切换刷新加载过场动画
                if (waitCache.getItem('isTabRefresh')) {
                    events.loading();
                    let iframe = $waitBodyNode.find('.tab-body-item.layui-show iframe');
                    iframe.attr('src', iframe.attr('src'));
                }
            }

            // 定位到当前切换的选项卡
            element.tabChange('tab-body-filter', id);

            // 标签记忆数据保存
            events.tabMemory();
        }
        // 左右滚动页面标签
        ,rollPage: function (type, index) {
            let tabsHeader = $('.wait-tabs .layui-tab-title');
            let liItem = tabsHeader.children('li');
            let outerWidth = tabsHeader.outerWidth();
            let tabsLeft = parseFloat(tabsHeader.css('left'));

            if (type === 'left') {
                if (!tabsLeft && tabsLeft <= 0) return;
                let prefLeft = -tabsLeft - outerWidth;
                liItem.each(function (index, item) {
                    let li = $(item), left = li.position().left;
                    if (left >= prefLeft) {
                        tabsHeader.css('left', -left);
                        return false;
                    }
                });
            } else if (type === 'auto') {
                (function () {
                    let thisLi = liItem.eq(index), thisLeft;
                    if (!thisLi[0]) return;
                    thisLeft = thisLi.position().left;
                    if (thisLeft < -tabsLeft) {
                        return tabsHeader.css('left', -thisLeft);
                    }
                    if (thisLeft + thisLi.outerWidth() >= outerWidth - tabsLeft) {
                        let subLeft = thisLeft + thisLi.outerWidth() - (outerWidth - tabsLeft);
                        liItem.each(function (i, item) {
                            let li = $(item), left = li.position().left;
                            if (left + tabsLeft > 0) {
                                if (left - tabsLeft > subLeft) {
                                    tabsHeader.css('left', -left);
                                    return false;
                                }
                            }
                        });
                    }
                }());
            } else {
                liItem.each(function (i, item) {
                    let li = $(item), left = li.position().left;
                    if (left + li.outerWidth() >= outerWidth - tabsLeft) {
                        tabsHeader.css('left', -left);
                        return false;
                    }
                });
            }
        }
        // 向右滚动页面标签
        ,leftPage: function () {
            events.rollPage('left');
        }
        // 向左滚动页面标签
        ,rightPage: function () {
            events.rollPage();
        }
        // 关闭全部的标签页
        ,closeAllTabs: function () {
            events.closeOtherTabs('all');
        }
        // 关闭当前的标签页
        ,closeThisTabs: function () {
            let index = events.tabsPage.index;
            if (index) {
                $waitTabsNode.find('.layui-tab-title > li').eq(index).remove();
                events.tabsBody(index).remove();

                let nodes = $waitTabsNode.find('.layui-tab-title > li');
                let i = nodes.length-1 >= index ? index : index - 1;
                nodes.eq(i).trigger('click');
                events.rollPage('auto', i);
            }
        }
        // 关闭其它的标签页
        ,closeOtherTabs: function (type) {
            events.rollPage('auto', 0);
            if (type === 'all') {
                $waitBodyNode.find('.tab-body-item:gt(0)').remove();
                $waitTabsNode.find('.layui-tab-title > li:gt(0)').remove();
                $waitTabsNode.find('.layui-tab-title li').eq(0).trigger('click');
            } else {
                $waitTabsNode.find('.layui-tab-title li').each(function (index, item) {
                    if (index && index != events.tabsPage.index) {
                        $(item).remove();
                        events.tabsBody(index).remove();
                    }
                });
            }
        }
    };

    /** 初始化事件 **/
    $(function(){
        // 初始菜单
        let firstMenuNode = $('.wait-sidebar .wait-menu-item');
        if (firstMenuNode.eq(0).length > 0) {
            if (firstMenuNode.eq(0).children('dl.wait-second-menu').length > 0) {
                firstMenuNode.eq(0).children('dl.wait-second-menu').show();
                $waitBodyNode.css({'left': '240px'});
            }
        }

        // 初始主题
        let theme = waitCache.getItem('theme');
        if (theme && theme != waitConfig.theme) {
            $mainBodyNode.attr('data-theme', theme);
        }

        // 隐藏标签
        if (waitCache.getItem('isTabHidden')) {
            $mainBodyNode.attr('data-tab', true);
        } else {
            // 记忆标签
            let tabMenus = sessionStorage.getItem('tabMenus');
            if (waitCache.getItem('isTabMemory') && tabMenus) {
                let data = JSON.parse(tabMenus);
                $waitBodyNode.html(data['body']);

                data['tabs'].forEach(function (item, index) {
                    element.tabAdd('tab-body-filter', {
                        id: item['id']
                        ,attr: item['attr']
                        ,title: '<span>'+item['text']+'</span>'
                    });

                    if (item['this']) {
                        events.tabsPage.id = item['id'];
                        events.tabsPage.index = index+1;
                    }
                });

               $waitTabsNode.find('.layui-tab-title li')
                    .eq(events.tabsPage.index).trigger('click');

                $waitBodyNode.find('.tab-body-item iframe').each(function () {
                    $iframe = $(this);
                    let loadNode = $iframe.parent().find('.wait-loading');
                    $iframe.on('load', function() {
                        if (loadNode.length) {
                            loadNode.fadeOut(1000, function () {
                                loadNode.remove();
                            });
                        }
                    });
                });
            }
        }

        // 加载完成
        setTimeout(function() {
            // 子窗主题
            let iframe = $waitBodyNode.find('.tab-body-item iframe');
            for (let i=0; i<iframe.length; i++) {
                let $body = $(iframe[i].contentWindow.document).find('body');
                $body.attr('data-theme', theme);
            }
            // 移除动画
            $waitLoadNode.fadeOut(1000, function() {
                $waitLoadNode.remove();
            });
        }, 1000);
    });

    /** 主级菜单事件 **/
    $waitSidebarNode.on('click', '.wait-menu-item',  function () {
        let id;
        let url
        let text;
        if (!$(this).children('a').hasClass('active')) {
            // 切换菜单
            $(this).siblings().find('a').removeClass('active');
            $(this).children('a').addClass('active');
            // 子级菜单
            $(this).siblings().find('.wait-second-menu').removeClass('activate');
            $(this).siblings().find('.wait-second-menu').removeAttr('style');
            if ($(this).children('.wait-second-menu').length) {
                // 预载节点
                let Node        = $(this).children('.wait-second-menu').find('a');
                let subMenuNode = $(this).children('.wait-second-menu');
                let shrinkNode  = $('.wait-side-shrink').length;
                // 显子菜单
                subMenuNode.addClass('activate');
                if ($(window).width() < 768 || layui.device().mobile) {
                    subMenuNode.css('display', 'block');
                }
                Node.eq(0).children('i').length ? Node.eq(1).addClass('active') : Node.eq(0).addClass('active');
                id   = Node.eq(0).children('i').length ? Node.eq(1).attr('lay-id') : Node.eq(0).attr('lay-id');
                url  = Node.eq(0).children('i').length ? Node.eq(1).attr('lay-attr') : Node.eq(0).attr('lay-attr');
                text = Node.eq(0).children('i').length ? Node.eq(1).text() : Node.eq(0).text();
                // 图标菜单
                shrinkNode ? $waitBodyNode.css({'left': '180px'}) : $waitBodyNode.css({'left': '240px'});
            } else {
                id   = $(this).children('a').attr('lay-id');
                url  = $(this).children('a').attr('lay-attr');
                text = $(this).children('a').children('cite').text();
                $('.wait-side-shrink').length ? $waitBodyNode.css({'left': '60px'}) : $waitBodyNode.removeAttr('style');
            }

            // 打开标签
            events.tabsOpen(id, url, text);
            return true;
        }
    });

    /** 子级菜单事件 **/
    $waitSidebarNode.on('click', '.wait-second-menu dd a', function () {
        if ($(this).attr('href')) {
            if ($(this).children('i').hasClass('layui-icon-triangle-d')) {
                $(this).children('i').removeClass('layui-icon-triangle-d');
                $(this).children('i').addClass('layui-icon-triangle-r');
                $(this).next().stop().slideUp();
            } else {
                $(this).children('i').removeClass('layui-icon-triangle-r');
                $(this).children('i').addClass('layui-icon-triangle-d');
                $(this).next().stop().slideDown();
            }
        } else {
            $('.wait-sidebar .wait-second-menu dd a').removeClass('active');
            $(this).addClass('active');
            id   = $(this).attr('lay-id');
            url  = $(this).attr('lay-attr');
            text = $(this).text();
            events.tabsOpen(id, url, text);
        }
    });

    /** 图标菜单事件 **/
    $waitHeaderNode.on('click', '.stretch i', function () {
        // 获取节点
        let shrinkNode  = $('.wait-side-shrink');
        let submenuNode = $('.wait-sidebar .wait-menu-item > a.active').next();

        if ($(window).width() < 768 || layui.device().mobile) {
            // 小屏幕或手机端菜单
            if ($(this).hasClass('layui-icon-shrink-right')) {
                $(this).removeClass('layui-icon-shrink-right');
                $(this).addClass('layui-icon-spread-left');
                $waitMaskNode.addClass('activate');
                $waitSidebarNode.addClass('develop-sidebar');
                $waitHeaderNode.children('.layui-layout-left').addClass('develop-sidebar');
                if (submenuNode.length) {
                    submenuNode.addClass('activate');
                    submenuNode.css('display', 'block');
                }
            }
            else {
                $(this).removeClass('layui-icon-spread-left');
                $(this).addClass('layui-icon-shrink-right');
                $waitMaskNode.removeClass('activate');
                $waitSidebarNode.removeClass('develop-sidebar');
                $waitHeaderNode.children('.layui-layout-left').removeClass('develop-sidebar');
                $('.wait-sidebar .wait-menu-item .wait-second-menu').removeClass('activate');
            }
        } else {
            // 判断伸缩小图标菜单
            if (!shrinkNode.length) {
                $waitAppNode.addClass('wait-side-shrink');
                $(this).removeClass('layui-icon-shrink-right');
                $(this).addClass('layui-icon-spread-left');
                submenuNode.length ? $waitBodyNode.css({'left': '180px'}) : $waitBodyNode.css({'left': '60px'});
            } else {
                $waitAppNode.removeAttr('class');
                $(this).removeClass('layui-icon-spread-left');
                $(this).addClass('layui-icon-shrink-right');
                submenuNode.length ? $waitBodyNode.css({'left': '240px'}) : $waitBodyNode.css({'left': '120px'});
            }
        }
    });

    /** 关闭遮罩事件 **/
    $waitMaskNode.on('click', '', function () {
        $('.wait-header a.stretch i').removeClass('layui-icon-spread-left');
        $('.wait-header .stretch i').addClass('layui-icon-shrink-right');
        $('.wait-sidebar').removeClass('develop-sidebar');
        $('.wait-header .layui-layout-left').removeClass('develop-sidebar');
        if ($(window).width() < 770 || layui.device().mobile) {
            if ($('.wait-sidebar .wait-menu-item a.active').next().length) {
                $('.wait-sidebar .wait-menu-item .wait-second-menu').removeClass('activate');
                $('.wait-sidebar .wait-menu-item .active').next().removeAttr('style');
            }
        }
        $(this).removeClass('activate');
    });

    /** 刷新当前页面 **/
    $waitHeaderNode.on('click', '.refresh', function () {
        events.loading();
        let $iframe = $('.wait-body .tab-body-item.layui-show iframe')
        $iframe.attr('src', $iframe.attr('src'));
    });

    /** 呼出系统配置 **/
    $waitHeaderNode.on('click', '.about', function () {
        let enter = '/' + window.location.pathname.split('/')[1];
        layer.open({
			type: 2
            ,title: '主题配置'
            ,shadeClose: true
            ,closeBtn: 0
            ,skin: 'layui-anim layui-anim-right layui-layer-right'
            ,area: ['300px', '100%']
            ,offset: 'r'
            ,anim: -1
            ,content: enter + '/index/setting'
		});
    });

    /** 按钮全屏退出 **/
    $waitHeaderNode.on('click', '.fullscreen', function () {
        let docElm = document.documentElement;
        if ($(this).children('i').hasClass('layui-icon-screen-restore')) {
            document.exitFullscreen();
            $(this).children('i').eq(0).removeClass('layui-icon-screen-restore');
        } else {
            docElm.requestFullscreen();
            $(this).children('i').eq(0).addClass('layui-icon-screen-restore');
        }
    });

    /** 键盘全屏退出 **/
    $waitHeaderNode.on('keydown', '', function (event) {
        event = event || window.event || arguments.callee.caller.arguments[0];
        // 按Esc
        if (event && event.keyCode === 27) {
            $('.fullscreen').children('i').eq(0).removeClass('layui-icon-screen-restore');
        }
        // 按F11
        if (event && event.keyCode === 122) {
            $('.fullscreen').children('i').eq(0).addClass('layui-icon-screen-restore');
        }
    });

    /** 监听页面改变 **/
    $(window).resize(function() {
        if ($(window).width() > 770 || !layui.device().mobile) {
            $('.wait-sidebar .wait-menu-item .wait-second-menu').removeAttr('style');
            if ($('.wait-sidebar .wait-menu-item a.active').next()) {
                $('.wait-sidebar .wait-menu-item .active').next().addClass('activate');
            }
        }
        if ($(window).width() <= 768 || layui.device().mobile) {
            if ($waitSidebarNode.hasClass('develop-sidebar')) {
                $waitSidebarNode.removeClass('develop-sidebar');
            }
            if ($waitHeaderNode.children('.layui-layout-left').hasClass('develop-sidebar')) {
                $waitHeaderNode.children('.layui-layout-left').removeClass('develop-sidebar');
            }
        }
        return true;
    });

    /** 监听点击事件 **/
    $(document).on('click', '*[lay-event]', function () {
        let that = $(this);
        let attrEvent = that.attr('lay-event');
        events[attrEvent] && events[attrEvent].call(this, that);
    });

    /** 标签切换事件 **/
    $waitTabsNode.on('click', '.layui-tab-title > li', function () {
        // 取出点击标签信息
        let shrinkNode  = $('.wait-side-shrink');
        let id = $(this).attr('lay-id');

        // 当前页面则不处理
        if (id === events.tabsPage.id && $(this).hasClass('layui-this')) {
            return true;
        }

        // 切到指定一级菜单
        let elem = $('.wait-sidebar .wait-menu-item a[lay-id='+id+']');
        elem.parents('li').siblings().find('a').removeClass('active');
        elem.parents('li').children('a').addClass('active');

        // 是否存在二级菜单
        if (elem.parents('li').has('dl').length) {
            $('.wait-sidebar .wait-menu-item dl').removeClass('activate');
            elem.parents('li').children('dl').addClass('activate');
            elem.parents('li').children('dl').find('a').removeClass('active');
            elem.parents('li').children('dl').find('a[lay-id='+id+']').addClass('active');
            shrinkNode.length ? $waitBodyNode.css({'left': '180px'}) : $waitBodyNode.css({'left': '240px'});
        } else {
            $('.wait-sidebar .wait-menu-item > dl').removeClass('activate');
            shrinkNode.length ? $waitBodyNode.css({'left': '60px'}) : $waitBodyNode.css({'left': '120px'});
        }

        // 存在页面直接切换
        $waitBodyNode.find('.tab-body-item').removeClass('layui-show');
        $waitBodyNode.find('.tab-body-item[lay-id='+id+']').addClass('layui-show');

        // 标签页面切换刷新
        if (waitCache.getItem('isTabRefresh')) {
            events.loading();
            let iframe = $waitBodyNode.find('.tab-body-item.layui-show iframe');
            iframe.attr('src', iframe.attr('src'));
        }
    });

    /** 标签选择事件 **/
    element.on('tab(tab-body-filter)', function (data) {
        // 记录切换后的下标
        events.tabsPage.index = data.index;
        events.rollPage('auto', data.index);

        // 标签记忆数据保存
        events.tabMemory();
    });

    /** 标签删除事件 **/
    element.on('tabDelete(tab-body-filter)', function (obj) {
        // 移除当前标签状态
        obj.index && events.tabsBody(obj.index).remove();
        events.tabsBody(obj.index).addClass('layui-show');
        events.tabsBody(obj.index).siblings().removeClass('layui-show');

        // 切换到附近的标签
        let nodes = $waitTabsNode.find('.layui-tab-title > li');
        let index = nodes.length-1 >= obj.index ? obj.index : obj.index - 1;
        nodes.eq(index).trigger('click');
        events.rollPage('auto', index);

        // 记录当前标签下标
        events.tabsPage.id = $(nodes.eq(index)).attr('lay-id');
        events.tabsPage.index = index;

        // 标签记忆数据保存
        events.tabMemory();
    });

});
