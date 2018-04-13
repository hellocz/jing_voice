<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
  
    <title>TT Menu - Bootstrap Mega Menu</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <!-- Font Awesome Styles -->
    <link type="text/css" rel="stylesheet" href="css/tt/font-awesome.css">
    <!-- Bootstrap Styles -->
    <link href="dist/css/bootstrap.css" rel="stylesheet">
    <link href="css/tt/menu.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Style switcher -->
    <link href="switcher/switcher.css" rel="stylesheet">
    <link rel="alternate stylesheet" type="text/css" href="jqueryfuncs/css/red.css" title="red" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="jqueryfuncs/css/blue.css" title="blue" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="jqueryfuncs/css/green.css" title="green" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="jqueryfuncs/css/orange.css" title="orange" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="css/tt/pink.css" title="pink" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="jqueryfuncs/css/hotred.css" title="hotred" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="jqueryfuncs/css/gray.css" title="gray" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="jqueryfuncs/css/darkblue.css" title="darkblue" media="all" />

</head>
<body class="gradient">

 


    <!-- ADD Switcher -->
        <div class="demo_changer">
            <div class="demo-icon"><i class="fa fa-cog fa-spin fa-2x"></i></div>
                <div class="form_holder">
                <h4>Select Color Scheme</h4>
                <a href="" rel="red" class="styleswitch"><img class="img-thumbnail" src="switcher/red.png" alt=""></a>   
                <a href="" rel="blue" class="styleswitch"><img class="img-thumbnail" src="switcher/blue.png" alt=""></a>   
                <a href="" rel="green" class="styleswitch"><img class="img-thumbnail" src="switcher/green.png" alt=""></a>   
                <a href="" rel="pink" class="styleswitch"><img class="img-thumbnail" src="switcher/pink.png" alt=""></a> 
                <a href="" rel="orange" class="styleswitch"><img class="img-thumbnail" src="switcher/orange.png" alt=""></a> 
                <a href="" rel="hotred" class="styleswitch"><img class="img-thumbnail" src="switcher/hotred.png" alt=""></a> 
                <a href="" rel="gray" class="styleswitch"><img class="img-thumbnail" src="switcher/gray.png" alt=""></a> 
                <a href="" rel="darkblue" class="styleswitch"><img class="img-thumbnail" src="switcher/darkblue.png" alt=""></a> 
                <hr>
                <h4>Vertical / Horizontal</h4>
                <a class="btn btn-primary" href="vertical.html">Vertical</a> <a class="btn btn-primary" href="horizontal.html">Horizontal</a>
                <hr>
                <h4>Select Drop Effect</h4>
                <a class="btn btn-primary" href="vertical-hover.html">V. Hover</a> <a class="btn btn-primary" href="vertical.html"> V. Clickable</a>  
                <a class="btn btn-primary" href="horizontal.html">H. Hover</a> <a class="btn btn-primary" href="horizontal-clickable.html"> H. Clickable</a>  
                <hr>
                <h4>Sticky / Normal</h4>
                <a class="btn btn-primary" href="sticky.html">Sticky</a> <a class="btn btn-primary" href="horizontal.html">Normal</a>
            </div>
        </div>
    <!-- END Switcher -->

	<div class="container">
    	<div class="full">
            <div id="stickymenu" class="hovermenu ttmenu dark-style menu-red-gradient">
                <div class="navbar navbar-default" role="navigation">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#"><i class="fa fa-home"></i></a>
                    </div><!-- end navbar-header -->
        
                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown ttmenu-full"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Tabbed <b class="dropme"></b></a>
                                <ul id="first-menu" class="dropdown-menu">
                                    <li>
                                    <div class="ttmenu-content">
                                        <div class="tabbable row">
                                            <div class="col-md-3">
                                                <ul class="nav nav-pills nav-stacked">
                                                    <li class="active"><a href="#tabs5-pane1" data-toggle="tab">Rich Features</a></li>
                                                    <li><a href="#tabs5-pane2" data-toggle="tab">Thumbnail Elements</a></li>
                                                    <li><a href="#tabs5-pane3" data-toggle="tab">Carousel Slider</a></li>
                                                    <li><a href="#tabs5-pane4" data-toggle="tab">Embed Video Support</a></li>
                                                </ul>
                                            </div><!-- end col -->
                                            <div class="col-md-9">
                                                <div class="tab-content">
                                                    <div id="tabs5-pane1" class="tab-pane active">
                                                        <div class="row">
                                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                                <div class="box">
                                                                    <ul>
                                                                        <li><h4>FONT ICON SUPPORT</h4></li>
                                                                        <li><a href="#">Web Design <span class="fa fa-laptop"></span></a></li>
                                                                        <li><a href="#">Web Development <span class="fa fa-gears"></span></a></li>
                                                                        <li><a href="#">Graphic Design <span class="fa fa-leaf"></span></a></li>
                                                                        <li><a href="#">IOS & ANDROID <span class="fa fa-android"></span></a></li>
                                                                        <li><a href="#">Logo Design <span class="fa fa-pencil"></span></a></li>
                                                                        <li><a href="#">Mockup Design <span class="fa fa-maxcdn"></span></a></li>
                                                                        <li><a href="#">e-Commerce <span class="fa fa-shopping-cart"></span></a></li>
                                                                        <li><a href="#">Digital Marketing <span class="fa fa-desktop"></span></a></li>
                                                                        <li><a href="#">SEO Services <span class="fa fa-area-chart"></span></a></li>
                                                                    </ul>
                                                                </div><!-- end box -->
                                                            </div><!-- end col -->

                                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                                <div class="box">
                                                                    <ul>
                                                                        <li><h4>WHY CHOOSE TT MENU</h4></li>
                                                                        <li><a href="#">Responsive Layout</a></li>
                                                                        <li><a href="#">Retina Display Ready</a></li>
                                                                        <li><a href="#">Tons of Icons</a></li>
                                                                        <li><a href="#">Gradient Colors</a></li>
                                                                        <li><a href="#">Beginner Friendly</a></li>
                                                                        <li><a href="#">Detailed Documentation</a></li>
                                                                        <li><a href="#">100% Bootstrap Base</a></li>
                                                                        <li><a href="#">HTML5 CSS3</a></li>
                                                                        <li><a href="#">And Much More...</a></li>
                                                                    </ul>
                                                                </div><!-- end box -->
                                                            </div><!-- end col -->
                                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                                <img src="images/darkmenubg.png" alt="" class="img-responsive">
                                                            </div><!-- end col -->
                                                        </div><!-- end row -->                  
                                                    </div>
                                                    <div id="tabs5-pane2" class="tab-pane">
                                                        <div class="row">
                                                            <div class="col-md-4 menu-image">
                                                                <div class="widget clearfix">
                                                                    <a href="#">
                                                                    <div class="entry">
                                                                        <img src="images/menu_mini_01.png" alt="" class="img-responsive">
                                                                        <span class="magnifier"></span>
                                                                    </div><!-- enntry --> 
                                                                    </a>
                                                                </div><!-- end widget -->
                                                            </div><!-- end col -->
                                                            <div class="col-md-4 menu-image">
                                                                <div class="widget clearfix">
                                                                    <a href="#">
                                                                    <div class="entry">
                                                                        <img src="images/menu_mini_02.png" alt="" class="img-responsive">
                                                                        <span class="magnifier"></span>
                                                                    </div><!-- enntry --> 
                                                                    </a>
                                                                </div><!-- end widget -->
                                                            </div><!-- end col -->
                                                            <div class="col-md-4 menu-image">
                                                                <div class="widget clearfix">
                                                                    <a href="#">
                                                                    <div class="entry">
                                                                        <img src="images/menu_mini_03.png" alt="" class="img-responsive">
                                                                        <span class="magnifier"></span>
                                                                    </div><!-- enntry --> 
                                                                    </a>
                                                                </div><!-- end widget -->
                                                            </div><!-- end col -->
                                                            <div class="col-md-4">
                                                                <div class="widget clearfix">
                                                                    <a href="#">
                                                                    <div class="entry">
                                                                        <img src="images/menu_mini_04.png" alt="" class="img-responsive">
                                                                        <span class="magnifier"></span>
                                                                    </div><!-- enntry --> 
                                                                    </a>
                                                                </div><!-- end widget -->
                                                            </div><!-- end col -->
                                                            <div class="col-md-4">
                                                                <div class="widget clearfix">
                                                                    <a href="#">
                                                                    <div class="entry">
                                                                        <img src="images/menu_mini_05.png" alt="" class="img-responsive">
                                                                        <span class="magnifier"></span>
                                                                    </div><!-- enntry --> 
                                                                    </a>
                                                                </div><!-- end widget -->
                                                            </div><!-- end col -->
                                                            <div class="col-md-4">
                                                                <div class="widget clearfix">
                                                                    <a href="#">
                                                                    <div class="entry">
                                                                        <img src="images/menu_mini_06.png" alt="" class="img-responsive">
                                                                        <span class="magnifier"></span>
                                                                    </div><!-- enntry --> 
                                                                    </a>
                                                                </div><!-- end widget -->
                                                            </div><!-- end col -->
                                                        </div><!-- end row -->      
                                                    </div>
                                                    <div id="tabs5-pane3" class="tab-pane">
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                <div class="box">
                                                                    <h4>ABOUT OUR COMPANY</h4>
                                                                    <p>Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham de  Bonorum " are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                                                                    <p>Finibus Bonorum et Malorum" are also form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                                                                    <button class="btn btn-primary small" href="">Read more</button>
                                                                </div><!-- end box -->
                                                            </div><!-- end col -->
                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                                                    <div class="carousel-inner">
                                                                        <div class="item active">
                                                                            <a href="single-project.html"><img src="images/menu_01.png" class="img-responsive" alt=""></a>
                                                                        </div><!-- End Item -->     
                                                                        <div class="item">
                                                                            <a href="single-project.html"><img src="images/menu_02.png" class="img-responsive" alt=""></a>
                                                                        </div><!-- End Item -->
                                                                    </div><!-- End Carousel Inner -->
                                                                </div><!-- /.carousel -->
                                                            </div><!-- end col -->
                                                        </div><!-- end row -->  
                                                    </div>
                                                    <div id="tabs5-pane4" class="tab-pane">
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                <div class="box">
                                                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/mdfMT5Zi8Eo" frameborder="0" allowfullscreen></iframe>
                                                                </div><!-- end box -->
                                                            </div><!-- end col -->
                                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                                <div class="box">
                                                                    <iframe src="https://player.vimeo.com/video/48401493?color=90cc62&title=0&byline=0" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                </div><!-- end box -->
                                                            </div><!-- end col -->
                                                        </div><!-- end row -->  
                                                    </div>
                                                </div><!-- /.tab-content -->
                                            </div><!-- end col -->
                                        </div><!-- /.tabbable -->
                                    </div><!-- end ttmenu-content -->
                                    </li>
                                </ul>
                            </li><!-- end mega menu -->
                            <li class="dropdown ttmenu-full"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Grid <b class="dropme"></b></a>
                                <ul class="dropdown-menu">
                                    <li>
                                    <div class="ttmenu-content">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                        </div><!-- end row -->
    
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                        </div><!-- end row -->
    
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                        </div><!-- end row -->
    
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                        </div><!-- end row -->
    
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="coldesc">col</div>
                                            </div>
                                        </div><!-- end row -->
    
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="coldesc">col</div>
                                            </div>
                                        </div><!-- end row -->
                                        
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="coldesc">col</div>
                                            </div>
                                        </div><!-- end row -->
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="coldesc">col</div>
                                            </div>
                                        </div><!-- end row -->
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="coldesc">col</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="coldesc">col</div>
                                            </div>
                                        </div><!-- end row -->
                                    </div><!-- end ttmenu-content -->
                                    </li>
                                </ul>
                            </li><!-- end mega menu -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="dropme"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Custom Menu</a></li>
                                    <li class="dropdown-submenu">
                                        <a href="#">Sub Menu <span class="dropme-left"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Second level</a></li>
                                            <li class="dropdown-submenu">
                                                <a href="#">Even More.. <span class="dropme-left"></span></a>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#">3rd level</a></li>
                                                    <li><a href="#">3rd level</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Second level</a></li>
                                            <li><a href="#">Second level</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                </ul>
                            </li>
                            <li class="dropdown ttmenu-full"><a href="#" data-toggle="dropdown" class="dropdown-toggle">List <b class="dropme"></b></a>
                                <ul class="dropdown-menu">
                                    <li>
                                    <div class="ttmenu-content">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="box">
                                                    <ul>
                                                        <li><a href="#">Icon Angellist <span class="fa fa-angellist"></span></a></li>
                                                        <li><a href="#">Icon Bus <span class="fa fa-bus"></span></a></li>
                                                        <li><a href="#">Icon PayPal <span class="fa fa-paypal"></span></a></li>
                                                        <li><a href="#">Icon Vısa Card <span class="fa fa-cc-visa"></span></a></li>
                                                        <li><a href="#">Icon Google Wallet <span class="fa fa-google-wallet"></span></a></li>
                                                    </ul>
                                                </div><!-- end box -->
                                            </div><!-- end col -->
                                            <div class="col-md-3">
                                                <div class="box">
                                                    <ul>
                                                        <li><a href="#">Icon Wifi <span class="fa fa-wifi"></span></a></li>
                                                        <li><a href="#">Icon Ils <span class="fa fa-ils"></span></a></li>
                                                        <li><a href="#">Icon Pie Chart <span class="fa fa-pie-chart"></span></a></li>
                                                        <li><a href="#">Icon Stripe Card <span class="fa fa-cc-stripe"></span></a></li>
                                                        <li><a href="#">Icon TTY <span class="fa fa-tty"></span></a></li>
                                                    </ul>
                                                </div><!-- end box -->
                                            </div><!-- end col -->
                                            <div class="col-md-3">
                                                <div class="box">
                                                    <ul>
                                                        <li><a href="#">Icon Line Chart <span class="fa fa-line-chart"></span></a></li>
                                                        <li><a href="#">Icon Calculator <span class="fa fa-calculator"></span></a></li>
                                                        <li><a href="#">Icon Yelp <span class="fa fa-yelp"></span></a></li>
                                                        <li><a href="#">Icon Soccer <span class="fa fa-soccer-ball-o"></span></a></li>
                                                        <li><a href="#">Icon Trash <span class="fa fa-trash"></span></a></li>
                                                    </ul>
                                                </div><!-- end box -->
                                            </div><!-- end col -->
                                            <div class="col-md-3">
                                                <div class="box">
                                                    <ul>
                                                        <li><a href="#">Icon Anchor <span class="fa fa-anchor"></span></a></li>
                                                        <li><a href="#">Icon Bpmb <span class="fa fa-bomb"></span></a></li>
                                                        <li><a href="#">Icon Cloud <span class="fa fa-cloud"></span></a></li>
                                                        <li><a href="#">Icon Crop <span class="fa fa-crop"></span></a></li>
                                                        <li><a href="#">Icon Fax <span class="fa fa-fax"></span></a></li>
                                                    </ul>
                                                </div><!-- end box -->
                                            </div><!-- end col -->
                                        </div><!-- end row -->
                                    </div><!-- end ttmenu-content -->
                                    </li>
                                </ul>
                            </li><!-- end mega menu -->
                            <li class="dropdown ttmenu-full"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Contact <b class="dropme"></b></a>
                                <ul class="dropdown-menu">
                                    <li>
                                    <div class="ttmenu-content">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="box">
                                                    <ul>
                                                        <li><h4>CONTACT DETAILS</h4></li>
                                                        <li><a href="#">support@yoursite.com <span class="fa fa-envelope-o"></span></a></li>
                                                        <li><a href="#">www.yoursite.com <span class="fa fa-link"></span></a></li>
                                                        <li><a href="#">(0) +911 333 44 55  <span class="fa fa-phone"></span></a></li>
                                                        <li><a href="#">(0) +911 333 44 80  <span class="fa fa-fax"></span></a></li>
                                                        <li><a href="#">Envato, King Street,<br> Melbourne, Victoria, Avustralya</a></li>
                                                    </ul>
                                                </div><!-- end box -->
                                            </div><!-- end col -->
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="box">
                                                    <br>
                                                        <form id="contact" action="#" class="row" name="contactform" method="post">
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                <input type="text" name="name" id="name2" class="form-control" placeholder="Name"> 
                                                                <input type="text" name="email" id="email2" class="form-control" placeholder="Email"> 
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                <input type="text" name="phone" id="phone2" class="form-control" placeholder="Phone">
                                                                <input type="text" name="subject" id="subject2" class="form-control" placeholder="Subject"> 
                                                            </div>
                                                            <div class="clearfix"></div>                   
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <textarea class="form-control" name="comments" id="comments2" rows="6" placeholder="Your Message ..."></textarea>
                                                            </div>   
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="pull-left">
                                                                    <input type="submit" value="SEND" id="submit2" class="btn btn-primary small">
                                                                </div>  
                                                            </div>
                                                        </form>
                                                    </div><!-- end box -->
                                                </div><!-- end row -->
                                            </div><!-- end col -->
                                            <div class="col-md-3">
                                                <div class="box">
                                                    <ul>
                                                        <li><h4>OTHER NOTES</h4></li>
                                                        <li><p>Finibus Bonorum et Malorum" are also form, accompanied by English versions from the  accompanied by English versions from theaccompanied by English versions from the 1914 translation by H. Rackham.</p></li>
                                                    </ul>
                                                </div><!-- end box -->
                                            </div><!-- end col -->
                                        </div><!-- end row -->
                                    </div><!-- end ttmenu content -->
                                    </li>
                                </ul>
                            </li><!-- end mega menu -->
                        </ul><!-- end nav navbar-nav -->
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <form class="searchbox">
                                    <input type="search" placeholder="Search......" name="search" class="searchbox-input" onkeyup="buttonUp();" required>
                                    <input type="submit" class="searchbox-submit" value="GO">
                                    <span class="searchbox-icon"><i class="fa fa-search"></i></span>
                                </form>
                            </li>
                        </ul><!-- end nav navbar-nav navbar-right -->
                    </div><!--/.nav-collapse -->
                </div><!-- end navbar navbar-default clearfix -->
            </div><!-- end menu 1 -->  
        </div><!-- end hero -->  
	</div><!-- /container -->

    <!-- Main Scripts-->
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <script src="js/search.js"></script>
    <script src="js/tt/ttmenu.js"></script>
    <script src="js/jquery.fitvids.js"></script>
    <script src="js/tt/switcher.js"></script>

</body>
</html>