function valida_campo1(campo1) {

    if (/[a-zA-Z_]/.test(campo1)) {
        return true;
    } else {
        return false;
    }
}
/////////////////////////////////////////////////////////////////////////////
//////////VALIDACIONES PARA EL FORMULARIO REGISTRARSE /////////////////
function valida_registro() {

    if (!/^\w+([\.=]?\w+)*@\w+([\.=]?\w+)*(\.\w{2,3})+$/.test(document.getElementById('InputCorreo').value)) {
        alertify.alert("Debe ingresar un correo válido");
        document.getElementById('InputCorreo').focus();
        return(false);
    }
    if (/^\s*$/.test(document.getElementById('InputPassword').value)) {
        alertify.alert("Debe ingresar password");
        document.getElementById('InputPassword').value = "";
        document.getElementById('InputPassword2').value = "";
        document.getElementById('InputPassword').focus();
        return(false);
    }
    if (/^\s*$/.test(document.getElementById('InputPassword2').value)) {
        alertify.alert("Debe ingresar confirmación de password");
        document.getElementById('InputPassword').value = "";
        document.getElementById('InputPassword2').value = "";
        document.getElementById('InputPassword').focus();
        return(false);
    }
    if ((document.getElementById('InputPassword').value) !== (document.getElementById('InputPassword2').value)) {
        alertify.alert("No hay coincidencia en el Password de Confirmacion. \n Ingreselo de nuevo");
        document.getElementById('InputPassword').value = "";
        document.getElementById('InputPassword2').value = "";
        document.getElementById('InputPassword').focus();
        return(false);
    }
//    
    document.getElementById('InputPassword').value = calcMD5(document.getElementById('InputPassword').value);
    document.getElementById('InputPassword2').value = "";

    document.getElementById('formRegistro').submit();
}

function limpiar_logueo()
{
//    reset();
    document.getElementById('InputCorreo').value = "";
    document.getElementById('InputPassword').value = "";
    document.getElementById('InputCorreo').focus();
}
/////////////////////////////////////////////////////////////////////////////
//////////VALIDACIONES PARA EL FORMULARIO lOGIN /////////////////
function validar_logueo()
{
    if (((/^\s*$/.test(document.getElementById('InputCorreo').value))) || ((/^\s*$/.test(document.getElementById('InputPassword').value))))
    {
        alertify.alert("Ingrese sus credenciales");
        limpiar_logueo();
        return (false);
    }
    if (!/^\w+([\.=]?\w+)*@\w+([\.=]?\w+)*(\.\w{2,3})+$/.test(document.getElementById('InputCorreo').value)) {
        alertify.alert("Debe ingresar un correo válido");
        document.getElementById('InputCorreo').focus();
        return(false);
    }
    document.getElementById('InputPassword').value = calcMD5(document.getElementById('InputPassword').value);
    document.formLogin.submit();
}


