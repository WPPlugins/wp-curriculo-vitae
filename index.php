<?php
/*
Plugin Name: WP-Currículo Vitae Free
Plugin URI: http://wiliamluis.wordpress.com/plugin/
Description: O WP-Curriculo Vitae e um plugin que permite que usuarios a cadastrem seu curriculo no site para divulgacao online ou para uso do site.
Version: 5.4
Author: William Luis da Silva
Author URI: http://www.williamluis.com.br/wp-cv-demonstracao/
License: GPLv2
*/


// Vamos criar uma tabela que irá guardar os IPs dos usuários que
// acessam o nosso website.
// ATENÇÃO: Este código é apenas didático. Se o usar para produção
// poderá vir a sofrer tempos de resposta maiores, pois irá guardar
// muitos dados na base de dados.

if (!class_exists('WpWls')) {
	include_once( plugin_dir_path( __FILE__ ) . 'admin/include/class.wpwls.php' );	
}

$wpcvf = new WpWls();

$wls_curriculo 					= $wpdb->prefix . 'wls_curriculo';
$wls_areas 						= $wpdb->prefix . 'wls_areas';
$wls_curriculo_options 			= $wpdb->prefix . 'wls_curriculo_options';


// Registamos a função para correr na ativação do plugin
register_activation_hook( __FILE__, 'wpcvf_install' );

do_action( 'plugins_loaded' );

add_action('init', 'wpcvf_create_table' ); #Cria o banco de dados e a pasta onde vai ser salvo os arquivos

add_action( 'admin_head', 'wpcvf_icone' );

add_action('wp_head','wpcvf_ajaxurl');

#shortcode que chama formulario de cadastro
add_shortcode( 'formCadastro', 'wpcvf_cadastrar');
	
#shortcode para listar os currículos
add_shortcode( 'listCurriculos', 'wpcvf_listCurriculos' );

add_action('admin_menu', 'wpcvf_configuracoes'); #Cria um painel do plugin no administrativo do wordpress

//Adiciona a funcao extra votos aos hooks ajax do WordPress.
add_action('wp_ajax_wpcvf_checkCpf', 'wpcvf_checkCpf');
add_action('wp_ajax_nopriv_wpcvf_checkCpf', 'wpcvf_checkCpf');

add_action('wp_ajax_wpcvf_checkArea', 'wpcvf_checkArea');
add_action('wp_ajax_nopriv_wpcvf_checkArea', 'wpcvf_checkArea');

add_action('wp_ajax_wpcvf_carregar_cidade', 'wpcvf_carregar_cidade');
add_action('wp_ajax_nopriv_wpcvf_carregar_cidade', 'wpcvf_carregar_cidade');

add_action('wp_ajax_wpcvf_carregar_bairro', 'wpcvf_carregar_bairro');
add_action('wp_ajax_nopriv_wpcvf_carregar_bairro', 'wpcvf_carregar_bairro');

add_action('wp_ajax_wpcvf_verificarArquivo', 'wpcvf_verificarArquivo');
add_action('wp_ajax_nopriv_wpcvf_verificarArquivo', 'wpcvf_verificarArquivo');

add_action('wp_ajax_wpcvf_editArea', 		'wpcvf_editArea');
add_action('wp_ajax_noprivf_wpcv_editArea', 'wpcvf_editArea');

add_action('wp_ajax_wpcvf_lightBoxCurAdmin', 				'wpcvf_lightBoxCurAdmin');
add_action('wp_ajax_nopriv_wpcvf_lightBoxCurAdmin', 		'wpcvf_lightBoxCurAdmin');

add_action('wp_print_styles', 'wpcvf_estilos'); #Onde é chamado os CSSs do plugin - Visual externo do plugin

register_deactivation_hook( __FILE__, 'wpcvf_unistall' );



function wpcvf_install() {
	define('DISALLOW_FILE_EDIT', true );
  // Vamos testar a versão do PHP e do WordPress
  // caso as versões sejam antigas, desativamos
  // o nosso plugin.
  if ( version_compare( PHP_VERSION, '5.2.1', '<' )
    or version_compare( get_bloginfo( 'version' ), '3.3', '<' ) ) {
      deactivate_plugins( basename( __FILE__ ) );
  }
}

#Função que faz instalação do banco e cria a pasta
function wpcvf_create_table() {
  include_once( plugin_dir_path( __FILE__ ) . 'install.php' );
}

function wpcvf_icone(){
	wp_enqueue_style( 'wpvcStyle', plugins_url('css/wp_curriculo_style.css', __FILE__) );
}

