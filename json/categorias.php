<?php
    header("Content-Type:application/json");
    
    include "config.php";
    
    $sql = "select * from categoria order by nome";
    
    $res = $pdo->prepare($sql);
    $res->execute();
    while ($l = $res->fetch(PDO::FETCH_OBJ)) {
        $dados[$l->id] = $l; 
    }
    
    echo json_encode($dados);