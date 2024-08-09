<?php
/**
 * astra-escuta Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package astra-escuta
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_ESCUTA_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'astra-escuta-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_ESCUTA_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

/*
 *	
 *	
 *	
 *	
 *	
 *	
 *	
 *	
 *	
 *	    <<<<<<<<<<<<< START >>>>>>>>>>>
 *		ADDED BY Setor de Suporte e T.I. 
*/

function add_header_custom_scripts(){
	?>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<?php
}

add_action( 'wp_head', 'add_header_custom_scripts' );

function add_footer_custom_scripts(){
	?>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
	<?php
}

add_action( 'wp_footer', 'add_footer_custom_scripts' );

/**
* Set the URL to redirect to on login.
*
* @param string $url The visited URL.
* @return string The URL to redirect to on login. Must be absolute.
*/
function my_forcelogin_redirect( $url ) {
	return home_url( '/' );
}
add_filter( 'v_forcelogin_redirect', 'my_forcelogin_redirect' );

/**
 * Hide the 'Back to {sitename}' link on the login screen.
**/
function my_forcelogin_hide_backtoblog() {
	echo '<style type="text/css">#backtoblog{display:none;}</style>';
}
add_action( 'login_enqueue_scripts', 'my_forcelogin_hide_backtoblog');

/** 
  * Carregando conteúdo extra com base na página
**/

function add_footer_custom_scripts_by_page(){
	
	if( is_page( "perfil" ) ){
		echo '<script src="' . get_stylesheet_directory_uri() . '/assets/js/perfil.js"></script>';
	}
}

add_action( 'wp_footer', 'add_footer_custom_scripts_by_page' );


/*

*/

function gerar_lista_assistidos() {
	global $wpdb;
    
    $assistidos = $wpdb->get_results("SELECT * FROM `wp_fafar_cf7crud_submissions` WHERE `form_id` = 54");
    
	$upload_dir    = wp_upload_dir();
    $cfdb7_dir_url = $upload_dir['baseurl'].'/fafar_cf7crud_uploads';

    $lines = "";

    if ( empty( $assistidos ) ) {
        return '<div>Nenhum encontrado</div>';
    }

	foreach ( $assistidos as $assistido ) {

		$assistido_obj  = unserialize( $assistido->submission_data );

		//print_r($assistido_obj);

		$lines .= "
				<div style='height: 128px' class='d-flex border-bottom w-100 p-2'>
					<img src='" . $cfdb7_dir_url . '/' . $assistido_obj["fotofafarcf7crudfile"] . "' class='w-25 h-100 object-fit-contain' />
					<div class='w-75 d-flex flex-column justify-content-between gap-2 ps-2'>
						<h5> <a class='text-decoration-none' href='/perfil?id=" . $assistido->submission_id . "'>" . ( empty( $assistido_obj["nome-social"] ) ? $assistido_obj["nome"] : $assistido_obj["nome-social"] ) . "</a> </h5>
							
						<div class='d-flex justify-content-between'>
							<small class='text-body-secondary'> " . $assistido_obj["tipo-ligacao-institucional"][0] . " </small>
								
							<small class='text-body-secondary'> " . $assistido_obj["telefone"] . " </small>
								
							<small class='text-body-secondary'> " . $assistido_obj["email"] . " </small>
						</div>
					</div>
				</div>
				";

	}

	$output = "<div>";
	$output .= $lines;
	$output .= "</div>";

    return $output;
}

add_shortcode('lista_assistidos', 'gerar_lista_assistidos');


