<?php

class local_nav_add_metabox
{

    public function __construct()
    {
        $this->post_types = get_post_types();
        $this->excluded_post_types = [
          'attachment',
          'revision',
          'nav_menu_item'
        ];
        $this->init();
    }

    /**
     * @description generate metaboxes on post edit and create
     */
    public function init()
    {

        add_action('load-post.php', array($this, 'local_nav_meta_box_setup'));
        add_action('load-post-new.php', 'local_nav_meta_box_setup');

    }

    public function local_nav_meta_box_setup()
    {
        add_action('add_meta_boxes',
          array($this, 'local_nav_add_post_meta_box'));

        add_action('save_post', array($this, 'local_nav_save_menu_text'), 10, 3);



    }

    public function local_nav_add_post_meta_box()
    {
        foreach ($this->post_types as $postType) {
            if (!in_array($postType, $this->excluded_post_types)) {
                add_meta_box('local_nav_menu_text',
                  esc_html__('Local Nav Menu Text', 'local_nav'),
                  array($this, 'local_nav_menu_text'), $postType, 'side',
                  'default');
            }
        }

    }

    public function local_nav_menu_text($object, $box)
    {
        ?>

        <?php wp_nonce_field(basename(__FILE__), 'local_nav_label_nonce'); ?>
        <p>
            <label
              for="local-nav-menu-text"><?php _e("Add custom menu text for this page in the nav",
                  'local_nav'); ?></label>
            <br/>
            <input class="widefat" type="text" name="local-nav-menu-text"
                   id="local-nav-menu-text"
                   value="<?php echo esc_attr(get_post_meta($object->ID,
                     'local-nav-menu-text', true)); ?>" size="30"/>
        </p>
        <?php

    }


    public function local_nav_save_menu_text($post_id, $post, $update)
    {


        /* Verify the nonce before proceeding. */
        if (!isset($_POST['local_nav_label_nonce']) || !wp_verify_nonce($_POST['local_nav_label_nonce'], basename(__FILE__))) {
            return $post_id;
        }

        $post_type = get_post_type_object( $post->post_type );

        /* Check if the current user has permission to edit the post. */
        if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
            return $post_id;
        }

        $new_meta_value = ( isset( $_POST['local-nav-menu-text'] ) ? sanitize_html_class( $_POST['local-nav-menu-text'] ) : '' );

        update_post_meta( $post_id, 'local-nav-menu-text', $new_meta_value );
    }

}