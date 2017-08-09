<?php
#define('DISALLOW_FILE_EDIT', true );
// Acesso ao objeto global de gestão de bases de dados
global $wpdb, $wpcvf, $wls_curriculo, $wls_areas, $wls_curriculo_options;

// Vamos checar se a nova tabela existe
// A propriedade prefix é o prefixo de tabela escolhido na
// instalação do WordPress


// Se a tabela não existe vamos criá-la
	if ( $wpdb->get_var( "SHOW TABLES LIKE '".$wls_curriculo."'" ) != $wls_curriculo ) {	  
  
	  $sql = "
			
			CREATE TABLE ".$wls_curriculo."(
				  id 			int(11) 		NOT NULL AUTO_INCREMENT,
				  id_area 		int(11) 		DEFAULT NULL,
				  nome 			varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  cpf 			varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  telefone 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  celular 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  email 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  site_blog 	varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  skype 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  estado_civil 	varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  idade 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  sexo 			int(11) 		DEFAULT NULL,
				  remuneracao 	varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  				  
				  rua 			varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  numero 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  bairro 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  cidade 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  estado 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  cep 			varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  
				  curriculo 	varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
				  
				  descricao 	text 			COLLATE latin1_bin,
				  status 		int(11) 		DEFAULT NULL,
				  PRIMARY KEY (id)
			);";
  }
 		
  if ( $wpdb->get_var( "SHOW TABLES LIKE '".$wls_areas."'" ) != $wls_areas ) {	  		  
	
	$sql1 = "
	
		CREATE TABLE ".$wls_areas." (
			id	 	int(11) 		NOT NULL AUTO_INCREMENT,
			area 	varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
			PRIMARY KEY (id)
		);";
		
  }
	
  if ( $wpdb->get_var( "SHOW TABLES LIKE '".$wls_curriculo_options."'" ) != $wls_curriculo_options ) {	  		  
	
	$sql2 = "
	
		CREATE TABLE ".$wls_curriculo_options." (
			id 						int(11) 		NOT NULL AUTO_INCREMENT,
			assunto_cadastro 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
			mensagem_cadastro 		text 			COLLATE latin1_bin DEFAULT NULL,
			assunto_cadastro_admin 	varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
			mensagem_cadastro_admin text 			COLLATE latin1_bin DEFAULT NULL,
			assunto_aprovacao 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
			mensagem_aprovacao 		text 			COLLATE latin1_bin DEFAULT NULL,
			assunto_esqueceu 		varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
			mensagem_esqueceu 		text 			COLLATE latin1_bin DEFAULT NULL,
			emails_recebimento 		text 			COLLATE latin1_bin DEFAULT NULL,
			
			tipo_envio 				int(11) 		DEFAULT '0',

			email 					varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
			nome 					varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
			
			usuario 				varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
			senha 					varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
			smtp_autententicacao 	int(11) 		DEFAULT '0',
			seguranca 				varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
			porta_saida 			varchar(255) 	COLLATE latin1_bin DEFAULT NULL,
			host 					varchar(255) 	COLLATE latin1_bin DEFAULT NULL,

			css 					text 			COLLATE latin1_bin DEFAULT NULL,
			PRIMARY KEY (id)
		);";
  	}

  	if(!is_array($wpdb->get_row("SELECT a.assunto_cadastro_admin FROM ".$wls_curriculo_options." a where 1=1 ", ARRAY_A))){
  		$wpdb->get_row("ALTER TABLE ".$wls_curriculo_options." ADD assunto_cadastro_admin varchar(255) DEFAULT NULL AFTER mensagem_cadastro", ARRAY_A);
  	}
  	
  	if(!is_array($wpdb->get_row("SELECT a.mensagem_cadastro_admin FROM ".$wls_curriculo_options." a where 1=1 ", ARRAY_A))){
  		$wpdb->get_row("ALTER TABLE ".$wls_curriculo_options." ADD mensagem_cadastro_admin text DEFAULT NULL AFTER assunto_cadastro_admin", ARRAY_A);
  	}

  	if(!is_array($wpdb->get_row("SELECT a.css FROM ".$wls_curriculo_options." a where 1=1 ", ARRAY_A))){
  		$wpdb->get_row("ALTER TABLE ".$wls_curriculo_options." ADD css text DEFAULT NULL AFTER host", ARRAY_A);
  	}
	
	$sqlOp = "SELECT * FROM ".$wls_curriculo_options." where id=1";
		
	$queryOp = $wpdb->get_results( $sqlOp, ARRAY_A );
	
	foreach($queryOp as $kOp => $vOp){
		$dadosOp = $vOp;
	}
	
	if($dadosOp['id']!=1){
		$assunto_cadastro 	= "Seu currículo foi cadastrado com sucesso!";
		$mensagem_cadastro 	= "Seu Currículo foi cadastrado com sucesso!<br/>\n";
		
		$assunto_cadastro_admin 	= "Novo currículo cadastrado";
		$mensagem_cadastro_admin 	= "Nome: @nome <br/>
			Área de serviço: @area";
		
		
		
		$varOptions = array(
		  'assunto_cadastro' 	=> $assunto_cadastro,
		  'mensagem_cadastro' 	=> $mensagem_cadastro,
		  'assunto_cadastro_admin' 	=> $assunto_cadastro_admin,
		  'mensagem_cadastro_admin' => $mensagem_cadastro_admin,
		  
		  
		);
		
		$wpdb->insert($wls_curriculo_options, $varOptions );
	}
		
	$sql3 ="	
		CREATE TABLE ".$wls_curriculo."wls_curriculo LIKE wls_curriculo;
		CREATE TABLE ".$wls_areas."wls_areas LIKE wls_areas;		";
		
				
		#$update = $wpdb->query( "UPDATE wls_curriculo SET curriculo = file" );
		#mysql_query($update);

  // Para usarmos a função dbDelta() é necessário carregar este ficheiro
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

  // Esta função cria a tabela na base de dados e executa as otimizações
  // necessárias.
  dbDelta( $sql );
  dbDelta( $sql1 );
  dbDelta( $sql2 );
  dbDelta( $sql3 );
  
  $upload = wp_upload_dir();
  $upload_dir = $upload['basedir'];
  $upload_dir = $upload_dir . '/curriculos';
  
  if (! is_dir($upload_dir)) {
	 mkdir( $upload_dir, 0777 );
  }

//}
?>