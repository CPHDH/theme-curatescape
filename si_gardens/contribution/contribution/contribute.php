<?php
/**
 * @version $Id$
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2010
 * @package Contribution
 */

$head = array('title' => 'Share A Story',
              'bodyclass' => 'contribution');
head($head); ?>
<?php echo js('contribution-public-form'); ?>
<script type="text/javascript">
// <![CDATA[
enableContributionAjaxForm(<?php echo js_escape(uri('contribution/type-form')); ?>);
// ]]>
</script>

<script type="text/javascript">
	//Reveal more file uploads
	jQuery(document).ready(function() {
		jQuery('.upload-more a').click(function(event){
			event.preventDefault();
			jQuery('.contribution article form .upload-hidden').slideDown();
			jQuery(this).hide();
		});
	});
</script>

<div id="content">
<article class="page show">
<h2 class="instapaper_title"><?php echo $head['title']; ?></h2>

	<div id="page-col-left">
		<aside>
		<!-- add left sidebar content here -->
		</aside>
	</div>


	<div id="primary" class="show" role="main">
	<?php echo flash(); ?>
    
    <form method="post" action="" enctype="multipart/form-data">
    
        <fieldset id="contribution-contributor-metadata" <?php if (!isset($typeForm)) { echo 'style="display: none;"'; }?>>
            <h3>About You</h3>
            <div class="field">
                <label for="contributor-name">Name <span>Only your first name and last initial will be published on the web.</span></label>
                <div class="inputs">
                    <div class="input">
                        <?php echo $this->formText('contributor-name', $_POST['contributor-name'], array('class' => 'textinput')); ?>
                    </div>
                </div>
            </div>
            <div class="field">
                <label for="contributor-email">Email Address <span>We will never share your email address with any third party or on the web. We will only contact you if we need clarification about your entry.</span></label>
                <div class="inputs">
                    <div class="input">
                        <?php echo $this->formText('contributor-email', $_POST['contributor-email'], array('class' => 'textinput')); ?>
                    </div>
                </div>
            </div>
        <?php
        foreach (contribution_get_contributor_fields() as $field) {
            echo $field;
        }
        ?>
        </fieldset>    
    
    
        <div class="inputs hidden">
            <label for="contribution-type">What type of item do you want to contribute?</label>
            <?php echo contribution_select_type(array( 'name' => 'contribution_type', 'id' => 'contribution-type'), $_POST['contribution_type']); ?>
            <input type="submit" name="submit-type" id="submit-type" value="Select" />
        </div>
        <div id="contribution-type-form">
        <?php if (isset($typeForm)): echo $typeForm; endif; ?>
        </div>


        <fieldset id="contribution-confirm-submit" <?php if (!isset($typeForm)) { echo 'style="display: none;"'; }?>>
            <div id="captcha" class="inputs"><?php echo $captchaScript; ?></div>
            <div class="inputs hidden">
                <?php echo $this->formCheckbox('contribution-public', '1', null, array('1','0')); ?>
                <?php echo $this->formLabel('contribution-public', 'Publish my contribution on the web.'); ?>
            </div>
            <p>I am over the age of 13 and have read and agree to the <a href="<?php echo uri('contribution/terms') ?>" target="_blank">Terms and Conditions</a>. <span>Or, I am an educator submitting work produced by my students and I have read and agree to all the Terms & Conditions and have collected the <a href="<?php echo uri('#') ?>" target="_blank">Parental Permission Slips</a> for my own records that allow my students to participate in this project.</span></p>
            <div class="inputs">
                <?php echo $this->formCheckbox('terms-agree', $_POST['terms-agree'], null, array('1', '0')); ?>
                <?php echo html_entity_decode($this->formLabel('terms-agree', 'I agree to the <a href="'.uri('contribution/terms').'" target="_blank">Terms and Conditions</a> and, if applicable, have collected <a href="'.uri('#').'" target="_blank">Parental Permission Slips</a>.')); ?>
            </div>
            <?php echo $this->formSubmit('form-submit', 'Contribute', array('class' => 'submitinput')); ?>
            <span id="help">Questions or problems? Contact us at <a href="mailto:communityofgardens">communityofgardens@si.edu</a>.</span>
        </fieldset>
    </form>


	</div>


	<div id="page-col-right">
		<aside id="page-sidebar">

		</aside>	
	</div>	

</article>
</div> <!-- end content -->


<?php echo foot(); ?>