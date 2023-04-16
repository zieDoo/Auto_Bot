<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$mprem = "'Oblasť:': 's20-sk.bitefight.gameforge.com', 'Rasa:': 'Upír', 'ID hráča:': '15944', 'Meno hráča:': 'Lichandro', 'Úroveň:': '7', 'Bojová hodnota:': '77', 'Pozícia v Highscore:': '27.882'";


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
    }
    elseif ($x == "show"){
        echo "- SHOW -";
    }

    $retval = exec("/usr/bin/python3.6 /var/www/html/Projects/Auto_Bot/Local.py '$x' ");

    print_r($retval);    
}




















function create_socket(){
    echo "- LOGIN -";
    //$param = "login";

//     exec("/usr/bin/python3.6 /home/doozie/Pycharm\ Projects/Python\ Learning/JA\ GAME/log2.py 2>&1", $output);
//     exec("/usr/bin/python3.6 /var/www/html/AutoBot/py_functions/login.py 2>&1 '$param' ", $output);
    // exec("/usr/bin/python3.6 /var/www/html/bot/ServerS1.py 2>&1 '$param' ", $output);
    // exec("/usr/bin/python3.6 /var/www/html/bot/ServerS1.py 2>&1", $output);
    $return_value = exec("nohup /usr/bin/python3.6 /var/www/html/Projects/Auto_Bot/Server.py > /dev/null 2>&1 &");
    // $return_value = exec("nohup /usr/bin/python3.6 /var/www/html/bot/ServerS1.py");
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
}




function get_update($x){

    if ($x == "update"){
        echo "- UPDATE -";
    }
    elseif ($x == "show"){
        echo "- SHOW -";
    }

    # echo "- UPDATE -";
    echo "<br>";

    // $output = shell_exec('sudo -u www-data /usr/bin/python3.6 /var/www/html/bot/LocalS1.py update');
    // $output = shell_exec('/usr/bin/python3.6 /var/www/html/bot/LocalS1.py update'); <<--- tento je spravny enable ak treba back.
    $output = shell_exec("/usr/bin/python3.6 /var/www/html/bot/LocalS1.py '$x' ");

    // var_dump($output);

    // $output = shell_exec('ls -la');
    // echo "<pre>";

    $binary_data = base64_decode($output);
    $utf8_string = mb_convert_encoding($binary_data, 'UTF-8');
    # echo $utf8_string;
    echo "<br>";
    // var_dump($utf8_string);
    // print_r($utf8_string);

    $variable_str = str_replace(":':", ':', $utf8_string);
    $bez_uvodz = str_replace(array("{","}", "'"), '', $variable_str);
    // $variable_str2 = str_replace('"', '', $variable_str); // - odstranili sme z neho uvodzovky

    // print_r($variable_str);

    $new_expl = explode(", ", $bez_uvodz);

    // var_dump($new_expl);
//     echo "<br>";
//     echo "<br>";
//     echo "<br>";
//     echo "<br>";
    // print_r($new_expl);

//     foreach($new_expl as $result){
//
//         echo $result . '<br>';
//     }


    $pole_ID = [];
    $pole_values = [];

    foreach($new_expl as $element){
        $el = trim($element);
        # print_r($el);
        // secho "<br>";
        $el2 = explode(': ', $el);

//         if (strpos($el, 'Liečenie zdravia:') !== false || strpos($el, 'Akčné body Regenerácia:') !== false) {
//         continue; // skip the current element if it contains "Liečenie zdravia" or "Akčné body Regenerácia"
//     }

//         if ($el2[0] == "Liečenie zdravia:" || $el2[0] == "Akčné body Regenerácia:"){
//             continue;
//         }
        // echo '<pre>';
        // var_dump($el2);
        // echo '</pre>';

        if ($el2[0] == "Liečenie zdravia" || $el2[0] == "Akčné body Regenerácia"){
            // echo "CO do Ppppppppppppppppppppppppppppppppppppp";
            break;
        } else {
            $pole_ID[] = $el2[0];
            $pole_values[] = trim($el2[1]);
        }
    # echo "TUTO TUTO HLEEEE KRTEEK";
    # var_dump($pole_ID);
    # var_dump($pole_values);

        // $pole_ID[] = $el2[0];

//         if ($pole_ID[0] == 'Liečenie zdravia') {
//             echo "CAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";
//         }


//         if ($el2[0] == "Liečenie zdravia:" || $el2[0] == "Akčné body Regenerácia:"){
//             continue;
//         }

        // $pole_values[] = trim($el2[1]);



        // print_r($pole_ID);
        // $pole_values = $el2;
    }

    // ------------------- TUTO SU HODNOTY KLUCOV PRE GRAFY -------------------
//     echo '<pre>';
//     var_dump($pole_values);
//     echo '</pre>';


// ********** TOTO JE do zaLOhy K TOMU dole



//     if (count($pole_ID) !== count($pole_values)) {
//     // Handle the error here
//     echo "Error: the two arrays have different lengths";
//     } else {
//         $data = array_combine($pole_ID, $pole_values);
//     // Loop through the $data array here
//         foreach ($data as $key => $value) {
//             //echo "$key: $value\n";
//         }
//     }
//
//     if (count($pole_ID) !== count($pole_values)) {
//         // Handle the error here
//         echo "Error: the two arrays have different lengths";
//     } else {
//         $data = array_combine($pole_ID, $pole_values);
//         if (!empty($data)) {
//             foreach ($data as $key => $value) {
//                 //echo "$key: $value\n";
//             }
//         } else {
//             echo "Error: the data array is empty";
//         }
//     }
//


// ********** TATO CAST J K TOMU HORE


// if count of array is more then 2 then skip




//     echo "pole_ID count: " . count($pole_ID) . "<br>";
//     echo "pole_values count: " . count($pole_values) . "<br>";
//
//     echo "pole_ID : " . $pole_ID . "<br>";
//     var_dump($pole_ID);
//     echo "pole_values : " . $pole_values . "<br>";
//     var_dump($pole_values);
//














    echo "<table>";
    echo "<tbody>";

    // foreach($pole_ID as $id and $pole_values as $value){
    foreach(array_combine($pole_ID, $pole_values) as $id => $value){
        echo "<tr>"."<td>".$id.":"."</td>"."<td>".$value."</td>"."</tr>";
    }
    echo "</tbody>";
    echo "</table>";

// }
    echo "</pre>";

}


















