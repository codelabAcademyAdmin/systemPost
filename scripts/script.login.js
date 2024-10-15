$(document).ready(function(){
    preparando_formulario()
})

function preparando_formulario(){
  
   //let formulario = document.getElementById("frm")

    $("#frm").on("submit", function(evento){
        evento.preventDefault()

        let email = $("#email").val()
        let password = $("#password").val()

        $("#btn_submit").slideUp(0)
        $("#btn_disabled").slideDown(0)

        console.log(email)
        console.log(password)

        obtener_datos(email,password)

    })
   
}

async function obtener_datos(email, pass){

    try {
        let datos = {
            email ,
            pass
        }

        let config = {
            method:"POST", 
            headers: {
                'Content-Type': 'application/json' 
            },
            body: JSON.stringify(datos)
        }
        

        let res = await fetch("https://codelabacademy.online/api/login", config)
        let data = await res.json()
        if(data.status === "success"){
            alert("Datos correctos")
        }else{
            alert("Datos incorrectos")
            $("#btn_submit").slideDown(0)
            $("#btn_disabled").slideUp(0)
        }

        console.log(data)
    } catch (error) {
        console.error(error)
        $("#btn_submit").slideDown(0)
        $("#btn_disabled").slideUp(0)
        
    }
    
}