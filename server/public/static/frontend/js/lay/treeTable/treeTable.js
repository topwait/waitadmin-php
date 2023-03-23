layui.define(["layer", "table"], function (exports) {
    let $ = layui.jquery;
    let layer = layui.layer;
    let table = layui.table;
    let renderParam = null;
    let renderTable = null;

    let treeTable = {
        // 渲染树表
        render: function (param) {
            // 检查参数
            if (!treeTable.checkParam(param)) {
                return;
            }
            // 获取数据
            renderParam = param
            if (param.data) {
                treeTable.init(param, param.data);
            } else {
                $.getJSON(param.url, param.where, function (res) {
                    treeTable.init(param, res.data);
                });
            }

            return treeTable;
        },
        // 重载表格
        reload: function (elem, param) {
            if (typeof(elem)==='string') {
                renderParam.id = elem;
                renderParam.where = param || renderParam.where;
            } else {
                renderParam.where = elem || renderParam.where;
            }

            let where = renderParam.where;
            if (renderParam.where.page.curr) {
                where['page'] = renderParam.where.page.curr;
            }

            $.getJSON(renderParam.url, where, function (res) {
                treeTable.init(renderParam, res.data);
            });
        },
        // 渲染表格
        init: function (param, data) {
            let mData = [];
            let doneCallback = param.done;
            let tNodes = data;
            // 补上id和pid字段
            for (let i = 0; i < tNodes.length; i++) {
                let tt = tNodes[i];
                if (!tt.id) {
                    if (!param.treeIdName) {
                        layer.msg('参数treeIdName不能为空', {icon: 5});
                        return;
                    }
                    tt.id = tt[param.treeIdName];
                }
                if (!tt.pid) {
                    if (!param.treePidName) {
                        layer.msg('参数treePidName不能为空', {icon: 5});
                        return;
                    }
                    tt.pid = tt[param.treePidName];
                }
            }

            // 对数据进行排序
            let sort = function (s_pid, data) {
                for (let i = 0; i < data.length; i++) {
                    if (data[i].pid === s_pid) {
                        let len = mData.length;
                        if (len > 0 && mData[len - 1].id === s_pid) {
                            mData[len - 1].isParent = true;
                        }
                        mData.push(data[i]);
                        sort(data[i].id, data);
                    }
                }
            };
            sort(param.treePid, tNodes);

            // 重写参数
            param.url = undefined;
            param.data = mData;
            param.page = {
                count: param.data.length,
                limit: param.data.length
            };
            param.cols[0][param.treeColIndex].templet = function (d) {
                let mId = d.id;
                let mPid = d.pid;
                let isDir = d.isParent;
                let emptyNum = treeTable.getEmptyNum(mPid, mData);
                let iconHtml = '';
                for (let i = 0; i < emptyNum; i++) {
                    iconHtml += '<span class="treeTable-empty"></span>';
                }
                //是否显示文件夹图标
                if (param.treeDirIcon) {
                    if (isDir) {
                        iconHtml += '<i class="layui-icon layui-icon-triangle-d"></i> <i class="layui-icon layui-icon-layer"></i>';
                    } else {
                        iconHtml += '<i class="layui-icon layui-icon-file"></i>';
                    }
                } else {
                    if (isDir) {
                        iconHtml += '<i class="layui-icon layui-icon-triangle-d"></i>';
                    } else {
                        iconHtml += '';
                    }
                }
                iconHtml += '&nbsp;&nbsp;';
                let tType = isDir ? 'dir' : 'file';
                let vg = '<span class="treeTable-icon open" lay-tid="' + mId + '" lay-tpid="' + mPid + '" lay-type="' + tType + '">';
                return vg + iconHtml + d[param.cols[0][param.treeColIndex].field] + '</span>'
            };

            param.done = function (res, curr, count) {
                $(param.elem).next().addClass('treeTable');
                $('.treeTable .layui-table-page').css('display', 'none');
                $(param.elem).next().attr('treeLinkage', param.treeLinkage);
                if (param.treeDefaultClose) {
                    treeTable.foldAll(param.elem);
                }
                if (doneCallback) {
                    doneCallback(res, curr, count);
                }
            };

            // 渲染表格
            renderTable = table.render(param);

            // 展开表格
            param.treeExpand = param.treeExpand || false;
            if (param.treeExpand === true) {
                treeTable.expandAll();
            }
        },
        // 计算缩进
        getEmptyNum: function (pid, data) {
            let num = 0;
            if (!pid) {
                return num;
            }
            let tPid;
            for (let i = 0; i < data.length; i++) {
                if (pid === data[i].id) {
                    num += 1;
                    tPid = data[i].pid;
                    break;
                }
            }
            return num + treeTable.getEmptyNum(tPid, data);
        },
        // 展开折叠
        toggleRows: function ($dom, linkage) {
            let type = $dom.attr('lay-type');
            if ('file' === type) {
                return;
            }
            let mId = $dom.attr('lay-tid');
            let isOpen = $dom.hasClass('open');
            if (isOpen) {
                $dom.removeClass('open');
            } else {
                $dom.addClass('open');
            }
            $dom.closest('tbody').find('tr').each(function () {
                let $ti = $(this).find('.treeTable-icon');
                let pid   = $ti.attr('lay-tpid');
                let tType = $ti.attr('lay-type');
                let tOpen = $ti.hasClass('open');
                if (mId === pid) {
                    if (isOpen) {
                        $(this).hide();
                        if ('dir' === tType && tOpen === isOpen) {
                            $ti.trigger('click');
                        }
                    } else {
                        $(this).show();
                        if (linkage && 'dir' === tType && tOpen === isOpen) {
                            $ti.trigger('click');
                        }
                    }
                }
            });
        },
        // 检查参数
        checkParam: function (param) {
            if (!param.treePid && param.treePid !== 0) {
                layer.msg('参数treePid不能为空', {icon: 5});
                return false;
            }

            if (!param.treeColIndex && param.treeColIndex !== 0) {
                layer.msg('参数treeColIndex不能为空', {icon: 5});
                return false;
            }
            return true;
        },
        // 展开所有
        expandAll: function (dom) {
            dom = dom || renderParam.elem;
            $(dom).next('.treeTable').find('.layui-table-body tbody tr').each(function () {
                let $ti   = $(this).find('.treeTable-icon');
                let tType = $ti.attr('lay-type');
                let tOpen = $ti.hasClass('open');
                if ('dir' === tType && !tOpen) {
                    $ti.trigger('click');
                }
            });
        },
        // 折叠所有
        foldAll: function (dom) {
            dom = dom || renderParam.elem;
            $(dom).next('.treeTable').find('.layui-table-body tbody tr').each(function () {
                let $ti   = $(this).find('.treeTable-icon');
                let tType = $ti.attr('lay-type');
                let tOpen = $ti.hasClass('open');
                if ('dir' === tType && tOpen) {
                    $ti.trigger('click');
                }
            });
        }
    };

    // 给图标列绑定事件
    $('body').on('click', '.treeTable .treeTable-icon', function () {
        let treeLinkage = $(this).parents('.treeTable').attr('treeLinkage');
        if ('true' === treeLinkage) {
            treeTable.toggleRows($(this), true);
        } else {
            treeTable.toggleRows($(this), false);
        }
    });

    layui.link(layui.cache.base + 'treeTable/treeTable.css');
    exports('treeTable', treeTable);
});
