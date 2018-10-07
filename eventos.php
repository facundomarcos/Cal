<?php
header('Content-Type: application/json');
$pdo=new PDO("mysql:dbname=calendar;host=127.0.0.1","root","z5VqdJiRmcJea4");
//si hay una accion, respeto la accion que viene del formulario, sino -> solo lectura
$accion= (isset($_GET['accion']))?$_GET['accion']:'leer';

switch($accion){
    case 'agregar':
        //instruccion de agregado
        $sql = $pdo->prepare("insert into eventos(title,descripcion,color,textColor,start,end) values(:title,:descripcion,:color,:textColor,:start,:end)");
        $respuesta=$sql->execute(array(
            "title" =>$_POST['title'],
            "descripcion" =>$_POST['descripcion'],
            "color" =>$_POST['color'],
            "textColor" =>$_POST['textColor'],
            "start" =>$_POST['start'],
            "end" =>$_POST['end']
        ));
        echo json_encode($respuesta);
        
        break;
    case 'eliminar':
        //instruccion de eliminado
        //echo "instruccion eliminar";
        $respuesta=false;
        if(isset($_POST['id'])){
            $sql= $pdo->prepare("delete from eventos where ID=:ID");
            $respuesta= $sql->execute(array("ID"=>$_POST['id']));
        }
        echo json_encode($respuesta);
        break;
    case 'modificar':
        //instruccion de modificar
        //echo "instruccion modificar";
        $sql = $pdo->prepare("update eventos set
        title=:title,
        descripcion=:descripcion,
        color=:color,
        textColor=:textColor,
        start=:start,
        end=:end
        where ID=:ID
        ");
        
         $respuesta=$sql->execute(array(
             "ID" =>$_POST['id'],
            "title" =>$_POST['title'],
            "descripcion" =>$_POST['descripcion'],
            "color" =>$_POST['color'],
            "textColor" =>$_POST['textColor'],
            "start" =>$_POST['start'],
            "end" =>$_POST['end']
         ));
        echo json_encode($respuesta);
        break;
    default:
        //seleccinar eventos
            $sql=$pdo->prepare("select * from eventos");
            $sql->execute();

            $res= $sql->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($res);
        break;
        
        
}




?>
