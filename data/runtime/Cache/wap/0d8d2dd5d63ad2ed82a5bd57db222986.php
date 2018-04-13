<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache"/>
<meta content="telephone=no" name="format-detection" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=2.0"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="">
<link rel="apple-touch-icon-precomposed" sizes="57x57" href="">
<title><?php echo ($page_seo["title"]); ?></title>
<meta name="keywords" content="<?php echo ($page_seo["keywords"]); ?>" />
<meta name="description" content="<?php echo ($page_seo["description"]); ?>" />
<link href="/static/css/wap/w_css.css" type="text/css" rel="stylesheet"/>
<script src="/static/js/wap/jquery-1.11.0.min.js" type="text/javascript"></script>



<script src="/static/js/wap/jquery-1.11.0.min.js" type="text/javascript"></script>

<script src="/static/js/wap/owl.carousel.min.js" type="text/javascript"></script>
<style type="text/css">

.badge {
    background-color: red;
    border-radius: 10px;
    color: #fff;
    display: inline-block;
    font-size: 12px;
    left: 2px;
    line-height: 1;
    min-width: 10px;
    padding: 2px;
    position: relative;
    text-align: center;
    white-space: nowrap;
}
</style>

</head>

<body style="background:#f5f5f5;">

<nav class="w_h1">

  <div  class="w_h1_c">

   <div  class="w_h1_c_l">

    <span data='0' id="mmm"><img src="/static/images/wap/bc_h_1.png" title="导航" alt="导航"/></span>

     <a href="<?php echo U('wap/index/index');?>" title="白菜首页" class="w_logo"><img src="/static/images/wap/bc_h_2.png" title="白菜首页" alt="白菜首页"/></a>

     <div class="w_nav" style="display:none;">

     <h2>频道</h2>

     <div class="w_p1">

       <a href="<?php echo U('wap/book/gny',array('ismy'=>0));?>" title="国内">国内</a>

       <a href="<?php echo U('wap/book/gny',array('ismy'=>1));?>" title="海淘">海淘</a>

       <a href="<?php echo U('wap/book/best');?>" title="精品汇">精品汇</a>

       <a href="<?php echo U('wap/article/index',array('id'=>10));?>" title="晒单">晒单</a>

       <a href="<?php echo U('wap/article/index',array('id'=>9));?>" title="攻略" class="nor">攻略</a>

     </div>

     <h2>其他</h2>

     <div class="w_p2">

       <a href="<?php echo U('wap/zr/index');?>" title="闲置转让">闲置转让</a>

       <a href="<?php echo U('wap/tick/index');?>" title="优惠劵">优惠劵</a>

       <a href="<?php echo U('wap/exchange/index');?>" title="积分兑换">积分兑换</a>

       <a href="<?php echo U('wap/orig/index');?>" title="商城导航" class="nor">商城导航</a>

     </div>

     </div>

   </div>

   <div  class="w_h1_c_r">

    <div class="t_c_order"><a href="<?php echo U('wap/index/index',array('type'=>isnice));?>" <?php if($tab == 'isnice'): ?>class="current"<?php endif; ?>>编辑推荐</a><a href="<?php echo U('bao/index',array('type'=>isbao));?>" <?php if($tab == 'isbao'): ?>class="current1"<?php endif; ?>>网友爆料<div class="badge">New</div></a></div>

   </div>

  </div> 

</nav>

<div class="w_center" style="padding-bottom:0;">

  <?php echo R('advert/index', array(14), 'Widget');?>

  <div class="w_sd">

    <ul>

      <li>

        <a href="<?php echo U('wap/book/index');?>" title="商品分类">

         <img src="/static/images/wap/3d_1.png"/><br/>商品分类

        </a>

      </li>

      <li><a href="javascript:;" id="J_sign" title="每日签到">

          <img src="/static/images/wap/3d_2.png"/><br/>每日签到

          </a>

      </li>

      <li><a href="<?php echo U('wap/user/index');?>" title="我的白菜">

          <img src="/static/images/wap/3d_3.png"/><br/>我的白菜

          </a>

      </li>

    </ul>

  </div>

     <div class="clear"></div>

  <div id="m_search" class="p_lr">

  <form action="<?php echo U('wap/search/index');?>" method="get" class="clearfix">

       <input type="text fl" class="text" name="q" placeholder="快速搜索你想要的商品" value=""><button class="btn fr" type="submit"></button> 

  </form></div>

  

  <ul class="list list_preferential" id="Items">

    <?php if(is_array($item_list)): $i = 0; $__LIST__ = $item_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$r): $mod = ($i % 2 );++$i;?><li> <a href="<?php echo U('wap/item/index',array('id'=>$r['id']));?>" title="<?php echo ($r["title"]); ?>">

	<div class="image_wrap">

	<div class="image"><img src="<?php if($r['img']==''): ?>/images/nopic.jpg<?php else: echo attach($r['img'],'item'); endif; ?>" title="<?php echo ($r["title"]); ?>" alt="<?php echo ($r["title"]); ?>"/></div>

	</div>

	<address>

	<span><?php echo (fdate($r['add_time'])); ?></span><?php echo ($r['orig_name']); ?>

	</address>

	<h2><?php echo ($r["title"]); ?></h2>

	<div class="tips"><span><i class="icons icon_comment"></i><?php echo ($r["comments"]); ?></span></div>

	<div style="color:#FF0000; "><?php echo ($r["price"]); ?></div>

	</a> </li><?php endforeach; endif; else: echo "" ;endif; ?>

  </ul>

   <div class="clear"></div>

   <div id="Gtmore" class="btn_getmore" ><a href="javascript:;" id="Get" onClick="return false;">加载更多</a></div>

  <div id="Loading" style="display: none;text-align:center"><img src="/static/images/wap/jiazai.gif" style="width:30%;margin-top:10px;"/></div>

  <input type="hidden" id="page" value="2"/>
