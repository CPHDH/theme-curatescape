<!DOCTYPE html>
<html lang="en" class="notie no-js">
<head>

<!-- Meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
<title><?php echo settings('site_title');?></title>

<meta name="description" content="<?php echo settings('description'); ?>" />
<meta name="keywords" content="<?php echo get_theme_option('meta_key'); ?>" /> 
<meta name="viewport" content="width=device-width,initial-scale=1, maximum-scale=1">
<link rel="shortcut icon" href="<?php echo img('favicon.ico');?>"/> <!-- ICO for old browsers -->

<!-- Apple Stuff -->
<link rel="apple-touch-icon-precomposed" href="<?php echo mh_apple_icon_logo_url();?>"/>

<!-- Stylesheets -->
<?php 
// also returns conditional styles from queue above
queue_css('screen');
display_css();
?>

<!-- Custom CSS via theme config -->
<?php echo mh_custom_css(); ?>

</head>

<body id="home" class="stealth-mode"> 

<div id="wrap">

	<header class="main">	
		<h1 id="site-title" class="visuallyhidden">
		<a href="<?php echo uri('');?>" ><?php echo settings('site_title');?></a>
		</h1>
		
		<div id="stealth-logo-container" class="clearfix">
			<div id="logo">
				<a href="<?php echo uri('');?>" >
				<?php echo mh_the_logo();?>
				</a>
			</div>
		</div>
	</header>		

<!--STEALTH MODE-->

<div id="content">


		
		<div id="columns">
				
			<section id="about">
			<header>
				<h2>About</h2>
			</header>
			
				<p><span class="soon">Coming Soon!</span> <?php echo mh_about();?></p>		
							
			</section>
			
			<section id="contact">
			<header><h2>Contact</h2></header>
			
				<?php 
				$contact_address = get_theme_option('contact_address');
					echo ($contact_address ? '<div id="homecol-address">'.$contact_address.'</div>' : '');	
								
				$contact_email = get_theme_option('contact_email');
					echo ($contact_email ? '<div id="homecol-email"><span class="contact-item">Email:</span> <a href="mailto:'.$contact_email.'">'.$contact_email.'</a></div>' : '');
					
				$twitter_username = get_theme_option('twitter_username');
					echo ($twitter_username ? '<div id="homecol-twitter"><span class="contact-item">Twitter:</span> <a href="http://twitter.com/'.$twitter_username.'">@'.$twitter_username.'</a></div>' : '');
	
				$contact_phone = get_theme_option('contact_phone');
					echo ($contact_phone ? '<div id="homecol-phone"><span class="contact-item">Phone:</span> '.$contact_phone.'</div>' : '');
													
				?>				
					
			</section>
								
		
		</div> <!-- end columns -->

<a href="http://curatescape.org/" style="border:none; opacity:.65; color:transparent;"><img title="Powered by Curatescape" alt="Powered by Curatescape" style="width:85%; max-width: 10em; margin: 1em 0 1em;" src="<?php echo img('curatescape-logo.png');?>"></a>

</div> <!-- end content -->