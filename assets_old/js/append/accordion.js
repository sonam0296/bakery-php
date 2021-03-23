(function(factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports !== 'undefined') {
        module.exports = factory(require('jquery'));
    } else {
        factory(jQuery);
    }

}(function($) {
    'use strict';
    var Accordion = window.Accordion || {};
    var AccordionEls = [];
    var AccordionItemsEls = [];

    Accordion = (function() {

        function Accordion(element, settings) {

            var _ = this, dataSettings;

            _.defaults = {
            	elements : {
					container : element,
					items : '[accordion-item]',
					trigger : '[accordion-title]',
	                content : '[accordion-content]',
	                image : '[accordion-image]',
	                submenu : '[accordion-nested]',
				},
				speed: 'slow', // velocidade da animação;
                multiSelect: "false", //caixa de inputs com selecção multipla
				multiOpen: "false", //Posso ter mais que um elemento aberto, ou posso forçar a que feche o que esta ativo quando um abre;
				allClosed: "true", //Posso ter todos fechados, ou forçar que fique um aberto;
                clickToClose: "true",  //Click no titulo para abrir e fechar ou so abrir, util para filtros;
				openIcon: 'plusminus' // Icon de cada titulo de accordion.
            };

            _.initials = {
                container : '',
                items : '',
                trigger : '',
                content : '',
                image : '',
                submenu : '',
                multiSelect: '',
                multiOpen: '',
                allClosed: '', 
                clickToClose: '', 
                openIcon: ''
            };

            dataSettings = $(element).data('accordion', _) || {};
            $.extend(_, _.initials);
            _.options = $.extend({}, _.defaults, dataSettings, settings);
						
            _.generateId();
            
            _.init = $.proxy(_.init, _);

        }
        return Accordion;

    }());
   	
    Accordion.prototype.makeId = function(limit, sufix, parent) {
        var _ = this;

        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < limit; i++){
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }

        var final_str = text+"-"+sufix;

        var arrayCompare;

        if(parent==1){
            arrayCompare = AccordionEls;
        }else{
            arrayCompare = AccordionItemsEls;
        }


        if(typeof arrayCompare == "undefined" || arrayCompare.indexOf(final_str)<0){
            return final_str;
        }else{
            return _.makeId(limit, sufix, parent);
        }
    }
    Accordion.prototype.generateId = function() {
        var _ = this;

        
        _.initials = {
            container : $(_.options.elements.container),
            items : $(_.options.elements.container).find(_.options.elements.items),
            trigger : $(_.options.elements.container).find(_.options.elements.trigger),
            content : $(_.options.elements.container).find(_.options.elements.content),
            image : $(_.options.elements.container).find(_.options.elements.image),
            submenu : $(_.options.elements.container).find(_.options.elements.submenu),
            
            multiSelect : $(_.options.elements.container).attr('accordion-multiSelect') || _.options.multiSelect,
            multiOpen : $(_.options.elements.container).attr('accordion-multiOpen') || _.options.multiOpen,
            allClosed : $(_.options.elements.container).attr('accordion-allClosed') || _.options.allClosed,
            clickToClose : $(_.options.elements.container).attr('accordion-clickToClose') || _.options.clickToClose,
            openIcon : $(_.options.elements.container).attr('accordion-icon') || _.options.openIcon,
        };

        if(_.initials.container.length>0){
            var id_accordion = $(_.initials.container)[0].id || _.makeId(6, "accordion", 1);

            _.initials.container.attr('id', id_accordion);
            AccordionEls.push(id_accordion);

            _.initials.container.attr('role', 'tree');


            if(_.initials.items.length>0 && _.initials.trigger.length>0 && (_.initials.content.length>0 || _.initials.submenu.length>0)){                
                $(_.initials.items).each(function(){
                    
                    var isSub = "";
                    if($(this).parents(_.options.elements.submenu).length>0){
                        isSub="sub-";
                    }

                    var thisid = $(this).attr('id') || id_accordion+'-item-'+isSub+$(this).index('[accordion-item]');
                    $(this).attr('id', thisid);

                    var $item = $(this);
                    var $trigger = $('#'+thisid+' > '+ _.options.elements.trigger);
                    var $content = $('#'+thisid+' > '+ _.options.elements.content);
                    var $submenu = $('#'+thisid+' > '+ _.options.elements.submenu);

                    var id_item = $content.attr('id') || _.makeId(6, "accordion", 0),
                        linkId = $trigger.attr('id') || id_item+'-label',
                        subId = $submenu.attr('id') || id_item+'-menu';

                    AccordionItemsEls.push(id_item);

                    $trigger.attr({
                        'aria-expanded': false,
                        'aria-selected': false,
                        'id': linkId
                    });

                    $item.attr({
                        'accordion-level' : $item.parents(_.options.elements.submenu).length+1
                    });

                    if($content.length>0){
                        $trigger.attr({
                            'role': 'tab',
                            'aria-controls': id_item,
                            'hasSub': 'true'
                        });

                        $content.attr({
                            'role': 'tabpanel',
                            'aria-hidden': false,
                            'aria-labelledby': linkId,
                            'id': id_item
                        });
                    }

                    if($submenu.length>0){
                        $item.attr({
                            'role': 'treeitem',
                            'aria-controls': subId
                        });

                        $trigger.attr({
                            'hasSub': 'true'
                        });

                        $submenu.attr({
                            'role': 'group',
                            'aria-hidden': false,
                            'aria-labelledby': linkId,
                            'id': subId,
                        });
                    }

                    //adiciona icon ao trigger element
                    if(typeof $(_.options.elements.container).attr('accordion-icon') == "undefined"){
                        $(_.options.elements.container).attr('accordion-icon', _.initials.openIcon);
                    }
                    if(_.initials.openIcon!="none") $(_.options.elements.container).find('[hasSub]').addClass(_.initials.openIcon);

                    //handle dos atributos do accordion
                    if(typeof $(_.options.elements.container).attr('accordion-multiSelect') == "undefined"){
                        $(_.options.elements.container).attr('accordion-multiSelect', _.initials.multiSelect);
                    }
                    if(typeof $(_.options.elements.container).attr('accordion-multiOpen') == "undefined"){
                        $(_.options.elements.container).attr('accordion-multiOpen', _.initials.multiOpen);
                    }
                    if(typeof $(_.options.elements.container).attr('accordion-allClosed') == "undefined"){
                        $(_.options.elements.container).attr('accordion-allClosed', _.initials.allClosed);
                    }
                    if(typeof $(_.options.elements.container).attr('accordion-clickToClose') == "undefined"){
                        $(_.options.elements.container).attr('accordion-clickToClose', _.initials.clickToClose);
                    }
                });

                _.init();
            }
        }
        
    }
   
    Accordion.prototype.init = function() {
        var _ = this;

        
        $(_.options.elements.container).attr('accordion-active', 'true');

		//Verifica se tem img e adiciona class
		if(_.initials.image.length>0){
			_.initials.image.parents(_.options.elements.items).attr('accordion-hasImg', '');
		}       

        _.initials.trigger.off( "click.accordionTrigger");
        _.initials.trigger.on( "click.accordionTrigger", function(e) {
            var $changer = $(this);
            var $opener = $(this).next(_.options.elements.content);
            
            if($(this).next(_.options.elements.submenu).length>0){
                $changer = $(this).parent();
                $opener = $(this).next(_.options.elements.submenu);
            }

        	if(typeof $(this).parent().attr('accordion-active') == "undefined"){ // Abrir
                var $scroller = $(window).scrollTop();
                var $offseter = $(_.options.elements.container).offset().top;
                
                if($(_.options.elements.container).parents('[ntgmodal-content]').length>0){
                    //$scroller = $(_.options.elements.container).parents('[ntgmodal-content]').scrollTop();
                    $scroller = 0;
                }
                if($(_.options.elements.container).parents('#details-modal').length>0){
                    //$scroller = $(_.options.elements.container).parents('.detail-ajax').scrollTop();
                    $scroller = 0;
                }
                
                if($scroller > $offseter){     
                    goTo('#'+$(this).attr('id'));
                }

        		if(_.initials.multiOpen == "false"){ //Posso ter mais que um aberto
                    var level = $(e.target).parent().attr('accordion-level');
                    var level2 = parseInt(level)+1;

                    var $parents = $(this).parents('[accordion]').attr('id');
                    var $els = '#'+$parents+' [accordion-level="'+level+'"][accordion-active], #'+$parents+' [accordion-level="'+level2+'"][accordion-active]';

                    $($els).each(function(){
                        var $this = $(this);

                        if(typeof $this.find('[content-visible]').attr('accordion-absolute') == "undefined"){
                            $this.find('[content-visible]').slideUp(_.options.speed);
                        }else{
                            $this.find('[content-visible]').fadeOut(_.options.speed);
                        }
                        
                        $this.find('[content-visible]').attr('aria-hidden', 'true');
                        $this.find('[content-visible]').removeAttr('content-visible');
                        
                        if($(this).find(_.options.elements.submenu).length>0){
                            $(this).attr('aria-expanded', 'false');
                            $(this).removeAttr('accordion-active');
                            $(this).find(_.options.elements.submenu).attr('aria-hidden', 'true');
                        }else{
                            $this.removeAttr('accordion-active');
                            $(this).find(_.options.elements.trigger).attr('aria-expanded', 'false');
                            $(this).find(_.options.elements.content).attr('aria-hidden', 'true');
                        }  
                    });

                    
                   /* if(_.initials.container.find('[accordion-level="'+level2+'"][accordion-active]').length>0){
                        _.initials.container.find('[accordion-level="'+level2+'"][accordion-active]').trigger('click');
                    }*/
            	}

                $opener.parent().attr('accordion-active', '');
                $changer.attr({
                    'aria-expanded': true,
                    'aria-selected': true
                });

                if(typeof $(_.options.elements.container).attr('accordion-absolute') == "undefined"){
                    $opener.slideDown(_.initials.speed);
                }else{
                    $opener.fadeIn(_.initials.speed);
                }

                $opener.attr({
                    'content-visible': '',
                    'aria-hidden':'false'
                });
            }else{ // Fechar
                var level = $(this).parent().attr('accordion-level');
                var activeItems = _.initials.container.find('[accordion-level="'+level+'"][accordion-active]');

                if(_.initials.clickToClose=="false" || (_.initials.allClosed=="true" && activeItems.length==1 && $(e.target).parent().get(0)!=activeItems.get(0))){ //Não posso fechar o aberto
                    return;
                }

            	if(_.initials.allClosed=="true"){ //Posso ter todos fechados
            		$opener.parent().removeAttr('accordion-active');
                    $changer.attr({
                        'aria-expanded': false,
                        'aria-selected': false
                    });
    
                    if(typeof $(_.options.elements.container).attr('accordion-absolute') == "undefined"){
                        $opener.slideUp(_.initials.speed, function(){
                            $changer.removeAttr('content-visible');
                            $changer.attr('aria-hidden', 'true');
                        });
                    }else{
                        $opener.fadeOut(_.initials.speed, function(){
                            $changer.removeAttr('content-visible');
                            $changer.attr('aria-hidden', 'true');
                        });
                    }


                    if(typeof $(_.options.elements.container).attr('accordion-absolute') == "undefined"){
                        $opener.slideUp(_.initials.speed);
                    }else{
                        $opener.fadeOut(_.initials.speed);
                    }

                    $opener.removeAttr('content-visible');
                    $opener.attr('aria-hidden', 'true');
            	}else{
            		if(_.initials.container.find('[content-visible]').length>1){
            			$(this).parent().removeAttr('accordion-active');
                        $changer.attr({
                            'aria-expanded': false,
                            'aria-selected': false
                        });

                        if(typeof $(_.options.elements.container).attr('accordion-absolute') == "undefined"){
                            $opener.slideUp(_.initials.speed);
                        }else{
                            $opener.fadeOut(_.initials.speed);
                        }

                        $opener.removeAttr('content-visible');
                        $opener.attr('aria-hidden', 'true');
            		}
            	}
                
            }
        });
    


        $(_.initials.items).each(function(){
            var $item = $(this);
            var $trigger = $('#'+$item.attr('id')+' > '+ _.options.elements.trigger);

            //Verifica se tem algum aberto
            if($item.find('[content-visible]').length>0){
                $trigger.click();
            }
        });
    };


    $.fn.accordion = function() {
        var _ = this,
            opt = arguments[0],
            args = Array.prototype.slice.call(arguments, 1),
            l = _.length,
            i = 0,
            ret;

        for (i; i < l; i++) {
            if (typeof opt == 'object' || typeof opt == 'undefined')
                _[i].accordion = new Accordion(_[i], opt);
            else
                ret = _[i].accordion[opt].apply(_[i].accordion, args);
            if (typeof ret != 'undefined') return ret;
        }
        return _;
    };

}));


