<?php
function ProcessRequest($request){
if($_SERVER['REQUEST_METHOD'] === "POST"){
    $post = $_POST;
    
    if(!empty($post['action'])){
        switch($post['action']){
            case 'SaveA':{
                $name = htmlspecialchars($_POST['name']);
                $phone =htmlspecialchars($_POST['phone']);
                $people =htmlspecialchars($_POST['people']);
                $type =htmlspecialchars($_POST['type']);
            
                $file = fopen('./ali.csv', 'a');
                if($file){
                    fputcsv($file,[$name, $phone , $people , $type]);
                    fclose($file);
                }
                die("Done");
            }break;
            case "SaveB":{
              
                $name2 = htmlspecialchars($_POST['name2']);
                $phone2 =htmlspecialchars($_POST['phone2']);
                $people2 =htmlspecialchars($_POST['people2']);
                $type2 =htmlspecialchars($_POST['type2']);
            
                $file = fopen('./alicoppy.csv', 'a');
                if($file){
                    fputcsv($file,[$name2, $phone2 , $people2 , $type2]);
                    fclose($file);
                }
                die("Done");
            }break;
        }
    }
}
}
?>


