<?php

/**
 * ampcontactform Project
 * @package ampcontactform
 * User: dankerizer
 * Date: 24/04/2017 / 16.59
 */
class ampcf_Contact
{
    var $Name;
    var $Email;
    var $ConfirmEmail;
    var $Message;
    var $EmailToSender;
    var $ErrorMessage;
    var $Errors;
    var $PostID;
    function  __construct()
    {
        $this->Errors = array();
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['ampfc'] ) ) {
            $ampcf = $_POST['ampfc'];
            $this->Name  = filter_var( $ampcf['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
            $this->Email = filter_var( $ampcf['email'], FILTER_SANITIZE_EMAIL );
            //$this->EmailToSender = isset( $ampcf['email-sender'] );
            if ( isset( $ampcf['confirm_email'] ) ) {
                $this->ConfirmEmail = filter_var( $ampcf['confirm_email'], FILTER_SANITIZE_EMAIL );
            }
            $this->Message = filter_var( $ampcf['message'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );

            if ( isset( $_POST['post-id'] ) ) {
                $this->PostID = $_POST['post-id'];
            }
            unset( $_POST['ampfc'] );
        }
    }

    public function IsValid(){
        $this->Errors = array();
        if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
            return false;
        }
        if ( strlen( $this->Email ) == 0 ) {
            $this->Errors['email'] = __( 'Please give your email address.', 'ampcontactform' );
        }
        if ( strlen( $this->ConfirmEmail ) == 0 ) {
            $this->Errors['confirm_email'] = __( 'Please confirm your email address.', 'ampcontactform' );
        }
        //name

        if ( strlen( $this->Name ) == 0 ) {
            $this->Errors['name'] = __( 'Please give your name.', 'ampcontactform' );
        }

        if ( strlen( $this->Message ) == 0 ) {
            $this->Errors['message'] = __( 'Please enter a message.', 'ampcontactform' );
        }
        //email invalid address

        if ( strlen( $this->Email ) > 0 && ! filter_var( $this->Email, FILTER_VALIDATE_EMAIL ) ) {
            $this->Errors['email'] = __( 'Please enter a valid email address.', 'ampcontactform' );
        }
        return count( $this->Errors ) == 0;
    }

    public
    function SendMail() {
       // apply_filters( 'cscf_spamfilter', $this );



        $filters = new ampcf_Filters();

        $filters->fromEmail = $this->Email;

        $filters->fromName = $this->Name;

        //add filters
        $filters->add( 'wp_mail_from' );
        $filters->add( 'wp_mail_from_name' );

        //headers
        $header = "Reply-To: " . $this->Email . "\r\n";

        //message
        $message = __( 'From: ', 'ampcontactform' ) . $this->Name . "\n\n";
        $message .= __( 'Email: ', 'ampcontactform' ) . $this->Email . "\n\n";
        $message .= __( 'Page URL: ', 'ampcontactform' ) . get_permalink( $this->PostID ) . "\n\n";
        $message .= __( 'Message:', 'ampcontactform' ) . "\n\n" . $this->Message;

        $result = ( wp_mail( ampcf_settings::RecipientEmails(), ampcf_settings::Subject(), stripslashes( $message ), $header ) );

        //remove filters (play nice)
        $filters->remove( 'wp_mail_from' );
        $filters->remove( 'wp_mail_from_name' );

        //send an email to the form-filler
        if ( $this->EmailToSender ) {
            $recipients = ampcf_settings::RecipientEmails();

            if ( ampcf_settings::OverrideFrom() & ampcf_settings::FromEmail() != "" ) {
                $filters->fromEmail = ampcf_settings::FromEmail();
            } else {
                $filters->fromEmail = $recipients[0];
            }

            $filters->fromName = get_bloginfo( 'name' );

            //add filters
            $filters->add( 'wp_mail_from' );
            $filters->add( 'wp_mail_from_name' );

            $header  = "";
            $message = ampcf_settings::SentMessageBody() . "\n\n";
            $message .= __( "Here is a copy of your message :", "ampcontactform" ) . "\n\n";
            $message .= $this->Message;

            $result = ( wp_mail( $this->Email, ampcf_settings::Subject(), stripslashes( $message ), $header ) );

            //remove filters (play nice)
            $filters->remove( 'wp_mail_from' );
            $filters->remove( 'wp_mail_from_name' );
        }

        return $result;
    }
}