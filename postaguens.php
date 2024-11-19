<?php
header('content-type: text/html; charser=utf-8');
include "connect.php";
echo "<br>";
$sql = "select * from tb_postagens where id_user = '$id_log'";
$postagens = mysqli_query($link, $sql);
while($dados = mysqli_fetch_array($postagens)){
    echo "<p class='posts'>$dados[id_postagem]: $dados[postagem]
    <a href='update.php?id_post=$dados[id_postagem]'><img src='imagens/Atualizar.png' class='img_posts'></a>
    <a href='user.php?page=3'><img src='imagens/creat.png' class='img_posts'></a><br>
    </p>";
}

?>


