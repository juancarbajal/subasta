// JavaScript Document



$(document).ready(function(){ //limpiar arbol categorias
	var $cat = $('.cat');
	
	
	$cat.find('.img.float').remove();
	$cat.find('.borde-b').remove();
	$cat.find('br').remove();
	$cat.find('dd img').remove();
	$cat.find('dd span').remove();
	
	$cat.find('a').attr('href', '#').removeAttr('alt');
	
	
	
	
	$cat.find('div.titulo').each(function(){
		var $titulo = $(this);
		var titulo = this.innerHTML;
		
		$titulo.before('<h3>'+ titulo +'</h3>');
		$titulo.remove();
		
	});
	
	
	
	
	var $lists = $cat.find('div.list');
	
	$lists.each(function(){
		var $list = $(this);
		var listhtml = '';
		
		$list.find('.listado').each(function(){
			listhtml += '<li>'+ this.innerHTML +'</li>';
		});
		
		var list = '<ul>'+ listhtml +'</ul>';
		
		$list.before(list);
		$list.remove();
	});
	
	
	
	var ItemListHTML = '';
	var $items = $cat.find('.Item');
	$items.each(function(){
		ItemListHTML += '<li>'+ this.innerHTML +'</li>';
	});
	$items.remove();
	
	
	$cat.find('h1').after('<ul>'+ ItemListHTML +'</ul>');
	
	
	
	
	var $categorias = $cat.find('> ul > li');
	
	$cat.find('> ul > li:first').addClass('first');
	$cat.find('> ul > li:last').addClass('last');
	
	$categorias.each(function(i){
		var $this = $(this);
		
		if( (i % 2) == 1  ){
			$this.addClass('odd');
		}else{
			$this.addClass('even');
		}
		
		$this.addClass('item-'+i);
		
		$this.find('> ul > li').each(function(j){
			var $this = $(this);
			if( (j % 2) == 1  ){
				$this.addClass('odd');
			}else{
				$this.addClass('even');
			}
			$this.addClass('item-'+j);
		});
		
	});
	
	
	
	$categorias.find('> ul > li:first').addClass('first');
	$categorias.find('> ul > li:last').addClass('last');

});



