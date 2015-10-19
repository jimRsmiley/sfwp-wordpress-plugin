<?php
/**
 * @package Salesforce Wordpress  Sync
 * @version 0.1
 */
/*
Plugin Name: Salesforce Wordpress Sync
Description: This is a plugin to help syncronize Salesforce and Wordpress.
Version: 0.1
*/

class MySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'SfWp Settings Admin', 
            'Saleforce/Wordpress Sync Settings', 
            'manage_options', 
            'sfwp-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'sfwp_sf_credentials' );
        ?>
        <div class="wrap">
            <h2>My Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'sfwp_option_group' );   
                do_settings_sections( 'sfwp-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'sfwp_option_group', // Option group
            'sfwp_sf_credentials', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'My Custom Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'sfwp-setting-admin' // Page
        );  

        add_settings_field(
            'sfwp_sf_user', // ID
            'Salesforce Username', // Title 
            array( $this, 'sfwp_sf_user_callback' ), // Callback
            'sfwp-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'sfwp_sf_token', 
            'Salesforce Token', 
            array( $this, 'sfwp_sf_token_callback' ), 
            'sfwp-setting-admin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        print $input;
        $new_input = array();
        if( isset( $input['sfwp_sf_user'] ) )
            $new_input['sfwp_sf_user'] = ( $input['sfwp_sf_user'] );

        if( isset( $input['sfwp_sf_token'] ) )
            $new_input['sfwp_sf_token'] = ( $input['sfwp_sf_token'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function sfwp_sf_user_callback()
    {
        printf(
            '<input type="text" id="sfwp_sf_user" name="sfwp_sf_credentials[sfwp_sf_user]" value="%s" />',
            isset( $this->options['sfwp_sf_user'] ) ? esc_attr( $this->options['sfwp_sf_user']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function sfwp_sf_token_callback()
    {
        printf(
            '<input type="password" id="sfwp_sf_token" name="sfwp_sf_credentials[sfwp_sf_token]" value="%s" size="20" />',
            isset( $this->options['sfwp_sf_token'] ) ? esc_attr( $this->options['sfwp_sf_token']) : ''
        );
    }
}

if( is_admin() )
    $sfwp_settings_page = new MySettingsPage();

?>
