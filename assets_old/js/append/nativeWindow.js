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
    var nativeWindow = window.nativeWindow || {};

    nativeWindow = (function() {

        function nativeWindow(element, settings) {
            var _ = this, dataSettings, willInit;

           _.defaults = {
                container : element,
                texts : {
                    title : '',
                    select : $recursos["selecione"],
                    filter: "Ok",
                    cancel: $recursos["cancelar"],
                    clear: $recursos["limpar"],
                },
                classes: {
                    trigger : "textos icon-down",
                    title : 'textos',
                    footer : 'text-center',
                },
                breakpoint: {
                    class:"hide-for-medium",
                    value: 950,
                },
                onOpen: function() { return false; }, //Callback for when the modal is opened.
                onClose: function() { return false; },// Callback for when the modal closes.
            };

            _.initials = {
                title : '',
                select : '',
            };
            
            dataSettings = $(element).data('nativewindow', _) || {};
            $.extend(_, _.initials);
            _.options = $.extend({}, _.defaults, dataSettings, settings);   

            _.init();

            _.init = $.proxy(_.init, _);

        }
        return nativeWindow;

    }());
       
    nativeWindow.prototype.init = function() {
        var _ = this; 
        var with_breakpoints = 1;

        if($(_.options.container).length>0){            
            if(typeof $(_.options.container).attr('native-window') == "undefined"){
                $(_.options.container).attr('native-window', '');
            }

            if(typeof $(_.options.container).attr('no-breakpoints') != "undefined"){
                with_breakpoints = 0;
                _.options.breakpoint.class="";
            }
        
            _.initials = {
                title : $(_.options.container).attr('data-title') || _.options.texts.title,
                select : $(_.options.container).attr('data-select') || _.options.texts.select,
                trigger : $('<div native-window-trigger class="inpt '+_.options.classes.trigger+' '+_.options.breakpoint.class+'" aria-haspopup="true" tabindex="0">'+_.initials.select+'</div>'),
                container : $('<div native-window-container role="dialog" aria-hidden="true"></div>'),
                wrapper : $('<div native-window-wrapper></div>'),
                head : $('<div native-window-head class="'+_.options.breakpoint.class+'"></div>'),
                body : $('<div native-window-body>'+$(_.options.container).children().first().html()+'</div>'),
                footer : $('<div native-window-footer class="'+_.options.classes.footer+' '+_.options.breakpoint.class+'"></div></div>'),
                buttons : $('<div class="row collapse align-spaced align-middle" style="height="100%"><div class="column shrink"><a class="list_subtit" native-window-cancel href="javascript:;" role="button">'+_.options.texts.cancel+'</a></div><div class="column shrink"><a class="list_subtit" native-window-clear href="javascript:;" role="button">'+_.options.texts.clear+'</a></div><div class="column shrink"><a class="list_subtit" native-window-filter href="javascript:;" role="button">'+_.options.texts.filter+'</a></div></div>'),
            };

            $(window).resized(function(){
                if(($(window).innerWidth() <= _.options.breakpoint.value) || with_breakpoints==0){
                    if($(_.options.container).find(_.initials.trigger).length==0){
                        $(_.initials.footer).append(_.initials.buttons);                        

                        $(_.initials.wrapper).append(_.initials.head);
                        $(_.initials.wrapper).append(_.initials.body);
                        $(_.initials.wrapper).append(_.initials.footer);

                        $(_.initials.container).append(_.initials.wrapper).attr('native-window-styled', '');

                        $(_.options.container).children().first().replaceWith(_.initials.container);
                        $(_.options.container).prepend(_.initials.trigger);

                        $(_.options.container).find(_.initials.head).html('<h3 class="'+_.options.classes.title+'"><strong>'+_.initials.title+'</strong></h3>');

                        _.initials.trigger.off("click.openWindow");
                        _.initials.trigger.on("click.openWindow", function(event) {
                            $('body').addClass('overHidden-windows');
                            $('.page-main.page-current').addClass('aboveAll-window');

                            $(this).addClass('txtUpdate');

                            $(_.initials.container).attr({
                                'native-window-opened': '',
                                'aria-hidden': false,
                                'tabindex': -1
                            });

                            _.options.onOpen();
                        });

                        $(_.options.container).find('[native-window-cancel]').off("click.cancelWindow");
                        $(_.options.container).find('[native-window-cancel]').on("click.cancelWindow", function(event) {
                            var inpts = $(_.options.container).find('.txtUpdate').attr('data-sels');                  
                            var name = $(_.options.container).find('input:eq(0)').attr('name');         
                            $('[name="'+name+'"]').prop('checked', false);

                            if(inpts && typeof inpts != "undefined"){
                                $(this).parents('[native-window-container]').find('input').each(function(){
                                    if(inpts.indexOf($(this).val())>=0){
                                        $(this).prop('checked', true);
                                    }
                                });
                            }

                            _.closeWindow();
                        });

                        $(_.options.container).find('[native-window-clear]').off("click.clearWindow");
                        $(_.options.container).find('[native-window-clear]').on("click.clearWindow", function(event) {
                            var $elems = $(this).parents('[native-window-container]').find('input:checked');

                            $('[native-window-trigger].txtUpdate').text(_.initials.select);

                            if($elems.length>0){
                                var name = $(this).parents('[native-window-container]').find('input:eq(0)').attr('name');
                                $('[name="'+name+'"]').prop('checked', false);
                            }

                            _.closeWindow();
                        });

                        $(_.options.container).find('[native-window-filter]').off("click.filterWindow");
                        $(_.options.container).find('[native-window-filter]').on("click.filterWindow", function(event) {
                            var $elems = $(this).parents('[native-window-container]').find('input:checked');
                            var texto = "";
                            var ids = "";

                            if($elems.length>0){
                                $elems.each(function(){
                                    ids+=$(this).val()+",";
                                    texto+=$(this).next().text()+", ";
                                });

                                if(ids) ids = ids.substr(0, ids.length - 1);
                                if(texto) texto = texto.substr(0, texto.length - 2);
                            }else{
                                texto = _.initials.select;
                            }

                            if($('[native-window-trigger].txtUpdate').length>0){
                                $('[native-window-trigger].txtUpdate').attr('data-sels', ids);
                                $('[native-window-trigger].txtUpdate').text(texto);
                            }else{
                                $(this).parents('[native-window-container]').prev('[native-window-trigger]').attr('data-sels', ids);
                                $(this).parents('[native-window-container]').prev('[native-window-trigger]').text(texto);
                            }
                            
                            _.closeWindow();
                        });


                        if($(_.options.container).find('[native-window-filter]').length>0){
                            $(_.options.container).find('[native-window-filter]').each(function(){
                                $(this).trigger('click');
                            });
                        }
                    }else{
                        $(_.initials.container).attr('native-window-styled', '');
                    }
                }else{
                    $(_.initials.container).removeAttr('native-window-styled');
                }
            });

            window.dispatchEvent(new Event('resize'));
        }
    };

    nativeWindow.prototype.closeWindow = function() {
        var _ = this; 
        
        $('.txtUpdate').removeClass('txtUpdate');     
        
        $(_.initials.container).removeAttr('native-window-opened');
        $(_.initials.container).attr({
            'aria-hidden': true,
            'tabindex': 0
        });

        $('.page-main.page-current').removeClass('aboveAll-window');
        $('body').removeClass('overHidden-windows');

        _.options.onClose();
    }

    $.fn.nativewindow = function() {
        var _ = this,
            opt = arguments[0],
            args = Array.prototype.slice.call(arguments, 1),
            l = _.length,
            i = 0,
            ret;

        for (i; i < l; i++) {
            if (typeof opt == 'object' || typeof opt == 'undefined')
                _[i].nativewindow = new nativeWindow(_[i], opt);
            else
                ret = _[i].nativewindow[opt].apply(_[i].nativewindow, args);
            if (typeof ret != 'undefined') return ret;
        }
        return _;
    };

}));


function initWindows(element){
    if($('[native-window]').length>0){
        $('[native-window]').nativewindow();
    }
}


/*
<div native-window data-title="cenas"> 
    <div class="div_100">
        <a class="div_100 filters" href="javascript:;">
        <input class="loja_inpt" type="checkbox" <?php echo $type; ?> data-name="filtros" name="filtros_<?php echo $filt_opc["cat"]; ?>" id="filtros_<?php echo $filt_opc["cat"]; ?>_<?php echo $sub_id; ?>" value="<?php echo $sub_id; ?>" <?php if(in_array($sub_id, $filtros)) echo "checked"; ?> />
        <h5><?php echo $filt_opc["nome"]; ?></h5><?php if($counters_active){ ?><p><?php echo "(".$totalRows_rsCounter.")"; ?></p><?php } ?>
    </a>
  </div>
</div>

*/