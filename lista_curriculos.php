<?php

global $wpdb, $wpcvf, $wls_curriculo, $wls_areas, $wls_curriculo_options;
	
$pg = $_GET['pg'];

foreach ($_POST as $key=>$value){
  ${$key} = $value;
}

$where = "";


if($buscar != ''){
	$where .= " and ( LOWER(a.nome) LIKE  '%".strtolower($buscar)."%' or LOWER(a.descricao) LIKE '%".strtolower($buscar)."%' or LOWER(b.area) LIKE '%".strtolower($buscar)."%')";
}

if($bairro != ''){
	$where .= " and LOWER(a.bairro) LIKE  '%".strtolower($bairro)."%'";
}

if($cidade != ''){
	$where .= " and LOWER(a.cidade) LIKE  '%".strtolower($cidade)."%'";
}

if($estado != ''){
	$where .= " and LOWER(a.estado) LIKE  '%".strtolower($estado)."%'";
}

######### INICIO Paginação
$numreg = 20; // Quantos registros por página vai ser mostrado
if (!isset($pg)) {
	$pg = 0;
}

$inicial = $pg * $numreg;

######### FIM dados Paginação

$sql = "SELECT a.*,
			   b.area
		
		FROM ".$wls_curriculo." a
		
			left join ".$wls_areas." b
				on a.id_area = b.id
		
		where 1=1 $where order by a.nome asc LIMIT $inicial, $numreg ";
		
$query = $wpdb->get_results( $sql );

$sqlRow = "SELECT a.*,
				  b.area
		   
		   FROM ".$wls_curriculo." a
		   
		   		left join ".$wls_areas." b
					on a.id_area = b.id
		   
		   where 1=1 $where order by a.nome asc";
$queryRow = $wpdb->get_results( $sqlRow );
$quantreg = $wpdb->num_rows; // Quantidade de registros pra paginação

	
include( plugin_dir_path( __FILE__ ) . 'classes/estados.php' );

?>
<div id="wp-cvpf">
        <form id="wp-curriculo-busca-rapida" method="post">
          <input type="hidden" id="url_ajax" value="<?php echo admin_url( 'admin-ajax.php' ); ?>"  />

          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
              <div class="form-group">
                <div class="controls">
                  <input type="text" name="buscar" placeholder="Nome, área de atuação, experiência..." class="form-control" > 
                </div>
              </div>   
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
              <div class="form-group">
                <div class="controls">
                  <select name="estado" class="form-control" id="estado">
                    <option value="">Selecione o estado</option>
                    <?php
                  
                        $sqlEstado = "SELECT estado FROM ".$wls_curriculo." where 1=1 group by estado order by estado";
                        $queryEstado = $wpdb->get_results( $sqlEstado );
                    ?>
                    <?php foreach($queryEstado as $kE => $vE){?>
                        <option value="<?php echo $vE->estado?>"><?php echo utf8_encode($estadoArray[$vE->estado])?></option>
                    <?php }?>
                  </select>
                </div>
              </div>
            </div>

          </div>   
          
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
              <div class="form-group">
                <div class="controls">
                  <select name="cidade" class="form-control" disabled="disabled" id="cidade">
                    <option value="">Selecione a cidade</option>
                  </select>
                </div>
              </div>   
            </div>
            
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
              <div class="form-group">
                <div class="controls">
                  <select name="bairro" class="form-control" disabled="disabled" id="bairro">
                    <option value="">Selecione o bairro</option>
                  </select>
                </div>
              </div>  
            </div> 

          </div>

          <button type="submit" name="novaSenha" class="btn btn-primary">Pesquisar</button>          

        </form>
        <div style="clear:both; height:30px; "></div>
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Área de serviço</th>
                <th style="text-align:center;">E-mail</th>
                <th style="text-align:center;">Arquivo</th>
              </tr>
            </thead>
            <tbody>
            <?php 
            $x=1;
              foreach($query as $k => $v){
                  //print_r($v);
                  
                  if($v->estado_civil==1){
                      $civil = "Solteiro(a)";
                  }elseif($v->estado_civil==2){
                      $civil = "Viuvo(a)";
                  }elseif($v->estado_civil==3){
                      $civil = "Casado(a)";
                  }elseif($v->estado_civil==4){
                      $civil = "Divorciado(a)";
                  }elseif($v->estado_civil==5){
                      $civil = "Amigável";
                  }
                  ?>
                  <input type="hidden" id="id_registro_<?php echo $x?>" value="<?php echo $v->id ?>" />
                  <tr>
                    <td><?php echo $v->nome ?></td>
                    
                    <td>
                    	<a class="abrirDescricao" rel="<?php echo $x; ?>" style="cursor:pointer;" >Visualizar</a>
                    </td>
                    
                    <td><?php echo $v->area ?></td>
                    
                    <td style="text-align:center;"><a href="mailto:<?php echo $v->email?>" target="_blank">
                    
                    <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></a></td>
                    
                    <td style="text-align:center;">
                      <?php if($v->curriculo != ""){ ?>
                                <a href="<?php echo content_url( 'uploads/curriculos/'.$v->curriculo); ?>" target="_blank" > 
                                <span class="glyphicon glyphicon-file" aria-hidden="true"></span></a>
                      <?php  }else{ ?>
                                -
                      <?php  } ?>
                      
                    </td>
                    
                  </tr>
                  
            <?php $x++; }  ?>
              
            </tbody>
          </table>
        </div>

        <div style="display:none" id="LigthBoxCurriculo" class="wpcvf_lightbox_content" ></div>
        
		<?php if($quantreg > $numreg){
				// Chama o arquivo que monta a paginação. ex: << anterior 1 2 3 4 5 próximo >> 
				include( plugin_dir_path( __FILE__ ) . 'classes/paginacao2.php' ); 
			}
		?>

<?php
wp_enqueue_script('scriptJSa', plugins_url('js/script.js', __FILE__));
?>
<div id="black_overlay"></div>
</div>