<h1 align="center">WaitAdmin管理后台</h1>

WaitAdmin是一款ThinkPHP8.1 + Layui2.13.*的极速后台开发框架。
实现了后台系统常见的RBAC权限管理，并且内置了诸多好用的工具。

## 项目说明
个人开发者利用的空闲碎片时间开发的后台管理系统。<br/>
市面上已有很多类似的后台管理系统了为什么还要去做呢？<br/>
难道多一种选择不好吗？

🚀🚀🚀 **开源不易，给个star支持一下吧！**

## 在线体验
- 官方网址: https://www.waitadmin.cn
- 开发文档: https://www.waitadmin.cn/docs/php
- 演示站点: https://php-demo.waitadmin.cn/admin.php

## 体验账号
- 账号：admin
- 密码：123456

## 环境要求

| 运行环境           | 要求版本    | 推荐版本   |
|:---------------|:--------|:-------|
| PHP            | >=8.0.2 | 8.0.30 |
| Mysql          | >=5.7   | 5.7    |
| nginx 或 apache | 无限制     | nginx  |

## 架构说明
#### 服务端 (server)
> 采用传统的方式实现页面渲染，以及提供接口服务。

- ThinkPHP8
- Layui
- Jquery
- Mysql

#### 移动端 (uniapp)
> 可以编译到多个端，如: `H5`、`APP`、`微信小程序`、`支付宝小程序`等。

- Uniapp
- WotUI
- Sass
- TailwindCss
- TypeScript
- Vite


## 主要特性
- 基于RBAC的权限管理系统
- 强大的代码构建功能 (包含控制器、模型、视图、菜单等)
- 支持多主题颜色的自由切换和菜单标签记忆等。
- 对常用JS插件进行二次封装，使JS代码变得更简洁，更加易维护。
- 支持国际化多语言的支持 (需要自定去翻译)。
- 完善的日志记录系统无需您额外编写代码自动记录。
- 支持插件化的开发，解耦您的代码，更易于维护和扩展。
- 支持自定义后台访问路径，防止别人找到对应后台地址。
- 内置文件附件管理系统，静态资源管理一目了然。
- 内置了很多常用的组件和工具 (后面介绍)

## 内置功能
- 用户管理：该功能主要完成系统用户配置。
- 部门管理：配置系统组织机构(公司、部门、小组)
- 岗位管理：配置系统用户所属担任职务。
- 菜单管理：配置系统菜单操作权限访问路径等。
- 角色管理：配置角色菜单权限分配
- 水印配置：配置上传图片增加水印
- 邮件配置：配置电子邮件发送功能
- 操作日志：系统操作日志记录和查询
- 定时任务：管理定时任务的(新增、修改、删除)
- 系统缓存：管理系统产生的缓存(可自行清理)
- 附件管理：管理用户上传的图片和视频
- 文章管理：管理文章的(新增、修改、删除)
- 插件管理：管理额外的代码包
- 文件存储：管理文件的存储(本地存储、阿里云OSS、腾讯云OSS、七牛云OSS)

## 演示图
<table>
    <tr>
        <td><img src="https://www.waitadmin.cn/others/ts_01.png" height="200" width="400" alt="" /></td>
        <td><img src="https://www.waitadmin.cn/others/ts_02.png" height="200" width="400" alt="" /></td>
    </tr>
    <tr>
        <td><img src="https://www.waitadmin.cn/others/ts_03.png" height="200" width="400" alt="" /></td>
        <td><img src="https://www.waitadmin.cn/others/ts_04.png" height="200" width="400" alt="" /></td>
    </tr>
    <tr>
        <td><img src="https://www.waitadmin.cn/others/ts_05.png" height="200" width="400" alt="" /></td>
        <td><img src="https://www.waitadmin.cn/others/ts_06.png" height="200" width="400" alt="" /></td>
    </tr>
    <tr>
        <td><img src="https://www.waitadmin.cn/others/ts_07.png" height="200" width="400" alt="" /></td>
        <td><img src="https://www.waitadmin.cn/others/ts_08.png" height="200" width="400" alt="" /></td>
    </tr>
</table>

## 交流群
QQ群1(已满员)：
<a href="https://gitee.com/link?target=http://qm.qq.com/cgi-bin/qm/qr?_wv=1027&k=4oSCLEiY0Wuc0XlaJ_v2BCpAz_-iuZ1t&authKey=vUn9S0p0b6d5jwRI9qo8YU8TBGiS3eGu2xJfcUBE56vUsS9TyVTC4GRMaSW4CGbH&noverify=0&group_code=613667155">
    <img src="https://img.shields.io/badge/613667155-blue.svg" alt="加入QQ群1(该群已满员)">
</a>

QQ群2：
<a href="https://gitee.com/link?target=https://qm.qq.com/cgi-bin/qm/qr?_wv=1027&k=XsxCMqxH9H1JRjE-TaBIKLWHl1EYg804&authKey=2lon5nRzzowAf4TIbYzuXRCyyxgLR8B%2FysM4N%2F0OK%2FvQdXcNSqPYABGkuF0jsu3t&noverify=0&group_code=1019900755">
    <img src="https://img.shields.io/badge/1019900755-blue.svg" alt="加入QQ群2">
</a>