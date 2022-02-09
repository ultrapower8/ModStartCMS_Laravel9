var $ = require('jquery');
var WebUploader = require('./../webuploader/webuploader.js');
import Cookies from 'js-cookie'

let apiStore = null

WebUploader.Uploader.register({
    'before-send': 'beforeBlockSend',
    'before-send-file': 'beforeSendFile'
}, {
    beforeBlockSend: function (block) {
        var task = new $.Deferred();
        if (block.chunk + 1 >= this.options.chunkUploaded) {
            setTimeout(task.resolve, 0);
        } else {
            setTimeout(task.reject, 0);
        }
        return $.when(task);
    },
    beforeSendFile: function (file) {
        var task = new $.Deferred();

        var input = {
            'action': 'init',
            'name': file.name,
            'type': file.type,
            'lastModifiedDate': file.lastModifiedDate,
            'size': file.size
        };
        var me = this;
        input.lastModifiedDate = input.lastModifiedDate.toString()
        $.ajax({
            type: 'POST',
            url: this.options.server,
            headers: this.options.headers,
            data: JSON.stringify(input),
            contentType: "application/json",
            dataType: 'json',
        })
            .done(function (res) {
                if (res.code) {
                    alert(res.msg);
                    task.reject();
                } else {
                    me.options.chunkUploaded = res.data.chunkUploaded;
                    task.resolve();
                }
            })
            .fail(function (res) {
                alert('上传出错');
                task.reject();
            });
        return $.when(task);
    }
});

export const UploadButtonUploader = function (selector, option) {
    var opt = $.extend({
        text: '上传文件',
        swf: '/Uploader.swf',
        server: '/path/to/server',
        sizeLimit: 2 * 1024 * 1024,
        extensions: 'gif,jpg,png,jpeg',
        chunked: true,
        chunkSize: 5 * 1024 * 1024,
        tipError: function (msg) {
            alert(msg);
        },
        callback: function (file, me) {
            // file.name
            // file.size
            // file.path
        },
        finish: function () {

        }
    }, option);

    return $(selector).each(function () {

        var me = this;
        var $me = $(this);

        $me.html('<div style="display:block;padding:0;margin:0;"><div class="picker">' + opt.text + '</div><ul class="webuploader-list"></ul></div>');

        var $list = $me.find('.webuploader-list');

        var headers = {}
        if (apiStore) {
            headers[apiStore.state.api.tokenKey] = Cookies.get(apiStore.state.api.tokenKey)
        }

        var uploader = WebUploader.create({
            auto: true,
            swf: opt.swf,
            server: opt.server,
            pick: $me.find('.picker'),
            fileSingleSizeLimit: opt.sizeLimit,
            chunked: opt.chunked,
            chunkSize: opt.chunkSize,
            chunkRetry: 5,
            threads: 1,
            accept: {
                extensions: opt.extensions
            },
            formData: {},
            headers: headers,
            duplicate: false
        });

        uploader.on('fileQueued', function (file) {
            var html = ' <li id="' + file.id + '">' +
                '<div class="progress-box">' +
                '<div class="progress-bar" style="width:0%"></div>' +
                '</div>' +
                '<div class="progress-info"><span class="status"><i class="iconfont icon-loading"></i></span> ' + file.name + '</div>' +
                '</li>';
            var $li = $(html);
            $list.append($li);
        });

        uploader.on('uploadProgress', function (file, percentage) {
            var $li = $('#' + file.id);
            $li.find('.progress-bar').css('width', percentage * 100 + '%');
            if (!$li.find('.status .iconfont').is('.icon-refresh')) {
                $li.find('.status').html('<i class="iconfont icon-refresh tw-animate-spin tw-inline-block"></i>');
            }
        });

        uploader.on('uploadAccept', function (file, response) {
            if (response.code) {
                return false;
            }
            return true;
        });

        uploader.on('uploadSuccess', function (file, res) {
            this.removeFile(file);
            var f = {
                name: res.data.data.filename,
                size: res.data.data.size,
                path: res.data.path
            };
            $('#' + file.id).fadeOut(function () {
                $('#' + file.id).remove();
            });
            opt.callback(f, me);
        });


        uploader.on('uploadError', function (file) {
            this.removeFile(file);
        });

        uploader.on('uploadFinished', function () {
            opt.finish();
        });

        uploader.on('error', function (type) {
            if ('Q_TYPE_DENIED' == type) {
                opt.tipError('文件类型不合法（只能上传' + opt.extensions + '文件）');
            } else if ('Q_EXCEED_SIZE_LIMIT' == type) {
                opt.tipError('文件大小不合法');
            } else if ('F_EXCEED_SIZE' == type) {
                opt.tipError('文件大小不合法');
            }
        });

    });

}



