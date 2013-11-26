<?php if (!$type): ?>
<p>You must choose a contribution type to continue.</p>
<?php else: ?>

<?php 
if ($type->isFileRequired()):
    $required = true;
?>

<div class="field">
        <?php echo $this->formLabel('contributed_file', 'Upload a file'); ?>
        <?php echo $this->formFile('contributed_file', array('class' => 'fileinput')); ?>
</div>

<?php endif; ?>

<fieldset>
<h3>Your Story</h3>
<?php 
foreach ($type->getTypeElements() as $contributionTypeElement) {
    echo html_entity_decode($this->elementForm($contributionTypeElement->Element, $item, array('contributionTypeElement'=>$contributionTypeElement)));
}
?>
</fieldset>

<?php 
if (!isset($required) && $type->isFileAllowed()):
?>
<!-- Adding a altenate/additional file input form requires adding the field name (eg contributed_file_02) to the array in plugins/Contribution/controllers/ContributionController.php on line 204 -->
<fieldset>
<h3>Upload Files</h3>
<span id="upload">Please upload any files that illustrate your story. You can upload up to ten photo, video, or audio files.</span>
<div class="field">
        <?php echo $this->formLabel('contributed_file_01', 'Upload a file (Optional)'); ?>
<?php echo $this->formFile('contributed_file_01', array('class' => 'fileinput')); ?>
</div>
<div class="upload-hidden">
<div class="field">
<?php echo $this->formLabel('contributed_file_02', 'Upload another file (Optional)'); ?>
<?php echo $this->formFile('contributed_file_02', array('class' => 'fileinput')); ?>
</div>
<div class="field">
<?php echo $this->formLabel('contributed_file_03', 'Upload another file (Optional)'); ?>
<?php echo $this->formFile('contributed_file_03', array('class' => 'fileinput')); ?>
</div>
<div class="field">
<?php echo $this->formLabel('contributed_file_04', 'Upload another file (Optional)'); ?>
<?php echo $this->formFile('contributed_file_04', array('class' => 'fileinput')); ?>
</div>
<div class="field">
<?php echo $this->formLabel('contributed_file_05', 'Upload another file (Optional)'); ?>
<?php echo $this->formFile('contributed_file_05', array('class' => 'fileinput')); ?>
</div>
<div class="field">
<?php echo $this->formLabel('contributed_file_06', 'Upload another file (Optional)'); ?>
<?php echo $this->formFile('contributed_file_06', array('class' => 'fileinput')); ?>
</div>
<div class="field">
<?php echo $this->formLabel('contributed_file_07', 'Upload another file (Optional)'); ?>
<?php echo $this->formFile('contributed_file_07', array('class' => 'fileinput')); ?>
</div>
<div class="field">
<?php echo $this->formLabel('contributed_file_08', 'Upload another file (Optional)'); ?>
<?php echo $this->formFile('contributed_file_08', array('class' => 'fileinput')); ?>
</div>
<div class="field">
<?php echo $this->formLabel('contributed_file_09', 'Upload another file (Optional)'); ?>
<?php echo $this->formFile('contributed_file_09', array('class' => 'fileinput')); ?>
</div>
<div class="field">
<?php echo $this->formLabel('contributed_file_10', 'Upload another file (Optional)'); ?>
<?php echo $this->formFile('contributed_file_10', array('class' => 'fileinput')); ?>
</div>
</div>
<div class="upload-more"><a style="cursor:pointer">Upload Additional Files +</a></div>
</fieldset>

<?php endif; ?>


<fieldset>
<h3>About You</h3>
<?php $user = current_user(); ?>
<?php if(get_option('contribution_simple') && !$user) : ?>

<div class="field">
    <?php echo html_entity_decode($this->formLabel('contribution_simple_email', __('Email Address <span>The Smithsonian will not disclose your email address on the website, share it with third parties, or use it to contact you about anything other than your submission. We will contact you if we need clarification about your story.</span>'))); ?>
    <?php echo $this->formText('contribution_simple_email'); ?>
</div>

<?php else: ?>


    <?php echo __('You are logged in as: %s', metadata($user, 'name')); ?>
    
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
            <span class='contribution-userprofile-visibility'>Show</span><span class='contribution-userprofile-visibility' style='display:none'>Hide</span>
            <?php else: ?>
            <span class='contribution-userprofile-visibility' style='display:none'>Show</span><span class='contribution-userprofile-visibility'>Hide</span>
        <?php endif; ?>
        </p>
        <div class='contribution-userprofile <?php echo $profile->exists() ? "exists" : ""  ?>'>
        <p class="user-profiles-profile-description"><?php echo $profileType->description; ?></p>
        <fieldset name="user-profiles">
        <?php 
        foreach($profileType->Elements as $element) {
            echo $this->profileElementForm($element, $profile);
        }
        ?>
        </fieldset>
        </div>
        <?php endif; ?>
       
<?php endif; ?>

         <div class="inputs">
            <?php $anonymous = isset($_POST['contribution-anonymous']) ? $_POST['contribution-anonymous'] : 0; ?>
            <?php echo $this->formCheckbox('contribution-anonymous', $anonymous, null, array(1, 0)); ?>
            <?php echo $this->formLabel('contribution-anonymous', __("Contribute anonymously.")); ?>
        </div>  
</fieldset> 

<fieldset>
<h3>Garden Location</h3>
<span id="location">If your story is about a publicly-accessible garden, please enter the full street address.<br><br><strong>Note for Private Gardens</strong>: If your story is about a private garden, please enter only the neighborhood, city, and state. For example, “Woodley Park, Washington, D.C.” Or, if you do not feel comfortable including the neighborhood, just the city and state. Do not include the street address. On the Community of Gardens map, private gardens are protected by the “zoom” function. At the city level, the residential garden pins will ‘disappear,’ only leaving the public gardens identified at the street level. </span>
<?php 
// Allow other plugins to append to the form (pass the type to allow decisions
// on a type-by-type basis). Used on this site only for Geolocation.
fire_plugin_hook('contribution_type_form', array('type'=>$type, 'view'=>$this));
?>
</fieldset>

<script>
	jQuery(".contribution article form button#geolocation_find_location_by_address").text("Click to Confirm Location");
</script>

<?php endif; ?>
