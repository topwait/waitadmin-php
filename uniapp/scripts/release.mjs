import path from 'path'
import fsExtra from 'fs-extra'
const { existsSync, remove, copy } = fsExtra
const cwd = process.cwd()

// 打包发布路径, 谨慎改动
const releaseRelativePath = '../server/public/mobile'
const distPath = path.resolve(cwd, 'dist/build/h5')
const releasePath = path.resolve(cwd, releaseRelativePath)

async function build() {
    if (existsSync(releasePath)) {
        await remove(releasePath)
    }
    // eslint-disable-next-line no-console
    console.log(`文件正在复制 ==> ${releaseRelativePath}`)
    try {
        await copyFile(distPath, releasePath)
    } catch (error) {
        // eslint-disable-next-line no-console
        console.log(`\n ${error}`)
    }
    // eslint-disable-next-line no-console
    console.log(`文件已复制 ==> ${releaseRelativePath}`)
}

function copyFile(sourceDir, targetDir) {
    return new Promise((resolve, reject) => {
        copy(sourceDir, targetDir, (err) => {
            if (err) {
                reject(err)
            } else {
                resolve()
            }
        })
    })
}

build()
