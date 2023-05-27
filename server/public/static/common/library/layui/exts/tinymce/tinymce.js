// +----------------------------------------------------------------------
// | 富文本
// +----------------------------------------------------------------------

layui.define([], function (exports) {
    let $ = layui.$;
    let plugin_filename = 'tinymce.min.js';
    let plugin_tinymce  = layui.cache.modules['tinymce'];
    let plugin_base_url = plugin_tinymce.substr(0, plugin_tinymce.lastIndexOf('.'));

    /**
     * 对象
     */
    let ojb = {
        // 编辑器加载
        render: function (options, callback) {
            options = options === undefined ? {} : options;
            let option = initOptions(options, callback);
            let editor = ojb.get(option.elem);
            if (editor) {
                editor.destroy();
            }

            return tinymce.init(option);
        },
        // 编辑器对象
        get: function (elem) {
            initTinymce();

            if (elem && /^#|\./.test(elem)) {
                let id = elem.substr(1);
                return tinymce.editors[id];
            } else {
                return false;
            }
        },
        // 编辑器重载
        reload: function (elem, option, callback) {
            let options = {};

            if(typeof elem == 'string'){
                option.elem = elem;
                options = $.extend({}, option)
            } else if (typeof elem == 'object' && typeof elem.elem == 'string'){
                options = $.extend({}, elem);
                callback = option
            }

            let optionCache = layui.sessionData('layui-tinymce')[options.elem];
            delete optionCache.init_instance_callback;
            $.extend(optionCache,options);
            return ojb.render(optionCache,callback)
        }
    };

    /**
     * 判空工具
     */
    function isset(value) {
        return typeof value !== 'undefined' && value !== null
    }

    /**
     * 初始编辑器
     */
    function initTinymce() {
        if (typeof tinymce == 'undefined') {
            $.ajax({
                url: plugin_base_url + '/' + plugin_filename,
                dataType: 'script',
                cache: true,
                async: false,
            });
        }
    }

    /**
     * 初始化参数
     */
    function initOptions(option, callback) {
        option.elem         = isset(option.elem)     ? option.elem : '#content';
        option.module       = isset(option.module)   ? option.module : 'frontend';
        option.uploader     = isset(option.uploader)   ? option.uploader : 'permanent';
        option.readonly     = isset(option.readonly) ? option.readonly : 0;
        option.suffix       = isset(option.suffix)   ? option.suffix : (plugin_filename.indexOf('.min')>-1 ? '.min' : '');
        option.base_url     = isset(option.base_url) ? option.base_url : plugin_base_url;
        option.language     = isset(option.language) ? option.language : 'zh_CN';
        option.selector     = isset(option.selector) ? option.selector : option.elem;
        option.resize       = isset(option.resize)   ? option.resize : false;
        option.branding     = isset(option.branding) ? option.branding : false;
        option.elementpath  = isset(option.elementpath) ? option.elementpath : false;
        option.convert_urls = isset(option.convert_urls) ? option.convert_urls : false;
        option.height       = isset(option.height)  ? option.height : 600;
        option.menu         = isset(option.menu)    ? option.menu : false;
        option.menubar      = isset(option.menubar) ? option.menubar : false;
        option.attach       = isset(option.attach)  ? option.attach : 'image media';

        option.content_style = 'p,h1,h2,h3,h4,h5,h6 {margin: 0;}'

        option.plugins = isset(option.plugins) ? option.plugins : `code preview fullpage searchreplace autolink
                    directionality visualblocks visualchars fullscreen link template charmap hr pagebreak 
                    nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern help emoticons 
                    table autosave paste print codesample ` + option.attach;

        option.toolbar = isset(option.toolbar) ? option.toolbar : [
            `code
                | bold italic underline strikethrough forecolor backcolor subscript superscript
                | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent
                | emoticons charmap wordcount | fullscreen`,
            `formatselect
                | fontselect | fontsizeselect | table hr blockquote codesample link | `+ option.attach +`
                | anchor removeformat print | searchreplace`
        ];

        /**
         * 初始化实例回调
         *
         * @type {(function(*=): void)|*}
         * @author zero
         */
        option.init_instance_callback = isset(option.init_instance_callback) ? option.init_instance_callback : function(inst) {
            if(typeof callback == 'function') callback(option, inst)
        };

        /**
         * 图片上传网址
         * 该属性会在上传图片时自动访问指定的url并获取返回地址
         *
         * @type {boolean}
         * @author zero
         */
        option.images_upload_url = isset(option.images_upload_url) ? option.images_upload_url : false;

        /**
         * 网址替换回调
         *
         * @type {boolean}
         * @author zero
         */
        option.urlconverter_callback = isset(option.urlconverter_callback) ? option.urlconverter_callback : false;

        /**
         * 文件选取器回调
         * 触发位置:
         *  [插入/编辑图片]: 常规: 地址栏
         *  [插入/编辑媒体]: 常规: 地址栏
         *  [插入/编辑媒体]: 高级: 替换的资源地址 / 封面(图片地址)
         *
         * @type {(function(*, *, *): void)|*}
         * @author zero
         */
        option.file_picker_callback = isset(option.file_picker_callback) ? option.file_picker_callback : function(callback, value, meta) {
            let pathname = option.module;
            if (option.module === 'backend') {
                pathname = window.location.pathname.split('/')[1];
            }

            let fileUrl;
            let fileType;
            let baseUrl = '/' + pathname + '/upload/' + option.uploader;
            switch(meta.filetype){
                case 'image':
                    fileUrl  = baseUrl + '?type=picture';
                    fileType = '.png, .jpg, .jpeg, .gif, .ico, .bmp';
                    break;
                case 'media':
                    fileUrl  = baseUrl + '?type=video';
                    fileType = '.mp4, .mp3, .avi, .flv, .rmvb, .mov';
                    break;
            }

            let input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', fileType);
            input.click();
            input.onchange = function () {
                let file = this.files[0];
                let xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', fileUrl);
                xhr.onload = function() {
                    let json;
                    if (xhr.status !== 200) {
                        return;
                    }
                    json = JSON.parse(xhr.responseText);
                    callback(json.data.url, {title: file.name});
                };
                formData = new FormData();
                formData.append('file', file, file.name );
                xhr.send(formData);
            };
        };

        /**
         * 图片上传处理器
         * 触发位置:
         *  [插入/编辑图片]: 上传: 拖放一张图片文件至此
         *
         * @type {(function(*, *, *): void)|*}
         * @author zero
         */
        option.images_upload_handler = isset(option.images_upload_handler) ? option.images_upload_handler : function(blobInfo, successFun, failFun) {
            let pathname = option.module;
            if (option.module === 'backend') {
                pathname = window.location.pathname.split('/')[1];
            }

            let baseUrl = '/' + pathname + '/upload/'+ option.uploader +'?type=picture';
            let xhr, formData;
            let file = blobInfo.blob();
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST',  option.images_upload_url ? option.images_upload_url : baseUrl);
            xhr.onload = function() {
                let json;
                if (xhr.status !== 200) {
                    failFun('HTTP Error: ' + xhr.status);
                    return;
                }

                json = JSON.parse(xhr.responseText);
                if (!json || typeof json.data.url != 'string') {
                    failFun('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                successFun(json.data.url);
            };
            formData = new FormData();
            formData.append('file', file, file.name );
            xhr.send(formData);
        };

        /**
         * 附件选择回调
         * 从图库选择的: [紧后台可用]
         *
         * @type {(function(*): void)|*}
         * @author zero
         */
        option.attach_upload_callback = isset(option.attach_upload_callback) ? option.attach_upload_callback : function(callback) {
            if (option.module !== 'backend') {
                return layer.msg('Module not supported!', {icon: 2})
            }

            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                shadeClose: true,
                content:
                    '<div style="padding:10px 40px;">'  +
                    '<button class="layui-btn" id="imageAttachBtn" style="margin:8px 0; background-color: #3e526a;"><i class="layui-icon layui-icon-picture"></i> 图片选择</button>' +
                    '<br/>' +
                    '<button class="layui-btn" id="videoAttachBtn" style="margin:8px 0; background-color: #3e526a;"><i class="layui-icon layui-icon-play"></i> 视频选择</button>' +
                    '</div>',
                success: function (layero, index) {
                    $(layero).find('#imageAttachBtn').click(function () {
                        layer.close(index);
                        waitUtil.uploader({
                            type: 'image',
                            limit: 20
                        }).then((res) => {
                            let urls = [];
                            res.forEach(function (item) {
                                urls.push(item.url);
                            });
                            callback(urls);
                        });
                    });

                    $(layero).find('#videoAttachBtn').click(function () {
                        layer.close(index);
                        waitUtil.uploader({
                            type: 'video',
                            limit: 20
                        }).then((res) => {
                            let urls = [];
                            res.forEach(function (item) {
                                urls.push(item.url);
                            });
                            callback(urls);
                        });
                    });
                }
            });
        };

        /**
         * 图片上传回调
         * 从图库选择的: [紧后台可用]
         *
         * @type {(function(*): void)|*}
         * @author zero
         */
        option.images_upload_callback = isset(option.images_upload_callback) ? option.images_upload_callback : function(callback) {
            if (option.module !== 'backend') {
                return layer.msg('Module not supported!', {icon: 2})
            } else {
                waitUtil.uploader({
                    type: 'image',
                    limit: 20
                }).then((res) => {
                    let urls = [];
                    res.forEach(function (item) {
                        urls.push(item.url);
                    });
                    callback(urls);
                });
            }
        };

        /**
         * 视频上传回调
         * 从图库选择的: [紧后台可用]
         *
         * @type {(function(*): void)|*}
         * @author zero
         */
        option.video_upload_callback = isset(option.video_upload_callback) ? option.video_upload_callback : function(callback) {
            if (option.module !== 'backend') {
                return layer.msg('Module not supported!', {icon: 2})
            } else {
                waitUtil.uploader({
                    type: 'video',
                    limit: 20
                }).then((res) => {
                    let urls = [];
                    res.forEach(function (item) {
                        urls.push(item.url);
                    });
                    callback(urls);
                });
            }
        };

        layui.sessionData('layui-tinymce', {
            key: option.selector,
            value: option
        });

        return option;
    }

    exports('tinymce', ojb);
});