function gerar_perfil_assistido() {

	global $wpdb;

	if ( !isset( $_GET['id'] ) ) {
		return "<br>[001] Solitação quebrado. Tente novamente mais tarde :-<";
	}
    
	$id = $_GET['id'];

    $assistido = $wpdb->get_results("SELECT * FROM `wp_fafar_cf7crud_submissions` WHERE `submission_id` = '" . $id ."'" );

	if ( !$assistido[0] ) {
		return "<br>[002] Assistido não encontrado. Tente novamente mais tarde :-<";
	}


	$upload_dir    = wp_upload_dir();
    $cfdb7_dir_url = $upload_dir['baseurl'].'/fafar_cf7crud_uploads';
	$assistido_obj  = unserialize( $assistido[0]->submission_data );

	$cfdb          = apply_filters( 'cfdb7_database', $wpdb );
	$table_name    = $cfdb->prefix.'db7_forms';
	$upload_dir    = wp_upload_dir();
	$cfdb7_dir_url = $upload_dir['baseurl'].'/fafar_cf7crud_uploads';
	$rm_underscore = apply_filters('cfdb7_remove_underscore_data', true); 


	$atributos_assistido = array_keys( $assistido_obj );
	
	$form_data  = unserialize( $assistido[0]->submission_data );
	
	$arquivos  = array();
	
	$linhas_informacao_tabela = "";

	foreach ($form_data as $chave => $data) {

		$matches = array();
		$chave     = esc_html( $chave );
	
		if ( $chave == 'fotofafarcf7crudfile' ) continue;
		if ( strpos( $chave, 'fafarcf7crudfile' ) !== false ) $chave = str_replace( 'fafarcf7crudfile', '', $chave );
		if( $rm_underscore ) preg_match('/^_.*$/m', $chave, $matches);
		if( ! empty($matches[0]) ) continue;
	
		$chave_val = str_replace("-", " ", $chave);
		$chave_val = ucwords( $chave_val );
		
		$val = $data;

		if ( is_array($data) )
			$val = $data[0];

		$linha_info_arquivos = false;
		if( is_array($data) && str_starts_with( $data[0], "http" ) ) {
			
			$linha_info_arquivos = true;
			
			$links_arquivos = $data;

			$val = "";
			foreach ( $links_arquivos as $link_arquivo ) {
				$link_arquivo = esc_html($link_arquivo);
				$link_arquivo = trim($link_arquivo);
				$link_arquivo = strval($link_arquivo);
				
				$url_decodificado = rawurldecode($link_arquivo);

				$url_decodificado = html_entity_decode($url_decodificado, ENT_QUOTES | ENT_HTML5, 'UTF-8');
				
				$partes_endereco_arquivo = mb_split("/", $url_decodificado);

				$nome_aquivo = end( $partes_endereco_arquivo );

				$val .= "<div class='linha-arquivo'> 
							<span> • </span>
							<a href='" . $link_arquivo . "' target='_blank'>" . $nome_aquivo . "</a>
						</div>";
			}
		}

		$estilo_para_info_binaria = "";
		if( strtolower( $val ) === "sim" ) $estilo_para_info_binaria = "texto-resposta-afirmativa";
		else if( strtolower( $val ) === "nao" || strtolower( $val ) === "não") $estilo_para_info_binaria = "texto-resposta-negativa";

		$linhas_informacao_tabela .= "<tr class='linha-info-perfil' data-chave-info='" . $chave . "' data-linha-arquivo='" . $linha_info_arquivos . "'>";
		$linhas_informacao_tabela .= 	"<td class='w-50'>" . strtoupper( $chave_val ) . "</td>";
		$linhas_informacao_tabela .= 	"<td contenteditable='false' class='fw-bold info-valor " . $estilo_para_info_binaria . "'><div class='d-flex flex-column'>" . $val . "</div></td>";
		$linhas_informacao_tabela .= "</tr>";

	}


	return "
			    <div class='d-flex flex-column gap-2'>
					<h5>" . ( empty( $assistido_obj["nome-social"] ) ? $assistido_obj["nome"] : $assistido_obj["nome-social"] ) . "</h5>
					
					<div class='container-perfil-imagem'>
						<img
							src='" . $cfdb7_dir_url . '/' . $assistido_obj["fotofafarcf7crudfile"] . "'
							alt=''
							class='w-50 h-50'
						/>
					</div>

					<div class='d-flex justify-content-between gap-4 my-4'>
						<a class='fafar-aw-clean-button has-ast-global-color-0-color has-ast-global-color-5-background-color' 
						href='/nova-escuta?assistido=" . $id . "'>
							<span class='dashicons dashicons-plus-alt'></span> Nova Escuta
						</a>

						<a class='fafar-aw-clean-button has-ast-global-color-2-color has-ast-global-color-5-background-color' 
							href='https://escuta.farmacia.ufmg.br/editar-acolhido?id=" . $id . "'>
								<span class='dashicons dashicons-edit'></span> Editar
						</a>
					</div>


					<table class='table border-start-0'>
						<tbody>
							" . $linhas_informacao_tabela . "
						</tbody>
					</table>
					
					<p class='fw-bold'>Escutas</p>
					<p>
						Nenhuma escuta castrada.
					</p>
				</div>
			";

}

add_shortcode('vizualizar_perfil_assistido', 'gerar_perfil_assistido');

