<?php

global $wpdb, $wpcvf, $wls_curriculo, $wls_areas, $wls_curriculo_options;

$pg = $_GET['pg'];

$buscar = $_POST['buscar'];

$msg = $_GET['msg'];

$where = "";

if($buscar){
	$where .= " and (nome LIKE  '%".$buscar."%' or descricao LIKE '%".$buscar."%')";
}


########### Função para excluir registro
if(isset($_POST['excl'])){
  $wpcvf->deleteTable($_POST['excl'], $wls_curriculo );
}

//######### INICIO Paginação
$numreg = 20; // Quantos registros por página vai ser mostrado
if (!isset($pg)) {
	$pg = 0;
}
$inicial = $pg * $numreg;

//######### FIM dados Paginação

$sql = "SELECT a.*,
			   b.area
		
		FROM ".$wls_curriculo." a
		
			left join ".$wls_areas." b
				on a.id_area = b.id
		
		where 1=1 $where order by a.nome asc LIMIT $inicial, $numreg ";
		
$query      = $wpdb->get_results( $sql );
$rowsCurr   = $wpdb->num_rows;

$sqlRow = "SELECT a.*,
				  b.area
		   
		   FROM ".$wls_curriculo." a
		   
		   		left join ".$wls_areas." b
					on a.id_area = b.id
		   
		   where 1=1 $where order by a.nome asc";
		   
$queryRow = $wpdb->get_results( $sqlRow );
$quantreg = $wpdb->num_rows; // Quantidade de registros pra paginação

wp_enqueue_style('wpcva_bootstrap', plugins_url('../css/bootstrap.min.css', __FILE__));
wp_enqueue_style('wpcva_styleAdminP', plugins_url('css/style.css', __FILE__));
wp_enqueue_style("wpcva_styleP", plugins_url('../css/wp_curriculo_style.css', __FILE__));

wp_enqueue_script('jquery');

wp_enqueue_script('wpcva_bootstrapAdminJS', plugins_url('../js/bootstrap.min.js', __FILE__));
#wp_enqueue_script('wpcva_lightboxjs', plugins_url('../js/wpcvf_lightbox.js', __FILE__));
wp_enqueue_script('wpcva_script', plugins_url('js/script.js', __FILE__));
		
?>
<div id="wp-cvpf">
    <div class="container-fluid">
      <h2 style="float:left;">Listagem de currículos</h2>
      
      <a class="bt_novo" href="?page=formulario-admin">Novo cadastro</a>
      
      <div style="clear:both;"></div>
      
  	  <?php if(@$_GET['msg']==2){ ?>

            <div class="alert alert-success">Atualizado com sucesso!</div>	
    
  	  <?php }elseif(@$_GET['msg']==3){ ?>

            <div class="alert alert-success">Registro deletado com sucesso!</div>	
          
      <?php }?>
      
      
      <div class="container">
        <div class="link-del-reg">
        	
          <span class="glyphicon glyphicon glyphicon-remove red" aria-hidden="true"></span>
          <a href="javascript:registros.submit();">Excluir registro</a>
        </div>      
      </div>
      
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Currículo</th>
              <th>Área de serviço</th>
              <th width="60" style="text-align:center;">E-mail</th>
              <th width="50" style="text-align:center;">Arquivo</th>
              <th width="30" style="text-align:center;">Editar</th>
              <th width="30" style="text-align:center;"><input type="checkbox" id="checkAll" /></th>
            </tr>
          </thead>
          <tbody>
          <form action="?page=lista-de-curriculos-admin" name="registros" id="registros" method="post">
          
          <?php 
          	$x=0;
            
            	foreach($query as $k => $v){
                
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
                  
                  <td ><a class="abrirDescricao" rel="<?php echo $x; ?>" style="cursor:pointer;" >Visualizar</a><?php #echo $v->descricao ?></td>
                  
                  
                  <td><?php echo $v->area ?></td>
                  
                  <td style="text-align:center;">
                    <a href="mailto:<?php echo $v->email?>" target="_blank">
                      <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                    </a>
                  </td>
                  
                  <td style="text-align:center;">
                    <?php if($v->curriculo != ""){ ?>
                              <a href="<?php echo content_url( 'uploads/curriculos/'.$v->curriculo); ?>" target="_blank" > 
                                <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                                </a>
                    <?php  }else{ ?>
                              -
                    <?php  } ?>
                    
                  </td>
                  
                  <td style="text-align:center;">
                  	
                      <a href="?page=formulario-admin&id_cadastro=<?php echo $v->id?>" >
                        
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      </a>
                        
                      
                  </td>
                  
                  <td style="text-align:center;">                    
                      <input type="checkbox" name="excl[]" value="<?php echo $v->id; ?>" class="check" />
                  </td>
                  
                </tr>
                
          <?php $x++; }  ?>
          </form>  
          </tbody>
        </table>
      </div>
      <span style="position: relative; top: -15px;"><?php echo 'Existe <strong>' . $rowsCurr . '</strong> ' . ($rowsCurr<=1?'currículo cadastrado.':'currículos cadastrados.'); ?></span>

      <div style="display:none" id="LigthBoxCurriculo" class="wpcvf_lightbox_content" ></div>

      <div style="clear:both;">

      <?php include( plugin_dir_path( __FILE__ ) . '../classes/paginacao2.php' ); ?>
      
    </div>
	<div id="black_overlay"></div>
  </div>