function initAccordions(element){
    if($('[accordion]').length>0){
        $('[accordion]').accordion();
    }
    if($('[dropdown]').length>0){
        $('[dropdown]').accordion();
    }
}


/*
<ul accordion>
  <li accordion-item>
    <a href="javascript:;" class="list_tit" accordion-title>Accordion 1</a>
    <div class="textos" accordion-content>
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pellentesque tincidunt lectus, et fermentum arcu gravida a. Donec sem nibh, congue non varius at, lobortis vitae leo. Quisque sodales sodales quam, eu vulputate tellus scelerisque at. Proin a elit finibus dolor iaculis condimentum eu vel quam. Sed ac nulla ut diam interdum varius in et est. Vestibulum a dui at massa pharetra ultricies in at enim. Vestibulum consectetur efficitur iaculis. Quisque nec mollis lorem. Cras elementum magna non dui ornare, sit amet ullamcorper orci rutrum. Donec congue velit ut diam vestibulum porttitor. Pellentesque imperdiet mauris ut ligula lobortis fermentum. Suspendisse at ante sit amet lorem aliquam porta. Donec bibendum purus a dolor suscipit venenatis. Quisque et sapien laoreet, convallis erat et, consectetur nisl. Nullam tristique interdum nisl nec hendrerit.
    </div>
  </li>
  <li accordion-item>
    <a href="javascript:;" class="list_tit" accordion-title>Accordion 2</a>
    <div class="textos" accordion-content>
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pellentesque tincidunt lectus, et fermentum arcu gravida a. Donec sem nibh, congue non varius at, lobortis vitae leo. Quisque sodales sodales quam, eu vulputate tellus scelerisque at. Proin a elit finibus dolor iaculis condimentum eu vel quam. Sed ac nulla ut diam interdum varius in et est. Vestibulum a dui at massa pharetra ultricies in at enim. Vestibulum consectetur efficitur iaculis. Quisque nec mollis lorem. Cras elementum magna non dui ornare, sit amet ullamcorper orci rutrum. Donec congue velit ut diam vestibulum porttitor. Pellentesque imperdiet mauris ut ligula lobortis fermentum. Suspendisse at ante sit amet lorem aliquam porta. Donec bibendum purus a dolor suscipit venenatis. Quisque et sapien laoreet, convallis erat et, consectetur nisl. Nullam tristique interdum nisl nec hendrerit.
    </div>
  </li>
  <li accordion-item>
    <a href="javascript:;" class="list_tit" accordion-title>Accordion 3</a>
    <div class="textos" accordion-content>
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pellentesque tincidunt lectus, et fermentum arcu gravida a. Donec sem nibh, congue non varius at, lobortis vitae leo. Quisque sodales sodales quam, eu vulputate tellus scelerisque at. Proin a elit finibus dolor iaculis condimentum eu vel quam. Sed ac nulla ut diam interdum varius in et est. Vestibulum a dui at massa pharetra ultricies in at enim. Vestibulum consectetur efficitur iaculis. Quisque nec mollis lorem. Cras elementum magna non dui ornare, sit amet ullamcorper orci rutrum. Donec congue velit ut diam vestibulum porttitor. Pellentesque imperdiet mauris ut ligula lobortis fermentum. Suspendisse at ante sit amet lorem aliquam porta. Donec bibendum purus a dolor suscipit venenatis. Quisque et sapien laoreet, convallis erat et, consectetur nisl. Nullam tristique interdum nisl nec hendrerit.
    </div>
    <div class="accordion-image has_bg" accordion-image style="background-image: url('imgs/img/01.jpg');"></div>
  </li>
  <li accordion-item>
    <a href="javascript:;" class="list_tit" accordion-title>Accordion 4</a>
    <ul accordion-nested>
      <li accordion-item>
        <a href="javascript:;" class="list_subtit" accordion-title>Item 1A</a>
        <ul accordion-nested>
          <li accordion-item><a href="javascript:;" class="list_txt" accordion-link>Item 1Ai</a></li>
          <li accordion-item><a href="javascript:;" class="list_txt" accordion-link>Item 1Aii</a></li>
          <li accordion-item><a href="javascript:;" class="list_txt" accordion-link>Item 1Aiii</a></li>
        </ul>
      </li>
      <li accordion-item><a href="javascript:;" class="list_subtit" accordion-link>Item 1B</a></li>
    </ul>
  </li>
</ul>

<ul dropdown accordion-absolute accordion-icon="icon-right">
   <li accordion-item>
    <a href="javascript:;" class="list_tit" accordion-title>Dropdown</a>
    <ul accordion-nested>
      <li accordion-item><a href="javascript:;" class="list_subtit" accordion-title>Item 1A</a></li>
      <li accordion-item><a href="javascript:;" class="list_subtit" accordion-title>Item 1B</a></li>
    </ul>
  </li>
</ul>


<ul dropdown accordion-absolute accordion-mutliSelect accordion-icon="icon-right">
   <li accordion-item>
    <a href="javascript:;" class="list_tit" accordion-title>Dropdown Multi Select</a>
    <ul accordion-nested>
        <li>
            <div class="inpt_checkbox">
                <input type="checkbox" name="objetivos[]" id="objetivos_1" value="Objectivo 1"/>
                <label for="objetivos_1">Objectivo 1</label>
            </div>
        </li>
        <li>
            <div class="inpt_checkbox">
                <input type="checkbox" name="objetivos[]" id="objetivos_2" value="Objectivo 2"/>
                <label for="objetivos_2">Objectivo 2</label>
            </div>
        </li>
        <li>
            <div class="inpt_checkbox">
                <input type="checkbox" name="objetivos[]" id="objetivos_3" value="Objectivo 3"/>
                <label for="objetivos_3">Objectivo 3</label>
            </div>
        </li>
    </ul>
  </li>
</ul>


*/
