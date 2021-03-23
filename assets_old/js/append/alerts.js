/*! -------------------------------------------------------------------------------------------
JAVASCRIPT alerts engine!

* @Version:    1.0 - 2017
* @author:     Netgócio
* @email:      geral@netgocio.pt
* @website:    http://www.netgocio.pt
--------------------------------------------------------------------------------------------*/
var time_alert = 10000;

function ntg_alert(text){
    swal({
        html: text,
        showCloseButton: true,
        showCancelButton: false,
        showConfirmButton: false,
        timer: time_alert,
    });
}
function ntg_success(text){
    swal({
        type: "success",
        html: text,
        showCloseButton: true,
        showCancelButton: false,
        showConfirmButton: false,
        timer: time_alert,
    });
}
function ntg_error(text){
    swal({
        type: "info",
        html: text,
        showCloseButton: true,
        showCancelButton: false,
        showConfirmButton: false,
        timer: time_alert,
    });
}
function ntg_confirm(options, onSuccess, onError, success, error){ 
    swal(options).then(function(result){
        if (result.value) {
            if(onSuccess) onSuccess();
            return true;
        }else if(result.dismiss === swal.DismissReason.cancel){
            if(onError) onError();
            return false;
        }
    }).then(function(results){
        if(success && results){
            ntg_success(success);
        }else{  
            if(error){
                ntg_error(error);
            }
        }
    }).catch(function(err){
        if (err) {
            ntg_error(error);
        }else {
            swal.hideLoading();
            swal.close();
        }
    });
}
function ntg_newsletter(title, text, icon, buttons, danger, callback, success, error){  
    
}



/*EXEMPLOS*/
// swal({
//     type: 'info',
//     title: "Teste",
//     html: "O seu contacto foi enviado com sucesso!<br>Entraremos em contacto consigo brevemente.<br><br>Obrigado.",
//     //background: '#fff url(/images/trees.png)',
//     //padding: '2rem',
//     showCloseButton: true,
//     showCancelButton: true,
//     cancelButtonText: 'Cancel',
//     showConfirmButton: true,
//     confirmButtonText: 'Confirmar',
//     timer: 300000,
//     onOpen: () => {

//     },
//     onClose: () => {

//     }

// });


/* CONFIRM COM CALLBACK EM RPC
ntg_confirm(
    {
        type: 'info',
        title: "Teste",
        html: "O seu contacto foi enviado com sucesso!<br>Entraremos em contacto consigo brevemente.<br><br>Obrigado.",
        showCloseButton: true,
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        showConfirmButton: true,
        confirmButtonText: 'confirmar',
    },
    function(){
        alert('callback');
    },
    function(){
        alert('closed');
    },
    "Sucesso!!!!",
    "Error!!!!"
);
*/

