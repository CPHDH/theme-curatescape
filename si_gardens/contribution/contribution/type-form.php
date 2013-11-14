<?php if (!$type): ?>
<p>You must choose a contribution type to continue.</p>
<?php 
else:
if ($type->isFileRequired()):
    $required = true;
?>
<div class="field">
        <?php echo $this->formLabel('contributed_file', 'Upload a file'); ?>
        <?php echo $this->formFile('contributed_file', array('class' => 'fileinput')); ?>
</div>
<?php 
endif;?>
<fieldset>
<h3>Your Story</h3>
<?php
foreach ($type->getTypeElements() as $element) {
    echo html_entity_decode($this->elementForm($element, $item));
}?>
</fieldset>
<?php
if (!isset($required) && $type->isFileAllowed()):
?>
<!--div class="field">
        <?php //echo $this->formLabel('contributed_file', 'Upload a file (Optional)'); ?>
        <?php //echo $this->formFile('contributed_file', array('class' => 'fileinput')); ?>
</div-->
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
<div class="upload-more"><a href="">Upload Additional Files +</a></div>
</fieldset>
<?php 
endif;
?>
<fieldset>
<h3>Garden Location</h3>
<span id="location">If your story is about a publicly-accessible garden, please enter the full street address. If your story is about a private garden, please only enter the neighborhood, city, and state. For example, “Woodley Park, Washington, D.C.” We will never reveal the actual address of a private garden. The pin on the map will only show the general location of a private garden to protect personally identifiable information.</span>
<?php
// Allow other plugins to append to the form (pass the type to allow decisions
// on a type-by-type basis).
fire_plugin_hook('contribution_append_to_type_form', $type);
endif;
?>
</fieldset>