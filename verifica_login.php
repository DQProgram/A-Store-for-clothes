<?php
include "connect.php"; 
//Recupero a sessão 
//SESSION_START();
//$l de login
$l = isset($_SESSION["login_user"])?$_SESSION["login_user"]:"";
//$s de senha
$s = isset($_SESSION["senha_user"])?$_SESSION["senha_user"]:"";

if($l != "" && $s != ""){
    $sql = mysqli_query($link,"select * from tb_user WHERE email = '$l'");
    while($d = mysqli_fetch_array($sql)){
        $id_log = $d['id_user'];
        $nome_log = $d['nome'];
        $email_log = $d['email'];
        $senha_log = $d['senha'];
        $dica_log = $d['dica'];
        $capa_log = $d['capa'];
        $perfil_log = $d['perfil'];
    }
}else{
    header('location:index.php');
}
?>