
<?php if (!$type): ?>
<p><?php echo __('You must choose a contribution type to continue.'); ?></p>
<?php else: ?>
<!-- h2><?php echo __('Contribute a %s', $type->display_name); ?></h2--> <!--EB: hide heading-->

<?php
if ($type->isFileRequired()):
    $required = true;
?>

<div class="field">
    <div class="two columns alpha">
        <?php echo $this->formLabel('contributed_file', __('Upload a file')); ?>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formFile('contributed_file', array('class' => 'fileinput')); ?>
    </div>
</div>

<?php endif; ?>

<fieldset><!--EB: add HTML-->
<h3>Your Story</h3><!--EB: add HTML-->

<?php
foreach ($type->getTypeElements() as $contributionTypeElement) {
    echo $this->elementForm($contributionTypeElement->Element, $item, array('contributionTypeElement'=>$contributionTypeElement));
}
?>
</fieldset><!--EB: add HTML-->

<?php
if (!isset($required) && $type->isFileAllowed()):
?>
<!--EB: hide default file form -->
<!--div class="field">
        <!--div class="two columns alpha">
            <?php //echo $this->formLabel('contributed_file', __('Upload a file (Optional)')); ?>
        </div-->
        <!--div class="inputs five columns omega">
            <?php //echo $this->formFile('contributed_file', array('class' => 'fileinput')); ?>
        </div>
</div-->


<!--EB: replace above file form with below -->
<!-- Adding a altenate/additional file input form requires adding the field name (eg contributed_file_02) to the array in plugins/Contribution/controllers/ContributionController.php on line 204 -->
<fieldset>
<h3>Upload Files</h3>
<span id="upload">Please upload any files that illustrate your story. You can upload up to ten photo or audio files. The maximum file size is 7 MB. If you would like to share a video, or larger audio files, please email it directly to <a href="mailto:communityofgardens@si.edu" target="_blank">communityofgardens@si.edu</a> with the name of the garden in the subject line. </span>
<?php for($i = 1; $i <= 10; $i++){
	$name="contributed_file_$i";
	
    echo '<div class="field '.($i>1 ? 'upload-hidden' : null).'">';
    	echo $this->formLabel($name, 'Upload a file'.($i>1 ? ' (Optional)' : null));
		echo $this->formFile($name, array('class' => 'fileinput'));
	echo '</div>';
}?>
<div class="upload-more"><a style="cursor:pointer">Upload Additional Files +</a></div>
</fieldset>
<!--EB: end replace -->


<?php endif; ?>

<fieldset><!--EB: add HTML-->
<h3>About You</h3><!--EB: add HTML-->


<?php $user = current_user(); ?>
<?php if(get_option('contribution_simple') && !$user) : ?>
<div class="field">
    <div class="two columns alpha">
    <?php echo $this->formLabel('contribution_simple_email', __('Email (Required)')); ?>
    </div>
    <div class="inputs five columns omega">
    <?php
        if(isset($_POST['contribution_simple_email'])) {
            $email = $_POST['contribution_simple_email'];
        } else {
            $email = '';
        }
    ?>
    <?php echo $this->formText('contribution_simple_email', $email ); ?>
    </div>
</div>

<?php else: ?>
    <p><?php echo __('You are logged in as: %s', metadata($user, 'name')); ?></p>
<?php endif; ?>
    <?php
    //pull in the user profile form if it is set
    if( isset($profileType) ): ?>

    <script type="text/javascript" charset="utf-8">
    //<![CDATA[
    jQuery(document).bind('omeka:elementformload', function (event) {
         Omeka.Elements.makeElementControls(event.target, <?php echo js_escape(url('user-profiles/profiles/element-form')); ?>,'UserProfilesProfile'<?php if ($id = metadata($profile, 'id')) echo ', '.$id; ?>);
         Omeka.Elements.enableWysiwyg(event.target);
    });
    //]]>
    </script>

        <h2 class='contribution-userprofile <?php echo $profile->exists() ? "exists" : ""  ?>'><?php echo  __('Your %s profile', $profileType->label); ?></h2>
        <p id='contribution-userprofile-visibility'>
        <?php if ($profile->exists()) :?>
            <span class='contribution-userprofile-visibility'><?php echo __('Show'); ?></span><span class='contribution-userprofile-visibility' style='display:none'><?php echo __('Hide'); ?></span>
            <?php else: ?>
            <span class='contribution-userprofile-visibility' style='display:none'><?php echo __('Show'); ?></span><span class='contribution-userprofile-visibility'><?php echo __('Hide'); ?></span>
        <?php endif; ?>
        </p>
        <div class='contribution-userprofile <?php echo $profile->exists() ? "exists" : ""  ?>'>
        <p class="user-profiles-profile-description"><?php echo $profileType->description; ?></p>
        <fieldset name="user-profiles">
        <?php
        foreach($profileType->Elements as $element) {
            echo html_entity_decode($this->profileElementForm($element, $profile));
        }
        ?>
        </fieldset>
        </div>
        <?php endif; ?>
    </fieldset><!--EB: add HTML-->
<fieldset> <!--EB: add HTML-->
<h3>Garden Location</h3>
<span id="location">If your story is about a publicly-accessible garden, please enter the full street address.<br><br><strong>Note for Private Gardens</strong>: If your story is about a private garden, please enter only the neighborhood, city, and state. For example, "Woodley Park, Washington, D.C." Or, if you do not feel comfortable including the neighborhood, just the city and state. Do not include the street address. On the Community of Gardens map, private gardens are protected by the "zoom" function. At the city level, the residential garden pins will "disappear," only leaving the public gardens identified at the street level. </span><!--EB: add HTML-->
<?php
// Allow other plugins to append to the form (pass the type to allow decisions
// on a type-by-type basis).
fire_plugin_hook('contribution_type_form', array('type'=>$type, 'view'=>$this));
?>
</fieldset> <!--EB: add HTML-->

<!--EB: add Script-->
<script type="text/javascript">
    
    jQuery(document).ready(function() {
	    //Reveal more file uploads
	    jQuery('.upload-more a').click(function(event){
	            event.preventDefault();
	            jQuery('.contribution article form .upload-hidden').slideDown();
	            jQuery(this).hide();
	    });	    
		//Edit the map button text
		jQuery(".contribution article form button#geolocation_find_location_by_address").text("Click to Confirm Location");    
    });

</script>
<?php endif; ?>