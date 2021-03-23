function showTooltip(x, y, contents) {
	$('<div id="tooltip">' + contents + '</div>').css({
		position: 'absolute',
		display: 'none',
		top: y + 5,
		left: x + 15,
		border: '1px solid #333',
		padding: '4px',
		color: '#fff',
		'border-radius': '3px',
		'background-color': '#333',
		opacity: 0.80
	}).appendTo("body").fadeIn(200);
}
//ESTATISTICAS VISITAS
function est_visitas_carrega(_op, _inicio, _fim){
	document.getElementById('listagem').innerHTML='';
	document.getElementById('loading').style.display='block';
	$('#listagem').load('est_visitas_rpc.php', {op:_op, inicio:_inicio, fim:_fim}, function(){
		document.getElementById('loading').style.display='none';	
		$("#tipo2").select2();
		
		if($('#sample_5').length>0){	
			TableAdvanced.init();	
		}
		
		if(_op == "geral"){
			est_visitas_carrega3("graph_1", _inicio, _fim);
		} 
		
		if($('#chart_7').length>0){	
			if(_op == "geral"){
				$.post('est_visitas_rpc.php', {op:"geral2", inicio:_inicio, fim:_fim}, function(data){
					ChartsAmcharts2.init(data);
				});
			}
		}
		
		if($('#chart_5').length>0){	
			if(_op === "navegador"){
				$.post('est_visitas_rpc.php', {op:"navegador2", inicio:_inicio, fim:_fim}, function(data){
					ChartsAmcharts.init(data);
				});
			}
			if(_op === "sistema_operativo"){
				$.post('est_visitas_rpc.php', {op:"sistema_operativo2", inicio:_inicio, fim:_fim}, function(data){
					ChartsAmcharts.init(data);
				});
			}
		}
	});
}

function est_visitas_carrega2(_op, _inicio, _fim, _filter){
	document.getElementById('listagem').innerHTML='';
	document.getElementById('loading').style.display='block';
	$('#listagem').load('est_visitas_rpc.php', {op:_op, inicio:_inicio, fim:_fim, filter:_filter}, function(){
		document.getElementById('loading').style.display='none';		
		$("#tipo2").select2();	
		if($('#sample_5').length>0){	
			TableAdvanced.init();	
		}																				
	});
}

function est_visitas_carrega3(_op, _inicio, _fim){
	document.getElementById('graph_div').innerHTML='';
	document.getElementById('graph_loading').style.display='block';
	
	$('#graph_div').load('est_visitas_rpc.php', {op:_op, inicio:_inicio, fim:_fim}, function(){
		document.getElementById('graph_loading').style.display='none';		
		$("#tipo2").select2();	
	});
}
///////////

//ESTATISTICAS VENDAS
function est_vendas_carrega(_op, _inicio, _fim){
	document.getElementById('listagem').innerHTML='';
	document.getElementById('loading').style.display='block';
	$('#listagem').load('est_vendas_rpc.php', {op:_op, inicio:_inicio, fim:_fim}, function(data){
		document.getElementById('loading').style.display='none';	
		$("#tipo2").select2();	
		
		if($('#sample_5').length>0){	
			TableAdvanced.init();	
		}
		
		if(_op == "geral"){
			est_vendas_carrega3("graph_1", _inicio, _fim);
		} 
		
		if($('#chart_7').length>0){	
			if(_op == "geral"){
				$.post('est_vendas_rpc.php', {op:"geral2", inicio:_inicio, fim:_fim}, function(data){
					ChartsAmcharts2.init(data);
				});
			}
		}
				
	});
}

function est_vendas_carrega2(_op, _inicio, _fim, _filter){
	document.getElementById('listagem').innerHTML='';
	document.getElementById('loading').style.display='block';
	$('#listagem').load('est_vendas_rpc.php', {op:_op, inicio:_inicio, fim:_fim, filter:_filter}, function(){
		document.getElementById('loading').style.display='none';	
		$("#tipo2").select2();	
		
		if($('#sample_5').length>0){	
			TableAdvanced.init();	
		}
		
	});
}

function est_vendas_carrega3(_op, _inicio, _fim){
	document.getElementById('graph_div').innerHTML='';
	document.getElementById('graph_loading').style.display='block';
	$('#graph_div').load('est_vendas_rpc.php', {op:_op, inicio:_inicio, fim:_fim}, function(data){
		document.getElementById('graph_loading').style.display='none';
		$("#tipo2").select2();	
		
		if($('#sample_5').length>0){	
			TableAdvanced.init();	
		}																							
	});
}


///////////

// FUNCOES VÁRIAS



function formatMoney(n, decPlaces, thouSeparator, decSeparator) {
    //var n = this,
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSeparator = decSeparator == undefined ? "." : decSeparator,
    thouSeparator = thouSeparator == undefined ? "" : thouSeparator,
    sign = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

if(!Array.indexOf){
	Array.prototype.indexOf = function(obj){
		for(var i=0; i<this.length; i++){
			if(this[i]==obj){
				return i;
			}
		}
		return -1;
	}
}