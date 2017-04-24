<?php

/**
 * ampcontactform Project
 * @package ampcontactform
 * User: dankerizer
 * Date: 24/04/2017 / 17.57
 */
class ampcf_admin
{
    public function __construct()
    {
        if (is_admin()) {
            add_action('admin_menu', array(
                $this,
                'add_plugin_page'
            ));
            add_action('admin_init', array(
                $this,
                'page_init'
            ));
        }
    }
    public function add_plugin_page(){
        // This page will be under "Settings"
        add_options_page(__('AMP Contact Form Settings', 'amp-contanct-form'), __('AMP Contact Form', 'amp-contanct-form'), 'manage_options', 'amp-contact-form-settings', array(
            $this,
            'create_admin_page'
        ));
    }

    public function page_init(){
        register_setting('test_option_group', AMPCF_OPTIONS_KEY, array(
            $this,
            'check_form'
        ));

        add_settings_section('section_message', '<h3>' . __('Message Settings', 'ampcontactform') . '</h3>', array(
            $this,
            'print_section_info_message'
        ), 'amp-contact-form-settings');
        add_settings_field('recipient_emails', __('Recipient Emails :', 'ampcontactform'), array(
            $this,
            'create_fields'
        ), 'amp-contact-form-settings', 'section_message', array(
            'recipient_emails'
        ));
//        add_settings_field('confirm-email', __('Confirm Email Address :', 'ampcontactform'), array(
//            $this,
//            'create_fields'
//        ), 'amp-contact-form-settings', 'section_message', array(
//            'confirm-email'
//        ));
//        add_settings_field('email-sender', '<span style="color:red;">' . __('*New*','ampcontactform') . '</span> ' . __('Allow users to email themselves a copy :', 'ampcontactform'), array(
//            $this,
//            'create_fields'
//        ), 'amp-contact-form-settings', 'section_message', array(
//            'email-sender'
//        ));


        add_settings_field('subject', __('Email Subject :', 'ampcontactform'), array(
            $this,
            'create_fields'
        ), 'amp-contact-form-settings', 'section_message', array(
            'subject'
        ));
        add_settings_field('message', __('Message :', 'ampcontactform'), array(
            $this,
            'create_fields'
        ), 'amp-contact-form-settings', 'section_message', array(
            'message'
        ));
        add_settings_field('sent_message_heading', __('Message Sent Heading :', 'ampcontactform'), array(
            $this,
            'create_fields'
        ), 'amp-contact-form-settings', 'section_message', array(
            'sent_message_heading'
        ));
        add_settings_field('sent_message_body', __('Message Sent Content :', 'ampcontactform'), array(
            $this,
            'create_fields'
        ), 'amp-contact-form-settings', 'section_message', array(
            'sent_message_body'
        ));

    }
    public  function create_admin_page(){
?>
        <?php screen_icon(); ?><h2><?php _e('AMP Simple Contact Form', 'ampcontactform'); ?></h2>
        <?php if (ampcf_settings::IsJetPackContactFormEnabled()) { ?>
            <p class="highlight">
                <?php _e('NOTICE: You have JetPack\'s Contact Form enabled please deactivate it or use the shortcode [ampfc-contact-form] instead.', 'ampcontactform'); ?>
                
            </p>
        <?php } ?>
        <p class="howto"><?php _e("Please Note: To add the contact form to your page please add the text", "ampcontactform"); ?>
            <code>[ampfc-contact-form]</code> <?php _e("to your post or page.", "ampcontactform"); ?></p>

        <form method="post" action="options.php">
            <?php
            submit_button();

            /* This prints out all hidden setting fields*/
            settings_fields('test_option_group');
            do_settings_sections('amp-contact-form-settings');

            submit_button();
            ?>
        </form>
<?php
    }

