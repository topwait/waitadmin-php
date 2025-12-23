<h1 align="center">WaitAdmin (Uniapp)</h1>

## 一、目录结构
```
├─📂 uniapp
│  ├─📂 node_modules  
│  ├─📂 scripts  
│  ├─📂 src                 
│  │  ├─📂 api              
│  │  ├─📂 assets             
│  │  ├─📂 bundle             
│  │  ├─📂 components             
│  │  ├─📂 config             
│  │  ├─📂 enums             
│  │  ├─📂 lang             
│  │  ├─📂 mixins             
│  │  ├─📂 pages             
│  │  ├─📂 stores             
│  │  ├─📂 utils             
│  │  ├─📄 App.ku.vue             
│  │  ├─📄 App.vue             
│  │  ├─📄 env.d.ts             
│  │  ├─📄 main.ts             
│  │  ├─📄 manifest.json             
│  │  ├─📄 pages.json             
│  │  ├─📄 shime-uni.d.ts             
│  │  ├─📄 uni.scss             
│  ├─📄 .env.template.development   
│  ├─📄 .env.template.production     
│  ├─📄 .gitignore                   
│  ├─📄 eslint.config.ts            
│  ├─📄 index.html                  
│  ├─📄 package.json                 
│  ├─📄 shims-uni.d.ts              
│  ├─📄 tailwind.config.ts           
│  ├─📄 tsconfig.json                
│  ├─📄 vite.config.ts               
```

## 二、前置知识
使用前请了解以下内容 (该项目使用到了以下依赖)
- `TypeScript`: [https://www.runoob.com/typescript/ts-tutorial.html]()
- `Tailwindcss`: [https://tailwindcss.com]()
- `WotUI`: [https://wot-ui.cn]()

源码下载: [https://gitee.com/wafts/waitadmin-php]() <br/>
文档地址: https://www.waitadmin.cn

## 三、安装与运行

### 1、环境配置
- **1、复制:** `.env.development.example`，**命名为:** `.env.development`
- **2、复制:** `.env.production.example`，**命名为:** `.env.production`
- **3、填写:** `VITE_APP_BASE_URL`
- **示例**
```text
// 注意: 这里是填写您服务端的域名
VITE_APP_BASE_URL=https://www.waitadmin.cn
```

### 2、安装依赖
> 你可以使用 pnpm、npm、yarn 等工具作为您的包管理工具
```shell
# 方法一: 使用 npm 进行安装
npm install

# 方法二: 使用 pnpm 进行安装
pnpm install

# 说明: 安装成功后，您的项目会多出一个 node_modules 目录
# 注意: 依赖安装只需要执行一次即可，建议你使用 pnpm 安装速度更快。
```

**3、开发运行 (Dev)：**
> 注意: dev是开发打包, 打包的内容都放在 `dist/dev` 目录下
```shell
# 1、运行到 H5端
pnpm run dev:h5

# 2、运行到 微信小程序
pnpm run dev:mp-weixin

# 3、运行到 支付宝小程序
pnpm run dev:mp-alipay
```

**4、生产打包 (Build)：**
> 注意: build是生产打包, 打包的内容都放在 `dist/build` 目录下
```shell
# 1、运行到 H5端
pnpm run build:h5

# 2、运行到 微信小程序
pnpm run build:mp-weixin

# 3、运行到 支付宝小程序
pnpm run build:mp-alipay

# 4、您也可以使用打包脚本按提示操作
pnpm run dev
```

**6、运行成功示例：**
```text
#【H5端】运行成功示例
vite v5.2.8 dev server running at:
➜  Local:   http://localhost:5173/mobile/
➜  Network: use --host to expose
ready in 1035ms.

#【微信小程序端】运行成功示例
✔ 当前使用 Tailwind CSS 版本为: 4.1.18                                    
DONE  Build complete. Watching for changes...
运行方式：打开 微信开发者工具, 导入 dist/dev/mp-weixin 运行。
ready in 7000ms.
```
