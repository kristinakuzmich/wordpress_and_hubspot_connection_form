<?php

/*
  Plugin Name: Custom Submit
  Plugin URI: https://github.com/kristinakuzmich/wordpress_and_hubspot_connection_form/blob/master/smartform.php
  Description: Custom form with fields
  Version: 1.0
  Author: Kristina Kuzmich
  Author URI: https://vk.com/krissstinushka
 */



  function custom_form( $first_name, $last_name, $email, $subject, $message ) 
  {
	    
	    
	 echo '
    <style>
    div {
      margin-bottom:2px;
    }
     
    input{
        margin-bottom:4px;
    }
    </style>
    ';

    echo '
    <form id="contact" action="" onsubmit="validateMyForm(this); event.preventDefault();" method="post">
     
    <div>
    <label for="firstname">First Name</label>
    <input type="text" name="fname" value="' . ( isset( $_POST['fname']) ? $first_name : null ) . '">
    </div>
     
    <div>
    <label for="lastname">Last Name</label>
    <input type="text" name="lname" value="' . ( isset( $_POST['lname']) ? $last_name : null ) . '">
    </div>
    <div>
    <label for="email">Email </label>
    <input type="text" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '">
    </div>
     
    <div>
    <label for="subject">Subject</label>
    <input type="text" name="subject" value="' . ( isset( $_POST['subject']) ? $subject : null ) . '">
    </div>
     
    <div>
    <label for="message">Message</label>
    <textarea name="message">' . ( isset( $_POST['message']) ? $message : null ) . '</textarea>
    </div>
    </br>
    <input type="submit" name="submit" value="Send"/>
    </form>
    ';

	 $sub = $_POST['subject'];
	 $mes = $_POST['message'];
	 $sub = htmlspecialchars($sub);
	 $mes = htmlspecialchars($mes);
	 $sub = urldecode($sub);
	 $mes = urldecode($mes);
	 $sub = trim($sub);
	 $mes = trim($mes);
	 if(mail("kristina.kuzmich.33@mail.ru", "Заявка с сайта","Тема:".$sub."","Сообщение:".$mes." ")){
	 echo "Сообщение успешно отправлено";
	 } else {
	 echo "При отправке сообщения возникли ошибки";
	 }
	}
	

	function form_validation( $first_name, $last_name, $email, $subject, $message )  
	{
		global $reg_errors;
		$reg_errors = new WP_Error;

		if ( empty( $first_name) || empty($last_name) || empty($email) || empty($subject) || empty($message) ) 
		{
	   	$reg_errors->add('field', 'Пожалуйста, заполните все поля.');
		}

	   if ( !is_email( $email ) ) 
	   {
	   	$reg_errors->add( 'email_invalid', 'Неправильный email' );
		}

		if ( email_exists( $email ) ) 
		{
	   	$reg_errors->add( 'email', 'Такой email уже используется' );
		}

		if ( is_wp_error( $reg_errors ) ) 
		{
		    foreach ( $reg_errors->get_error_messages() as $error ) 
		    {
		        echo '<div>';
		        echo '<strong>ERROR</strong>:';
		        echo $error . '<br/>';
		        echo '</div>';
		    }
		}
	}

	function complete_form() 
	{
	    global $reg_errors, $first_name, $last_name, $email, $subject, $message;
	    if ( 1 > count( $reg_errors->get_error_messages() ) ) 
	    {
	        $userdata = array(
	        'first_name'    =>   $first_name,
	        'last_name'     =>   $last_name,
	        'user_email'    =>   $email,
	        'subject'      =>    $subject,
	        'description'   =>   $message,
	        );
	        $user = wp_insert_user( $userdata );
	        echo 'Спасибо за успешное заполнение формы, '.$first_name.' '.$last_name.'!';   
	    }
	}

	function custom_function() 
	{
	    if ( isset($_POST['submit'] ) ) 
	    {
	        form_validation(
	        $_POST['fname'],
	        $_POST['lname'],
	        $_POST['email'],
	        $_POST['subject'],
	        $_POST['message']
	        );
	         
	        // sanitize user form input
	        global $first_name, $last_name, $email, $subject, $message;
	        $first_name =   sanitize_text_field( $_POST['fname'] );
	        $last_name  =   sanitize_text_field( $_POST['lname'] );
	        $email      =   sanitize_email( $_POST['email'] );
	        $subject    =   sanitize_text_field( $_POST['subject'] );
	        $message    =   esc_textarea( $_POST['message'] );
	 
	        // call @function complete_registration to create the user
	        // only when no WP_error is found
	        complete_form(
	        $first_name,
	        $last_name,
	        $email,
	        $subject,
	        $message
	        );
	    }
	 
	    	  custom_form(
	        $first_name,
	        $last_name,
	        $email,
	        $subject,
	        $message
	        );
	}
	
	?>

	<script type="text/javascript">
	function validateMyForm(formDataRaw){
	  console.log(formDataRaw);
	  var formData = new FormData( formDataRaw );
	  loadDoc(formData);
	  return true;
	}
	function loadDoc(formData) {
	  var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	      console.log(this.responseText);
	      if (this.responseText=="200"){
	          document.getElementById("contact").innerHTML = 'Submitted. Thank you.';} else if (this.responseText=="409"){document.getElementById("contact").innerHTML = 'Submitted. Thank you.';} else {alert("There was an error processing your submission.");}
	    } 
	  };
	  xhttp.open("POST", "../../../wp-json/hscontact/v1/create", true);
	  xhttp.send(formData);
	}
	</script>

	<?php 
	
	// Register a new shortcode: [cr_custom_registration]
	add_shortcode( 'cr_custom_form', 'custom_registration_shortcode' );
	 
	// The callback function that will replace [book]
	function custom_registration_shortcode() 
	{
	    ob_start();
	    custom_function();
	    return ob_get_clean();
	}

		function my_custom_redirect ($data) 
	{
	 $arr = array(
            'properties' => array(
                array(
                    'property' => 'email',
                    'value' => $data['email']
                ),
                array(
                    'property' => 'firstname',
                    'value' => $data['firstname']
                ),
                array(
                    'property' => 'lastname',
                    'value' => $data['lastname']
                )
            )
        );
	        $post_json = json_encode($arr);
	        $hapikey = "0f891567-38e9-4779-b251-3c2da783f9d7";
	        $endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=' . $hapikey;
	        $ch = @curl_init();
	        @curl_setopt($ch, CURLOPT_POST, true);
	        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
	        @curl_setopt($ch, CURLOPT_URL, $endpoint);
	        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        $response = @curl_exec($ch);
	        $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
	        $curl_errors = curl_error($ch);
	        @curl_close($ch);
		echo $status_code;
		exit();
	}

	add_action( 'rest_api_init', function () {
	  register_rest_route( 'hscontact/v1', '/create', array(
	    'methods' => 'POST',
	    'callback' => 'my_custom_redirect',
	  ) );
	} );
	
?>