    public
    function check_form($input)
    {


        //sent_message_heading
        $input['sent_message_heading'] = filter_var($input['sent_message_heading'], FILTER_SANITIZE_STRING);

        //sent_message_body
        $input['sent_message_body'] = filter_var($input['sent_message_body'], FILTER_SANITIZE_STRING);

        //message
        $input['message'] = filter_var($input['message'], FILTER_SANITIZE_STRING);

        //recipient_emails
        foreach ($input['recipient_emails'] as $key => $recipient) {
            if (!filter_var($input['recipient_emails'][$key], FILTER_VALIDATE_EMAIL)) {
                unset($input['recipient_emails'][$key]);
            }
        }

        //from
        if (!filter_var($input['from-email'], FILTER_VALIDATE_EMAIL)) {
            unset($input['from-email']);
        }

        //subject
        $input['subject'] = trim(filter_var($input['subject'], FILTER_SANITIZE_STRING));
        if (empty($input['subject'])) {
            unset($input['subject']);
        }

        if (isset($_POST['add_recipient'])) {
            $input['recipient_emails'][] = "";
        }

        if (isset($_POST['remove_recipient'])) {
            foreach ($_POST['remove_recipient'] as $key => $element) {
                unset($input['recipient_emails'][$key]);
            }
        }

        //tidy up the keys
        $tidiedRecipients = array();
        foreach ($input['recipient_emails'] as $recipient) {
            $tidiedRecipients[] = $recipient;
        }
        $input['recipient_emails'] = $tidiedRecipients;


        return $input;
    }
    public
    function print_section_info_message()
    {
        print __('Enter your message settings below :', 'ampcontactform');
    }

    public
    function print_section_info_styling()
    {

        //print 'Enter your styling settings below:';

    }

    public
    function create_fields($args)
    {
        $fieldname = $args[0];

        switch ($fieldname) {
            
            case 'recipient_emails':
                ?>
                <ul id="recipients"><?php
                foreach (ampcf_settings::RecipientEmails() as $key => $recipientEmail) {
                    ?>
                    <li class="recipient_email" data-element="<?php echo $key; ?>">
                        <input class="enter_recipient" type="email" size="50"
                               name="<?php echo AMPCF_OPTIONS_KEY; ?>[recipient_emails][<?php echo $key ?>]"
                               value="<?php echo $recipientEmail; ?>"/>
                        <input class="add_recipient" title="Add New Recipient" type="submit" name="add_recipient"
                               value="+">
                        <input class="remove_recipient" title="Remove This Recipient" type="submit"
                               name="remove_recipient[<?php echo $key; ?>]" value="-">
                    </li>

                    <?php
                }
                ?></ul><?php
                break;
            case 'confirm-email':
                $checked = ampcf_settings::ConfirmEmail() == true ? "checked" : "";
                ?><input type="checkbox" <?php echo $checked; ?>  id="confirm-email"
                         name="<?php echo AMPCF_OPTIONS_KEY; ?>[confirm-email]"><?php
                break;

            case 'email-sender':
                $checked = ampcf_settings::EmailToSender() == true ? "checked" : "";
                ?><input type="checkbox" <?php echo $checked; ?>  id="email-sender"
                         name="<?php echo AMPCF_OPTIONS_KEY; ?>[email-sender]"><?php
                break;
            case 'from-email':

                ?><input type="text" size="60" id="from-email"
                                                  name="<?php echo AMPCF_OPTIONS_KEY; ?>[from-email]"
                                                  value="<?php echo ampcf_settings::FromEmail(); ?>" /><?php
                break;
            case 'subject':
                ?><input type="text" size="60" id="subject" name="<?php echo AMPCF_OPTIONS_KEY; ?>[subject]"
                         value="<?php echo ampcf_settings::Subject(); ?>" /><?php
                break;
            case 'sent_message_heading':
                ?><input type="text" size="60" id="sent_message_heading"
                         name="<?php echo AMPCF_OPTIONS_KEY; ?>[sent_message_heading]"
                         value="<?php echo ampcf_settings::SentMessageHeading(); ?>" /><?php
                break;
            case 'sent_message_body':
                ?><textarea cols="63" rows="8"
                            name="<?php echo AMPCF_OPTIONS_KEY; ?>[sent_message_body]"><?php echo ampcf_settings::SentMessageBody(); ?></textarea><?php
                break;
            case 'message':
                ?><textarea cols="63" rows="8"
                            name="<?php echo AMPCF_OPTIONS_KEY; ?>[message]"><?php echo ampcf_settings::Message(); ?></textarea><?php
                break;

            default:
                break;
        }
    }
}