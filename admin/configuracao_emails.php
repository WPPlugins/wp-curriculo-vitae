<?php

global $wpdb, $wpcvf, $wls_curriculo, $wls_areas, $wls_curriculo_options;


if(isset($_POST['salvar'])){
	require_once("include/enviarOptions.php");
}

$sqlOp = "SELECT * FROM ".$wls_curriculo_options." where 1=1 and id=1";
		
$queryOp = $wpdb->get_results( $sqlOp, ARRAY_A );
foreach($queryOp as $kOp => $vOp){
	$dadosOp = $vOp;
}

wp_enqueue_style('wpcva_bootstrap', plugins_url('../css/bootstrap.css', __FILE__));

wp_enqueue_script('jquery');	
wp_enqueue_script('wpcva_bootstrapJS', plugins_url('../js/bootstrap.min.js', __FILE__));
wp_enqueue_script('wpcva_script', plugins_url('js/script.js', __FILE__));

?>
<div id="wp-cvpf">
  <div class="container">
      
      <h2>Configurações de e-mails</h2>
      <p>Para usar as informações do cadastrado no e-mail usar os comandos abaixo:</p>
      <div class="rows">
      	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
              <strong>@nome</strong><br />
              <strong>@email</strong><br />
              <strong>@cpf</strong><br />
              <strong>@cep</strong><br />
              <strong>@rua</strong><br />
              <strong>@bairro</strong><br />
  		</div>
          <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
          	<strong>@cidade</strong><br />
            <strong>@estado</strong><br />
            <strong>@numero</strong><br />
            <strong>@telefone</strong><br />
            <strong>@celular</strong><br />
            <strong>@site_blog</strong><br />
            <strong>@skype</strong><br />

          </div>
      </div>
      <div style="clear:both; height:20px;"></div>

  <?php 
  if($_POST['salvar']){ 
  	include(plugin_dir_path( __FILE__ ) . 'include/enviarOptions.php');  
  }
  ?>
  	
  <?php if(@$_GET['msg']==1){ ?>

    <div class="alert alert-success">Salvo com sucesso!</div>	
    
  <?php } ?>

  	<form method="post">
          <h2>Configuração de CSS</h2>   
          <div class="form-group">
            <label class="control-label">CSS:</label>
            <div class="controls">
              <textarea name="css" id="css" class="form-control" ><?php echo $dadosOp['css'];?></textarea>
            </div>
          </div> 
  		    <h3>Configura&ccedil;&otilde;es de e-mail cadastro</h3>
      
          <div class="form-group">
            <label class="control-label">Assunto:</label>
            <div class="controls">
              <input type="text" name="assunto_cadastro" id="assunto_cadastro" value="<?php echo $dadosOp['assunto_cadastro'];?>" class="form-control" /> 
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label">Mensagem:</label>
            <div class="controls">
              
              <?php /*<textarea name="mensagem_cadastro" id="mensagem_cadastro" class="form-control" ><?php echo $dadosOp['mensagem_cadastro'];?></textarea> */ ?>
              
              <?php wp_editor( $dadosOp['mensagem_cadastro'], 'wpa_mensagem_cadastro', $settings = array('textarea_name' => mensagem_cadastro) ); ?>
            </div>
          </div>

          <h3>Configura&ccedil;&otilde;es de e-mail cadastro para o admin</h3>
      
          <div class="form-group">
            <label class="control-label">Assunto:</label>
            <div class="controls">
              <input type="text" name="assunto_cadastro_admin" id="assunto_cadastro_admin" value="<?php echo $dadosOp['assunto_cadastro_admin'];?>" class="form-control" /> 
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label">Mensagem:</label>
            <div class="controls">
              <?php /*<textarea name="mensagem_cadastro_admin" class="form-control" id="wpcvp_mensagem_cadastro_admin"><?php echo $dadosOp['mensagem_cadastro_admin'];?></textarea> */?>
              
              <?php wp_editor( $dadosOp['mensagem_cadastro_admin'], 'wpcvp_mensagem_cadastro_admin', $settings = array('textarea_name' => mensagem_cadastro_admin) ); ?>
              
            </div>
          </div>
      		
          <h3>Personalizar configura&ccedil;&otilde;es de remetente</h3>

          <div class="rows">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <label for="tipo_envio" class="control-label">Tipo de envio:</label>
                <div class="controls">
                  <select class="form-control" name="tipo_envio" id="tipo_envio">
                      <option value="0" <?php echo @$dadosOp['tipo_envio']=="0"?"selected":"";?>>Normal</option>
                      <option value="1" <?php echo @$dadosOp['tipo_envio']=="1"?"selected":"";?>>SMTP</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          
          <?php $hidden =  @$dadosOp['tipo_envio']=="1"?"":"style='display:none;'";?>

          <div class="rows smtp" <?php echo $hidden ?>>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group">
              <label class="control-label">Host:</label>
              <div class="controls">
                <input type="text" name="host" id="host" value="<?php echo $dadosOp['host'];?>" class="form-control" /> 
              </div>
            </div>
          </div>
          
          <div class="rows smtp" <?php echo $hidden ?>>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
              <div class="form-group">
                  <label class="control-label">Usuário:</label>
                  <div class="controls">
                    <input type="text" name="usuario" id="usuario" value="<?php echo $dadosOp['usuario'];?>" class="form-control" /> 
                  </div>
                </div>
            </div>

            
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
              <div class="form-group">
                  <label class="control-label">Senha:</label>
                  <div class="controls">
                    <input type="password" name="senha" id="senha" value="<?php echo $dadosOp['senha'];?>" class="form-control" /> 
                  </div>
                </div>
            </div>
          </div>

          <div class="rows smtp" <?php echo $hidden ?>>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
              <div class="checkbox">
              <label>
                <input type="checkbox" value="1" name="smtp_autententicacao" <?php echo @$dadosOp['smtp_autententicacao']=="1"?"checked":"";?>>
                  SMTP com altenticação de segurança?
              </label>
            </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
              <div class="form-group">
              <label class="control-label">Segurança:</label>
              <div class="controls">
                <select class="form-control" name="seguranca" id="seguranca">
                  <option value="0"></option>
                  <option value="STARTTLS" <?php echo @$dadosOp['seguranca']=="STARTTLS"?"selected":"";?>>STARTTLS</option>
                  <option value="SSL/TLS" <?php echo @$dadosOp['seguranca']=="SSL/TLS"?"selected":"";?>>SSL/TLS</option>
                </select>
              </div>
            </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
              <div class="form-group">
                    <label class="control-label">Porta de saída:</label>
                    <div class="controls">
                      <input type="text" name="porta_saida" id="porta_saida" value="<?php echo $dadosOp['porta_saida'];?>" class="form-control" /> 
                    </div>
                  </div>
              </div>
            </div>
          </div>

          <div class="rows">
          	
              <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
              	<div class="form-group">
                    <label class="control-label">Nome:</label>
                    <div class="controls">
                      <input type="text" name="nome" id="nome" value="<?php echo $dadosOp['nome'];?>" class="form-control" /> 
                    </div>
                  </div>
              </div>
              
              <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
              	<div class="form-group">
                    <label class="control-label">E-mail:</label>
                    <div class="controls">
                      <input type="text" name="email" id="email" value="<?php echo $dadosOp['email'];?>" class="form-control" /> 
                    </div>
                  </div>
              </div>
              
          </div>
          <div style="clear:both; height:20px;"></div>
          <button type="submit" name="salvar" id="cadastrar" class="btn btn-primary">Salvar</button>
          
      </form>

      
  </div>
</div>