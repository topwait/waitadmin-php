/** 文件类型 **/
export type FileType = 'image' | 'video' | 'all' | undefined;

/** 选择参数类型 **/
type ChooseFileOptions = UniApp.ChooseFileOptions
type ChooseImageOptions = UniApp.ChooseImageOptions
type ChooseVideoOptions = UniApp.ChooseVideoOptions
type ChooseOptions = (ChooseFileOptions | ChooseImageOptions | ChooseVideoOptions)

/** 选择成功类型 **/
type ChooseVideoSuccess = UniApp.ChooseVideoSuccess
type ChooseImageSuccessCallbackResult = UniApp.ChooseImageSuccessCallbackResult
type ChooseFileSuccessCallbackResult = UniApp.ChooseFileSuccessCallbackResult

/** 选择结果类型 **/
export interface ChooseResult {
    tempFilePaths: string[] | string
    tempFiles: any[]
    errMsg?: string
}

/** 文件数据类型 **/
export interface FileData {
    // 文件类型
    fileType?: FileType;
    // 文件名称
    name?: string;
    // 文件大小
    size?: number;
    // 文件扩展
    ext?: string;
    // 文件地址
    url: string;
    // 上传状态
    status?: 'uploading' | 'success' | 'failed';
    // 上传进度
    progress?: number;
    // 错误信息
    errMsg?: string;
}

/**
 * 选择图片
 */
function chooseImage(opts: ChooseImageOptions): Promise<ChooseResult> {
    return new Promise<ChooseResult>((resolve, reject): void => {
        uni.chooseImage({
            count: opts.count,
            sizeType: opts.sizeType,
            sourceType: opts.sourceType,
            extension: opts.extension,
            success(res: ChooseImageSuccessCallbackResult): void {
                resolve(normalizeFileRes(res as ChooseResult, 'image'))
            },
            fail(res): void {
                if (res.errMsg !== 'chooseImage:fail cancel') {
                    reject({
                        errMsg: res.errMsg
                    })
                }
            }
        })
    })
}

/**
 * 选择视频
 */
function chooseVideo(opts: ChooseVideoOptions): Promise<ChooseResult> {
    return new Promise<ChooseResult>((resolve, reject): void => {
        uni.chooseVideo({
            camera: opts.camera,
            compressed: opts.compressed,
            maxDuration: opts.maxDuration,
            sourceType: opts.sourceType,
            extension: opts.extension,
            success: (res: ChooseVideoSuccess): void => {
                resolve(
                    normalizeFileRes(
                        {
                            errMsg: 'chooseVideo:ok',
                            tempFilePaths: [res.tempFilePath],
                            tempFiles: [
                                {
                                    name: res.tempFile && res.tempFile.name || '',
                                    path: res.tempFilePath,
                                    size: res.size,
                                    type: res.tempFile && res.tempFile.type || '',
                                    width: res.width,
                                    height: res.height,
                                    duration: res.duration,
                                    fileType: 'video'
                                }
                            ]
                        },
                        'video'
                    )
                )
            },
            fail(res): void {
                if (res.errMsg !== 'chooseVideo:fail cancel') {
                    reject({
                        errMsg: res.errMsg
                    })
                }
            }
        })
    })
}

/**
 * 选择所有
 */
function chooseAll(opts: ChooseFileOptions): Promise<ChooseResult> {
    return new Promise<ChooseResult>((resolve, reject): void => {
        let chooseFile: any = uni.chooseFile
        if (typeof uni.chooseMessageFile === 'function') {
            chooseFile = uni.chooseMessageFile
        }

        chooseFile({
            type: 'all',
            count: opts.count,
            extension: opts.extension,
            success(res: ChooseFileSuccessCallbackResult): void {
                resolve(normalizeFileRes(res as ChooseResult))
            },
            fail(res: any): void {
                if (res.errMsg !== 'chooseFile:fail cancel') {
                    reject({
                        errMsg: res.errMsg
                    })
                }
            }
        })
    })
}

/**
 * 选择文件 (根据类型调用对应选择框)
 */
function chooseFile(opts: ChooseOptions): Promise<ChooseResult> {
    if ('type' in opts && opts.type === 'image') {
        return chooseImage(opts as ChooseImageOptions)
    } else if ('type' in opts && opts.type === 'video') {
        return chooseVideo(opts as ChooseVideoOptions)
    }
    return chooseAll(opts as ChooseFileOptions)
}

/**
 * 规范化选择的文件
 *
 * @param {ChooseResult} res
 * @param {FileType} fileType
 */
function normalizeFileRes(res: ChooseResult, fileType?: FileType): ChooseResult {
    res.tempFiles.forEach((item): void => {
        if (!item.name) {
            item.name = item.path.substring(item.path.lastIndexOf('/') + 1)
        }
        if (fileType) {
            item.fileType = fileType
        }
    })

    if (!res.tempFilePaths) {
        res.tempFilePaths = res.tempFiles.map((file) => file.path)
    }

    return res
}

/**
 * 处理选择文件格式
 */
const handleFiles = (file: any): FileData => {
    console.log(file)
    return {
        fileType: file.fileType,
        name: file.name,
        size: file.size,
        ext: getFileExt(file.name),
        url: file.tempFilePath || file.path,
        progress: 0,
        status: 'uploading',
        errMsg: ''
    } as FileData
}

/**
 * 获取文件类型
 */
const getFileType = (url: string): 'image' | 'video' | 'all' => {
    const imageExtArr: string[] = [
        '.jpg', '.jpeg', '.png', '.gif', '.ico',
        '.webp', '.bmp', '.svg', '.tif', '.tiff'
    ]

    const videoExtArr: string[] = [
        '.mp4', '.webm', '.mov', '.mkv', '.avi',
        '.wmv', '.mpg', '.mpeg', '.m4v', '.ogv',
        '.av1', '.dv', '.dif'
    ]

    const ext: string = url.substring(url.lastIndexOf('.')).toLowerCase()
    if (imageExtArr.some((e) => ext.includes(e))) {
        return 'image'
    }
    if (videoExtArr.some((e) => ext.includes(e))) {
        return 'video'
    }
    return 'all'
}

/**
 * 获取文件名称
 */
const getFileName = (path: string): string => {
    if (path) {
        const lastLen: number = path.lastIndexOf('.')
        const lastPath: number = path.lastIndexOf('/')
        if (lastLen === -1) {
            return path
        }
        return path.substring(lastPath + 1)
    }
    return ''
}

/**
 * 获取文件扩展
 */
const getFileExt = (name: string): string => {
    if (name) {
        const lastLen: number = name.lastIndexOf('.')
        const len: number = name.length
        const ext: string = name.substring(lastLen + 1, len)
        return ext.toLowerCase()
    }
    return ''
}

export {
    chooseImage,
    chooseVideo,
    chooseAll,
    chooseFile,
    getFileType,
    getFileName,
    getFileExt,
    handleFiles
}
