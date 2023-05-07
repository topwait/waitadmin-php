layui.use(['form']);

/**
 * 防抖动
 * @type {boolean}
 */
let throttle = false;

/**
 * 重载这步
 */
function goLoad() {
    window.location.reload();
}

/**
 * 后退一步
 */
function goBack() {
    window.history.go(-1);
}

/**
 * 前进一步
 */
function goNext(step) {
    if (step === 4) {
        if (throttle === true) return;
        layui.form.on('submit(addForm)', function (data) {
            if (throttle === true) return;
            throttle = true;
            if (!data.field['host'])       return layer.msg('数据库主机不可为空!');
            if (!data.field['port'])       return layer.msg('数据库端口不可为空!');
            if (!data.field['username'])   return layer.msg('数据库用户不可为空!');
            if (!data.field['password'])   return layer.msg('数据库密码不可为空!');
            if (!data.field['db'])         return layer.msg('数据库名称不可为空!');
            if (!data.field['prefix'])     return layer.msg('数据库前缀不可为空!');
            if (!data.field['admin_user']) return layer.msg('管理员账号不可为空!');
            if (!data.field['admin_pwd'])  return layer.msg('管理员密码不可为空!');
            if (!data.field['admin_pwd_confirm']) return layer.msg('确认密码不可为空!');
            if (data.field['admin_pwd'].length < 6) return layer.msg('密码不能少于6位数!');
            if (data.field['admin_pwd'] !== data.field['admin_pwd_confirm']) return layer.msg('两次密码不一致!');
            data.field['ajax'] = true;
            layui.$.ajax({
                url: '/install/install.php?step=4',
                data: data.field,
                type: 'POST',
                timeout: 3000,
                dataType: "json",
                success: function (res) {
                    throttle = false;
                    if (res.code === 1) {
                        return layer.msg(res.msg);
                    } else {
                        setTimeout(function () {
                            document.form.action = "?step=" + step;
                            document.form.submit();
                        }, 1500)
                    }
                }
            })
        })
    } else {
        document.form.action = "?step=" + step;
        document.form.submit();
        throttle = false;
    }
}

/**
 * 插入数据表
 */
function pushSqlTable(successTables) {
    // 节点
    let elem = document.getElementById('sqlBox');
    // 内容
    let div = document.createElement('div');
    div.className = 'sql';
    div.innerHTML = `<div class="sql-left">
                    <div class="layui-icon layui-icon-ok"></div>
                    <div>创建数据表${successTables[0]}完成！</div>
                </div>
                <div class="sql-right">${successTables[1]}</div>`;
    // 插入
    elem.append(div);
}

/**
 * 显示插入节点
 * @param index
 */
function showParts(index) {
    if (typeof successTables == "undefined") {
        return;
    }

    function getRndInteger(min, max) {
        return Math.floor(Math.random() * (max - min) ) + min;
    }

    if (index < successTables.length) {
        setTimeout(function () {
            pushSqlTable(successTables[index]); showParts(++index);
        }, getRndInteger(50, 100));
    }

    if (index === successTables.length) {
        goNext(5);
    }
}

/**
 * 延迟执行命令
 */
setTimeout(function () {
    showParts(0);
}, 100);