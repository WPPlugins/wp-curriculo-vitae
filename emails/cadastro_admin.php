<?php

global $wpdb, $wpcvf, $wls_curriculo, $wls_areas, $wls_curriculo_options;

if (!class_exists('PHPMailer')) {
require (dirname( __FILE__ ).'/../../../../wp-includes/class-phpmailer.php');
require (dirname( __FILE__ ).'/../../../../wp-includes/class-smtp.php');
}

$cpf = $_POST['cpf'];

$sql = "SELECT a.*,
			   b.area
		
		FROM ".$wls_curriculo." a
		
			left join ".$wls_areas." b
				on a.id_area = b.id
		
		where 1=1 and id = '".$id_cadastro."'";
		
$query = $wpdb->get_results( $sql, ARRAY_A  );

$caracteres = 8;
$senha = substr(uniqid(rand(), true),0,$caracteres);
$senha2 = $senha;

$var = array(
		'senha' 		=> $senha2,
	  );

$qry = $wpdb->update($wls_curriculo, $var, array('id' => $query[0]['id']), $format = null, $where_format = null );									
$sqlOp = "SELECT * FROM ".$wls_curriculo_options." where id=1";
		
$queryOp = $wpdb->get_results( $sqlOp, ARRAY_A );

foreach($queryOp as $kOp => $vOp){
	$dadosOp = $vOp;
}

$sqlCurriculo = "SELECT a.*, 
						b.area 
				
				FROM ".$wls_curriculo." a
				
					left join ".$wls_areas." b
						on a.id_area = b.id
				
				where a.id='".$id_cadastro."'";
		
$queryCurriculo = $wpdb->get_results( $sqlCurriculo, ARRAY_A );

foreach($queryCurriculo as $kCurriculo => $vCurriculo){
	$dadosCurriculo = $vCurriculo;
}

$msge = $dadosOp['mensagem_cadastro_admin'];

$msge = str_replace('@area'			, $dadosCurriculo['area']		, $msge);
$msge = str_replace('@senha'		, $dadosCurriculo['senha']		, $msge);
$msge = str_replace('@nome'			, $dadosCurriculo['nome']		, $msge);
$msge = str_replace('@email'		, $dadosCurriculo['email']		, $msge);
$msge = str_replace('@cpf'			, $dadosCurriculo['cpf']		, $msge);
$msge = str_replace('@cep'			, $dadosCurriculo['cep']		, $msge);
$msge = str_replace('@rua'			, $dadosCurriculo['rua']		, $msge);
$msge = str_replace('@bairro'		, $dadosCurriculo['bairro']		, $msge);
$msge = str_replace('@cidade'		, $dadosCurriculo['cidade']		, $msge);
$msge = str_replace('@estado'		, $dadosCurriculo['estado']		, $msge);
$msge = str_replace('@numero'		, $dadosCurriculo['numero']		, $msge);
$msge = str_replace('@telefone'		, $dadosCurriculo['telefone']	, $msge);
$msge = str_replace('@celular'		, $dadosCurriculo['celular']	, $msge);
$msge = str_replace('@site_blog'	, $dadosCurriculo['site_blog']	, $msge);
$msge = str_replace('@skype'		, $dadosCurriculo['skype']		, $msge);

$subject = $dadosOp['assunto_cadastro_admin']!=""?$dadosOp['assunto_cadastro_admin']:"Nova senha foi enviada";

$subject = str_replace('@area'		, $dadosCurriculo['area']		, $subject);
$subject = str_replace('@senha'		, $dadosCurriculo['senha']		, $subject);
$subject = str_replace('@nome'		, $dadosCurriculo['nome']		, $subject);
$subject = str_replace('@email'		, $dadosCurriculo['email']		, $subject);
$subject = str_replace('@cpf'		, $dadosCurriculo['cpf']		, $subject);
$subject = str_replace('@cep'		, $dadosCurriculo['cep']		, $subject);
$subject = str_replace('@rua'		, $dadosCurriculo['rua']		, $subject);
$subject = str_replace('@bairro'	, $dadosCurriculo['bairro']		, $subject);
$subject = str_replace('@cidade'	, $dadosCurriculo['cidade']		, $subject);
$subject = str_replace('@estado'	, $dadosCurriculo['estado']		, $subject);
$subject = str_replace('@numero'	, $dadosCurriculo['numero']		, $subject);
$subject = str_replace('@telefone'	, $dadosCurriculo['telefone']	, $subject);
$subject = str_replace('@celular'	, $dadosCurriculo['celular']	, $subject);
$subject = str_replace('@site_blog'	, $dadosCurriculo['site_blog']	, $subject);
$subject = str_replace('@skype'		, $dadosCurriculo['skype']		, $subject);

if($dadosOp['tipo_envio']=="0"){

	//$headers = "";
	$headers[] = "MIME-Version: 1.0\r\n";
	$headers[] = "Content-Type: text/html; charset=utf-8\r\n";
	$headers[] = "From: ".$dadosCurriculo['nome']." <".$dadosCurriculo['email'].">\r\n";

	// Call the wp_mail function, display message based on the result.
	if(wp_mail($dadosOp['email'], utf8_decode($subject), utf8_decode($msge),  $headers) ) {
		
		// the message was sent...
	    //echo '<div class="alert alert-success">Foi enviado uma mensagem para o seu e-mail.</div>';
	    $sendEmail="&sendMail=1";
		
	} else {
	    
		// the message was not sent...
	    //echo '<div class="alert alert-danger">Erro ao cadastrar o currículo. Tente novamente mais tarde.</div>';
	    $sendEmail="&sendMail=2";
		
	}
}elseif($dadosOp['tipo_envio']=="1"){
		
	$mail = new PHPMailer;

	//$mail->SMTPDebug = 3;                               // Enable verbose debug output

	//$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host 		= $dadosOp['mail.mydomain.com'];  					// Specify main and backup SMTP servers
	$mail->SMTPAuth 	= $dadosOp['smtp_autententicacao']=="1"?true:false; // Enable SMTP authentication
	$mail->Username 	= $dadosOp['usuario'];                 				// SMTP username
	$mail->Password 	= $dadosOp['senha'];                           		// SMTP password
	$mail->SMTPSecure 	= $dadosOp['seguranca']=="STARTTLS"?"tls":"ssl"; 	// Enable TLS encryption, `ssl` also accepted
	$mail->Port 		= (int)$dadosOp['porta_saida'];                  	// TCP port to connect to

	$mail->setFrom($dadosCurriculo['email'], $dadosCurriculo['nome']);
	
	$mail->addAddress($dadosOp['email'], $dadosOp['nome']);     // Add a recipient
	//$mail->addAddress('ellen@example.com');               // Name is optional

	//$mail->addReplyTo('info@example.com', 'Information');
	//$mail->addCC('cc@example.com');
	//$mail->addBCC('bcc@example.com');

	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = utf8_decode($subject);
	$mail->Body    = utf8_decode($msge);
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if(!$mail->send()) {
	    echo utf8_decode('Mensagem não enviado.<br/>');
	    echo utf8_decode('Mailer Error: ' . $mail->ErrorInfo);
	} else {
	    echo utf8_decode('Mensagem enviado para o administrador.<br/>');
	}

	

}

?>
