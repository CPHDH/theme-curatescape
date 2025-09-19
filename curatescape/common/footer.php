
	</div><!--end wrap-->

	<footer class="main <?php echo get_theme_option('footer_style');?>" id="navigation-target">
		<div class="navigation-container">
			<nav class="navigation-content" id="footer-nav-control" aria-label="<?php echo __('Footer Navigation');?>">
				<?php echo mh_simple_search('footer-search',array('id'=>'footer-search-form'),__('Search - Footer'));?>
				<div>
					<div class="navigation-inner">
						<?php echo mh_global_nav(); ?> 
						<aside>
							<div id="social-column">
								<?php echo mh_footer_find_us();?>
							</div>
							<div>
								<?php echo random_item_link();?>
								<?php echo mh_appstore_downloads();?>
							</div>
						</aside>
					</div>
				</div>
			</nav>
		</div>

		<div class="default">
			<div id="copyright"><?php echo mh_license();?></div>
			<div id="powered-by"><?php echo __('Powered by <a href="http://omeka.org/">Omeka</a> + <a href="http://curatescape.org">Curatescape</a>');?></div>
		</div>

		<div class="custom"><?php echo get_theme_option('custom_footer_html');?></div>
	
		<?php echo fire_plugin_hook('public_footer', array('view'=>$this)); ?>
		
		<?php mh_google_analytics();?>	

	</footer>
</div> <!-- end page-content -->
	
</body>
</html>