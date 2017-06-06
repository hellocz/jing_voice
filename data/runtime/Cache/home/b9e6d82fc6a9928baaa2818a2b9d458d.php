<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo ($page_seo["title"]); ?></title>

<meta name="keywords" content="<?php echo ($page_seo["keywords"]); ?>" />

<meta name="description" content="<?php echo ($page_seo["description"]); ?>" />

<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <!-- Bootstrap core CSS -->
    <link href="/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
     <link href="/assets/css/jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/assets/js/ie-emulation-modes-warning.js"></script>


<link href="/assets/css/blog.css" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body> <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">The Voice</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            <li class="active"><a href="/">首页</a></li>
            <li><a href="#about">关于我们</a></li>
            <li><a href="#contact">联系我们</a></li>
            <li><a href="<?php echo U('blog/index');?>">开发日志</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
        <?php if(!empty($visitor)): ?><div class="navbar-right">
             <a class="navbar-brand" href="#"><?php echo ($visitor['username']); ?></a>
             </div>
             <?php else: ?>
          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">登陆</button>
            <a href="<?php echo U('user/register');?>"><button type="button"  class="btn btn-info">注册</button></a>
          </form><?php endif; ?>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>
<div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>开发者日志</h1>
        <p>这里将记录整个网站的开发记录,或许也可以提供给各位朋友一些帮助,更希望大家能给整个网站提提意见和建议.</p>
        <p>This place is record all the documents for this website and it is also can provide you in hunan, and welcome you to join and provide some ideas.</p>
        <p>
          <a class="btn btn-lg btn-primary" href="<?php echo U('blog/add');?>" role="button">写日志 »</a>
        </p>
      </div>

      <div class="row"><div class="col-md-9"><nav class="entries_nav"><a class="active" href="/posts">首页</a><a href="/posts/reviews">评测</a><a href="/posts/specials">专题</a><a href="/posts/talks">访谈</a></nav></div></div>
      <div class="row">
        <div class="col-md-9">
      <ul class="block_list">
      <li><section class="thumbnail--article thumbnail--article--review"><div class="clearfix"><div class="thumbnail--article-thing"><a target="_blank" title="有了贴心的闺蜜还要什么男票？-Dunoon 向日葵马克杯评测" href="/things/dunoon-xiang-ri-kui-ma-ke-bei/reviews/5901c98b6e9b0f0082000f95"><div class="thumbnail--article-cover"><img alt="有了贴心的闺蜜还要什么男票？" src="https://making-photos.b0.upaiyun.com/photos/2194ed37ca0092372763cd9ee2d490e3.jpg!thing.fixed.small"></div><h3>Dunoon 向日葵马克杯</h3></a></div><div class="thumbnail--article-review"><ul><li><img alt="https://making-photos.b0.upaiyun.com/review_photos/ba91acb92b5c53534c91df9d3bcd69ad.jpg!small" itemprop="thumbnailUrl" src="https://making-photos.b0.upaiyun.com/review_photos/ba91acb92b5c53534c91df9d3bcd69ad.jpg!small"></li><li><img alt="https://making-photos.b0.upaiyun.com/review_photos/ad73dafaf138050f5d69fb93f24235c5.jpg!small" itemprop="thumbnailUrl" src="https://making-photos.b0.upaiyun.com/review_photos/ad73dafaf138050f5d69fb93f24235c5.jpg!small"></li><li><img alt="https://making-photos.b0.upaiyun.com/review_photos/350c8a159361a02f64be68897a78a1b8.jpg!small" itemprop="thumbnailUrl" src="https://making-photos.b0.upaiyun.com/review_photos/350c8a159361a02f64be68897a78a1b8.jpg!small"></li><li><img alt="https://making-photos.b0.upaiyun.com/review_photos/24b27321edabedff710820fee568251c.jpg!small" itemprop="thumbnailUrl" src="https://making-photos.b0.upaiyun.com/review_photos/24b27321edabedff710820fee568251c.jpg!small"></li><li><img alt="https://making-photos.b0.upaiyun.com/review_photos/9bc866332a4ae634b71dff047e50514c.jpg!small" itemprop="thumbnailUrl" src="https://making-photos.b0.upaiyun.com/review_photos/9bc866332a4ae634b71dff047e50514c.jpg!small"></li></ul><p>有了闺蜜还要什么男票？这是我的真心话，不是大冒险。直男癌的男朋友只会在你不舒服的时候木讷地来一句：“多喝热水。”或者在你说“没什么”的时候“天真地”信以为真。 但是闺蜜就不一样了啊，我们之间根本...</p></div></div><div class="thumbnail-body"><h2 class="thumbnail-title"><a target="_blank" href="/things/dunoon-xiang-ri-kui-ma-ke-bei/reviews/5901c98b6e9b0f0082000f95">有了贴心的闺蜜还要什么男票？<span>-Dunoon 向日葵马克杯评测</span></a></h2><ul class="metas"><li class="avatar"><a target="_blank" rel="author" data-profile-popover="564abbf46e38d637380001f3" href="/users/564abbf46e38d637380001f3" data-original-title="" title=""><img alt="山本直树" src="https://ko-avatar.b0.upaiyun.com/web/611da2175f7fdc5fd40c4d015e70ea45.jpg!small"></a></li><li class="name"><a data-profile-popover="564abbf46e38d637380001f3" target="_blank" rel="author" href="/users/564abbf46e38d637380001f3" data-original-title="" title="">山本直树</a></li><li class="time"><time datetime="2017-04-27T18:35:55+08:00" title="2017-04-27 18:35:55" class="timeago" data-time-ago="2017-04-27T18:35:55+08:00">27天前</time></li><li class="votable" data-vlink="/things/dunoon-xiang-ri-kui-ma-ke-bei/reviews/5901c98b6e9b0f0082000f95/vote" id="voting-5901c98b6e9b0f0082000f95"><i class="fa fa-thumbs-o-up"></i><span>1</span></li></ul></div></section></li>

         <li><section class="thumbnail--article thumbnail--article--review"><div class="clearfix"><div class="thumbnail--article-thing"><a target="_blank" title="有了贴心的闺蜜还要什么男票？-Dunoon 向日葵马克杯评测" href="/things/dunoon-xiang-ri-kui-ma-ke-bei/reviews/5901c98b6e9b0f0082000f95"><div class="thumbnail--article-cover"><img alt="有了贴心的闺蜜还要什么男票？" src="https://making-photos.b0.upaiyun.com/photos/2194ed37ca0092372763cd9ee2d490e3.jpg!thing.fixed.small"></div><h3>Dunoon 向日葵马克杯</h3></a></div><div class="thumbnail--article-review"><ul><li><img alt="https://making-photos.b0.upaiyun.com/review_photos/ba91acb92b5c53534c91df9d3bcd69ad.jpg!small" itemprop="thumbnailUrl" src="https://making-photos.b0.upaiyun.com/review_photos/ba91acb92b5c53534c91df9d3bcd69ad.jpg!small"></li><li><img alt="https://making-photos.b0.upaiyun.com/review_photos/ad73dafaf138050f5d69fb93f24235c5.jpg!small" itemprop="thumbnailUrl" src="https://making-photos.b0.upaiyun.com/review_photos/ad73dafaf138050f5d69fb93f24235c5.jpg!small"></li><li><img alt="https://making-photos.b0.upaiyun.com/review_photos/350c8a159361a02f64be68897a78a1b8.jpg!small" itemprop="thumbnailUrl" src="https://making-photos.b0.upaiyun.com/review_photos/350c8a159361a02f64be68897a78a1b8.jpg!small"></li><li><img alt="https://making-photos.b0.upaiyun.com/review_photos/24b27321edabedff710820fee568251c.jpg!small" itemprop="thumbnailUrl" src="https://making-photos.b0.upaiyun.com/review_photos/24b27321edabedff710820fee568251c.jpg!small"></li><li><img alt="https://making-photos.b0.upaiyun.com/review_photos/9bc866332a4ae634b71dff047e50514c.jpg!small" itemprop="thumbnailUrl" src="https://making-photos.b0.upaiyun.com/review_photos/9bc866332a4ae634b71dff047e50514c.jpg!small"></li></ul><p>有了闺蜜还要什么男票？这是我的真心话，不是大冒险。直男癌的男朋友只会在你不舒服的时候木讷地来一句：“多喝热水。”或者在你说“没什么”的时候“天真地”信以为真。 但是闺蜜就不一样了啊，我们之间根本...</p></div></div><div class="thumbnail-body"><h2 class="thumbnail-title"><a target="_blank" href="/things/dunoon-xiang-ri-kui-ma-ke-bei/reviews/5901c98b6e9b0f0082000f95">有了贴心的闺蜜还要什么男票？<span>-Dunoon 向日葵马克杯评测</span></a></h2><ul class="metas"><li class="avatar"><a target="_blank" rel="author" data-profile-popover="564abbf46e38d637380001f3" href="/users/564abbf46e38d637380001f3" data-original-title="" title=""><img alt="山本直树" src="https://ko-avatar.b0.upaiyun.com/web/611da2175f7fdc5fd40c4d015e70ea45.jpg!small"></a></li><li class="name"><a data-profile-popover="564abbf46e38d637380001f3" target="_blank" rel="author" href="/users/564abbf46e38d637380001f3" data-original-title="" title="">山本直树</a></li><li class="time"><time datetime="2017-04-27T18:35:55+08:00" title="2017-04-27 18:35:55" class="timeago" data-time-ago="2017-04-27T18:35:55+08:00">27天前</time></li><li class="votable" data-vlink="/things/dunoon-xiang-ri-kui-ma-ke-bei/reviews/5901c98b6e9b0f0082000f95/vote" id="voting-5901c98b6e9b0f0082000f95"><i class="fa fa-thumbs-o-up"></i><span>1</span></li></ul></div></section></li>
      </ul>

    </div>
    </div>
       <footer>
        <p>&copy; 2017 Company, Inc.</p>
</footer>
    </div>
    


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/assets/js/vendor/jquery.min.js"></script>
    <script src="/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>