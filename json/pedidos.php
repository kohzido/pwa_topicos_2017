<?php
    session_start();
    header("Content-Type:application/json");
    
    include "config.php";
    
    $msg = "";
    if (!isset($_SESSION["usuario"])) {
        $msg = array("erro"=>1,
            "mensagem"=>"O usuário não está logado");
    } else if ($_POST["id"]) {
        $id = trim($_POST["id"]);
        $sql = "select *"
                . ", date_format(data, '%d/%m/%Y') dt "
                . "from pedido where client_id = ? "
                . "order by data";
        $res = $pdo->prepare($sql);
        $res->bindParam(1, $id);
        $res->execute();
        while ($l = $res->fetch(PDO::FETCH_OBJ)) {
            $l->imagem = "http://LOCALHOST/imgs/$l->imagem";
            $dados[$l->id] = $l;
        }      
    }
    
    echo json_encode($dados);