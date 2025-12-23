import path from 'path'
import fsExtra from 'fs-extra'

const cwd = process.cwd()
const copy = fsExtra?.copy
const remove = fsExtra?.remove
const existsSync = fsExtra?.existsSync

// 打包发布路径
const releaseRelativePath = '../server/public/mobile'
const distPath = path.resolve(cwd, 'dist/build/h5')
const releasePath = path.resolve(cwd, releaseRelativePath)

async function build() {
    if (existsSync(releasePath)) {
        await remove(releasePath)
    }

    console.log(`文件正在复制 ==> ${releaseRelativePath}`)
    try {
        await copyFile(distPath, releasePath)
    } catch (error) {
        console.log(`\n ${error}`)
    }

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

build().then()
