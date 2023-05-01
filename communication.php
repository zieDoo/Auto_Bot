<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// var_dump($_POST);
// print_r($_POST);
// $function = $_POST["function"];

if(isset($_POST['function']))
{
  $function = $_POST["function"];
}


if ($function == "login"){
    create_socket();
} else if ($function == "start"){
    start();
}else if ($function == "stop"){
    stop();
}else if ($function == "update"){
    get_update_new($function);
}else if ($function == "show"){
    get_update_new($function);
}else if ($function == "logout"){
    destroy_socket();
}else if ($function == "test"){
    test();
}


function get_update_new($x){
    
    if ($x == "update"){
        echo "- UPDATE -";
        echo "<br>";
    }
    elseif ($x == "show"){
        echo "- SHOW -";
        echo "<br>";
    }

    $output = array();
    exec("/usr/bin/python3.6 /var/www/html/Projects/Auto_Bot/Local.py '$x' ", $output);

    // $returned_string_coded = exec("/usr/bin/python3.6 /var/www/html/Projects/Auto_Bot/Local.py '$x' 2>&1 ");
    // var_dump($output);

    $result_str = implode(' ', $output);
    // echo $result_str;

    $decodujem_string = base64_decode($result_str);
    // echo $decodujem_string;

    $new_final = mb_convert_encoding($decodujem_string, 'UTF-8');
    
    echo '<pre>';
    // echo $new_final;
    // var_dump($new_final);
    // print_r($new_final);
    // var_dump($new_final);

    $bez_uvodz = str_replace(array("{", "}", "'"), '', $new_final);
    // print_r($bez_uvodz);


    $new_expl = explode(", ", $bez_uvodz);
    // print_r($new_expl);

    $pole_ID = [];
    $pole_values = [];

    foreach($new_expl as $element){
        $el = trim($element);
        # print_r($el);
        // secho "<br>";
        $el2 = explode(': ', $el);

        $pole_ID[] = $el2[0];
        $pole_values[] = trim($el2[1]);
    }

    // var_dump($pole_ID);
    // var_dump($pole_values);


    echo "<table>";
    echo "<tbody>";

    // foreach($pole_ID as $id and $pole_values as $value){
    foreach(array_combine($pole_ID, $pole_values) as $id => $value){
        echo "<tr>"."<td>".$id.":"."</td>"."<td>".$value."</td>"."</tr>";
    }
    echo "</tbody>";
    echo "</table>";


    echo '</pre>';
 
}



function create_socket(){
    echo "- LOGIN -";
//     $param = "login";
//
//     exec("/usr/bin/python3.6 /home/doozie/Pycharm\ Projects/Python\ Learning/JA\ GAME/log2.py 2>&1", $output);
//     exec("/usr/bin/python3.6 /var/www/html/AutoBot/py_functions/login.py 2>&1 '$param' ", $output);
//     exec("/usr/bin/python3.6 /var/www/html/bot/ServerS1.py 2>&1 '$param' ", $output);
//     exec("/usr/bin/python3.6 /var/www/html/bot/ServerS1.py 2>&1", $output);

    $return_value = exec("nohup /usr/bin/python3.6 /var/www/html/Projects/Auto_Bot/Server.py > /dev/null 2>&1 &");
//     $return_value = exec("nohup /usr/bin/python3.6 /var/www/html/bot/ServerS1.py");
    echo "server bezi";

    if ($return_value == 0) {
        echo "<br>";
        echo "Python script executed successfully<br>";
    }
    else {
        echo "Error executing python script: Return value: " . $return_value . "<br>";
    }

}


function destroy_socket(){
    echo "- LOGOUT -";
    $param = "logout";

    exec("/usr/bin/python3.6 /var/www/html/Projects/Auto_Bot/Local.py 2>&1 '$param' ", $output);

    echo "Web chrome session was closed.";
}



?>