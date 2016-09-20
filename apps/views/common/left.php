<?php
use common\YUrl;
?>
<div class="responsive-header visible-xs visible-sm">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="top-section">
<div class="profile-image">
<img src="<?php echo YUrl::assets('image', '/frontend/profile.jpg'); ?>" alt="Volton">
<?php
?>
</div>
<div class="profile-content">
<h3 class="profile-title">Volton</h3>
<p class="profile-description">Digital Photographer</p>
</div>
</div>
</div>
</div>
<a href="#" class="toggle-menu"><i class="fa fa-bars"></i></a>
<div class="main-navigation responsive-menu">
<ul class="navigation">
<li><a href="#top"><i class="fa fa-home"></i>Home</a></li>
<li><a href="about"><i class="fa fa-user"></i>About Me</a></li>
<li><a href="#projects"><i class="fa fa-newspaper-o"></i>My Gallery</a></li>
<li><a href="#contact"><i class="fa fa-envelope"></i>Contact Me</a></li>
</ul>
</div>
</div>
</div>

<!-- SIDEBAR -->
<div class="sidebar-menu hidden-xs hidden-sm">
<div class="top-section">
<div class="profile-image">
<img src="<?php echo YUrl::assets('image', '/frontend/profile.jpg'); ?>" alt="Volton">
</div>
<h3 class="profile-title">Volton</h3>
<p class="profile-description">Digital Photography</p>
</div> <!-- top-section -->
<div class="main-navigation">
<ul>
<li><a href="<?php echo YUrl::createFrontendUrl('Index', 'Index'); ?>"><i class="fa fa-globe"></i>首页</a></li>
<li><a href="<?php echo YUrl::createFrontendUrl('Ask', 'Index'); ?>"><i class="fa fa-pencil"></i>有问必答</a></li>
<li><a href="<?php echo YUrl::createFrontendUrl('Code', 'Index'); ?>"><i class="fa fa-pencil"></i>开源鉴赏</a></li>
<li><a href="<?php echo YUrl::createFrontendUrl('Speak', 'Index'); ?>"><i class="fa fa-pencil"></i>案例解说</a></li>
<li><a href="<?php echo YUrl::createFrontendUrl('News', 'Index'); ?>"><i class="fa fa-pencil"></i>技术文章</a></li>
<li><a href="<?php echo YUrl::createFrontendUrl('Index', 'about'); ?>"><i class="fa fa-pencil"></i>关于</a></li>
<li><a href="<?php echo YUrl::createFrontendUrl('Index', 'contact'); ?>"><i class="fa fa-link"></i>联系我们</a></li>
</ul>
</div> <!-- .main-navigation -->
<div class="social-icons">
<ul>
<li><a href="#"><i class="fa fa-facebook"></i></a></li>
<li><a href="#"><i class="fa fa-twitter"></i></a></li>
<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
<li><a href="#"><i class="fa fa-youtube"></i></a></li>
<li><a href="#"><i class="fa fa-rss"></i></a></li>
</ul>
</div> <!-- .social-icons -->
</div> <!-- .sidebar-menu -->