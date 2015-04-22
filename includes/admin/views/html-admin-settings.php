<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php $aSendy = get_option('sendy_configuration') /* RECUPERA AS INFORMAÇÕES DO BANCO */ ?>
	<form method="post" action="">
		<h3>Sendy</h3>
		<?php
			/* SALVA NO BANCO */
			if( isset($_POST['enviar']) && $_POST['enviar']=='ok' ){
				if( filter_var($_POST['url'], FILTER_VALIDATE_URL)  && isset($_POST['url']) && isset($_POST['lista']) ) {

					/* ATUALIZA OS DADOS NO BANCO */
					update_option('sendy_configuration',array(
						'url' => mysql_real_escape_string($_POST['url']),
						'lista' => mysql_real_escape_string($_POST['lista'])
					));

					/* CONFIGURA UMA MENSAGEM */
					add_settings_error('sendy-update','sendy-update','Dados salvos com sucesso.','updated');
				} else {
					/* CONFIGURA UMA MENSAGEM */
					add_settings_error('sendy-erro','sendy-erro','Por favor preencha corretamente, todos os campos.','error');
				}

				/* MOSTRA A MENSAGEM */
				settings_errors();
			}

			/* RECUPERA AS INFORMAÇÕES DO BANCO */
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

		<?php submit_button(); /* INSERE UM BOTÃO DE SUBMIT DO WORDPRESS */ ?>
	</form>
</div>