var global_var
var global_button
var global_show_button
var setter

function login_function(){
    let pre_logout;
    const dec_log = document.getElementById("login");
    if(dec_log.innerText === "Login"){
//        setter = 'login';
        create_buttons(setter);
        fill_div();
        start_server();
//        decision_function(setter);
//        start_server(function(){
//            global_button.disabled = true;
//            console.log("POSLANE SERVER 1")
//        });
        dec_log.innerText = "Logout";
    }else if(dec_log.innerText === "Logout"){
        stop_server();
//        setter = 'login';
        logout(global_var);
        dec_log.innerText = "Login";
    }
}


function create_buttons(set){
    console.log('wtf is this');
    let btn = document.createElement("button");
    btn.innerHTML = "START";
    btn.id = "start";
    document.body.appendChild(btn);
<!--    document.getElementById("two").append(btn);   -->
    document.getElementsByClassName("two")[0].append(btn);
    btn.addEventListener("click", function () {
        btn.disabled = true;
        btn2.disabled = false;
        console.log("Klikol som na START BUTTON");
        clearInterval(global_var);
//        global_var = setInterval(function(){myLoop()}, 1000);
        global_var = setInterval(function(){decision_function(set)}, 5000);
        console.log('MY LOOP VAR: ', global_var);
    });

    let btn2 = document.createElement("button");
    btn2.innerHTML = "STOP";
    btn2.id = "stop";
    btn2.disabled = true;
    document.body.appendChild(btn2);
    document.getElementsByClassName("two")[0].append(btn2);
    btn2.addEventListener("click", function () {
        btn.disabled = false;
        btn2.disabled = true;
        console.log("Klikol som na STOP BUTTON");
        console.log("Loop Var pri STOPNUTI: ", global_var);
        clearInterval(global_var);
    });

    global_button = document.createElement("button");
//    let btn1 = document.createElement("button");
    global_button.innerHTML = "UPDATE";
    global_button.id = "update";
    // global_button.disabled = false;
    document.body.appendChild(global_button);
    document.getElementsByClassName("two")[0].append(global_button);
    // global_button.disabled = false;
    global_button.addEventListener("click", function () {
//        set = "update";
        set = "update";
        console.log("After CLICKED");
        //global_button.disabled = false
        // --- global_button.disabled = true;
        decision_function(set);
        // console.log(prideme_na_to)
        
        global_button.disabled = true;

//        btn1.disabled = true;
    });

    global_show_button = document.createElement("button");
    global_show_button.innerHTML = "SHOW";
    global_show_button.id = "show";
    console.log("Show HOS how SHOW WHOSHOW HOS HOW ");
    document.body.appendChild(global_show_button);
    document.getElementsByClassName("two")[0].append(global_show_button);
    global_show_button.addEventListener("click", function (){
        set = "show";
        console.log("POSLAL SOM SHow: ")
        // global_show_button.disabled = true;
        // decision_function(set);
        decision_function(set);
        
        global_show_button.disabled = true;


    });

}


function logout(global_var) {
    console.log("LogoutFunction was called");
<!--    console.log('riava je prazdna: ', riava);-->
    clearInterval(global_var);

<!-- ODSTRANI BUTTONY PRI LOGOUTNUTI -->

    var elem = document.getElementById('start');
    elem.parentNode.removeChild(elem);
<!--    return false;-->

    var elem1 = document.getElementById('stop');
    elem1.parentNode.removeChild(elem1);
<!--    return false;-->

    var elem2 = document.getElementById('update');
    elem2.parentNode.removeChild(elem2);
<!--    return false;-->

    var elem3 = document.getElementById('content');
    if (elem3){
        elem3.parentNode.removeChild(elem3);
    }

    var elem4 = document.getElementById('show');
    elem4.parentNode.removeChild(elem4);
}


function start_server(){
    console.log("Start server function started");
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
        const content = document.getElementById("content");
        if (content){
            content.innerHTML = xhttp.responseText;
        }
        //document.getElementById("content").innerHTML = xhttp.responseText;
    }
  };
  xhttp.open("POST", "communication.php");
  xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  //xhttp.send("function=login");
  xhttp.send("function=login");
  console.log("LOGIN POSLANY");
  console.log("Start server function finished");
}

function stop_server(){
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
        if(document.getElementById("content")){
            document.getElementById("content").innerHTML = xhttp.responseText;
}

        //document.getElementById("content").innerHTML = xhttp.responseText;
    }
  };
  xhttp.open("POST", "communication.php");
  xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhttp.send("function=logout");
  //xhttp.send();
  console.log("LOGOUT POSLANY");
}

//function myFunc(){
//    loadFile("pointer.php", function(){
//        if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
//            document.getElementById("content").innerHTML = xmlhttp.responseText;
//        }
//    });
//}

function fill_div(x){
    console.log("Fill div function started");
    var cont_div = document.getElementById("content");
    if (cont_div){
        console.log("Content DIV exists - MAZEM");
        cont_div.parentNode.removeChild(cont_div);
        cont_div = document.createElement("div");
        cont_div.id = "content";
        document.body.appendChild(cont_div);
        //global_button.disabled = true;
//        myFunc2(x, function() {    // ----------------------------------------------- TUTO BOLA ORIGINAAAALNAAA

    } else {
//        console.log("Element DOESNT EXISTS");
        var cont_div = document.createElement("div");
        cont_div.id = "content";
        document.body.appendChild(cont_div);
        //global_button.disabled = true;
//        myFunc2(x, function() {        // -----------------------------------------------   TATO TIEZ ORIGOS


    }
    console.log("Fill div function finished");
}


//function loadFile(file, func){
function loadFile(file, func, param){
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = func;
    xmlhttp.open("POST", file, true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    // xmlhttp.send();
    // --- original dole --- ak bude treba tak enabluj
    // xmlhttp.send("function=update");
    xmlhttp.send("function=" + param)

}

// TUTO INVESTIGUJES

function send_to_local(param){
    var clicked_button;
    loadFile("communication.php", function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
            document.getElementById("content").innerHTML = xmlhttp.responseText;
            //global_button.disabled = false;
            // global_show_button.disabled = false;

            if (param === "update"){
                // clicked_button = global_button;
                global_button.disabled = false
            } else if (param === "show"){
                // clicked_button = global_show_button;
                global_show_button.disabled = false
            }
            // clicked_button.disabled = false;
            // callback();
        }
    }, param);
}

//
function decision_function(p){
    var cont_div = document.getElementById("content");

    if (cont_div){
        console.log("Content DIV exists - MAZEM");
        cont_div.parentNode.removeChild(cont_div);
        cont_div = document.createElement("div");
        cont_div.id = "content";
        document.body.appendChild(cont_div);
//        myFunc2();
        //global_button.disabled = true;
//        myFunc2(x, function() {    // ----------------------------------------------- TUTO BOLA ORIGINAAAALNAAA
//            global_button.disabled = false;
//            console.log("AFTER FUNCTION RETURN VALUES");
//        });

        send_to_local(p);    // --- await ??? pred send...
        console.log("PRESEU SOM PO MAZANI");
        // clicked_button.disabled = false;
        // global_show_button.disabled = false;

        } else {
//        console.log("Element DOESNT EXISTS");
        var cont_div = document.createElement("div");
        cont_div.id = "content";
        document.body.appendChild(cont_div);

        send_to_local(p);   // --- await ??? pred send...
        console.log("DOSTANEM SA TU NIEKEDY ?");
        //clicked_button.disabled = false;

    }
}