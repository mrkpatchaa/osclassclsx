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
<div id="sidebar" class="columns">
    <?php if(!osc_is_web_user_logged_in() || osc_logged_user_id()!=osc_item_user_id()) { ?>
        <form action="<?php echo osc_base_url(true); ?>" method="post" name="mask_as_form" id="mask_as_form">
            <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" />
            <input type="hidden" name="as" value="spam" />
            <input type="hidden" name="action" value="mark" />
            <input type="hidden" name="page" value="item" />
            <select name="as" id="as" class="mark_as">
                    <option><?php _e("Mark as...", 'osclassclsx'); ?></option>
                    <option value="spam"><?php _e("Mark as spam", 'osclassclsx'); ?></option>
                    <option value="badcat"><?php _e("Mark as misclassified", 'osclassclsx'); ?></option>
                    <option value="repeated"><?php _e("Mark as duplicated", 'osclassclsx'); ?></option>
                    <option value="expired"><?php _e("Mark as expired", 'osclassclsx'); ?></option>
                    <option value="offensive"><?php _e("Mark as offensive", 'osclassclsx'); ?></option>
            </select>
        </form>
    <?php } ?>

    <?php if( osc_get_preference('sidebar-300x250', 'osclassclsx') != '') {?>
    <!-- sidebar ad 350x250 -->
    <div class="ads_300">
        <?php echo osc_get_preference('sidebar-300x250', 'osclassclsx'); ?>
    </div>
    <!-- /sidebar ad 350x250 -->
    <?php } ?>
    <p><a data-open="contact" class="button"><?php _e("Contact publisher", 'osclassclsx'); ?></a></p>
</div> <!-- /sidebar -->
