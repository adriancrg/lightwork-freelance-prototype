document.getElementById("btn__sign-in").addEventListener("click", signin);
document.getElementById("btn__sign-up").addEventListener("click", signup);
window.addEventListener("resize", anchoPagina);

//Declaración de variables
var contenedor_signinsignup=document.querySelector(".contenedor__sign-in-sign-up");

var formulario_signin=document.querySelector(".formulario__sign-in");
var formulario_signup=document.querySelector(".formulario__sign-up");

var caja_trasera_signin=document.querySelector(".caja__trasera-sign-in");
var caja_trasera_signup=document.querySelector(".caja__trasera-sign-up");

function anchoPagina(){
    if (window.innerWidth > 850){
        caja_trasera_signin.style.display = "block";
        caja_trasera_signup.style.display= "block";
    }else{
        caja_trasera_signup.style.display= "block";
        caja_trasera_signup.style.opacity= "1";
        caja_trasera_signin.style.display= "none";
        formulario_signin.style.display= "block";
        formulario_signup.style.display= "none";
        contenedor_signinsignup.style.left= "0px";
    }
}

anchoPagina()

function signin(){

    if(window.innerWidth > 850){
        formulario_signup.style.display = "none";
        contenedor_signinsignup.style.left= "10px";
        formulario_signin.style.display= "block";
        caja_trasera_signup.style.opacity= "1";
        caja_trasera_signin.style.opacity= "0"; 
    }else{
        formulario_signup.style.display = "none";
        contenedor_signinsignup.style.left= "0px";
        formulario_signin.style.display= "block";
        caja_trasera_signup.style.display= "block";
        caja_trasera_signin.style.display= "none";
    }
}

function signup(){
    if (window.innerWidth >850){
        formulario_signup.style.display = "block";
        contenedor_signinsignup.style.left= "410px";
        formulario_signin.style.display= "none";
        caja_trasera_signup.style.opacity= "0";
        caja_trasera_signin.style.opacity= "1";
    }else{
        formulario_signup.style.display = "block";
        contenedor_signinsignup.style.left= "0px";
        formulario_signin.style.display= "none";
        caja_trasera_signup.style.display= "none";
        caja_trasera_signin.style.display= "block";
        caja_trasera_signup.style.opacity= "1";
    }
    
}