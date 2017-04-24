<?php
/**
 * amp-contact-form Project
 * @package amp-contact-form
 * User: dankerizer
 * Date: 21/04/2017 / 20.52
 */

class ampcf{
    public function __construct()
    {
        //add settings link to plugins page
        add_filter("plugin_action_links", array(
            $this,
            'SettingsLink'
        ) , 10, 2);

        //allow short codes to be added in the widget area
        add_filter('widget_text', 'do_shortcode');

        //add action for loading js files


        add_action('admin_enqueue_scripts', array(
            $this,
            'RegisterAdminScripts'
        ));

        add_action('plugins_loaded', array(
            $this,
            'RegisterTextDomain'
        ));

        add_filter('ampcf_spamfilter',array($this,'SpamFilter'));
        
        $settings = new ampcf_admin();
    }
    //load text domain
    function RegisterTextDomain()
    {
        //$path = CSCF_PLUGIN_DIR . '/languages';
        $path = '/' . AMPCF_PLUGIN_NAME . '/languages';
        load_plugin_textdomain('amp-contact-form', false, $path );
    }

    function RegisterScripts()
    {

    }

    function RegisterAdminScripts($hook)
    {
        if ( $hook != 'settings_page_contact-form-settings')
            return;

        wp_register_script('ampcf-admin-settings', AMPCF_PLUGIN_URL . '/js/jquery.admin.settings.js',
            array(
                'jquery-ui-sortable',
            ) , AMPCF_VERSION, false );

        wp_enqueue_script('ampcf-admin-settings');
    }

    /*
     * Add the settings link to the plugin page
    */

    function SettingsLink($links, $file)
    {

        if ($file == AMPCF_PLUGIN_NAME . '/ampcontactform.php')
        {

            /*
             * Insert the link at the beginning
            */
            $in = '<a href="options-general.php?page=amp-contact-form-settings">' . __('Settings', 'ampcontactform') . '</a>';
            array_unshift($links, $in);

            /*
             * Insert at the end
            */

            // $links[] = '<a href="options-general.php?page=contact-form-settings">'.__('Settings','contact-form').'</a>';

        }

        return $links;
    }
}