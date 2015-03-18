<?php

// I repurposed this code from another GitHub user here: https://gist.github.com/davejamesmiller/1966347
// It allows you to update options for all blogs in a multisite installation.
// It was very handy when I needed to make blanket updates for the SBE plugin.
 
// This function does the actual work
function sbe_update_blog($blog_id = null)
{
    if ($blog_id) {
        switch_to_blog($blog_id);
    }
    //================================================================================
 
    // Put the update code here
    // For example:
    $notification_subject = get_option('subscribe_by_email_instant_notification_subject');
    
    if ($notification_subject == 'BLOGNAME: New Post') {
      update_option('subscribe_by_email_instant_notification_subject', 'POST_TITLE');
    }
    
    $notification_content = get_option('subscribe_by_email_instant_notification_content');
    
    if (strpos($notification_content, 'Cancel subscription:') != False) {
      $text_to_replace = 'Cancel subscription:';
      $replacement_cancel_text = 'To cancel these email alerts click on this link and you will automatically be unsubscribed:';
      $new_notification_content = str_replace($text_to_replace, $replacement_cancel_text, $notification_content);
      update_option('subscribe_by_email_instant_notification_content', $new_notification_content);
    }
 
    //================================================================================
    if ($blog_id) {
        restore_current_blog();
    }
}
 
function subscribe_by_email_change_options()
{
    global $wpdb;
 
    if (!empty($_POST['update_this'])) {
 
        // Update This Blog
        sbe_update_blog();
        $message = __('Blog updated.');
 
    } elseif (!empty($_POST['update_all'])) {
 
        // Update All Blogs
        $blogs = $wpdb->get_results("
            SELECT blog_id
            FROM {$wpdb->blogs}
            WHERE site_id = '{$wpdb->siteid}'
            AND archived = '0'
            AND spam = '0'
            AND deleted = '0'
        ");
 
        foreach ($blogs as $blog) {
            sbe_update_blog($blog->blog_id);
        }
 
        $message = __('All blogs updated.');
 
    }
    ?>
 
    <div class="wrap">
        <h2>Update All Blogs</h2>
 
        <?php if ($message) { ?>
            <div class="updated"><p><strong><?php echo $message ?></strong></p></div>
        <?php } ?>
 
        <form action="" method="post">
            <p>Use this form to run the update script for this blog or all network blogs.</p>
            <p><input type="submit" name="update_this" class="button" value="<?php esc_attr_e('Update This Blog') ?>" /></p>
            <p><input type="submit" name="update_all" class="button" value="<?php esc_attr_e('Update All Blogs') ?>" onclick="return confirm('Are you sure you want to run the update for all blogs?');" /></p>
        </form>
    </div>
 
    <?php
}