<?php head(array('bodyid'=>'home','bodyclass'=>'stealth-mode')); ?>	
<!--STEALTH MODE-->

<div id="content">
	
		<section id="downloads">
			<?php mh_appstore_downloads(); ?>
		</section> 

		
		<div id="columns">
				
			<section id="about">
			<header>
				<h2>About</h2>
			</header>
			
				<p><?php echo mh_about();?></p>		
							
			</section>
			
			<section id="contact">
			<header><h2>Contact</h2></header>
			
				<?php 
				$contact_email = get_theme_option('contact_email');
					echo ($contact_email ? '<p id="homecol-email"><span class="contact-item">Email:</span> <a href="mailto:'.$contact_email.'">'.$contact_email.'</a></p>' : '');
					
				$twitter_username = get_theme_option('twitter_username');
					echo ($twitter_username ? '<p id="homecol-twitter"><span class="contact-item">Twitter:</span> <a href="http://twitter.com/'.$twitter_username.'">@'.$twitter_username.'</a></p>' : '');
	
				$contact_phone = get_theme_option('contact_phone');
					echo ($contact_phone ? '<p id="homecol-phone"><span class="contact-item">Phone:</span> '.$contact_phone.'</p>' : '');
					
				$contact_address = get_theme_option('contact_address');
					echo ($contact_address ? '<p id="homecol-address">'.$contact_address.'</p>' : '');									
				?>			
					
					
			</section>
					
					
			<section id="donate">
			<header><h2>Donate</h2></header>
					
				<p>Your donation helps us continue to provide the <?php echo settings('site_title');?> App. Future updates will include additional content, new features and more, but we will need your support. Help us keep this app free and continue to keep our history history alive.</p>
	
				<?php
				$donate_button = get_theme_option('donate_button');
				echo ($donate_button 
				? '<p>'.$donate_button.'</p>' 
				: '<p>Please contact us directly if you would like to support the project.</p>');
				?>	
					
			</section>
		
		</div> <!-- end columns -->

</div> <!-- end content -->
<?php foot(); ?>