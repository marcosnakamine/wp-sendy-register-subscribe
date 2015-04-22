<?php
/**
 * Plugin Name: WP Send register subscribe
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

add_action('user_register','cadastrar_newsletter');
function cadastrar_newsletter($user_id){
	$aConfig = get_options( 'sendy_configuration_group' );

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
	//--------------------------------------------------//
}

add_option( 'sendy_configuration', array() );

if (is_admin()) {
	add_action('admin_menu','add_admin_menu_sendy_configuration');
	function add_admin_menu_sendy_configuration(){
		add_options_page('My Options', 'My Plugin', 'manage_options', 'sendy', function(){
			?>
				<div class="wrap">
					<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
					<?php $aSendy = get_option('sendy_configuration') ?>
					<form method="post" action="">
						<h3>Sendy</h3>
						<?php
							/* SALVA NO BANCO */
							if( isset($_POST['enviar']) && $_POST['enviar']=='ok' ){
								if( filter_var($_POST['url'], FILTER_VALIDATE_URL)  && isset($_POST['url']) && isset($_POST['lista']) ) {
									update_option('sendy_configuration',array(
										'url' => mysql_real_escape_string($_POST['url']),
										'lista' => mysql_real_escape_string($_POST['lista'])
									));
									add_settings_error('sendy-update','sendy-update','Dados salvos com sucesso.','updated');
									settings_errors();
								} else {
									add_settings_error('sendy-erro','sendy-erro','Por favor preencha corretamente, todos os campos.','error');
									settings_errors();
								}
							}
							/* SALVA NO BANCO */

							$aSendy = get_option('sendy_configuration');
						?>
						<p>Sendy description</p>
						<table class="form-table">
							<tbody>
								<tr valign="top">
									<th scope="row"><label>URL</label></th>
									<td><input required type="text" class="input-text regular-input" value="<?php echo $aSendy['url'] ?>" name="url" id="sendy_configuration_url"/></td>
								</tr>
								<tr valign="top">
									<th scope="row"><label>Lista</label></th>
									<td><input required type="text" class="input-text regular-input" value="<?php echo $aSendy['lista'] ?>" name="lista" id="sendy_configuration_lista"/></td>
								</tr>
							</tbody>
						</table>

						<input type="hidden" name="enviar" value="ok">
						<?php submit_button(); ?>
					</form>
				</div>
			<?php
		});
	}
}
