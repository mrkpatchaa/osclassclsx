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

    // meta tag robots
    if( osc_item_is_spam() || osc_premium_is_spam() ) {
        osc_add_hook('header','osclassclsx_nofollow_construct');
    } else {
        osc_add_hook('header','osclassclsx_follow_construct');
    }

    // osc_enqueue_script('fancybox');
    // osc_enqueue_style('fancybox', osc_current_web_theme_url('js/fancybox/jquery.fancybox.css'));
    osc_enqueue_script('jquery-validate');

    osclassclsx_add_body_class('item');
    // osc_add_hook('after-main','sidebar');
    // function sidebar(){
    //     osc_current_web_theme_path('item-sidebar.php');
    // }

    $location = array();
    if( osc_item_city_area() !== '' ) {
        $location[] = osc_item_city_area();
    }
    if( osc_item_city() !== '' ) {
        $location[] = osc_item_city();
    }
    if( osc_item_region() !== '' ) {
        $location[] = osc_item_region();
    }
    if( osc_item_country() !== '' ) {
        $location[] = osc_item_country();
    }

    osc_current_web_theme_path('header.php');
?>
<div id="item-content" class="columns small-12">
        <h1><strong><?php echo osc_item_title(); ?></strong></h1>
        <!-- <div class="item-header">
            <div>
                <?php if ( osc_item_pub_date() !== '' ) { printf( __('<strong class="publish">Published date</strong>: %1$s', 'osclassclsx'), osc_format_date( osc_item_pub_date() ) ); } ?>
            </div>
            <div>
                <?php if ( osc_item_mod_date() !== '' ) { printf( __('<strong class="update">Modified date:</strong> %1$s', 'osclassclsx'), osc_format_date( osc_item_mod_date() ) ); } ?>
            </div>
            <?php if (count($location)>0) { ?>
                <ul id="item_location">
                    <li><strong><?php _e("Location", 'osclassclsx'); ?></strong>: <?php echo implode(', ', $location); ?></li>
                </ul>
            <?php }; ?>
        </div> -->
        <?php /*if(osc_is_web_user_logged_in() && osc_logged_user_id()==osc_item_user_id()) { ?>
            <p id="edit_item_view">
                <strong>
                    <a href="<?php echo osc_item_edit_url(); ?>" rel="nofollow"><?php _e('Edit item', 'osclassclsx'); ?></a>
                </strong>
            </p>
        <?php }*/ ?>

    <div class="row">
        <div id="main" class="medium-8 columns">
            <?php osc_run_hook('inside-main'); ?>
            <!-- Price -->
            <?php if( osc_price_enabled_at_items() ) { ?><span class="price"><?php echo osc_item_formated_price(); ?></span> <?php } ?>
            <?php if( osc_images_enabled_at_items() ) { ?>
                <?php
                if( osc_count_item_resources() > 0 ) {
                    $i = 0;
                    $orbit_bullets = '';
                    $bullet_format = '<button class="%s" data-slide="%s"><a href="#" title="%s"><span class="show-for-sr">%s</span> <img src="%s" width="75" alt="%s" title="%s" /></a></button>';
                ?>
                <div class="item-photos">
                <div class="orbit" role="region" aria-label="<?php _e("Ad's images", 'osclassclsx'); ?>" data-orbit data-use-m-u-i="true" data-auto-play="false">
                    <ul class="orbit-container">
                        <button class="orbit-previous" aria-label="previous"><span class="show-for-sr"><?php _e("Previous Image", 'osclassclsx'); ?></span>&#9664;</button>
                        <button class="orbit-next" aria-label="next"><span class="show-for-sr"><?php _e("Next Image", 'osclassclsx'); ?></span>&#9654;</button>
                        <?php for ( $i = 0; osc_has_item_resources(); $i++ ) { ?>
                        <li class="orbit-slide<?php echo ($i == 0) ? ' is-active' : ''; ?>">
                            <img src="<?php echo osc_resource_url(); ?>" alt="<?php echo osc_item_title(); ?>" title="<?php echo osc_item_title(); ?>" />
                            <figcaption class="orbit-caption show-for-sr"><?php _e('Image', 'osclassclsx'); ?> <?php echo $i+1;?> / <?php echo osc_count_item_resources();?></figcaption>
                        </li>
                        <?php
                        // Save the thumbnails
                        $orbit_bullets.= sprintf($bullet_format,
                            ($i == 0) ? 'is-active' : '',
                            $i,
                            _('Image', 'osclassclsx') . ' / ' . osc_count_item_resources(),
                            _('Image', 'osclassclsx') . ' / ' . osc_count_item_resources(),
                            osc_resource_thumbnail_url(),
                            osc_item_title(),
                            osc_item_title()
                        );
                        ?>
                        <?php } ?>
                    </ul>
                    <nav class="orbit-bullets">
                        <?php echo $orbit_bullets; ?>
                    </nav>
                </div>
                </div>
                <?php } ?>
            <?php } ?>
            <div id="description">
                <p><?php echo osc_item_description(); ?></p>
                <div id="custom_fields">
                    <?php if( osc_count_item_meta() >= 1 ) { ?>
                        <br />
                        <div class="meta_list">
                            <?php while ( osc_has_item_meta() ) { ?>
                                <?php if(osc_item_meta_value()!='') { ?>
                                    <div class="meta">
                                        <strong><?php echo osc_item_meta_name(); ?>:</strong> <?php echo osc_item_meta_value(); ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <?php osc_run_hook('item_detail', osc_item() ); ?>
                <p class="contact_button">
                    <?php if( !osc_item_is_expired () ) { ?>
                    <?php if( !( ( osc_logged_user_id() == osc_item_user_id() ) && osc_logged_user_id() != 0 ) ) { ?>
                        <?php     if(osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact() ) { ?>
                            <a href="#contact" class="ui-button ui-button-middle ui-button-main resp-toogle"><?php _e('Contact seller', 'osclassclsx'); ?></a>
                        <?php     } ?>
                    <?php     } ?>
                    <?php } ?>
                   <a href="<?php echo osc_item_send_friend_url(); ?>" rel="nofollow" class="ui-button ui-button-middle"><?php _e('Share', 'osclassclsx'); ?></a>
                </p>
                <?php osc_run_hook('location'); ?>
            </div>
            <!-- plugins -->
            <div id="useful_info" class="bordered-box">
                <h2><?php _e('Useful information', 'osclassclsx'); ?></h2>
                <ul>
                    <li><?php _e('Avoid scams by acting locally or paying with PayPal', 'osclassclsx'); ?></li>
                    <li><?php _e('Never pay with Western Union, Moneygram or other anonymous payment services', 'osclassclsx'); ?></li>
                    <li><?php _e('Don\'t buy or sell outside of your country. Don\'t accept cashier cheques from outside your country', 'osclassclsx'); ?></li>
                    <li><?php _e('This site is never involved in any transaction, and does not handle payments, shipping, guarantee transactions, provide escrow services, or offer "buyer protection" or "seller certification"', 'osclassclsx'); ?></li>
                </ul>
            </div>

            <?php related_listings(); ?>
            <?php if( osc_count_items() > 0 ) { ?>
            <div class="similar_ads">
                <h2><?php _e('Related listings', 'osclassclsx'); ?></h2>
                <?php
                View::newInstance()->_exportVariableToView("listType", 'items');
                osc_current_web_theme_path('loop.php');
                ?>
                <div class="clear"></div>
            </div>
            <?php } ?>
            <?php if( osc_comments_enabled() ) { ?>
                <?php if( osc_reg_user_post_comments () && osc_is_web_user_logged_in() || !osc_reg_user_post_comments() ) { ?>
                <div id="comments">
                    <h2><?php _e('Comments', 'osclassclsx'); ?></h2>
                    <ul id="comment_error_list"></ul>
                    <?php //CommentForm::js_validation(); ?>
                    <?php if( osc_count_item_comments() >= 1 ) { ?>
                        <div class="comments_list">
                            <?php while ( osc_has_item_comments() ) { ?>
                                <div class="comment">
                                    <h3><strong><?php echo osc_comment_title(); ?></strong> <em><?php _e("by", 'osclassclsx'); ?> <?php echo osc_comment_author_name(); ?>:</em></h3>
                                    <p><?php echo nl2br( osc_comment_body() ); ?> </p>
                                    <?php if ( osc_comment_user_id() && (osc_comment_user_id() == osc_logged_user_id()) ) { ?>
                                    <p>
                                        <a rel="nofollow" href="<?php echo osc_delete_comment_url(); ?>" title="<?php _e('Delete your comment', 'osclassclsx'); ?>"><?php _e('Delete', 'osclassclsx'); ?></a>
                                    </p>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="paginate" style="text-align: right;">
                                <?php echo osc_comments_pagination(); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-container form-horizontal">
                        <div class="header">
                            <h3><?php _e('Leave your comment (spam and offensive messages will be removed)', 'osclassclsx'); ?></h3>
                        </div>
                        <div class="resp-wrapper">
                            <form action="<?php echo osc_base_url(true); ?>" method="post" name="comment_form" id="comment_form">
                                <fieldset>

                                    <input type="hidden" name="action" value="add_comment" />
                                    <input type="hidden" name="page" value="item" />
                                    <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" />
                                    <?php if(osc_is_web_user_logged_in()) { ?>
                                        <input type="hidden" name="authorName" value="<?php echo osc_esc_html( osc_logged_user_name() ); ?>" />
                                        <input type="hidden" name="authorEmail" value="<?php echo osc_logged_user_email();?>" />
                                    <?php } else { ?>
                                        <div class="control-group">
                                            <label class="control-label" for="authorName"><?php _e('Your name', 'osclassclsx'); ?></label>
                                            <div class="controls">
                                                <?php CommentForm::author_input_text(); ?>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label" for="authorEmail"><?php _e('Your e-mail', 'osclassclsx'); ?></label>
                                            <div class="controls">
                                                <?php CommentForm::email_input_text(); ?>
                                            </div>
                                        </div>
                                    <?php }; ?>
                                    <div class="control-group">
                                        <label class="control-label" for="title"><?php _e('Title', 'osclassclsx'); ?></label>
                                        <div class="controls">
                                            <?php CommentForm::title_input_text(); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="body"><?php _e('Comment', 'osclassclsx'); ?></label>
                                        <div class="controls textarea">
                                            <?php CommentForm::body_input_textarea(); ?>
                                        </div>
                                    </div>
                                    <div class="actions">
                                        <button type="submit"><?php _e('Send', 'osclassclsx'); ?></button>
                                    </div>

                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
                <?php } ?>
            <?php } ?>
        </div> <!-- main -->
        <?php osc_current_web_theme_path('item-sidebar.php'); ?>
    </div> <!-- row -->
    <?php osc_current_web_theme_path('item-contact.php'); ?>
</div> <!-- item-content -->
<?php osc_current_web_theme_path('footer.php') ; ?>