// ---------------------------- REFAtoRing 22222222222 FUNCTION --------------------------


// function get_update(){
//     echo "- UPDATE -";
//     echo "<br>";
//     $param2 = "update";
//
//     // $output = shell_exec('sudo -u www-data /usr/bin/python3.6 /var/www/html/bot/LocalS1.py update');
//     // echo $output;
//
//
//     $handle = popen('/usr/bin/python3.6 /var/www/html/bot/LocalS1.py update', 'r');
//     echo "<br>";
//     echo "- PO POPNE -";
//     echo "<br>";
//
//     while ($line = fgets($handle, 4096)) {
//         // print_r($line);
//         echo "LINE: $line\n";
//         $decoded_data = utf8_decode(trim($line));
//         var_dump($decoded_data[0]);
//         echo "\n";
//
//         $json_data = json_decode($decoded_data);
//         // var_dump($omg_data);
//
//         flush();
//
//         // var_dump($line);
//         // echo $line;
//         // print_r($line);
//         // echo "<br>";
//         // $regular_str = mb_convert_encoding($line, 'UTF-8');
//         // echo $regular_str;
//         // $decoded_data = mb_convert_encoding($line, 'UTF-8', 'ISO-8859-1');
//         // $json_data = json_decode($line);
//         // $decoded_data = iconv("ISO-8859-2", "UTF-8", $line);
//         // var_dump($json_data);
//         // echo $line;
//        // flush();
//     }
//     pclose($handle);
//     echo "- PO KONCOVKE -";
//     echo "<br>";
//
// }
//
//












// ----------------------------- REFAtoRing UPDATE FUNCTION -----------------------------



//
// function get_update(){
// //     global $mprem;
// //     echo $mprem;
// //     print_r($mprem);
// //     var_dump($mprem);
//
//     echo "- UPDATE -";
//     $param2 = "update";
//     $cmd_for_exec = "/usr/bin/python3.6 /var/www/html/bot/LocalS1.py update 2>&1";
//
//     // $ret_val = exec("/usr/bin/python3.6 /var/www/html/bot/LocalS1.py 2>&1 '$param2' ", $output);
//     // $ret_val = shell_exec("/usr/bin/python3.6 /var/www/html/bot/LocalS1.py");
//     // $ret_val = shell_exec($cmd_for_exec);
//     // $ret_val = passthru($cmd_for_exec);
//     // passthru($cmd_for_exec);
//     // $kks_out = shell_exec($cmd_for_exec);
//     $output = exec("/usr/bin/python3.6 /var/www/html/bot/LocalS1.py update 2>&1");
//     // $output = shell_exec("/usr/bin/python3.6 /var/www/html/bot/LocalS1.py $param2");
//
//     echo "<pre>";
//     print_r($output[0]);
//     var_dump($output[1]);
//     echo $output[2];
//     // echo "<pre>$output</pre>";
//     echo "DA FAK";
//     echo $output;
//     // print_r($ret_val);
//     // var_dump($ret_val);
//     // echo $ret_val;
//     echo "</pre>";
//
//
//     //$output = shell_exec("ls -lart");
//
//     if ($output === NULL){
//         $error = error_get_last();
//         echo "CHYBA ???";
//         if ($error !== NULL){
//             echo "Error: " . $error['message'];
//         }
//     } else {
//         echo $output;
//     }
//
//     echo "<pre>";
//     print_r($output);
//     var_dump($output);
//     echo $output;
//     echo "<pre>$output</pre>";
//     echo $output;
//     // print_r($ret_val);
//     // var_dump($ret_val);
//     // echo $ret_val;
//     echo "</pre>";
//
//
//     if ($output == 0) {
//         echo "UPDATEs executed successfully<br>";
//         echo "<pre>$output</pre>";
//         echo $output;
//         // print_r($output[0]);
//         // echo $output[0];
//         // var_dump($output[0]);
//     }
//     else {
//         echo "UPDATEs ERORINGUJE executing python script: Return value: " . $ret_val . "<br>";
//     }
//
//     // $new_prem = $output;
//     // print_r($new_prem[0]);
//     // var_dump($new_prem[0]);

// }




?>