<?php
/**
 * @version $Id$
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Center for History and New Media, 2010
 * @package Contribution
 */

queue_js_file('contribution-public-form');
$contributionPath = get_option('contribution_page_path');
if(!$contributionPath) {
    $contributionPath = 'contribution';
}
queue_css_file('form');

//load user profiles js and css if needed
if(get_option('contribution_user_profile_type') && plugin_is_active('UserProfiles') ) {
    queue_js_file('admin-globals');
    queue_js_file('tiny_mce', 'javascripts/vendor/tiny_mce');
    queue_js_file('elements');
    queue_css_string("input.add-element {display: block}");
}

$head = array('title' => 'Contribute',
              'bodyclass' => 'contribution');
echo head($head); ?>
<script type="text/javascript">
// <![CDATA[
enableContributionAjaxForm(<?php echo js_escape(url($contributionPath.'/type-form')); ?>);
// ]]>
</script>

<div id="content"><!--EB: add HTML-->
<article class="page show"><!--EB: add HTML-->
<h2 class="instapaper_title"><?php echo $head['title']; ?></h2><!--EB: add HTML-->

	<?php echo mh_contribution_user_nav();?><!--EB: add user subnav-->
	
<div id="primary">
<?php echo flash(); ?>
    
    <?php if(!get_option('contribution_simple') && !$user = current_user()) :?>
        <?php $session = new Zend_Session_Namespace;
              $session->redirect = absolute_url();
        ?>
        <p>You must <a href='<?php echo url('guest-user/user/register'); ?>'>create an account</a> or <a href='<?php echo url('guest-user/user/login'); ?>'>log in</a> before contributing. You can still leave your identity to site visitors anonymous.</p>        
    <?php else: ?>
        <form method="post" action="" enctype="multipart/form-data">
            <!--fieldset id="contribution-item-metadata"--><!--EB: modify HTML structure-->
                <div class="inputs hidden"> <!--EB: add hidden class-->
                    <label for="contribution-type"><?php echo __("What type of item do you want to contribute?"); ?></label>
                    <?php $options = get_table_options('ContributionType' ); ?>
                    <?php $typeId = isset($type) ? $type->id : '' ; ?>
                    <?php echo $this->formSelect( 'contribution_type', $typeId, array('multiple' => false, 'id' => 'contribution-type') , $options); ?>
                    <input type="submit" name="submit-type" id="submit-type" value="Select" />
                </div>
                <!--div id="contribution-type-form"--><!--EB: modify HTML structure-->
                <?php if(isset($type)) { include('type-form.php'); }?>
                <!--/div--><!--EB: modify HTML structure-->
            <!--/fieldset--><!--EB: modify HTML structure-->

            <fieldset id="contribution-confirm-submit" <?php if (!isset($type)) { echo 'style="display: none;"'; }?>>
                <?php if(isset($captchaScript)): ?>
                    <div id="captcha" class="inputs"><?php echo $captchaScript; ?></div>
                <?php endif; ?>
                <div class="inputs hidden"> <!--EB: add hidden class -->
                    <?php $public = 1; //EB: always make public, hide the checkbox so it cannot be modified ?>
                    <?php echo $this->formCheckbox('contribution-public', $public, null, array('1', '0')); ?>
                    <?php echo $this->formLabel('contribution-public', __('Publish my contribution on the web.')); ?>
                </div>
                <div class="inputs hidden"> <!--EB: add hidden class-->
                    <?php $anonymous = 0; ?>
                    <?php echo $this->formCheckbox('contribution-anonymous', $anonymous, null, array(1, 0)); ?>
                    <?php echo $this->formLabel('contribution-anonymous', __("Contribute anonymously.")); ?>
                </div>
                <p><?php echo __("In order to contribute, you must read and agree to the %s",  "<a href='" . contribution_contribute_url('terms') . "' target='_blank'>" . __('Terms and Conditions') . ".</a>"); ?></p>
                <div class="inputs">
                    <?php $agree = isset( $_POST['terms-agree']) ?  $_POST['terms-agree'] : 0 ?>
                    <?php echo $this->formCheckbox('terms-agree', $agree, null, array('1', '0')); ?>
                    <!--EB: replace terms description-->
                    <?php echo html_entity_decode($this->formLabel('terms-agree', 'I am over the age of 18 and have read and agree to the <a href="'.url('submission-agreement').'" target="_blank">Submission Agreement</a>. Or, I am an educator submitting work produced by my students and I have read and agreed to the Submission Agreement and have collected the <a href="'.img('CoG-parental-consent-form.pdf').'" target="_blank">Parental Permission Slips</a> for my own records that allow my students to participate in this project.')); ?>
                </div>
                <?php echo $this->formSubmit('form-submit', __('Contribute'), array('class' => 'submitinput')); ?>
                <span id="help">Questions or problems? Contact us at <a href="mailto:communityofgardens">communityofgardens@si.edu</a>.</span> <!--EB: add HTML-->
            </fieldset>
            <?php echo $csrf; ?>
        </form>
    <?php endif; ?>
</div>


</article><!--EB: add HTML-->
</div><!--EB: add HTML-->
<?php echo foot();
