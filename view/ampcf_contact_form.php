<?php
/**
 * ampcontactform Project
 * @package ampcontactform
 * User: dankerizer
 * Date: 24/04/2017 / 16.46
 */
add_shortcode('contact-form', 'ampfc_ContactForm');
add_shortcode('ampfc-contact-form', 'ampfc_ContactForm');

function ampfc_ContactForm(){
    global $post;
    $permalink = get_the_permalink($post->ID);
    $permalink = str_replace('http:','',$permalink);
    $options = get_option('ampcf_options');
    //var_dump($options);
    $message = '';

    if(isset($_POST['ampfc'])){
        $contact = new ampcf_Contact;
        $result['sent'] = false;
        $result['errorlist'] = $contact->Errors;
        $result['valid'] = $contact->IsValid();
        if ($result['valid']) {
            $result['sent'] = $contact->SendMail();
            $message = '<h2>'.$options['sent_message_heading'].'</h2>';
             $message .= '<p>'.$options['sent_message_body'].'</p>';
        }else{

            $message = '<p>Sorry, there has been a problem and your message was not sent</p>';
        }

        // die();
    }



    ?>
    <?php echo $message;?>
    <form   action-xhr="/components/amp-form/submit-form-input-text-xhr" class="form-inline" method="post" target="_top">
        <input type="hidden" name="post-id" value="<?php echo $post->ID; ?>">
            <fieldset>
                <div class="form-group">
                    <label for="ampcf_name"><?php _e( 'Name', 'ampcontactform' ); ?></label>
                    <input type="text" class="form-input" name="ampfc[name]" id="ampcf_name" placeholder="<?php _e( 'Name', 'ampcontactform' ); ?>" required>
                </div>
                <div class="form-group">
                    <label for="ampcf_email"><?php _e( 'Email', 'ampcontactform' ); ?></label>
                    <input type="email" class="form-input" name="ampfc[email]" id="ampcf_email" placeholder="<?php _e( 'Email Address', 'ampcontactform' ); ?>" required>
                </div>
                <div class="form-group">
                    <label for="ampcf_confirm_email"><?php _e( 'Confirm Email', 'ampcontactform' ); ?></label>
                    <input type="email" class="form-input" name="ampfc[confirm_email]" id="ampcf_confirm_email" placeholder="<?php _e( 'Confirm Email Address', 'ampcontactform' ); ?>" required>
                </div>
                <div class="form-group">
                    <label for="ampcf_message"><?php _e( 'Message', 'ampcontactform' ); ?></label>
                    <textarea  class="form-input" name="ampfc[message]" id="ampcf_message" placeholder="<?php _e( 'Your Message', 'ampcontactform' ); ?>" rows="10" required></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" id="ampcf_SubmitButton"  class="btn btn-default" value="<?php _e( 'Send Message', 'ampcontactform' ); ?>"/>

                </div>
            </fieldset>

    </form>
<?php


}
?>