function wpcvf_ajaxurl() {
?>

<script type="text/javascript">

/*Função Pai de Mascaras*/
function Mascara(o,f){
		v_obj=o
		v_fun=f
		setTimeout("execmascara()",1)
}

/*Função que Executa os objetos*/
function execmascara(){
		v_obj.value=v_fun(v_obj.value)
}

/*Função que Determina as expressões regulares dos objetos*/
function leech(v){
		v=v.replace(/o/gi,"0")
		v=v.replace(/i/gi,"1")
		v=v.replace(/z/gi,"2")
		v=v.replace(/e/gi,"3")
		v=v.replace(/a/gi,"4")
		v=v.replace(/s/gi,"5")
		v=v.replace(/t/gi,"7")
		return v
}

/*Função que permite apenas numeros*/
function Integer(v){
		return v.replace(/\D/g,"")
}

/*Função que padroniza CPF*/
function Cpf(v){
		v=v.replace(/\D/g,"")                                   
		v=v.replace(/(\d{3})(\d)/,"$1.$2")         
		v=v.replace(/(\d{3})(\d)/,"$1.$2")         
																						 
		v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2")

		return v
}

var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}

function wpcvf_configuracoes() {
	
	#Cria um menu dentro do menu options
	add_menu_page('Currículo Vitae', 'Currículo Vitae', 'manage_options', 'curriculo_vitae', 'curriculo_vitae', '');
	
	add_submenu_page('curriculo_vitae', 'Informações', 'Informações', 'manage_options', 'curriculo_vitae','curriculo_vitae', '');
	
	add_submenu_page('curriculo_vitae', 'Configuração de e-mails', 'Configuração de e-mails', 'manage_options', 'configuracao-emails','wpcvf_configuracao_emails', '');
	
	#Submenu para fazer um novo cadastro
	add_submenu_page('curriculo_vitae', 'Novo cadastro', 'Novo cadastro', 'manage_options', 'formulario-admin', 'wpcvf_formulario_admin');
	
	#Submenu que exibe a lista de currículos cadastrados
	add_submenu_page('curriculo_vitae', 'Lista de currículos', 'Lista de currículos', 'manage_options', 'lista-de-curriculos-admin', 'wpcvf_lista_curriculos_admin');
	
	#Submenu que exibe as áreas de serviços cadastrada e possibilita cadastrar novas áreas  
	add_submenu_page('curriculo_vitae', 'Áreas de serviços', 'Áreas de serviços', 'manage_options', 'areas-de-servicos', 'wpcvf_areas' );

}

function curriculo_vitae() {
	include_once( plugin_dir_path( __FILE__ ) . 'admin/informativo.php' );
}

function wpcvf_configuracao_emails() {
	include_once( plugin_dir_path( __FILE__ ) . 'admin/configuracao_emails.php' );
}

function wpcvf_formulario_admin() {
	include_once( plugin_dir_path( __FILE__ ) . 'admin/formulario.php' );
}

function wpcvf_lista_curriculos_admin() {
	include_once( plugin_dir_path( __FILE__ ) . 'admin/lista_curriculos.php' );
}

function wpcvf_areas() {
	include_once( plugin_dir_path( __FILE__ ) . 'admin/lista_areas_servicos.php' );
}

function wpcvf_estilos() {
	include_once( plugin_dir_path( __FILE__ ) . 'style.php' );
}

function wpcvf_checkCpf()
{
	global $wpdb;
	global $_POST;
		
	$cpf = $_POST['cpf'];

	$sqlCheckCpf = "SELECT cpf FROM ".$wpdb->prefix."wls_curriculo where cpf = '".$cpf."'";
    $queryCheckCpf = $wpdb->get_results( $sqlCheckCpf );
	
	$check = array();
	
	echo sizeof($queryCheckCpf);
	
}

function wpcvf_checkArea()
{
	global $wpdb;
	global $_POST;
		
	$area = $_POST['area'];

	$sqlCheckArea = "SELECT area FROM " . $wpdb->prefix . "wls_areas where area = '".$area."'";
    $queryCheckArea = $wpdb->get_results( $sqlCheckArea );
	
	$check = array();
	
	echo sizeof($queryCheckArea);
	
}

function wpcvf_carregar_cidade(){
	
	global $wpdb;
	global $_POST;
		
	$estado = $_POST['estado'];
	
	$optionCidade = "";
	$optionCidade .= "<option value=\"\">Selecione a cidade</option>";
	
	$sqlCidade = "SELECT cidade FROM ".$wpdb->prefix."wls_curriculo where estado = '".$estado."' group by cidade";
	
	$queryCidade = $wpdb->get_results( $sqlCidade );
	
	foreach($queryCidade as $kC => $vC){
         $optionCidade .= "<option value=\"".$vC->cidade."\">".$vC->cidade."</option>";
	}
	
	echo $optionCidade;
	die();
	
}

function wpcvf_carregar_bairro(){
	
	global $wpdb;
	global $_POST;
		
	$estado = $_POST['estado'];
	$cidade = $_POST['cidade'];
	
	$optionBairro = "";
	$optionBairro .= "<option value=\"\">Selecione o bairro</option>";
	
	$sqlBairro = "SELECT bairro FROM ".$wpdb->prefix."wls_curriculo where estado = '".$estado."' and cidade = '".$cidade."' group by bairro";
	$queryBairro = $wpdb->get_results( $sqlBairro );
	foreach($queryBairro as $kB => $vB){
         $optionBairro .= "<option value=\"".$vB->bairro."\">".$vB->bairro."</option>";
	}
	
	echo $optionBairro;	
	die();
	
}

