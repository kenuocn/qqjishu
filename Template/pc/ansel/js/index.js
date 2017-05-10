// Lazy Load - jQuery plugin for lazy loading images Version: 1.9.0
!function(a,b,c,d){var e=a(b);a.fn.lazyload=function(f){function g(){var b=0;i.each(function(){var c=a(this);if(!j.skip_invisible||c.is(":visible"))if(a.abovethetop(this,j)||a.leftofbegin(this,j));else if(a.belowthefold(this,j)||a.rightoffold(this,j)){if(++b>j.failure_limit)return!1}else c.trigger("appear"),b=0})}var h,i=this,j={threshold:0,failure_limit:0,event:"scroll",effect:"show",container:b,data_attribute:"original",skip_invisible:!0,appear:null,load:null,placeholder:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"};return f&&(d!==f.failurelimit&&(f.failure_limit=f.failurelimit,delete f.failurelimit),d!==f.effectspeed&&(f.effect_speed=f.effectspeed,delete f.effectspeed),a.extend(j,f)),h=j.container===d||j.container===b?e:a(j.container),0===j.event.indexOf("scroll")&&h.bind(j.event,function(){return g()}),this.each(function(){var b=this,c=a(b);b.loaded=!1,(c.attr("src")===d||c.attr("src")===!1)&&c.attr("src",j.placeholder),c.one("appear",function(){if(!this.loaded){if(j.appear){var d=i.length;j.appear.call(b,d,j)}a("<img />").bind("load",function(){var d=c.data(j.data_attribute);c.hide(),c.is("img")?c.attr("src",d):c.css("background-image","url('"+d+"')"),c[j.effect](j.effect_speed),b.loaded=!0;var e=a.grep(i,function(a){return!a.loaded});if(i=a(e),j.load){var f=i.length;j.load.call(b,f,j)}}).attr("src",c.data(j.data_attribute))}}),0!==j.event.indexOf("scroll")&&c.bind(j.event,function(){b.loaded||c.trigger("appear")})}),e.bind("resize",function(){g()}),/iphone|ipod|ipad.*os 5/gi.test(navigator.appVersion)&&e.bind("pageshow",function(b){b.originalEvent&&b.originalEvent.persisted&&i.each(function(){a(this).trigger("appear")})}),a(c).ready(function(){g()}),this},a.belowthefold=function(c,f){var g;return g=f.container===d||f.container===b?(b.innerHeight?b.innerHeight:e.height())+e.scrollTop():a(f.container).offset().top+a(f.container).height(),g<=a(c).offset().top-f.threshold},a.rightoffold=function(c,f){var g;return g=f.container===d||f.container===b?e.width()+e.scrollLeft():a(f.container).offset().left+a(f.container).width(),g<=a(c).offset().left-f.threshold},a.abovethetop=function(c,f){var g;return g=f.container===d||f.container===b?e.scrollTop():a(f.container).offset().top,g>=a(c).offset().top+f.threshold+a(c).height()},a.leftofbegin=function(c,f){var g;return g=f.container===d||f.container===b?e.scrollLeft():a(f.container).offset().left,g>=a(c).offset().left+f.threshold+a(c).width()},a.inviewport=function(b,c){return!(a.rightoffold(b,c)||a.leftofbegin(b,c)||a.belowthefold(b,c)||a.abovethetop(b,c))},a.extend(a.expr[":"],{"below-the-fold":function(b){return a.belowthefold(b,{threshold:0})},"above-the-top":function(b){return!a.belowthefold(b,{threshold:0})},"right-of-screen":function(b){return a.rightoffold(b,{threshold:0})},"left-of-screen":function(b){return!a.rightoffold(b,{threshold:0})},"in-viewport":function(b){return a.inviewport(b,{threshold:0})},"above-the-fold":function(b){return!a.belowthefold(b,{threshold:0})},"right-of-fold":function(b){return a.rightoffold(b,{threshold:0})},"left-of-fold":function(b){return!a.rightoffold(b,{threshold:0})}})}(jQuery,window,document);

!function(){var a=jQuery.event.special,b="D"+ +new Date,c="D"+(+new Date+1);a.scrollstart={setup:function(){var c,d=function(b){var d=this,e=arguments;c?clearTimeout(c):(b.type="scrollstart",jQuery.event.dispatch.apply(d,e)),c=setTimeout(function(){c=null},a.scrollstop.latency)};jQuery(this).bind("scroll",d).data(b,d)},teardown:function(){jQuery(this).unbind("scroll",jQuery(this).data(b))}},a.scrollstop={latency:300,setup:function(){var b,d=function(c){var d=this,e=arguments;b&&clearTimeout(b),b=setTimeout(function(){b=null,c.type="scrollstop",jQuery.event.dispatch.apply(d,e)},a.scrollstop.latency)};jQuery(this).bind("scroll",d).data(c,d)},teardown:function(){jQuery(this).unbind("scroll",jQuery(this).data(c))}}}();


// Infinite Ajax Scroll, a jQuery plugin 1.0.2
(function(e){"use strict";Date.now=Date.now||function(){return+(new Date)},e.ias=function(t){function u(){var t;i.onChangePage(function(e,t,r){s&&s.setPage(e,r),n.onPageChange.call(this,e,r,t)});if(n.triggerPageThreshold>0)a();else if(e(n.next).attr("href")){var u=r.getCurrentScrollOffset(n.scrollContainer);E(function(){p(u)})}return s&&s.havePage()&&(l(),t=s.getPage(),r.forceScrollTop(function(){var n;t>1?(v(t),n=h(!0),e("html, body").scrollTop(n)):a()})),o}function a(){c(),n.scrollContainer.scroll(f)}function f(){var e,t;e=r.getCurrentScrollOffset(n.scrollContainer),t=h(),e>=t&&(m()>=n.triggerPageThreshold?(l(),E(function(){p(e)})):p(e))}function l(){n.scrollContainer.unbind("scroll",f)}function c(){e(n.pagination).hide()}function h(t){var r,i;return r=e(n.container).find(n.item).last(),r.size()===0?0:(i=r.offset().top+r.height(),t||(i+=n.thresholdMargin),i)}function p(t,r){var s;s=e(n.next).attr("href");if(!s)return n.noneleft&&e(n.container).find(n.item).last().after(n.noneleft),l();if(n.beforePageChange&&e.isFunction(n.beforePageChange)&&n.beforePageChange(t,s)===!1)return;i.pushPages(t,s),l(),y(),d(s,function(t,i){var o=n.onLoadItems.call(this,i),u;o!==!1&&(e(i).hide(),u=e(n.container).find(n.item).last(),u.after(i),e(i).fadeIn()),s=e(n.next,t).attr("href"),e(n.pagination).replaceWith(e(n.pagination,t)),b(),c(),s?a():l(),n.onRenderComplete.call(this,i),r&&r.call(this)})}function d(t,r,i){var s=[],o,u=Date.now(),a,f;i=i||n.loaderDelay,e.get(t,null,function(t){o=e(n.container,t).eq(0),0===o.length&&(o=e(t).filter(n.container).eq(0)),o&&o.find(n.item).each(function(){s.push(this)}),r&&(f=this,a=Date.now()-u,a<i?setTimeout(function(){r.call(f,t,s)},i-a):r.call(f,t,s))},"html")}function v(t){var n=h(!0);n>0&&p(n,function(){l(),i.getCurPageNum(n)+1<t?(v(t),e("html,body").animate({scrollTop:n},400,"swing")):(e("html,body").animate({scrollTop:n},1e3,"swing"),a())})}function m(){var e=r.getCurrentScrollOffset(n.scrollContainer);return i.getCurPageNum(e)}function g(){var t=e(".ias_loader");return t.size()===0&&(t=e('<div class="ias_loader">'+n.loader+"</div>"),t.hide()),t}function y(){var t=g(),r;n.customLoaderProc!==!1?n.customLoaderProc(t):(r=e(n.container).find(n.item).last(),r.after(t),t.fadeIn())}function b(){var e=g();e.remove()}function w(t){var r=e(".ias_trigger");return r.size()===0&&(r=e('<div class="ias_trigger"><a href="#">'+n.trigger+"</a></div>"),r.hide()),e("a",r).unbind("click").bind("click",function(){return S(),t.call(),!1}),r}function E(t){var r=w(t),i;n.customTriggerProc!==!1?n.customTriggerProc(r):(i=e(n.container).find(n.item).last(),i.after(r),r.fadeIn())}function S(){var e=w();e.remove()}var n=e.extend({},e.ias.defaults,t),r=new e.ias.util,i=new e.ias.paging(n.scrollContainer),s=n.history?new e.ias.history:!1,o=this;u()},e.ias.defaults={container:"#container",scrollContainer:e(window),item:".item",pagination:"#pagination",next:".next",noneleft:!1,loader:'<img src="images/loader.gif"/>',loaderDelay:600,triggerPageThreshold:3,trigger:"Load more items",thresholdMargin:0,history:!0,onPageChange:function(){},beforePageChange:function(){},onLoadItems:function(){},onRenderComplete:function(){},customLoaderProc:!1,customTriggerProc:!1},e.ias.util=function(){function i(){e(window).load(function(){t=!0})}var t=!1,n=!1,r=this;i(),this.forceScrollTop=function(i){e("html,body").scrollTop(0),n||(t?(i.call(),n=!0):setTimeout(function(){r.forceScrollTop(i)},1))},this.getCurrentScrollOffset=function(e){var t,n;return e.get(0)===window?t=e.scrollTop():t=e.offset().top,n=e.height(),t+n}},e.ias.paging=function(){function s(){e(window).scroll(o)}function o(){var t,s,o,f,l;t=i.getCurrentScrollOffset(e(window)),s=u(t),o=a(t),r!==s&&(f=o[0],l=o[1],n.call({},s,f,l)),r=s}function u(e){for(var n=t.length-1;n>0;n--)if(e>t[n][0])return n+1;return 1}function a(e){for(var n=t.length-1;n>=0;n--)if(e>t[n][0])return t[n];return null}var t=[[0,document.location.toString()]],n=function(){},r=1,i=new e.ias.util;s(),this.getCurPageNum=function(t){return t=t||i.getCurrentScrollOffset(e(window)),u(t)},this.onChangePage=function(e){n=e},this.pushPages=function(e,n){t.push([e,n])}},e.ias.history=function(){function n(){t=!!(window.history&&history.pushState&&history.replaceState),t=!1}var e=!1,t=!1;n(),this.setPage=function(e,t){this.updateState({page:e},"",t)},this.havePage=function(){return this.getState()!==!1},this.getPage=function(){var e;return this.havePage()?(e=this.getState(),e.page):1},this.getState=function(){var e,n,r;if(t){n=history.state;if(n&&n.ias)return n.ias}else{e=window.location.hash.substring(0,7)==="#/page/";if(e)return r=parseInt(window.location.hash.replace("#/page/",""),10),{page:r}}return!1},this.updateState=function(t,n,r){e?this.replaceState(t,n,r):this.pushState(t,n,r)},this.pushState=function(n,r,i){var s;t?history.pushState({ias:n},r,i):(s=n.page>0?"#/page/"+n.page:"",window.location.hash=s),e=!0},this.replaceState=function(e,n,r){t?history.replaceState({ias:e},n,r):this.pushState(e,n,r)}}})(jQuery);



+(function($) {
    var el_carousel = $('.carousel')

    el_carousel.carousel({
        interval: 4000
    })

    if( el_carousel.length && $('body').hasClass('focusslide_s_m') ){
        var mc = new Hammer(el_carousel[0]);

        mc.on("panleft panright swipeleft swiperight", function(ev) {
            if( ev.type == 'swipeleft' || ev.type == 'panleft' ){
                el_carousel.carousel('next')
            }else if( ev.type == 'swiperight' || ev.type == 'panright' ){
                el_carousel.carousel('prev')
            }
            // el_carousel[0].textContent = ev.type +" gesture detected.";
        });
    }

    /* 
     * 
     * ====================================================================================================
    */
    $('.navmore').on('click', function(){
        $('body').toggleClass('navshows');
    })

    $('body').append('<div class="rollto"><a href="javascript:;"></a></div>')

    // lazy avatar
    $('.content .avatar').lazyload({
        placeholder: jui.uri + '/images/avatar.jpg',
        event: 'scrollstop'
    });

    $('.sidebar .avatar').lazyload({
        placeholder: jui.uri + '/images/avatar.jpg',
        event: 'scrollstop'
    });

    $('.content .thumb').lazyload({
        placeholder: jui.uri + '/images/thumbnail.png',
        event: 'scrollstop'
    });

    $('.sidebar .thumb').lazyload({
        placeholder: jui.uri + '/images/thumbnail.png',
        event: 'scrollstop'
    });

    $('.content .wp-smiley').lazyload({
        event: 'scrollstop'
    });

    $('.sidebar .wp-smiley').lazyload({
        event: 'scrollstop'
    });


    var elments = {
        sidebar: $('.sidebar'),
        footer: $('.footer')
    }

    $('.feed-weixin').popover({
        placement: $('body').hasClass('ui-navtop')?'bottom':'right',
        trigger: 'hover',
        container: 'body',
        html: true
    })

    if( Number(jui.ajaxpager) ){
        $.ias({
            triggerPageThreshold: jui.ajaxpager?Number(jui.ajaxpager)+1:5,
            history: false,
            container : '.content',
            item: '.excerpt',
            pagination: '.pagination',
            next: '.next-page a',
            loader: '<div class="pagination-loading"><img src="'+jui.uri+'/images/ajax-loader.gif"></div>',
            trigger: 'More',
            onRenderComplete: function() {
                $('.excerpt .thumb').lazyload({
                    placeholder: jui.uri + '/images/thumbnail.png',
                    threshold: 400
                });
            }
        });
    }


    /* 
     * page search
     * ====================================================
    */
    if( $('body').hasClass('search-results') ){
        var val = $('.search-form .form-control').val()
        var reg = eval('/'+val+'/i')
        $('.excerpt h2 a, .excerpt .note').each(function(){
            $(this).html( $(this).text().replace(reg, function(w){ return '<span style="color:#FF5E52;">'+w+'</span>' }) )
        })
    }

    if( elments.sidebar.length && jui.roll ){

        jui.roll = jui.roll.split(' ')

    	var h1 = 20, h2 = 40, h3 = 20

    	if( $('body').hasClass('ui-navtop') ){
    		h1 = 100, h2 = 120
    	}

        var rollFirst = elments.sidebar.find('.widget:eq('+(Number(jui.roll[0])-1)+')')
        var sheight = rollFirst[0].offsetHeight
        rollFirst.on('affix-top.bs.affix', function(){
            rollFirst.css({top: 0}) 
            sheight = rollFirst[0].offsetHeight

            for (var i = 1; i < jui.roll.length; i++) {
                var item = Number(jui.roll[i])-1
                var current = elments.sidebar.find('.widget:eq('+item+')')
                current.removeClass('affix').css({top: 0})
            };
        })

        rollFirst.on('affix.bs.affix', function(){
            rollFirst.css({top: h1}) 

            for (var i = 1; i < jui.roll.length; i++) {
                var item = Number(jui.roll[i])-1
                var current = elments.sidebar.find('.widget:eq('+item+')')
                current.addClass('affix').css({top: sheight+h2})
                sheight += current[0].offsetHeight + h3
            };
        })

        rollFirst.affix({
            offset: {
                top: elments.sidebar.height(),
                bottom: (elments.footer.height()||0) + 10
            }
        })
    }

    $('.excerpt header small').each(function() {
        $(this).tooltip({
            container: 'body',
            title: '此文有 ' + $(this).text() + '张 图片'
        })
    })

    $('.article-tags a, .post-tags a').each(function() {
        $(this).tooltip({
            container: 'body',
            placement: 'bottom',
            title: '查看关于 ' + $(this).text() + ' 的文章'
        })
    })

    $('.cat').each(function() {
        $(this).tooltip({
            container: 'body',
            title: '查看关于 ' + $(this).text() + ' 的文章'
        })
    })

    $('.widget_tags a').tooltip({
        container: 'body'
    })

    $('.readers a, .widget_comments a').tooltip({
        container: 'body',
        placement: 'top'
    })

    $('.article-meta li:eq(1) a').tooltip({
        container: 'body',
        placement: 'bottom'
    })

    $('.rollto a').on('click', function() {
        scrollTo()
    })

    $(window).scroll(function() {
        var scroller = $('.rollto');
        document.documentElement.scrollTop + document.body.scrollTop > 200 ? scroller.fadeIn() : scroller.fadeOut();
    })

    /* functions
     * ====================================================
     */
    function scrollTo(name, speed) {
        if (!speed) speed = 300
        if (!name) {
            $('html,body').animate({
                scrollTop: 0
            }, speed)
        } else {
            if ($(name).length > 0) {
                $('html,body').animate({
                    scrollTop: $(name).offset().top
                }, speed)
            }
        }
    }

})(jQuery)
//登陆
function login(obj){
	var username = $("#login-uname").val();
	if(!username){show_login_error('请输入账号');return;}
	var password = $("#login-pwd").val();
	if(!password){show_login_error('请输入密码');return;}
	var url=$("#login-form").attr('action');	
	$.ajax({  
		url : url,  
		type : "post",  
		dataType : "json",  
		data: {username:username,password:password},  
		success : function(res) {  
			if(res.status) {   
				reloadPage(window);
			} else {  
				show_login_error(res.info);
			}  
		}  
	});  
}
//注册
function reg(obj){
	var username = $("#reg-uname").val();
	if(!username){
		show_reg_error("请输入用户名");return false;	
	}else if( /^\d.*$/.test( username ) ){
		show_reg_error("用户名不能以数字开头");return false;	
	}else if(username.length<6 || username.length>18 ){
		show_reg_error("合法长度为6-18个字符");return false;	
	}else if(! /^\w+$/.test( username ) ){
		show_reg_error("用户名只能包含_,英文字母，数字");return false;	
	}else if(! /^([a-z]|[A-Z])[0-9a-zA-Z_]+$/.test( username ) ){
		show_reg_error("用户名只能英文字母开头");return false;	
	}else if(!(/[0-9a-zA-Z]+$/.test( username ))){
		show_reg_error("用户名只能英文字母或数字结尾");return false;	
	}
	var email = $("#reg-email").val();
	var email_reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
	if(!email){
		show_reg_error('请输入刚才已验证的邮箱');return false;
	}else if(!email_reg.test(email)){
		show_reg_error('邮箱格式错误');return false;	
	}
	var code = $("#reg-email-code").val();
	if(!code){
		show_reg_error('请输入邮箱验证码');return false;
	}else if(code.length!=6){
		show_reg_error('邮箱验证码为6位字母加数字');return false;	
	}
	var password = $("#reg-pwd").val();
	if(!password){
		show_reg_error('请输入密码');return false;
	}else if(username.length<6){
		show_reg_error('密码必须大于6位');return false;	
	}
	var url=$("#reg-form").attr('action');	
	$.ajax({  
		url : url,  
		type : "post",  
		dataType : "json",  
		data: {username:username,email:email,code:code,password:password},  
		success : function(res) {  
			if(res.status) {   
				layer.msg(""+res.info+"....",{icon:1,time:1000},function(){
					reloadPage(window); 
				});
			} else {  
				show_reg_error(res.info);
			}  
		}  
	});  
}
//显示登陆错误
function show_login_error(text){
	$('.login-error').show().html('<i class="fa fa-warning"></i> '+text);
	setTimeout(function(){
    	$(".login-error").fadeOut();
	}, 2000);	
}
//显示注册错误
function show_reg_error(text){
	$('.reg-error').show().html('<i class="fa fa-warning"></i> '+text);
	setTimeout(function(){
    	$(".reg-error").fadeOut();
	}, 2000);	
}
//重新刷新页面，使用location.reload()有可能导致重新提交
function reloadPage(win) {
    var location = win.location;
    location.href = location.pathname + location.search;
}
//发送邮件
var sends = {
	send:function(obj){
			if(!$('#reg-uname').val()){show_reg_error('请输入用户名');return false;}
			var mail = $('#reg-email').val();
			if (mail) {
				var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
				if (!reg.test(mail)) {layer.msg('邮箱格式不正确，请重新填写!');return false;}
				var time = 30;
				function timeCountDown(){
					if(time==0){
						clearInterval(timer);
						$(obj).removeAttr("disabled");
						$(obj).val("发送验证码")
						return true;
					}
					$(obj).val(time+"S后再次发送");
					time--;
					return false;
				}
				function send_code(mail){
					var url='/send_email.html';
					$.ajax({  
						url : url,  
						type : "post",  
						dataType : "json",  
						data: {email:mail},  
						success : function(res) {  
							if(res.status) {   
								layer.msg("验证码发送成功，请前往邮箱查看");
							} else {  
								clearInterval(timer); 
								$('.btn-send').removeAttr("disabled");
								$('.btn-send').val("发送验证码")
								show_reg_error(res.info);return false;
							}  
						}  
					}); 
				}
				$(obj).attr('disabled',"true");
				$(obj).val("发送验证码")
				timeCountDown();
				send_code(mail);
				var timer = setInterval(timeCountDown,1000);
		}else{
			layer.msg('请输入邮箱!');return false;	
		}
	}
}
 