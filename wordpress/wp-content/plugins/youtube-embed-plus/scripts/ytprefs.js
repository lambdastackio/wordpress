(function (window, $)
{
    window._EPYT_ = window._EPYT_ || {
        ajaxurl: "\/wp-admin\/admin-ajax.php",
        security: "",
        gallery_scrolloffset: 100,
        eppathtoscripts: "\/wp-content\/plugins\/youtube-embed-plus\/scripts\/",
        epresponsiveselector: ["iframe.__youtube_prefs_widget__"],
        epdovol: true,
        evselector: 'iframe.__youtube_prefs__[src], iframe[src*="youtube.com/embed/"], iframe[src*="youtube-nocookie.com/embed/"]',
        usingdefault: true
    };

    if (!document.querySelectorAll)
    {
        document.querySelectorAll = function (selector)
        {
            var doc = document, head = doc.documentElement.firstChild, styleTag = doc.createElement('STYLE');
            head.appendChild(styleTag);
            doc.__qsaels = [];
            styleTag.styleSheet.cssText = selector + "{x:expression(document.__qsaels.push(this))}";
            window.scrollBy(0, 0);
            return doc.__qsaels;
        };
    }

    if (typeof window._EPADashboard_ === 'undefined')
    {
        window._EPADashboard_ =
                {
                    initStarted: false,
                    checkCount: 0,
                    onPlayerReady: function (event)
                    {
                        try
                        {
                            if (typeof _EPYT_.epdovol !== "undefined" && _EPYT_.epdovol)
                            {
                                var vol = parseInt(event.target.getIframe().getAttribute("data-vol"));
                                if (!isNaN(vol))
                                {
                                    if (vol === 0)
                                    {
                                        event.target.mute();
                                    }
                                    else
                                    {
                                        if (event.target.isMuted())
                                        {
                                            event.target.unMute();
                                        }
                                        event.target.setVolume(vol);
                                    }
                                }
                            }
                        }
                        catch (err) {
                        }

                        try {
                            var $ifm = $(event.target.getIframe());
                            if ($ifm.hasClass('epyt-lbif') && $ifm.closest('.lity-content').length)
                            {
//                                var thumbplay = $container.find('.epyt-pagebutton').first().data('thumbplay');
//                                if (thumbplay !== '0' && thumbplay !== 0)
                                event.target.playVideo();
                            }
                        }
                        catch (err2)
                        {
                        }


                        setTimeout(function ()
                        {
                            try
                            {
                                var ytid = _EPADashboard_.justid(event.target.getVideoUrl());
                                _EPADashboard_.jp("ytid=" + ytid);

                            }
                            catch (err)
                            {
                            }
                        }, 1700);
                    },
                    onPlayerStateChange: function (event)
                    {
                        var ifm = event.target.getIframe();
                        if (event.data === YT.PlayerState.PLAYING && event.target.ponce !== true && ifm.src.indexOf('autoplay=1') === -1)
                        {
                            event.target.ponce = true;

                            try
                            {
                                var ytid = _EPADashboard_.justid(event.target.getVideoUrl());
                                _EPADashboard_.jp("ytid=" + ytid + "&p=1");

                            } catch (err)
                            {
                            }
                        }

                        var $gallery = $(ifm).closest('.epyt-gallery');
                        if (!$gallery.length)
                        {
                            $gallery = $('#' + $(ifm).data('epytgalleryid'));
                        }
                        if ($gallery.length)
                        {
                            var autonext = $gallery.find('.epyt-pagebutton').first().data('autonext') == '1';
                            if (autonext && event.data === YT.PlayerState.ENDED)
                            {
                                var $currvid = $gallery.find('.epyt-current-video');
                                if (!$currvid.length)
                                {
                                    $currvid = $gallery.find('.epyt-gallery-thumb').first();
                                }
                                var $nextvid = $currvid.find(' ~ .epyt-gallery-thumb').first();
                                var $lityopen = $('div.lity-wrap[data-lity-close]');
                                if ($nextvid.length)
                                {
                                    if ($lityopen.length)
                                    {
                                        _EPADashboard_.lb.close();
                                        setTimeout(function () {
                                            $nextvid.click();
                                        }, 1000);

                                    }
                                    else
                                    {
                                        $nextvid.click();
                                    }
                                }
                                else
                                {
                                    if ($lityopen.length)
                                    {
                                        _EPADashboard_.lb.close();
                                        setTimeout(function () {
                                            $gallery.find('.epyt-pagebutton.epyt-next[data-pagetoken!=""][data-pagetoken]').first().click();
                                        }, 1000);

                                    }
                                    else
                                    {
                                        $gallery.find('.epyt-pagebutton.epyt-next[data-pagetoken!=""][data-pagetoken]').first().click();
                                    }

                                }
                            }
                        }

                    },
                    justid: function (s)
                    {
                        return new RegExp("[\\?&]v=([^&#]*)").exec(s)[1];
                    },
                    dynsetupevents: function ()
                    {
                        if (typeof this.epytsetupdone === 'undefined')
                        {
                            return _EPADashboard_.setupevents(this.id);
                        }
                    },
                    setupevents: function (iframeid)
                    {
                        _EPADashboard_.log('Setting up YT API events: ' + iframeid);
                        if (YT.loaded)
                        {
                            var thisvid = document.getElementById(iframeid);
                            thisvid.epytsetupdone = true;
                            return new YT.Player(iframeid, {
                                events: {
                                    "onReady": _EPADashboard_.onPlayerReady,
                                    "onStateChange": _EPADashboard_.onPlayerStateChange
                                }
                            });
                        }
                    },
                    jp: function (q)
                    {
                        // debug
                    },
                    apiInit: function () {
                        setTimeout(function ()
                        {
                            if (typeof (YT) !== 'undefined')
                            {
                                _EPADashboard_.initStarted = true;
                                var __allytifr = document.querySelectorAll(_EPYT_.evselector);
                                for (var i = 0; i < __allytifr.length; i++)
                                {
                                    if (!__allytifr[i].hasAttribute("id"))
                                    {
                                        __allytifr[i].id = "_dytid_" + Math.round(Math.random() * 8999 + 1000);
                                    }
                                    _EPADashboard_.setupevents(__allytifr[i].id);
                                }




                            }
                        }, 300);
                    },
                    log: function (msg) {
                        try
                        {
                            console.log(msg);
                        }
                        catch (err) {
                        }
                    },
                    doubleCheck: function () {
                        _EPADashboard_.checkInterval = setInterval(function () {
                            _EPADashboard_.checkCount++;
                            if (_EPADashboard_.checkCount >= 5 || _EPADashboard_.initStarted) {
                                clearInterval(_EPADashboard_.checkInterval);
                            }
                            else {
                                _EPADashboard_.apiInit();
                                _EPADashboard_.log('YT API init');
                            }

                        }, 1000);
                    },
                    selectText: function (ele) {
                        if (document.selection) {
                            var range = document.body.createTextRange();
                            range.moveToElementText(ele);
                            range.select();
                        } else if (window.getSelection) {
                            var range = document.createRange();
                            range.selectNode(ele);
                            window.getSelection().addRange(range);
                        }
                    },
                    lb: typeof (lity) !== 'undefined' ? lity() : function () {
                    }

                };
    }

    if (typeof window.YT === 'undefined')
    {
        var iapi = document.createElement('script');
        iapi.src = "//www.youtube.com/iframe_api";
        iapi.type = "text/javascript";
        document.getElementsByTagName('head')[0].appendChild(iapi);
    }
    else if (YT.loaded)
    {
        _EPADashboard_.apiInit();
        _EPADashboard_.log('YT API available');
    }

    _EPADashboard_.doubleCheck();

    window.onYouTubeIframeAPIReady = typeof window.onYouTubeIframeAPIReady !== 'undefined' ? window.onYouTubeIframeAPIReady : function ()
    {
        _EPADashboard_.apiInit();
        _EPADashboard_.log('YT API ready');
    };


})(window, jQuery);



