<?php

/**
 * ampcontactform Project
 * @package ampcontactform
 * User: dankerizer
 * Date: 24/04/2017 / 17.30
 */
class ampcf_settings
{
    static
    function RecipientEmails() {
        $options = get_option( AMPCF_OPTIONS_KEY );
        if ( isset( $options['recipient_emails'] ) && count( $options['recipient_emails'] ) == 0 ) {
            unset( $options['recipient_emails'] );
        }

        return isset( $options['recipient_emails'] ) ? $options['recipient_emails'] : array( get_bloginfo( 'admin_email' ) );
    }

    static
    function Subject()
    {
        $options = get_option(AMPCF_OPTIONS_KEY);

        return isset($options['subject']) ? __($options['subject'], 'ampcontactform') : get_bloginfo('name') . __(' -  Web Enquiry', 'ampcontactform');
    }
    static
    function FromEmail()
    {
        $options = get_option(AMPCF_OPTIONS_KEY);

        return isset($options['from-email']) ? $options['from-email'] : "";
    }

    static
    function EmailToSender()
    {

        $options = get_option(AMPCF_OPTIONS_KEY);

        return isset($options['email-sender']) ? true : false;

    }
    static
    function ConfirmEmail()
    {
        $options = get_option(AMPCF_OPTIONS_KEY);
        return isset($options['confirm-email']) ? true : false;
    }

    static
    function Message()
    {
        $options = get_option(AMPCF_OPTIONS_KEY);

        return isset($options['message']) ? __($options['message'], 'ampcontactform') : __('Please enter your contact details and a short message below and I will try to answer your query as soon as possible.', 'ampcontactform');
    }

    static
    function OverrideFrom()
    {

        $options = get_option(AMPCF_OPTIONS_KEY);

        return isset($options['override-from']) ? true : false;

    }
    static
    function SentMessageHeading()
    {
        $options = get_option(AMPCF_OPTIONS_KEY);

        return isset($options['sent_message_heading']) ? __($options['sent_message_heading'], 'clean-and-simple-contact-form-by-meg-nicholas') : __('Message Sent', 'clean-and-simple-contact-form-by-meg-nicholas');
    }

    static
    function SentMessageBody()
    {
        $options = get_option(AMPCF_OPTIONS_KEY);

        return isset($options['sent_message_body']) ? __($options['sent_message_body'], 'clean-and-simple-contact-form-by-meg-nicholas') : __('Thank you for your message, we will be in touch very shortly.', 'clean-and-simple-contact-form-by-meg-nicholas');
    }

    static
    function IsJetPackContactFormEnabled()
    {
        //check for jetpack plugin
        if (!is_plugin_active('jetpack/jetpack.php'))
            return false;

        //check we can use the jetpack method
        if (!method_exists('JetPack', 'get_active_modules'))
            return false;

        //now check if it is in the active modules
        return in_array('contact-form', JetPack::get_active_modules());

    }
}