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
<form action="<?php echo U('blog/addblog');?>" method="post">
  <div class="form-group">
    <label for="title">标题</label>
    <input type="title" class="form-control" name="title" placeholder="标题">
  </div>
  <div class="form-group">
    <label for="tags">标签(通过标签更好的把你的文章分类)</label>
    <input type="tags" class="form-control" name="tags" placeholder="标签">
  </div>
  <div class="form-group">
  <textarea name="content" id="TextArea1" cols="20" rows="2" class="ckeditor"></textarea>
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>
      <!-- Main component for a primary marketing message or call to action -->

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
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
</body>
</html>