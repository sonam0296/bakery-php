// Logout e bloquear ecra 
function logout(qf, path){

	$.post( path+"logout.php", { id: qf }, function(data) {
		if(data==2){
			window.location=path+'index_lock.php?l=1';
		}else{
			window.location=path+'index.php?l=1';
		}
	});
}

//pesquisa nas listagens
function submete(e) {
    if (e.keyCode == 13) {
        document.getElementById('pesquisa').click();
    }
}

//opção selecionada - checkbox
function opcaoSelecionada(objName)
{
	obj=document.getElementsByName(objName);
	if(obj)
	{
		hasSelected=0;
		for(i=0;i<obj.length;i++)
		{
			if(obj[i].checked)
			{
				hasSelected=obj[i].value;
			}
		}
		return hasSelected;
	}
}


//PRE-VISUALIZAÇÃO DO GOOGLE-->
var meta_blog=0;
function carregaPreview(){
	var url=$('#url').val();
	var title=$('#title').val();
	var description=$('#description').val();
	var keywords=$('#keywords').val();
	
	$.post("../metas-rpc.php", {op:"carregaPreview", url:url, title:title, description:description, keywords:keywords, blog:meta_blog}, function(data){
		$("#googlePreview").html(data);		
	});
}

/*********** Só permite inserir numeros ***********/
function onlyNumber(obj,e){
  var valor, val;

  liberado = new Array('');
  liberadoE = new Array(188,190,8);

  valor = obj.value;
  if(document.all){
    if(!((e.keyCode > 47 && e.keyCode < 58) || (e.keyCode > 95 && e.keyCode < 106) || Array.find(liberadoE,e.keyCode) != '-1' )) {
        obj.value = valor.substr(0,valor.length - 1);
    }
  }
  else{
    val = '';

    for (x = 0; x < valor.length; x++){
      if(!isNaN(valor[x]) || Array.find(liberado,valor[x]) != '-1'){
        val += valor[x];
      }
    }
    obj.value = val;
  }
}

Array.find = function(ary, element){
    for(var i=0; i<ary.length; i++){
        if(ary == element){
            return i;
        }
    }
    return -1;
}
/*********** Campo tem Caracteres ***********/
function temCaracteres(obj){
	var er = /[a-z]{1}/gim;
	
	if (obj != ""){
		er.lastIndex = 0;
		pl = obj;		
		pl = pl.toUpperCase();
		
		if (er.test(pl)){
			return 1;						
		}else{
			return 0;
		}
	}	
}
/****************************************************/
function validaNum(value, max_, min_){
	var n = parseFloat(value);
	
	if(temCaracteres(value)==1){
		return 1;
	}else{
		if (isNaN(n) && value!='') {
			return 1;
		}else{
			if((n > max_ || n < min_) && value!=''){
				return 1;
			}else{
				return 0;
			}		
		}
	}
}
//verifica se é numérico
function validaNumero(value){
	var n = parseFloat(value);
	
	if(temCaracteres(value)==1){
		return 1;
	}else{
		if (isNaN(n)){
			return 1;
		}else{
			return 0;
		}
	}
}
// Verifica se um objecto tem alguma opção seleccionada
function hasSelectedOption(objName)
{
	obj=document.getElementsByName(objName);
	if(obj)
	{
		hasSelected=false;
		for(i=0;i<obj.length;i++)
		{
			if(obj[i].checked)
			{
				hasSelected=true;
			}
		}
		return hasSelected;
	}
}

function onlyDecimal(obj,e){
	value = obj.value;
	var n = parseFloat(value);
	
	if(temCaracteres(value)==1){
		obj.value='';
	}else{	
		if (isNaN(n))
			obj.value='';
	}
}

function validaInteiro(obj,min_,max_){
	value = obj.value;
	var n = parseFloat(value);
	
	if(temCaracteres(value)==1){
		obj.value='';
	}else{
		if (!isNaN(n)){
			if(n > max_ || n < min_)
				obj.value='';
		}
	}
}

function CheckTime(THISTIME) {
	var err=0
	a=THISTIME.value
	if (a.length != 8) err=1
	f = a.substring(0, 2)// Hour f
	c = a.substring(2, 3)// ':'
	b = a.substring(3, 5)// Min b
	if (b<0 || b>59) err = 1
	if (f<0 || f>23) err = 1
	if (err==1) {
		msg = THISTIME.value+' não é válido. Por favor insira no seguinte formato: HH:MM';
	}else{
		msg = '';
	}
	return msg;
}

function formatar(src, mask){
	var i = src.value.length;
	var saida = mask.substring(0,1);
	var texto = mask.substring(i)
	if (texto.substring(0,1) != saida) {
		src.value += texto.substring(0,1);
	}
}