function wpcvf_verificarArquivo()
{
	global $wpdb;
	global $_POST;
		
	$arquivo = $_POST['arquivo'];
	
	$array 	= explode("\\", $arquivo);
	$ext 	= explode(".", $array[count($array)-1]);
	
	#print_r($array);
	echo $ext[count($ext)-1];
	
}

function wpcvf_editArea()
{
	global $wpdb;
	global $_POST;
		
	$id 	= $_POST['rel'];
	$texto 	= $_POST['texto'];
			
	$var = array(
			  'area' 		=> $texto,	
	);

	// Guardar os valores na tabela
	$qry = $wpdb->update( $wpdb->prefix."wls_areas", $var, array('id' => $id), $format = null, $where_format = null );
	
	if($qry == false && $qry != 0) { 
		
		$wpdb->show_errors(); 
		
		$wpdb->print_error();
		
		exit;
		
	}else{	
		echo 1;
		echo $id;
		echo $texto;
	}
		
}

// Cria o lightbox para mostrar as informações do currículo no admin
function wpcvf_lightBoxCurAdmin(){

	global $wpdb;
	global $wpcvp;
	global $_POST;

	$id = $_POST['id'];

	$sql = "SELECT a.*,
				   b.area

						FROM ".$wpdb->prefix . "wls_curriculo a 
			
			left join ".$wpdb->prefix."wls_areas b
					on a.id_area = b.id

			WHERE a.id = " . $id;

	$dados = $wpdb->get_row($sql, ARRAY_A );

	//print "<pre>";
	//print_r($dados);
	//print "</pre>";

	if($dados['estado_civil']==1){
		$civil = "Solteiro(a)";
	}elseif($dados['estado_civil']==2){
		$civil = "Viuvo(a)";
	}elseif($dados['estado_civil']==3){
		$civil = "Casado(a)";
	}elseif($dados['estado_civil']==4){
		$civil = "Divorciado(a)";
	}elseif($dados['estado_civil']==5){
		$civil = "Amigável";
	}

	$descrInte = '<div class="container-fluid" >
                         
                          <h3><center>'.$dados['nome'].'</center></h3>                        
                          <p>
                              <strong>Nome:</strong> '.$dados['nome'].'<br />
                              <strong>Telefone:</strong> '.$dados['telefone'].'
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <strong>Celular:</strong> '.$dados['celular'].'<br />
                              <strong>E-mail:</strong> '.$dados['email'].'<br />
                              <strong>Site/Blog:</strong> '.$dados['site_blog'].'<br />
                              <strong>Skype:</strong> '.$dados['skype'].'<br />
                              <strong>Estado cívil:</strong> '.$civil.'
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <strong>Idade:</strong> '.$dados['idade'].'<br />
                              <strong>Área pretendida:</strong> '.$dados['area'].'<br />
                              <strong>Remuneração:</strong> R$ '.$dados['remuneracao'].'
                          </p>
                          
                          <p>
                              <strong>Endereço:</strong>
                              '.$dados['rua'].', '.$dados['numero'].'<br />
                              '.$dados['bairro'].' - '.$dados['cidade'].' - '.$dados['estado'].'<br />
                              <strong>CEP:</strong> '.$dados['cep'].'
                          </p>
                          
                          <p>
                          	<strong>Descrição:</strong><br />
  							            '.$dados['descricao'].'
                          </p>
                                                    
                          <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> 
                          <a href="mailto:'.$dados['email'].'" target="_blank">
                        		 '.$dados['email'].'
                          </a><br />';
                          
                          if($dados['curriculo'] != ""){ 
                            $descrInte .= '<a href="'.content_url( 'uploads/curriculos/'.$dados['curriculo']).'" target="_blank" >Baixar currículo</a>';
                          }
                    $descrInte .= '</div>';

    echo $descrInte;
    

}

function wpcvf_fecharSessao() {
    session_destroy ();
}

function wpcvf_unistall(){
	include_once( plugin_dir_path( __FILE__ ) . 'uninstall.php' );
}

#funcão que faz o cadastro
function wpcvf_cadastrar() {
	ob_start();
	  
	include_once( plugin_dir_path( __FILE__ ) . 'formulario.php' );
	  
	$formCadastro = ob_get_contents();
	ob_end_clean();
	
	return $formCadastro;
}

function wpcvf_listCurriculos() {
	
	ob_start();
	
	include_once( plugin_dir_path( __FILE__ ) . 'lista_curriculos.php' );
	
	$listCurriculos = ob_get_contents();
	
	ob_end_clean();
	
	return $listCurriculos;
}

?>