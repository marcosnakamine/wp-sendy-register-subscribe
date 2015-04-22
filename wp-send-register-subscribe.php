<?php
/**
 * Plugin Name: WP Sendy register subscribe
 * Plugin URI: https://github.com/marcosnakamine/wp-sendy-register-subscribe
 * Description: Subscribe Sendy´s newsletter on register
 * Version: 0.1
 * Author: Marcos Nakamine
 * Author URI: http://myn.com.br/
 * Text Domain: wp-send-register-subscribe
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* EXECUTA O 'cadastrar_newsletter' QUANDO O USUÁRIO SE REGISTRA */
add_action('user_register','cadastrar_newsletter');
function cadastrar_newsletter($user_id){
	$sendy_url = $aConfig['url'];
	$list = $aConfig['lista'];

	$name = $_POST['user_login'];
	$email = $_POST['user_email'];

	$postdata = http_build_query(
	    array(
	    'name' => $name,
	    'email' => $email,
	    'list' => $list,
	    'boolean' => 'true'
	    )
	);
	$opts = array('http' => array('method'  => 'POST', 'header'  => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));
	$context  = stream_context_create($opts);
	$result = file_get_contents($sendy_url.'/subscribe', false, $context);
}

/* CRIA O CAMPO sendy_configuration NO BANCO */
add_option( 'sendy_configuration', array() );

if (is_admin()) {
	add_action('admin_menu','add_admin_menu_sendy_configuration');
	function add_admin_menu_sendy_configuration(){
		add_options_page('Sendy subscribe list', 'Sendy subscribe list', 'manage_options', 'sendy', function(){
			include_once 'includes/admin/views/html-admin-settings.php';
		});
	}
}
