<?php

global $wpdb, $wpcvf, $wls_curriculo, $wls_areas, $wls_curriculo_options;

########### Função para excluir registro

if(isset($_POST['excl'])){
	$wpcvf->deleteTable($_POST['excl'], $wls_areas );
	
}


$cadastrar 		= @$_POST["cadastrar"];
$cadastrar_x 	= @$_POST["cadastrar_x"];

if(isset($_POST["cadastrar"])){

	global $wpdb;
   
	$area 		= $_POST["area"];
	
	// A Hora a que o usuário acessou
	$current_time = current_time( 'mysql' );
	
	// Checamos se não existe nenhum registo procedemos
	$var = array(
	  'area' 		=> $area,
	);
	
	/*print"<pre>";
	print_r($var);
	print"</pre>";*/
	
	$proto = strtolower(preg_replace('/[^a-zA-Z]/','',$_SERVER['SERVER_PROTOCOL'])); //pegando só o que for letra 
	$location = $proto.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
	$path = $wpcvf->removeMsg($location);
	
	$sql = "SELECT * FROM ".$wls_areas ." where area = '".$area."'  ";
	$query = $wpdb->get_results( $sql );
	$rows = $wpdb->num_rows;
	
	if($rows == 0){
		
		// Guardar os valores na tabela
		$qry = $wpdb->insert($wls_areas , $var );
		$msg = "&msg=1";
		echo "<script>location.href='".$path."".$msg."'</script>";
		
		if($qry == false) { 
		
		  $wpdb->show_errors(); 
		  
		  $wpdb->print_error();
		  
		  exit;
	  
  		}
		
	}else{
		
		$msg = "&msg=2";
		echo "<script>location.href='".$path."".$msg."'</script>";
		
		#$wpdb->show_errors();
		#$wpdb->print_error();
		#exit;
	}

}

wp_enqueue_style('wpcva_bootstrap', plugins_url('../css/bootstrap.min.css', __FILE__));
wp_enqueue_style('wpcva_style', plugins_url('css/style.css', __FILE__));

wp_enqueue_script('jquery');	
wp_enqueue_script('wpcva_bootstrapJS', plugins_url('../js/bootstrap.min.js', __FILE__));
wp_enqueue_script('wpcva_script', plugins_url('js/script.js', __FILE__));

if(isset($_GET['msg'])){
	$msg = @$_GET['msg'];
}

?>
<div id="wp-cvpf">
<div class="container">
    <h2>Áreas de serviços</h2>
    
    <form method="post">
      <div class="row">
      	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        	<div class="form-group has-feedback">
              	<label class="control-label" for="area">Cadastrar uma nova área:</label>
              	<input type="text" class="form-control" name="area" id="area" aria-describedby=""/>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        	<button type="submit" name="cadastrar" class="btn pull-left btn-primary">Cadastrar</button>
        </div>
      </div>
    </form>				
    
    <?php

		//######### INICIO Paginação
		$numreg = 20; // Quantos registros por página vai ser mostrado
		if($_GET['pg']){
			$pg = $_GET['pg'];
		}else{
			$pg = 0;
		}
		$inicial = $pg * $numreg;
		//######### FIM dados Paginação

		$sql 		= "SELECT * FROM ".$wls_areas." where 1=1 group by area order by area asc LIMIT $inicial, $numreg";
		$query 		= @$wpdb->get_results( @$sql );
		$rowsAreas 	= $wpdb->num_rows;

		$sqlRow = "SELECT * FROM ".$wls_areas." where 1=1 group by area order by area asc ";
		$queryRow = $wpdb->get_results( $sqlRow );
		$quantreg = $wpdb->num_rows; // Quantidade de registros pra paginação
		if($queryRow){
	?>
    		<?php if(@$_GET['msg']==1){?>
    			<div class="alert alert-success">Área de serviço cadastrado com sucesso!</div>
            <?php }elseif(@$_GET['msg']==2){?>
            	<div class="alert alert-danger">Essa área já existe.</div>
            <?php }elseif(@$_GET['msg']==3){ ?>
            	<div class="alert alert-success alert-dismissible">Área deletado com sucesso!</div>
            <?php }?>
            

            <div class="alert alert-warning" role="alert">
            	<strong>Atenção!</strong> <span style="color:red;">*</span>Para editar a área de serviço clique no nome que deseja alterar.
            </div>
            
            <div class="row">
            	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            		
            	</div>
            	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            		<div class="link-del-reg">
		              <span class="glyphicon glyphicon-remove red" aria-hidden="true"></span>
		              <a href="javascript:registros.submit();">Excluir registro</a>
		            </div>
            	</div>
            </div>
            
    		<form action="" method="post" name="registros" id="registros">
    			<div class="table-responsive">
	                <table class="table table-striped table-bordered table-condensed table-hover">
	                  <thead>
	                    <tr>
	                      <th>Áreas cadastradas</th>
	                      <th width="30" style="text-align:center;"><input type="checkbox" id="checkAll" /></th>
	                    </tr>
	                  </thead>
	                  <tbody>
	                  
	                  <?php 
	                      $x=0;
	                      foreach($query as $k => $v){
	                      //print_r($v);
	                  ?>
	                  
	                    <tr>
	                      <td><div id="response"></div><span class="areaEdit" rel="<?php echo $v->id ?>" ><?php echo @$v->area ?></span></td>
	                      <td style="text-align:center;">
							<input type="checkbox" name="excl[]" value="<?php echo $v->id ?>" class="check" />
	                      </td>
	                    </tr>
	                    
	                  <?php $x++; }  ?>
	                  
	                  </tbody>
	                </table>
	            </div>
            </form>
            <span style="position: relative; top: -15px;"><?php echo 'Existe <strong>' . $rowsAreas . '</strong> ' . ($rowsAreas<=1?'área de serviço cadastrada.':'áreas de serviços cadastradas.'); ?></span>

            <div style="clear:both;">
            
		<?php }else{ ?>
        
        	<div class="alert alert-success" style="text-align:center;">Nenhum cadastro de área encontrado.</div>
            
        <?php } ?>
        
    	<?php include( plugin_dir_path( __FILE__ ) . '../classes/paginacao2.php' ); ?>
    
</div>
<div id="black_overlay"></div>
<div id="aviso">
	<h4>Atualizando área de serviço</h4>
    <div id="avisoMensagem">
    	Aguarde um momento, enquanto atualiza a área de serviço <strong><span id="nomeArea"></span></strong>.
    </div>
    <div id="avisoMensagemErro" style="display:none;"><span id='fecharAviso'><strong>Clique aqui</strong></span> para fechar a mensagem.</div>
</div>
</div>