<!--	
<div class="btn_getmore">

<span><a href="http://y3.jiaodaoren.com/">电脑版</a></span>

</div> -->

<div class="bc_fixed_right">

		<img src="/static/images/wap/bc_topbtn.png" />

</div>

<div class="clear"></div>

    <div class="w_bot">          

  <div class="w_bot1">

      <a href="<?php echo U('wap/aboutus/index', array('id'=>2));?>" title="关于我们">关于我们</a>

      <a href="<?php echo U('wap/aboutus/index', array('id'=>3));?>" title="联系我们">联系我们</a>

      <a href="<?php echo U('wap/aboutus/index', array('id'=>4));?>" title="客服专区">客服专区</a>

      <a href="<?php echo U('wap/aboutus/index', array('id'=>8));?>" title="网站地图">网站地图</a>

<script src=" http://hm.baidu.com/h.js?49113e6b733eb50457f8170c967ff321" type="text/javascript"></script>
  </div>

  版权所有   白菜哦-高性价比海淘购物推荐 所有资讯均受著作湘ICP备13002285号

</div>
<script>

var PINER = {

    root: "__ROOT__",

    uid: "<?php echo $visitor['id'];?>", 

    async_sendmail: "<?php echo $async_sendmail;?>",

    config: {

        wall_distance: "<?php echo C('pin_wall_distance');?>",

        wall_spage_max: "<?php echo C('pin_wall_spage_max');?>"

    },

    //URL

    url: {}

};
    (function(win,doc){
        var s = doc.createElement("script"), h = doc.getElementsByTagName("head")[0];
        if (!win.alimamatk_show) {
            s.charset = "gbk";
            s.async = true;
            s.src = "https://alimama.alicdn.com/tkapi.js";
            h.insertBefore(s, h.firstChild);
        };
        var o = {
            pid: "mm_27883119_3410238_93410083",/*推广单元ID，用于区分不同的推广渠道*/
            appkey: "",/*通过TOP平台申请的appkey，设置后引导成交会关联appkey*/
            unid: "",/*自定义统计字段*/
            type: "click" /* click 组件的入口标志 （使用click组件必设）*/
        };
        win.alimamatk_onload = win.alimamatk_onload || [];
        win.alimamatk_onload.push(o);
    })(window,document);
</script>

<script src="/static/js/wap/weui.min.js"></script>

<link rel='stylesheet' type='text/css' href='/static/js/wap/weui.css'>

<script>

$(function(){

	$("#mmm").click(function(){

		if($(this).attr("data")==0){

			$(this).addClass('w_sp1');

			$('.w_nav').slideToggle(500);

			$(this).attr('data',"1");

		}else{

			$(this).removeClass('w_sp1');

			$('.w_nav').slideToggle(500);

			$(this).attr('data',"0");	

		}

	});

	

	$('.bc_fixed_right').click(function(){

		$("body").animate({scrollTop: 0}, 500);

	})

})

//签到

$("#J_sign").click(function(){

	if(PINER.uid==""){

		window.location="<?php echo U('wap/user/login');?>";

		return false;

	}

	$.get(PINER.root+'/?g=wap&m=user&a=sign',function(result){

		if(result.status==0){

			weui.Loading.error(result.msg);

		}else{

			weui.Loading.success(result.msg);

		}

	},'json');

});

var page=<?php echo ($p); ?>;

var para='<?php echo ($tab); ?>';

$("#Get").click(function(){

	getmore();

});

function getmore(){

	var cid=$("#cid").val(),page=$("#page").val(),l=$("#Loading"),g=$("#Gtmore"),I=$("#Items");

	l.show();g.hide();

	$.get('<?php echo U("wap/ajax/getpbl");?>',{para:para,p:page},

	function(res){

		if(res.status==1){

			I.append(res.data.resp);

			$("#page").val(parseInt(page)+1);

			g.show();l.hide();

		}else{

			weui.Loading.error("已经到最后一页了");

			l.hide();

		}

	},'json');

	

}

</script>

<script language="javascript">

	$(function() { 

	function loadMeinv() {

		$('.jiazai').show();



		$.get('/index.php?m=ajax&a=getpbl', {para:para ,p:page,pagesize:pagesize}, function (result){

			if(result.status==1){

				$('.jiazai').hide();

				$("#C_drc").append(result.data.resp);

				$("#J_page").html(result.data.pagebar);

				if(pageend<page||pageend==null) {

					$('#J_page').show();

				}else{

					$('#J_page').show();

				}

			}

		},'json');		

	}

	page=page+1;

	//无限加载

	

	$(window).scroll(function(){

		var scrollTop = $(this).scrollTop();

		var scrollHeight = $(document).height();

		var windowHeight = $(this).height();

		if (scrollTop + windowHeight == scrollHeight) {

				//alert(333);

				$("#Get").click();

				loadMeinv();//加载新图片

				page=page+1;

		}

		

	})

})

</script>

</div>

</body>

</html>