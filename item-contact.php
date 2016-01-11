<?php
    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2014 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */
?>
<div id="contact" class="form-container form-vertical reveal row" data-reveal data-close-on-click="true" data-animation-in="slide-in-down" data-animation-out="fade-out" data-show-delay="1" data-hide-delay="5" data-v-offset="10">
    <div class="modal-header columns small-12">
        <h2><i class="fa fa-envelope"></i>&nbsp;<?php _e("Contact publisher", 'osclassclsx'); ?></h2>
    </div>
    <?php if( osc_item_is_expired () ) { ?>
        <p>
            <?php _e("The listing is expired. You can't contact the publisher.", 'osclassclsx'); ?>
        </p>
    <?php } else if( ( osc_logged_user_id() == osc_item_user_id() ) && osc_logged_user_id() != 0 ) { ?>
        <p>
            <?php _e("It's your own listing, you can't contact the publisher.", 'osclassclsx'); ?>
        </p>
    <?php } else if( osc_reg_user_can_contact() && !osc_is_web_user_logged_in() ) { ?>
        <p>
            <?php _e("You must log in or register a new account in order to contact the advertiser", 'osclassclsx'); ?>
        </p>
        <p class="contact_button">
            <strong><a href="<?php echo osc_user_login_url(); ?>"><?php _e('Login', 'osclassclsx'); ?></a></strong>
            <strong><a href="<?php echo osc_register_account_url(); ?>"><?php _e('Register for a free account', 'osclassclsx'); ?></a></strong>
        </p>
    <?php } else { ?>
        <?php /*if( osc_item_user_id() != null ) { ?>
            <p class="name"><?php _e('Name', 'osclassclsx') ?>: <a href="<?php echo osc_user_public_profile_url( osc_item_user_id() ); ?>" ><?php echo osc_item_contact_name(); ?></a></p>
        <?php } else { ?>
            <p class="name"><?php printf(__('Name: %s', 'osclassclsx'), osc_item_contact_name()); ?></p>
        <?php } ?>
        <?php if( osc_item_show_email() ) { ?>
            <p class="email"><?php printf(__('E-mail: %s', 'osclassclsx'), osc_item_contact_email()); ?></p>
        <?php } ?>
        <?php if ( osc_user_phone() != '' ) { ?>
            <p class="phone"><?php printf(__("Phone: %s", 'osclassclsx'), osc_user_phone()); ?></p>
        <?php } */ ?>
        <ul id="error_list"></ul>
        <form action="<?php echo osc_base_url(true); ?>" method="post" name="contact_form" id="contact_form" <?php if(osc_item_attachment()) { echo 'enctype="multipart/form-data"'; };?> class="columns small-12" >
            <div class="row">
                <?php osc_prepare_user_info(); ?>
                    <?php /*
                    <input type="hidden" name="action" value="contact_post" />
                    <input type="hidden" name="page" value="item" />
                    <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" /> */ ?>
                    <?php ContactForm::primary_input_hidden(); ?>
                    <?php ContactForm::action_hidden(); ?>
                    <?php ContactForm::page_hidden(); ?>
                <div class="form-row columns small-12 columns small-12">
                    <label class="control-label" for="yourName"><?php _e('Your name', 'osclassclsx'); ?>:
                        <?php ContactForm::your_name(); ?>
                    </label>
                </div>
                <div class="form-row columns small-12">
                    <label class="control-label" for="yourEmail"><?php _e('Your e-mail address', 'osclassclsx'); ?>:
                        <?php ContactForm::your_email(); ?>
                    </label>
                </div>
                <div class="form-row columns small-12">
                    <label class="control-label" for="phoneNumber"><?php _e('Phone number', 'osclassclsx'); ?> (<?php _e('optional', 'osclassclsx'); ?>):
                        <?php ContactForm::your_phone_number(); ?>
                    </label>
                </div>

                <div class="form-row columns small-12">
                    <label class="control-label" for="message"><?php _e('Message', 'osclassclsx'); ?>:
                        <?php ContactForm::your_message(); ?>
                    </label>
                </div>

                <?php if(osc_item_attachment()) { ?>
                    <div class="form-row columns small-12">
                        <label class="control-label" for="attachment"><?php _e('Attachment', 'osclassclsx'); ?>:</label>
                        <div class="controls"><?php ContactForm::your_attachment(); ?></div>
                    </div>
                <?php }; ?>

                <div class="form-row columns small-12">
                    <div class="controls">
                        <?php osc_run_hook('item_contact_form', osc_item_id()); ?>
                        <?php if( osc_recaptcha_public_key() ) { ?>
                        <script type="text/javascript">
                            var RecaptchaOptions = {
                                theme : 'custom',
                                custom_theme_widget: 'recaptcha_widget'
                            };
                        </script>
                        <style type="text/css">
                          div#recaptcha_widget, div#recaptcha_image > img { width:240px; margin-top: 5px; }
                          div#recaptcha_image { margin-bottom: 15px; }
                        </style>
                        <div id="recaptcha_widget">
                            <div id="recaptcha_image"><img /></div>
                            <span class="recaptcha_only_if_image"><?php _e('Enter the words above','osclassclsx'); ?>:</span>
                            <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
                            <div><a href="javascript:Recaptcha.showhelp()"><?php _e('Help', 'osclassclsx'); ?></a></div>
                        </div>
                        <?php } ?>
                        <?php osc_show_recaptcha(); ?>
                        <button type="submit" class="ui-button ui-button-middle ui-button-main"><?php _e("Send", 'osclassclsx');?></button>
                    </div>
                </div>
            </div>
        </form>
        <?php //ContactForm::js_validation(); ?>
    <?php } ?>
    <button class="close-button" data-close aria-label="Close reveal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
