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
<!doctype html>
<html class="no-js" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
    <head>
        <?php osc_current_web_theme_path('includes/head.php') ; ?>
    </head>
<body <?php osclassclsx_body_class(); ?>>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
<header id="header">
    <div class="row wrapper">
        <div class="top-bar columns">
            <div class="top-bar-left">
                <div id="logo">
                    <?php echo logo_header(); ?>
                    <span id="description"><?php echo osc_page_description(); ?></span>
                </div>
            </div>
            <div class="top-bar-right">
                <ul class="dropdown menu" data-dropdown-menu>
                    <!-- <li class="has-submenu">
                        <a href="#">One</a>
                        <ul class="submenu menu vertical" data-submenu>
                            <li><a href="#">One</a></li>
                            <li><a href="#">Two</a></li>
                            <li><a href="#">Three</a></li>
                        </ul>
                    </li> -->
                    <?php if( osc_is_static_page() || osc_is_contact_page() ){ ?>
                        <li class="search"><a class="ico-search icons" data-bclass-toggle="display-search"></a></li>
                        <li class="cat"><a class="ico-menu icons" data-bclass-toggle="display-cat"></a></li>
                    <?php } ?>
                    <?php if( osc_users_enabled() ) { ?>
                    <?php if( osc_is_web_user_logged_in() ) { ?>
                        <li class="first logged">
                            <span><?php echo sprintf(__('Hi %s', 'osclassclsx'), osc_logged_user_name() . '!'); ?>  &middot;</span>
                            <strong><a href="<?php echo osc_user_dashboard_url(); ?>"><?php _e('My account', 'osclassclsx'); ?></a></strong> &middot;
                            <a href="<?php echo osc_user_logout_url(); ?>"><?php _e('Logout', 'osclassclsx'); ?></a>
                        </li>
                    <?php } else { ?>
                        <li><a id="login_open" href="<?php echo osc_user_login_url(); ?>"><?php _e('Login', 'osclassclsx') ; ?></a></li>
                        <?php if(osc_user_registration_enabled()) { ?>
                            <li><a href="<?php echo osc_register_account_url() ; ?>"><?php _e('Register for a free account', 'osclassclsx'); ?></a></li>
                        <?php }; ?>
                    <?php } ?>
                    <?php } ?>
                    <?php if( osc_users_enabled() || ( !osc_users_enabled() && !osc_reg_user_post() )) { ?>
                    <li class="publish"><a href="<?php echo osc_item_post_url_in_category() ; ?>" class="alert button uppercase bold"><?php _e("Publish your ad for free", 'osclassclsx');?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <!-- header ad 728x60-->
    <div class="ads_header row">
    <?php echo osc_get_preference('header-728x90', 'osclassclsx'); ?>
    <!-- /header ad 728x60-->
    </div>
    <?php /*if( osc_is_home_page() || osc_is_static_page() || osc_is_contact_page() ) { ?>
    <form action="<?php echo osc_base_url(true); ?>" method="get" class="search nocsrf" <?php /* onsubmit="javascript:return doSearch();"*//* ?>>
        <input type="hidden" name="page" value="search"/>
        <div class="main-search">
            <div class="cell">
                <input type="text" name="sPattern" id="query" class="input-text" value="" placeholder="<?php echo osc_esc_html(__(osc_get_preference('keyword_placeholder', 'osclassclsx'), 'osclassclsx')); ?>" />
            </div>
            <?php  if ( osc_count_categories() ) { ?>
                <div class="cell selector">
                    <?php osc_categories_select('sCategory', null, __('Select a category', 'osclassclsx')) ; ?>
                </div>
                <div class="cell reset-padding">
            <?php  } else { ?>
                <div class="cell">
            <?php  } ?>
                <button class="ui-button ui-button-big js-submit"><?php _e("Search", 'osclassclsx');?></button>
            </div>
        </div>
        <div id="message-seach"></div>
    </form>
    <?php }*/ ?>
</header>
<?php osc_show_widgets('header'); ?>
<div class="row wrapper wrapper-flash">
    <?php
        $breadcrumb = osc_breadcrumb('', false, get_breadcrumb_lang());
        if( $breadcrumb !== '') { ?>
        <div class="columns small-12">
            <nav aria-label="You are here:" role="navigation">
                <?php echo $breadcrumb; ?>
            </nav>
        </div>
    <?php
        }
    ?>
    <?php osc_show_flash_message(); ?>
</div>
<?php osc_run_hook('before-content'); ?>
<div class="row wrapper" id="content">
    <?php osc_run_hook('before-main'); ?>
