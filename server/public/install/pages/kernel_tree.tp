layui.use(['jquery', 'element'], function() {
    let $ = layui.jquery;                       // 加载JQuery
    let element = layui.element;                // 加载Element
    let $mainBodyNode    = $('body')            // 主体应用节点
    let $waitAppNode     = $('#app');           // 主应用的节点
    let $waitTabsNode    = $('.wait-tabs');     // 选项卡的节点
    let $waitBodyNode    = $('.wait-body');     // 主体内容节点
    let $waitMaskNode    = $('.wait-mask');     // 辅助遮罩节点
    let $waitLoadNode    = $('.wait-load');     // 加载动画节点
    let $waitHeaderNode  = $('.wait-header');   // 头部内容节点
    let $waitSidebarNode = $('.wait-sidebar');  // 侧边内容节点

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
                    let $body = $($iframe[0].contentWindow.document).find('body');
                    $body.attr('data-theme', theme);

                    loadNode.fadeOut(1000, function() {
                        loadNode.remove();
                    });
                })
            }
        }
        // 记忆标签页面数据
        ,tabMemory: function () {
            let isTabMemory = waitCache.getItem('isTabMemory') || waitConfig.isTabMemory;
            if (isTabMemory) {
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
        // 获取当前标签主体
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
                let isTabRefresh = waitCache.getItem('isTabRefresh') || waitConfig.isTabRefresh;
                if (isTabRefresh) {
                    events.loading();
                    let iframe = $waitBodyNode.find('.tab-body-item.layui-show iframe');
                    iframe.attr('src', iframe.attr('src'));
                }
            }

            // 定位当前的选项卡
            events.tabsPage.id = id;
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

    /** 初始化配置 **/
    $(function(){
        // 切换主题
        let theme = waitCache.getItem('theme') || waitConfig.theme;
        $mainBodyNode.attr('data-theme', theme);
        waitCache.setItem('theme', theme);

        // 隐藏标签
        let isTabHidden = waitCache.getItem('isTabHidden') || waitConfig.isTabHidden;
        if (isTabHidden) {
            $mainBodyNode.attr('data-tab', true);
        } else {
            // 记忆标签
            let tabMenus = sessionStorage.getItem('tabMenus');
            let isTabMemory = waitCache.getItem('isTabMemory') || waitConfig.isTabMemory;
            if (isTabMemory && tabMenus) {
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
    })

    /** 主级菜单事件 **/
    $waitSidebarNode.on('click', '.wait-menu-item',  function () {
        if (!$(this).children('dl').length) {
            $waitSidebarNode.find('dd.active').removeClass('active');
            $waitSidebarNode.find('dd').removeAttr('class');
            $(this).siblings().removeClass('on');
            $(this).addClass('on');
            let id   = $(this).children('a').attr('lay-id');
            let url  = $(this).children('a').attr('lay-attr');
            let text = $(this).children('a').children('cite').html();
            events.tabsOpen(id, url, text);
        } else {
            $waitShrinkNode = $('.wait-side-shrink');
            if ($waitShrinkNode.length) {
                $('.stretch i').trigger('click');
            }
            if (!$(this).hasClass('active')) {
                $(this).siblings().removeClass('active');
                $(this).siblings().children('a').children('span').addClass('layui-icon-left');
                $(this).siblings().children('a').children('span').removeClass('layui-icon-down');
                $(this).addClass('active');
                $(this).children('dl').stop().slideDown();
                $(this).siblings().children('.wait-second-menu').slideUp();
                $(this).children('a').children('span').addClass('layui-icon-down');
                $(this).children('a').children('span').removeClass('layui-icon-left');
            } else {
                if (!$waitShrinkNode.length) {
                    $(this).removeClass('active');
                    $(this).children('dl').stop().slideUp();
                    $(this).children('a').children('span').addClass('layui-icon-left');
                    $(this).children('a').children('span').removeClass('layui-icon-down');
                }
            }
        }
    });

    /** 子级菜单事件 **/
    $waitSidebarNode.on('click', '.wait-second-menu dd > a', function (e) {
        if ($(this).next().length) {
            if (!$(this).parent().hasClass('active')) {
                $(this).parent().addClass('active');
                $(this).parent().children('dl').stop().slideDown();
                $(this).children('span').addClass('layui-icon-down');
                $(this).children('span').removeClass('layui-icon-left');
            } else {
                $(this).parent().children('dl').stop().slideUp();
                $(this).parent().removeClass('active');
                $(this).parent().removeAttr('class');
                $(this).children('span').addClass('layui-icon-left');
                $(this).children('span').removeClass('layui-icon-down');
            }
        } else {
            $waitSidebarNode.find('.wait-menu-item').removeClass('on')
            $waitSidebarNode.find('dd.on').removeAttr('class');
            $(this).parent().addClass('on');
            let id   = $(this).attr('lay-id');
            let url  = $(this).attr('lay-attr');
            let text = $(this).html();
            events.tabsOpen(id, url, text);
            if ($waitSidebarNode.hasClass('develop-sidebar')) {
                $('.wait-header a.stretch i').trigger('click');
            }
        }
        e.stopPropagation();
    });

    /** 图标菜单事件 **/
    $waitHeaderNode.on('click', '.stretch i', function () {
        // 获取节点
        let shrinkNode  = $('.wait-side-shrink');

        if ($(window).width() < 768 || layui.device().mobile) {
            // 小屏幕或手机端菜单
            if ($(this).hasClass('layui-icon-shrink-right')) {
                $(this).removeClass('layui-icon-shrink-right');
                $(this).addClass('layui-icon-spread-left');
                $waitMaskNode.addClass('activate');
                $waitSidebarNode.addClass('develop-sidebar');
                $waitHeaderNode.children('.layui-layout-left').addClass('develop-sidebar');
            }
            else {
                $(this).removeClass('layui-icon-spread-left');
                $(this).addClass('layui-icon-shrink-right');
                $waitMaskNode.removeClass('activate');
                $waitSidebarNode.removeClass('develop-sidebar');
                $waitHeaderNode.children('.layui-layout-left').removeClass('develop-sidebar');
            }
        } else {
            // 判断伸缩小图标菜单
            if (!shrinkNode.length) {
                $waitAppNode.addClass('wait-side-shrink');
                $(this).removeClass('layui-icon-shrink-right');
                $(this).addClass('layui-icon-spread-left');
                $waitSidebarNode.find('.wait-second-menu').hide();
            } else {
                $waitAppNode.removeClass('wait-side-shrink');
                $(this).removeClass('layui-icon-spread-left');
                $(this).addClass('layui-icon-shrink-right');
                $waitSidebarNode.find('.wait-menu-item.active .wait-second-menu').show();
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
                $('.wait-sidebar .wait-menu-item .wait-second-menu').removeClass('active');
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
            type: 2,
            title: '主题配置',
            shadeClose: true,
            closeBtn: 0,
            skin: 'layui-anim layui-anim-right layui-layer-right',
            area: ['300px', '100%'],
            offset: 'r',
            anim: -1,
            content: enter + '/index/setting'
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
    $(window).resize(function(){
        if ($(window).width() <= 768 || layui.device().mobile) {
            $('.wait-header a.stretch i').removeClass('layui-icon-spread-left');
            $('.wait-header .stretch i').addClass('layui-icon-shrink-right');
            if ($waitAppNode.hasClass('wait-side-shrink')) {
                $waitAppNode.removeAttr("class");
            }
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
        let id = $(this).attr('lay-id');
        let elem = $('.wait-sidebar .wait-menu-item a[lay-id='+id+']');
        let oneLevelLI = elem.parent().parents('li');

        // 当前页面则不处理
        if (id === events.tabsPage.id && $(this).hasClass('layui-this')) {
            return true;
        }

        // 取消原来的选中态
        oneLevelLI.siblings('.active').find('> a span').addClass('layui-icon-left');
        oneLevelLI.siblings('.active').find('> a span').removeClass('layui-icon-down');
        oneLevelLI.siblings('.active').children('dl').stop().slideUp();
        oneLevelLI.siblings('.active').removeClass('active');
        $waitSidebarNode.find('dd.on').removeClass('on');
        $waitSidebarNode.find('li.on').removeClass('on');

        // 绑定现在的选中态
        elem.parent().addClass('on');
        if (!oneLevelLI.hasClass('active')) {
            oneLevelLI.addClass('active');
            if (!$('.wait-side-shrink').length) {
                oneLevelLI.children('dl').stop().slideDown();
            }
            oneLevelLI.children('a').children('span').addClass('layui-icon-down');
            oneLevelLI.children('a').children('span').removeClass('layui-icon-left');
        }

        // 三级菜单的激活态
        let threeLevelDD = elem.parent().parents('dd');
        if (threeLevelDD.length && !threeLevelDD.hasClass('active')) {
            threeLevelDD.addClass('active');
            threeLevelDD.children('dl').removeAttr('sytle');
            threeLevelDD.children('dl').stop().slideDown();
            threeLevelDD.children('a').children('span').addClass('layui-icon-down');
            threeLevelDD.children('a').children('span').removeClass('layui-icon-left');
        }

        // 存在页面直接切换
        $waitBodyNode.find('.tab-body-item').removeClass('layui-show');
        $waitBodyNode.find('.tab-body-item[lay-id='+id+']').addClass('layui-show');

        // 标签页面切换刷新
        let isTabRefresh = waitCache.getItem('isTabRefresh') || waitConfig.isTabRefresh;
        if (isTabRefresh) {
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
