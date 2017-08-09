<?php 

header("Content-type: text/css");

include('../../../wp-config.php');

// conecta ao banco de dados 
$con = mysql_pconnect(DB_HOST, DB_USER, DB_PASSWORD) or trigger_error(mysql_error(),E_USER_ERROR); 
// seleciona a base de dados em que vamos trabalhar 
mysql_select_db(DB_NAME, $con);

$wls_curriculo_options 			     = $table_prefix . 'wls_curriculo_options';

$sql = "SELECT * FROM ".$wls_curriculo_options." where 1=1 and id=1"; 

$query = mysql_query($sql, $con) or die(mysql_error()); 
while($dados = mysql_fetch_array($query, MYSQL_ASSOC)){

?>



<?php echo $dados['css'];?>

<?php } ?>