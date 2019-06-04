;
!function(window, undefined) {
    "use strict";

    var $, win, ready = {
        getPath: function() {
            var js = document.scripts, script = js[js.length - 1], jsPath = script.src;
            if (script.getAttribute('merge'))
                return;
            return jsPath.substring(0, jsPath.lastIndexOf("/") + 1);
        }(),
        //屏蔽Enter触发弹层
        enter: function(e) {
            if (e.keyCode === 13)
                e.preventDefault();
        },
        config: {}, end: {},
        btn: ['&#x786E;&#x5B9A;', '&#x53D6;&#x6D88;'],
        //五种原始层模式
        type: ['dialog', 'page', 'iframe', 'loading', 'tips']
    };

//默认内置方法。
    var layer = {
        ie6: !!window.ActiveXObject && !window.XMLHttpRequest,
        index: 0,
        path: ready.getPath,
        config: function(options, fn) {
            options = options || {};
            layer.cache = ready.config = $.extend(ready.config, options);
            layer.path = ready.config.path || layer.path;
            typeof options.extend === 'string' && (options.extend = [options.extend]);
            return this;
        },
        ready: function(path, fn) {
            var type = typeof path === 'function';
            if (type)
                fn = path;
            layer.config($.extend(ready.config, function() {
                return type ? {} : {path: path};
            }()), fn);
            return this;
        },
        //各种快捷引用
        alert: function(content, options, yes) {
            var type = typeof options === 'function';
            if (type)
                yes = options;
            return layer.open($.extend({
                content: content || '',
                yes: yes
            }, type ? {} : options));
        },
        confirm: function(content, options, yes, cancel) {
            var type = typeof options === 'function';
            if (type) {
                cancel = yes;
                yes = options;
            }
            return layer.open($.extend({
                content: content || '',
                btn: ready.btn,
                yes: yes,
                btn2: cancel
            }, type ? {} : options));
        },
        msg: function(content, options, end) { //最常用提示层
            var type = typeof options === 'function', rskin = ready.config.skin;
            var skin = (rskin ? rskin + ' ' + rskin + '-msg' : '') || 'qhz-layer-msg';
            var shift = doms.anim.length - 1;
            if (type)
                end = options;
            return layer.open($.extend({
                content: content || '',
                time: 3000,
                shade: false,
                skin: skin,
                title: false,
                closeBtn: false,
                btn: false,
                end: end
            }, (type && !ready.config.skin) ? {
                skin: skin + ' qhz-layer-hui',
                shift: shift
            } : function() {
                options = options || {};
                if (options.icon === -1 || options.icon === undefined && !ready.config.skin) {
                    options.skin = skin + ' ' + (options.skin || 'qhz-layer-hui');
                }
                return options;
            }()));
        },
        load: function(icon, options) {
            return layer.open($.extend({
                type: 3,
                icon: icon || 0,
                shade: 0.01
            }, options));
        },
        hideload: function() {
            layer.closeAll('loading');
        },
        tips: function(content, follow, options) {
            return layer.open($.extend({
                type: 4,
                content: [content, follow],
                closeBtn: false,
                time: 3000,
                shade: false,
                maxWidth: 210
            }, options));
        }
    };

    var Class = function(setings) {
        var _this = this;
        _this.index = ++layer.index;
        _this.config = $.extend({}, _this.config, ready.config, setings);
        _this.creat();
    };

    Class.pt = Class.prototype;

//缓存常用字符
    var doms = ['qhz-layer', '.qhz-layer-title', '.qhz-layer-main', '.qhz-layer-dialog', 'qhz-layer-iframe', 'qhz-layer-content', 'qhz-layer-btn', 'qhz-layer-close'];
    doms.anim = ['layer-anim', 'layer-anim-01', 'layer-anim-02', 'layer-anim-03', 'layer-anim-04', 'layer-anim-05', 'layer-anim-06'];

//默认配置
    Class.pt.config = {
        type: 0,
        shade: 0.3,
        fix: true,
        title: '&#x4FE1;&#x606F;',
        offset: 'auto',
        area: 'auto',
        closeBtn: 1,
        time: 0, //0表示不自动关闭
        zIndex: 99999999,
        maxWidth: 360,
        shift: 0,
        icon: -1,
        scrollbar: true, //是否允许浏览器滚动条
        tips: 2
    };

//容器
    Class.pt.vessel = function(conType, callback) {
        var _this = this, times = _this.index, config = _this.config;
        var zIndex = config.zIndex + times, titype = typeof config.title === 'object';
        var titleHTML = (config.title ? '<div class="qhz-layer-title" style="' + (titype ? config.title[1] : '') + '">'
                + (titype ? config.title[0] : '提示')
                + '</div>' : '');

        config.zIndex = zIndex;
        callback([
            //遮罩
            config.shade ? ('<div class="qhz-layer-shade" id="qhz-layer-shade' + times + '" times="' + times + '" style="' + ('z-index:' + (zIndex - 1) + '; background-color:' + (config.shade[1] || '#000') + '; opacity:' + (config.shade[0] || config.shade) + '; filter:alpha(opacity=' + (config.shade[0] * 100 || config.shade * 100) + ');') + '"></div>') : '',
            //主体
            '<div class="' + doms[0] + ' ' + (doms.anim[config.shift] || '') + (' qhz-layer-' + ready.type[config.type]) + (((config.type == 0 || config.type == 2) && !config.shade) ? ' qhz-layer-border' : '') + ' ' + (config.skin || '') + '" id="' + doms[0] + times + '" type="' + ready.type[config.type] + '" times="' + times + '" showtime="' + config.time + '" conType="' + (conType ? 'object' : 'string') + '" style="z-index: ' + zIndex + '; width:' + config.area[0] + ';height:' + config.area[1] + (config.fix ? '' : ';position:absolute;') + '">'
                    + (conType && config.type != 2 ? '' : titleHTML)
                    + '<div id="' + (config.id || '') + '" class="qhz-layer-content' + ((config.type == 0 && config.icon !== -1) ? ' qhz-layer-padding' : '') + (config.type == 3 ? ' qhz-layer-loading' + config.icon : '') + '">'
                    + (config.type == 0 && config.icon !== -1 ? '<i class="qhz-layer-ico qhz-layer-ico' + config.icon + '"></i>' : '')
                    + (config.type == 1 && conType ? '' : (config.content || ''))
                    + '</div>'
                    + '<span class="qhz-layer-setwin">' + function() {
                        var closebtn = '';
                        config.closeBtn && (closebtn += '<a class="qhz-layer-ico ' + doms[7] + ' ' + doms[7] + (config.title ? config.closeBtn : (config.type == 4 ? '1' : '2')) + '" href="javascript:;"></a>');
                        return closebtn;
                    }() + '</span>'
                    + (config.btn ? function() {
                        var button = '';
                        typeof config.btn === 'string' && (config.btn = [config.btn]);
                        for (var i = 0, len = config.btn.length; i < len; i++) {
                            button += '<a class="' + doms[6] + '' + i + '">' + config.btn[i] + '</a>'
                        }
                        return '<div class="' + doms[6] + '">' + button + '</div>'
                    }() : '')
                    + '</div>'
        ], titleHTML);
        return _this;
    };

//创建骨架
    Class.pt.creat = function() {
        var _this = this, config = _this.config, times = _this.index, nodeIndex;
        var content = config.content, conType = typeof content === 'object';

        if ($('#' + config.id)[0])
            return;

        if (typeof config.area === 'string') {
            config.area = config.area === 'auto' ? ['', ''] : [config.area, ''];
        }

        switch (config.type) {
            case 0:
                config.btn = ('btn' in config) ? config.btn : ready.btn[0];
                layer.closeAll('dialog');
                break;
            case 2:
                var content = config.content = conType ? config.content : [config.content || 'http://www.qianhezi.cn', 'auto'];
                config.content = '<iframe scrolling="' + (config.content[1] || 'auto') + '" allowtransparency="true" id="' + doms[4] + '' + times + '" name="' + doms[4] + '' + times + '" onload="this.className=\'\';" class="qhz-layer-load" frameborder="0" src="' + config.content[0] + '"></iframe>';
                break;
            case 3:
                config.title = false;
                config.closeBtn = false;
                config.icon === -1 && (config.icon === 0);
                layer.closeAll('loading');
                break;
            case 4:
                conType || (config.content = [config.content, 'body']);
                config.follow = config.content[1];
                config.content = config.content[0] + '<i class="qhz-layer-TipsG"></i>';
                config.title = false;
                config.fix = false;
                config.tips = typeof config.tips === 'object' ? config.tips : [config.tips, true];
                config.tipsMore || layer.closeAll('tips');
                break;
        }

        //建立容器
        _this.vessel(conType, function(html, titleHTML) {
            $('body').append(html[0]);
            conType ? function() {
                (config.type == 2 || config.type == 4) ? function() {
                    $('body').append(html[1]);
                }() : function() {
                    if (!content.parents('.' + doms[0])[0]) {
                        content.show().addClass('qhz-layer-wrap').wrap(html[1]);
                        $('#' + doms[0] + times).find('.' + doms[5]).before(titleHTML);
                    }
                }();
            }() : $('body').append(html[1]);
            _this.layero = $('#' + doms[0] + times);
            config.scrollbar || doms.html.css('overflow', 'hidden').attr('layer-full', times);
        }).callback().auto(times);

        config.type == 2 && layer.ie6 && _this.layero.find('iframe').attr('src', content[0]);
        $(document).off('keydown', ready.enter).on('keydown', ready.enter);
        _this.layero.on('keydown', function(e) {
            $(document).off('keydown', ready.enter);
        });

        //坐标自适应浏览器窗口尺寸
        config.type == 4 ? _this.tips() : _this.offset();
        if (config.fix) {
            win.on('resize', function() {
                _this.offset();
                (/^\d+%$/.test(config.area[0]) || /^\d+%$/.test(config.area[1])) && _this.auto(times);
                config.type == 4 && _this.tips();
            });
        }

        config.time <= 0 || setTimeout(function() {
            layer.close(_this.index)
        }, config.time);
    };

//自适应
    Class.pt.auto = function(index) {
        var _this = this, config = _this.config, layero = $('#' + doms[0] + index);
        if (config.area[0] === '' && config.maxWidth > 0) {
            //为了修复IE7下一个让人难以理解的bug
            if (/MSIE 7/.test(navigator.userAgent) && config.btn) {
                layero.width(layero.innerWidth());
            }
            layero.outerWidth() > config.maxWidth && layero.width(config.maxWidth);
        }
        var area = [layero.innerWidth(), layero.innerHeight()];
        var titHeight = layero.find(doms[1]).outerHeight() || 0;
        var btnHeight = layero.find('.' + doms[6]).outerHeight() || 0;
        function setHeight(elem) {
            elem = layero.find(elem);
            elem.height(area[1] - titHeight - btnHeight - 2 * (parseFloat(elem.css('padding')) | 0));
        }
        switch (config.type) {
            case 2:
                setHeight('iframe');
                break;
            default:
                if (config.area[1] === '') {
                    if (config.fix && area[1] >= win.height()) {
                        area[1] = win.height();
                        setHeight('.' + doms[5]);
                    }
                } else {
                    setHeight('.' + doms[5]);
                }
                break;
        }
        return _this;
    };

//计算坐标
    Class.pt.offset = function() {
        var _this = this, config = _this.config, layero = _this.layero;
        var area = [layero.outerWidth(), layero.outerHeight()];
        var type = typeof config.offset === 'object';
        _this.offsetTop = (win.height() - area[1]) / 2;
        _this.offsetLeft = (win.width() - area[0]) / 2;
        if (type) {
            _this.offsetTop = config.offset[0];
            _this.offsetLeft = config.offset[1] || _this.offsetLeft;
        } else if (config.offset !== 'auto') {
            _this.offsetTop = config.offset;
            if (config.offset === 'rb') { //右下角
                _this.offsetTop = win.height() - area[1];
                _this.offsetLeft = win.width() - area[0];
            }
        }
        if (!config.fix) {
            _this.offsetTop = /%$/.test(_this.offsetTop) ?
                    win.height() * parseFloat(_this.offsetTop) / 100
                    : parseFloat(_this.offsetTop);
            _this.offsetLeft = /%$/.test(_this.offsetLeft) ?
                    win.width() * parseFloat(_this.offsetLeft) / 100
                    : parseFloat(_this.offsetLeft);
            _this.offsetTop += win.scrollTop();
            _this.offsetLeft += win.scrollLeft();
        }
        layero.css({top: _this.offsetTop, left: _this.offsetLeft});
    };

//Tips
    Class.pt.tips = function() {
        var _this = this, config = _this.config, layero = _this.layero;
        var layArea = [layero.outerWidth(), layero.outerHeight()], follow = $(config.follow);
        if (!follow[0])
            follow = $('body');
        var goal = {
            width: follow.outerWidth(),
            height: follow.outerHeight(),
            top: follow.offset().top,
            left: follow.offset().left
        }, tipsG = layero.find('.qhz-layer-TipsG');

        var guide = config.tips[0];
        config.tips[1] || tipsG.remove();

        goal.autoLeft = function() {
            if (goal.left + layArea[0] - win.width() > 0) {
                goal.tipLeft = goal.left + goal.width - layArea[0];
                tipsG.css({right: 12, left: 'auto'});
            } else {
                goal.tipLeft = goal.left;
            }
            ;
        };
        //辨别tips的方位
        goal.where = [function() { //上        
                goal.autoLeft();
                goal.tipTop = goal.top - layArea[1] - 10;
                tipsG.removeClass('qhz-layer-TipsB').addClass('qhz-layer-TipsT').css('border-right-color', config.tips[1]);
            }, function() { //右
                goal.tipLeft = goal.left + goal.width + 10;
                goal.tipTop = goal.top;
                tipsG.removeClass('qhz-layer-TipsL').addClass('qhz-layer-TipsR').css('border-bottom-color', config.tips[1]);
            }, function() { //下
                goal.autoLeft();
                goal.tipTop = goal.top + goal.height + 10;
                tipsG.removeClass('qhz-layer-TipsT').addClass('qhz-layer-TipsB').css('border-right-color', config.tips[1]);
            }, function() { //左
                goal.tipLeft = goal.left - layArea[0] - 10;
                goal.tipTop = goal.top;
                tipsG.removeClass('qhz-layer-TipsR').addClass('qhz-layer-TipsL').css('border-bottom-color', config.tips[1]);
            }];
        goal.where[guide - 1]();

        /* 8*2为小三角形占据的空间 */
        if (guide === 1) {
            goal.top - (win.scrollTop() + layArea[1] + 8 * 2) < 0 && goal.where[2]();
        } else if (guide === 2) {
            win.width() - (goal.left + goal.width + layArea[0] + 8 * 2) > 0 || goal.where[3]()
        } else if (guide === 3) {
            (goal.top - win.scrollTop() + goal.height + layArea[1] + 8 * 2) - win.height() > 0 && goal.where[0]();
        } else if (guide === 4) {
            layArea[0] + 8 * 2 - goal.left > 0 && goal.where[1]()
        }

        layero.find('.' + doms[5]).css({
            'background-color': config.tips[1],
            'padding-right': (config.closeBtn ? '30px' : '')
        });
        layero.css({left: goal.tipLeft, top: goal.tipTop});
    };

    Class.pt.callback = function() {
        var _this = this, layero = _this.layero, config = _this.config;
        _this.openLayer();
        if (config.success) {
            if (config.type == 2) {
                layero.find('iframe').on('load', function() {
                    config.success(layero, _this.index);
                });
            } else {
                config.success(layero, _this.index);
            }
        }
        layer.ie6 && _this.IE6(layero);

        //按钮
        layero.find('.' + doms[6]).children('a').on('click', function() {
            var index = $(this).index();
            if (index === 0) {
                if (config.yes) {
                    config.yes(_this.index, layero)
                } else if (config['btn1']) {
                    config['btn1'](_this.index, layero)
                } else {
                    layer.close(_this.index);
                }
            } else {
                var close = config['btn' + (index + 1)] && config['btn' + (index + 1)](_this.index, layero);
                close === false || layer.close(_this.index);
            }
        });

        //取消
        function cancel() {
            var close = config.cancel && config.cancel(_this.index, layero);
            close === false || layer.close(_this.index);
        }

        //右上角关闭回调
        layero.find('.' + doms[7]).on('click', cancel);

        //点遮罩关闭
        if (config.shadeClose) {
            $('#qhz-layer-shade' + _this.index).on('click', function() {
                layer.close(_this.index);
            });
        }
        config.end && (ready.end[_this.index] = config.end);
        return _this;
    };

//for ie6 恢复select
    ready.reselect = function() {
        $.each($('select'), function(index, value) {
            var sthis = $(this);
            if (!sthis.parents('.' + doms[0])[0]) {
                (sthis.attr('layer') == 1 && $('.' + doms[0]).length < 1) && sthis.removeAttr('layer').show();
            }
            sthis = null;
        });
    };

    Class.pt.IE6 = function(layero) {
        var _this = this, _ieTop = layero.offset().top;

        //ie6的固定与相对定位
        function ie6Fix() {
            layero.css({top: _ieTop + (_this.config.fix ? win.scrollTop() : 0)});
        }
        ;
        ie6Fix();
        win.scroll(ie6Fix);

        //隐藏select
        $('select').each(function(index, value) {
            var sthis = $(this);
            if (!sthis.parents('.' + doms[0])[0]) {
                sthis.css('display') === 'none' || sthis.attr({'layer': '1'}).hide();
            }
            sthis = null;
        });
    };

//需依赖原型的对外方法
    Class.pt.openLayer = function() {
        var _this = this;

        //置顶当前窗口
        layer.zIndex = _this.config.zIndex;
        layer.setTop = function(layero) {
            var setZindex = function() {
                layer.zIndex++;
                layero.css('z-index', layer.zIndex + 1);
            };
            layer.zIndex = parseInt(layero[0].style.zIndex);
            layero.on('mousedown', setZindex);
            return layer.zIndex;
        };
    };

    ready.record = function(layero) {
        var area = [
            layero.outerWidth(),
            layero.outerHeight(),
            layero.position().top,
            layero.position().left + parseFloat(layero.css('margin-left'))
        ];
        layero.find('.qhz-layer-max').addClass('qhz-layer-maxmin');
        layero.attr({area: area});
    };

    ready.rescollbar = function(index) {
        if (doms.html.attr('layer-full') == index) {
            if (doms.html[0].style.removeProperty) {
                doms.html[0].style.removeProperty('overflow');
            } else {
                doms.html[0].style.removeAttribute('overflow');
            }
            doms.html.removeAttr('layer-full');
        }
    };
    /** 内置成员 */
    window.layer = layer;

//获取子iframe的DOM
    layer.getChildFrame = function(selector, index) {
        index = index || $('.' + doms[4]).attr('times');
        return $('#' + doms[0] + index).find('iframe').contents().find(selector);
    };

//得到当前iframe层的索引，子iframe时使用
    layer.getFrameIndex = function(name) {
        return $('#' + name).parents('.' + doms[4]).attr('times');
    };

//iframe层自适应宽高
    layer.iframeAuto = function(index) {
        if (!index)
            return;
        var heg = layer.getChildFrame('html', index).outerHeight();
        var layero = $('#' + doms[0] + index);
        var titHeight = layero.find(doms[1]).outerHeight() || 0;
        var btnHeight = layero.find('.' + doms[6]).outerHeight() || 0;
        layero.css({height: heg + titHeight + btnHeight});
        layero.find('iframe').css({height: heg});
    };

//重置iframe url
    layer.iframeSrc = function(index, url) {
        $('#' + doms[0] + index).find('iframe').attr('src', url);
    };

//设定层的样式
    layer.style = function(index, options) {
        var layero = $('#' + doms[0] + index), type = layero.attr('type');
        var titHeight = layero.find(doms[1]).outerHeight() || 0;
        var btnHeight = layero.find('.' + doms[6]).outerHeight() || 0;
        if (type === ready.type[1] || type === ready.type[2]) {
            layero.css(options);
            if (type === ready.type[2]) {
                layero.find('iframe').css({
                    height: parseFloat(options.height) - titHeight - btnHeight
                });
            }
        }
    };

//改变title
    layer.title = function(name, index) {
        var title = $('#' + doms[0] + (index || layer.index)).find(doms[1]);
        title.html(name);
    };

//关闭layer总方法
    layer.close = function(index) {
        var layero = $('#' + doms[0] + index), type = layero.attr('type');
        if (!layero[0])
            return;
        if (type === ready.type[1] && layero.attr('conType') === 'object') {
            layero.children(':not(.' + doms[5] + ')').remove();
            for (var i = 0; i < 2; i++) {
                layero.find('.qhz-layer-wrap').unwrap().hide();
            }
        } else {
            //低版本IE 回收 iframe
            if (type === ready.type[2]) {
                try {
                    var iframe = $('#' + doms[4] + index)[0];
                    iframe.contentWindow.document.write('');
                    iframe.contentWindow.close();
                    layero.find('.' + doms[5])[0].removeChild(iframe);
                } catch (e) {
                }
            }
            layero[0].innerHTML = '';
            layero.remove();
        }
        $('#qhz-layer-moves, #qhz-layer-shade' + index).remove();
        layer.ie6 && ready.reselect();
        ready.rescollbar(index);
        $(document).off('keydown', ready.enter);
        typeof ready.end[index] === 'function' && ready.end[index]();
        delete ready.end[index];
    };

//关闭所有层
    layer.closeAll = function(type) {
        $.each($('.' + doms[0]), function() {
            var othis = $(this);
            var is = type ? (othis.attr('type') === type) : 1;
            is && layer.close(othis.attr('times'));
            is = null;
        });
    };
//系统prompt
    layer.prompt = function(options, yes) {
        options = options || {};
        if (typeof options === 'function')
            yes = options;
        var prompt, content = options.formType == 2 ? '<textarea class="qhz-layer-input">' + (options.value || '') + '</textarea>' : function() {
            return '<input type="' + (options.formType == 1 ? 'password' : 'text') + '" class="qhz-layer-input" value="' + (options.value || '') + '">';
        }();
        return layer.open($.extend({
            btn: ['&#x786E;&#x5B9A;', '&#x53D6;&#x6D88;'],
            content: content,
            skin: 'qhz-layer-prompt' + skin('prompt'),
            success: function(layero) {
                prompt = layero.find('.qhz-layer-input');
                prompt.focus();
            }, yes: function(index) {
                var value = prompt.val();
                if (value === '') {
                    prompt.focus();
                } else if (value.length > (options.maxlength || 500)) {
                    layer.tips('&#x6700;&#x591A;&#x8F93;&#x5165;' + (options.maxlength || 500) + '&#x4E2A;&#x5B57;&#x6570;', prompt, {tips: 1});
                } else {
                    yes && yes(value, index, prompt);
                }
            }
        }, options));
    };
//主入口
    ready.run = function() {
        $ = jQuery;
        win = $(window);
        doms.html = $('html');
        layer.open = function(deliver) {
            var o = new Class(deliver);
            return o.index;
        };
    };
    'function' === typeof define ? define(function() {
        ready.run();
        return layer;
    }) : function() {
        ready.run();
    }();
}(window);