(function ($) {
    $(document).ready(function () {
        $('.epyt-gallery').each(function () {
            var $container = $(this);
            var $iframe = $(this).find('iframe').first();
            var contentlbid = 'content' + $iframe.attr('id');
            $container.find('.lity-hide').attr('id', contentlbid);
            var initSrc = $iframe.attr('src');
            if (!initSrc)
            {
                initSrc = $iframe.data('ep-src');
            }
            var firstId = $(this).find('.epyt-gallery-list .epyt-gallery-thumb').first().data('videoid');
            initSrc = initSrc.replace(firstId, 'GALLERYVIDEOID');
            $iframe.data('ep-gallerysrc', initSrc);


            var $listgallery = $container.find('.epyt-gallery-list');
            var pagenumsalign = function () {
                try {
                    if ($listgallery.hasClass('epyt-gallery-style-carousel'))
                    {
                        var thumbheight = $($listgallery.find('.epyt-gallery-thumb').get(0)).height();
                        var topval = thumbheight / 2;
                        var $pagenums = $listgallery.find('.epyt-pagination:first-child .epyt-pagenumbers');
                        $pagenums.css('top', (topval + 15) + "px");
                    }
                }
                catch (e) {
                }
            };
            setTimeout(function () {
                pagenumsalign();
            }, 300);
            $(window).resize(pagenumsalign);


            $container.on('click', '.epyt-gallery-list .epyt-gallery-thumb', function () {
                $container.find('.epyt-gallery-list .epyt-gallery-thumb').removeClass('epyt-current-video');
                $(this).addClass('epyt-current-video');
                var vid = $(this).data('videoid');
                $container.data('currvid', vid);
                var vidSrc = $iframe.data('ep-gallerysrc').replace('GALLERYVIDEOID', vid);

                var thumbplay = $container.find('.epyt-pagebutton').first().data('thumbplay');
                if (thumbplay !== '0' && thumbplay !== 0)
                {
                    if (vidSrc.indexOf('autoplay') > 0)
                    {
                        vidSrc = vidSrc.replace('autoplay=0', 'autoplay=1');
                    }
                    else
                    {
                        vidSrc += '&autoplay=1';
                    }
                }


                if ($container.hasClass('epyt-lb'))
                {
                    _EPADashboard_.lb('#' + contentlbid);

                    vidSrc = vidSrc.replace('autoplay=1', 'autoplay=0');
                    $iframe.attr('src', vidSrc);
                    var player = _EPADashboard_.setupevents($iframe.attr('id'));

                    $('.lity-close').focus();

                }
                else
                {
                    $('html, body').animate({
                        scrollTop: $iframe.offset().top - parseInt(_EPYT_.gallery_scrolloffset)
                    }, 500, function () {
                        $iframe.attr('src', vidSrc);
                        _EPADashboard_.setupevents($iframe.attr('id'));
                    });

                }

            }).on('keydown', '.epyt-gallery-list .epyt-gallery-thumb, .epyt-pagebutton', function (e) {
                var code = e.which;
                if ((code === 13) || (code === 32)) {
                    e.preventDefault();
                    $(this).click();

                }
            });

            $container.on('mouseenter', '.epyt-gallery-list .epyt-gallery-thumb', function () {
                $(this).addClass('hover');
                if ($listgallery.hasClass('epyt-gallery-style-carousel') && $container.find('.epyt-pagebutton').first().data('showtitle') == 1)
                {
                    $container.find('.epyt-pagenumbers').addClass('hide');
                    var ttl = $(this).find('.epyt-gallery-notitle span').text();
                    $container.find('.epyt-gallery-rowtitle').text(ttl).addClass('hover');
                }
            });

            $container.on('mouseleave', '.epyt-gallery-list .epyt-gallery-thumb', function () {
                $(this).removeClass('hover');
                if ($listgallery.hasClass('epyt-gallery-style-carousel') && $container.find('.epyt-pagebutton').first().data('showtitle') == 1)
                {
                    $container.find('.epyt-gallery-rowtitle').text('').removeClass('hover');
                    if ($container.find('.epyt-pagebutton[data-pagetoken!=""]').length > 0)
                    {
                        $container.find('.epyt-pagenumbers').removeClass('hide');
                    }
                }
            });

            $container.on('click', '.epyt-pagebutton', function () {
                var pageData = {
                    action: 'my_embedplus_gallery_page',
                    security: _EPYT_.security,
                    options: {
                        playlistId: $(this).data('playlistid'),
                        pageToken: $(this).data('pagetoken'),
                        pageSize: $(this).data('pagesize'),
                        columns: $(this).data('columns'),
                        showTitle: $(this).data('showtitle'),
                        showPaging: $(this).data('showpaging'),
                        autonext: $(this).data('autonext'),
                        style: $(this).data('style'),
                        thumbcrop: $(this).data('thumbcrop'),
                        thumbplay: $(this).data('thumbplay')
                    }
                };
                if ($(this).data('showdsc'))
                {
                    pageData.options.showDsc = $(this).data('showdsc');
                }

                var forward = $(this).hasClass('epyt-next');
                var currpage = parseInt($container.data('currpage') + "");
                currpage += forward ? 1 : -1;
                $container.data('currpage', currpage);
                $container.find('.epyt-gallery-list').addClass('epyt-loading');

                $.post(_EPYT_.ajaxurl, pageData, function (response) {
                    $container.find('.epyt-gallery-list').html(response);
                    $container.find('.epyt-current').each(function () {
                        $(this).text($container.data('currpage'));
                    });
                    $container.find('.epyt-gallery-thumb[data-videoid="' + $container.data('currvid') + '"]').addClass('epyt-current-video');

                    if ($container.find('.epyt-pagebutton').first().data('autonext') == '1')
                    {
                        $container.find('.epyt-gallery-thumb').first().click();
                    }

                })
                        .fail(function () {
                            alert('Sorry, there was an error loading the next page.');
                        })
                        .always(function () {
                            $container.find('.epyt-gallery-list').removeClass('epyt-loading');
                            pagenumsalign();
                        });

            });

        });
    });
})(jQuery);
