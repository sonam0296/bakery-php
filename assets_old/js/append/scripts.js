/***************** INITS *****************/
function init_svg() {
	'use strict';
	// if(!Modernizr.svg) {
	// 	$('img[src*="svg"]').each(function() {
	//            var new_attr = $(this).attr('src');
	// 		new_attr = new_attr.replace('.svg', '.png');
	// 		$(this).attr('src', new_attr);
	//        });
	// }

	// var ua = window.navigator.userAgent;
	// var msie = ua.indexOf('MSIE ');
	// var trident = ua.indexOf('Trident/');
	// var edge = ua.indexOf('Edge/');

	// if (msie > 0 || trident > 0 || edge > 0) {
	// 	$('img[src*="svg"]').each(function() {
	//            var new_attr = $(this).attr('src');
	// 		new_attr = new_attr.replace('.svg', '.png');
	// 		$(this).attr('src', new_attr);
	//        });
	// }
}

/**
 * Init load banner HOMEPAGE
 * @return null
 */
function initBanners() {
	'use strict';

	if ($('#banners').length > 0) { //Banners Homepage        
		if ($('.slick-banners').length > 0) {
			if ($('.banners').hasClass('fullscreen') && !$('.header').hasClass('absolute-start')) {
				$(window).resized(function () {
					var newH = $('.header').outerHeight();

					var icons_section = 0;
					if ($('.icons-section').length > 0) {
						icons_section = $('.icons-section').outerHeight();
					}

					var promotion_section = 0;
					if ($('.promotion_section').length > 0) {
						promotion_section = $('.promotion_section').outerHeight();
					}

					if ($(window).width() >= 951) {
						newH = newH + icons_section;
					} else {
						newH = newH + promotion_section;
					}


					if (newH !== $('.banners').attr('data-margin')) {
						$('.banners').attr('data-margin', newH);

						$('.banners').css('height', '-webkit-calc(100vh - ' + newH + 'px)');
						$('.banners').css('height', 'calc(100vh - ' + newH + 'px)');
					}
				});
			}
			$('.slick-banners').on('init', function (event, slick, currentSlide, nextSlide) {
				var bgss = new bgsrcset();
				bgss.callonce = false;
				bgss.init('.banners_slide');

				toogleBanners(0, $('.slick-banners').find('.slick-current'));
			});
			$('.slick-banners').slick({
				dots: true,
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: true,
				infinite: false,
				adaptiveHeight: false,
				fade: true,
				autoplay: true,
				autoplaySpeed: 4000,
				cssEase: 'linear',
			});
			$('.slick-banners').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
				toogleBanners(1, $('.slick-banners').find('.slick-current'));

				if ($('.slick-slide:eq(' + currentSlide + ') video').length > 0) {
					$('.slick-slide:eq(' + currentSlide + ') video').get(0).pause();
				}
			});
			$('.slick-banners').on('afterChange', function (event, slick, currentSlide, nextSlide) {
				toogleBanners(0, $('.slick-banners').find('.slick-current'));

				if ($('.slick-slide:eq(' + currentSlide + ') video').length > 0) {
					$('.slick-slide:eq(' + currentSlide + ') video').get(0).play();
				}
			});
		}
	}

	function toogleBanners(type, $el) {
		if ($el.length > 0) {
			var tit = $el.find('h1');
			var txt = $el.find('h2');
			var btn = $el.find('a');

			$(tit).toggleClass('show');
			$(txt).toggleClass('show');
			$(btn).toggleClass('show');
		}
	}
}
/**
 * Used on function initBanners()
 * @param  {[element]} $el next slider
 */
function toogleBanners(type, $el) {
	if ($el.length > 0) {
		var tit = $el.find('h1');
		var txt = $el.find('h2');
		var btn = $el.find('a');

		$(tit).toggleClass('show');
		$(txt).toggleClass('show');
		$(btn).toggleClass('show');
	}
}
function init_fades() {
	'use strict';
	//Elements Fading
	if ($('.elements_animated').length > 0) {
		$('.elements_animated').each(function (index, element) {
			var watcher = scrollMonitor.create(element);

			if ($(this).parents('.detail-ajax.active').length > 0) {
				var containerElement = $('.detail-ajax.active')[0];
				var containerMonitor = scrollMonitor.createContainer(containerElement);

				var childElement = element;
				watcher = containerMonitor.create(childElement);
			}

			if ($(this).parents('[ntgmodal][ntgmodal-opened]').length > 0) {
				var containerElement = $('[ntgmodal][ntgmodal-opened]')[0];
				var containerMonitor = scrollMonitor.createContainer(containerElement);

				var childElement = element;
				watcher = containerMonitor.create(childElement);
			}

			watcher.enterViewport(function () {
				$(element).addClass('active');

				setTimeout(function () {
					$(element).removeClass('elements_animated active');
				}, 1000);

				watcher.destroy();
			});
		});
	}
}

function init_animation() {
	'use strict';
	var scrollElemToWatch = $('.block_revelear');

	if (scrollElemToWatch.length > 0) {
		scrollElemToWatch.each(function () {
			if (!$(this).hasClass('revealed')) {
				var element = $(this),
					el_color = $(this).attr('data-color'),
					el_direction = $(this).attr('data-direction'),
					el_cover = $(this).attr('data-cover'),
					watcher;

				if (!el_cover) {
					el_cover = 0;
				}

				var rev = new RevealFx(element[0], {
					revealSettings: {
						bgcolor: el_color,
						direction: el_direction,
						coverArea: el_cover,
						onCover: function (contentEl, revealerEl) {
							contentEl.style.opacity = 1;
						},
					}
				});

				if ($(this).parents('.detail-ajax').length > 0) {
					var containerElement = $('.detail-ajax.active')[0];
					var containerMonitor = scrollMonitor.createContainer(containerElement);

					var childElement = element[0];
					watcher = containerMonitor.create(childElement);
				} else {
					watcher = scrollMonitor.create(element[0]);
				}


				if (!$(this).hasClass('fully')) {
					watcher.enterViewport(function () {
						setTimeout(function () {
							rev.reveal();
							element.addClass('revealed');
							if (!$(this).hasClass('always')) watcher.destroy();
						}, 300);
					});
				} else {
					watcher.enterViewport(function () {
						setTimeout(function () {
							rev.reveal();
							element.addClass('revealed');
							if (!$(this).hasClass('always')) watcher.destroy();
						}, 1000);
					});
				}
			}
		});
	}
}


function init_shares() {
	'use strict';

	if ($('.share-opener').length > 0) {
		$('.share-opener').click(function () {
			if ($(this).parents('.share_container').find('.share_modal').length > 0) {
				$(this).parents('.share_container').find('.share_modal').toggleClass('active');
			}
		});
		$('body').on('click', function (e) {
			if ($(e.target).closest('.share_container').length == 0 && $('.share_modal').hasClass('active')) {
				$('.share_modal').removeClass('active')
			}
		});
	}
	if ($('.share-button').length > 0) {
		$('.share-button').click(function () {
			if ($(this).attr('data-link')) {
				window.open($(this).attr('data-link'), 'targetWindow', 'toolbar=no, location=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=500 ,height=400');
			}
		});
	}
}

function initIframes() {
	'use strict';
	if ($('.video_frame').length > 0) {
		$('.video_frame').each(function (index, element) {
			var vid = $(this).attr('data-vid');

			if (vid && !$(element).hasClass('framed')) {
				if (vid.indexOf("/") !== -1) {
					vid = vid.split('/');
					vid = vid[vid.length - 1];
					if (vid.indexOf("?") !== -1) {
						vid = vid.split('?');
						vid = vid[0];
					}
				}

				var embed;
				var div = document.createElement("div");
				div.setAttribute("data-vid", vid);
				div.classList.add('has_bg');

				if ($(element).hasClass('youtube')) {
					var thumb = "hqdefault";
					embed = "https://www.youtube.com/embed/" + vid + "?autoplay=1";

					if ($(element).hasClass('full')) {
						thumb = "maxresdefault";
					}


					if ($(element).parents('[data-thumb]').length > 0) {
						$(element).parents('[data-thumb]').attr('data-thumb', 'https://i.ytimg.com/vi/' + vid + '/default.jpg');
					}

					div.style.backgroundImage = "url('https://i.ytimg.com/vi/" + vid + "/" + thumb + ".jpg')";
				} else if ($(element).hasClass('vimeo')) {
					embed = "https://player.vimeo.com/video/" + vid + "?autoplay=1";

					$.getJSON('http://www.vimeo.com/api/v2/video/' + vid + '.json?callback=?', { format: "json" }, function (data) {
						var thumb = data[0].thumbnail_large;

						if ($(element).hasClass('full')) {
							var thumbSplit = thumb.split(/\d{3}(?=.jpg)/);
							thumb = thumbSplit[0] + '1920x1080' + thumbSplit[1];
						}

						if ($(element).parents('[data-thumb]').length > 0) {
							$(element).parents('[data-thumb]').attr('data-thumb', thumbSplit[0] + '100x100' + thumbSplit[1]);
						}

						div.style.backgroundImage = "url('" + thumb + "')";
					});
				}


				$(div).html('<div class="play"><svg x="0px" y="0px" viewBox="0 0 30.5 30.5" xml:space="preserve"><g><path d="M28.5,7.6c-2-3.5-5.3-6.1-9.3-7.1c-1.3-0.4-2.6-0.5-4-0.5C6.8,0,0,6.9,0,15.3c0,2.7,0.7,5.3,2,7.5c4.1,7.4,13.4,10,20.7,5.9s10-13.4,5.9-20.7C28.6,7.9,28.5,7.8,28.5,7.6zM28.3,18.8c-0.9,3.5-3.2,6.4-6.3,8.2c-6.5,3.7-14.7,1.5-18.5-5C-0.2,15.5,2,7.2,8.5,3.5c3.1-1.8,6.8-2.3,10.2-1.3C26,4.1,30.3,11.5,28.3,18.8z"/><path d="M21,14.6l-8.4-4.9c-0.8-0.5-1.4-0.1-1.4,0.8v9.7c0,0.9,0.6,1.2,1.4,0.8l8.3-4.8c0.4-0.2,0.7-0.6,0.5-1.1C21.4,14.9,21.3,14.7,21,14.6z"/></g></svg></div>');

				$(div).click(function () {
					var iframe = document.createElement("iframe");

					iframe.setAttribute("src", embed);
					iframe.setAttribute("frameborder", "0");
					iframe.setAttribute("allowfullscreen", "1");
					this.parentNode.replaceChild(iframe, this);
				});

				element.appendChild(div);
				$(element).addClass('framed');
			}
		});
	}
}

function initVideo() {
	'use strict';
	if ($('video').length > 0 && checkMobile()) {
		$('video').each(function () {
			if ($(this).attr('autoplay')) {
				$(this).attr("muted", "muted");
				$(this)[0].play();
			}
		})
	}
}

function initLazyLoad(element) {
	if ($('.lazy', $(element)).length > 0) {
		$('.lazy', $(element)).lazy({
			imageBase: _images_path,
			bind: "event",
			delay: 0,
			afterLoad: function (element) {
				$(element).addClass('lazy-completed');
			}
		});
	}
}

var array_captcha = [];
function init_inputs() {
	'use strict';

	if ($('.inpt_holder').length > 0) {
		$('.inpt_holder').each(function (index, element) {
			$("input, textarea", element).on("blur keypress", function () {
				if ($(this).val() !== '') {
					$(this).parents('.inpt_holder').addClass('focused');
				} else {
					$(this).parents('.inpt_holder').removeClass('focused');
				}
			});
			$("input, textarea", element).on("focus", function () {
				$(this).parents('.inpt_holder').addClass('hovered');
			});
			$("input, textarea", element).on("focusout", function () {
				$(this).parents('.inpt_holder').removeClass('hovered');
			});


			// if($(element).attr("class").indexOf('icon-')>0){
			// 	$(element).on('click', function(e){
			// 		if($(e.target).context===$(element).context){
			// 			e.preventDefault();
			// 			e.stopPropagation();
			// 			$(element).find('input').focus();
			// 		}
			// 	});
			// }
		});
	}
	$("textarea").on("blur", function () {
		if ($(this).val() !== '') {
			$(this).removeClass('hovered').addClass('focused');
		} else {
			$(this).removeClass('focused').removeClass('hovered');
		}
	});

	//  if($('[data-toggle="datepicker"]').length>0){
	// 	if(checkMobile()){
	// 		$('[data-toggle="datepicker"]').each(function() {
	// 			if($(this).attr("type")!="date"){
	// 				$(this).attr("type", "date");

	// 				if($(this).hasClass('range')){ 
	// 				  $(this).on('change', function(){
	// 				  	if(typeof $(this).attr('data-range-start') !== "undefined"){
	// 				        $(this).parents('.range_holder').find('.range[data-range-end]').attr('min', $(this).val());
	// 				      }
	// 				      if(typeof $(this).attr('data-range-end') !== "undefined"){
	// 				        $(this).parents('.range_holder').find('.range[data-range-start]').attr('max', $(this).val());
	// 				      }
	// 				  });

	// 				  if($(this).val()){
	// 				  	$(this).trigger('change');
	// 				  }
	// 			    }
	// 			}
	// 		});
	// 	}
	// }

	if ($('input[type="date"]').length > 0) {
		$('input[type="date"]').on("change", function () {

			if ($(this).hasClass('range')) {
				if (typeof $(this).attr('data-range-start') !== "undefined") {
					$(this).parents('.range_holder').find('.range[data-range-end]').attr('min', $(this).val());
				}
				if (typeof $(this).attr('data-range-end') !== "undefined") {
					$(this).parents('.range_holder').find('.range[data-range-start]').attr('max', $(this).val());
				}
			}

			if ($(this).val() !== '') {
				$(this).addClass('has-value');
			} else {
				$(this).removeClass('has-value');
			}
		});
	}

	if ($("input[min]").length > 0) {
		$("input[min]").on("blur keyup", function () {
			if (parseFloat($(this).val()) < parseFloat($(this).attr('min'))) {
				$(this).val($(this).attr('min'));
				$(this).trigger('change');
			}
		});
	}

	if ($("input[max]").length > 0) {
		$("input[max]").on("blur keyup", function () {
			if (parseFloat($(this).val()) > parseFloat($(this).attr('max'))) {
				$(this).val($(this).attr('max'));
				$(this).trigger('change');
			}
		});
	}

	if ($('.cod_confirm').length > 0) {
		$('.cod_confirm').each(function () {
			$(this)[0].onpaste = function (e) {
				e.preventDefault();
			};
		});
	}

	//if (!detectIE()){
	if ($('.inputfile').length > 0) {
		$('.inputfile').each(function () {
			var $input = $(this),
				$label = $input.parent().find('.inpt'),
				labelVal = $label.val();

			$input.off('change.inptFile');
			$input.on('change.inptFile', function (e) {
				var fileName = '';
				var $this = $(this)[0];

				//HANDLE PROGRESS
				if ($(this).hasClass('progress')) {
					var form = $(this).parents('form').attr('id');
					var inpt = $(this).attr('id');
					var folder = $(this).attr('folder');
					var callback = $(this).attr('data-callback');

					uploadFileProgress(inpt, form, folder, callback);
				}

				//HANDLE PREVIEW
				if ($(this).hasClass('preview')) {
					$($(this).attr('data-appender')).html("");
				}

				if ($this.files && $this.files.length > 1) {
					for (var i = 0; i < $this.files.length; i++) {
						if ($(this).hasClass('preview')) {
							readFile(e.target.files.item(i), $(this).attr('data-appender'));
						}

						if (fileName) {
							fileName = fileName + ", " + $this.files.item(i).name;
						} else {
							fileName = $this.files.item(i).name;
						}
					}
					$input.addClass('has-files');
				} else if (e.target.value) {
					fileName = e.target.value.split('\\').pop();

					if ($(this).hasClass('preview')) {
						readFile(e.target.files[0], $(this).attr('data-appender'));
					}

					$input.addClass('has-files');
				} else {
					$input.removeClass('has-files');
				}

				if (fileName) $label.val(fileName);
				else $label.val(labelVal);
			});

			// Firefox bug fix
			$input
				.on('focus', function () { $input.addClass('has-focus'); })
				.on('blur', function () { $input.removeClass('has-focus'); });
		});
	}
	//}
}
var captchaTime = 0;
function init_captchas() {
	clearTimeout(captchaTime);
	if ($('.captcha').length > 0) {
		$('.captcha').each(function () {
			var captcha = $(this).attr('id');
			if (typeof grecaptcha != 'undefined' && typeof grecaptcha.render != 'undefined' && $('#' + captcha + ' > div').length == 0) {
				array_captcha[captcha] = grecaptcha.render(captcha, {
					'sitekey': $('#' + captcha).attr('data-sitekey'),
					'callback': '',
				});
			} else if (grecaptcha.render == 'undefined') {
				captchaTime = setTimeout(captchaTime, 250);
			}
		});
	}
}
function initCookies() {
	'use strict';
	window.setTimeout(function () {
		$('.div_cookies').fadeIn(500);
	}, 1500);
}

function initVoltarTopo() {
	'use strict';
	if ($("#voltar_acima").length > 0) {
		var topo = $(document).scrollTop();
		var menu = $(".mainDiv").position().top + 180;

		if (topo >= menu) $("#voltar_acima").fadeIn();
		else $("#voltar_acima").fadeOut();
	}
}
function initEqualizer() {
	var groupsArray = [];
	if ($('[data-equalize]').length > 0) {
		$('[data-equalize]').each(function () {
			var targetHeight = 0;

			var canStart = true;
			if ($(this).attr('data-equalize-start') && $(this).attr('data-equalize-start') > 0) {
				if ($('body').innerWidth() > $(this).attr('data-equalize-start')) canStart = false;
			}
			if ($(this).attr('data-equalize-stop') && $(this).attr('data-equalize-stop') > 0) {
				if ($('body').innerWidth() <= $(this).attr('data-equalize-stop')) canStart = false;
			}

			if (canStart) {
				if (groupsArray.indexOf($(this).attr('data-equalize')) == -1) {
					groupsArray.push($(this).attr('data-equalize'));
					$('[data-equalize=' + $(this).attr('data-equalize') + ']').css('height', 'auto')
					$('[data-equalize=' + $(this).attr('data-equalize') + ']').each(function () {
						//if ($(this).outerHeight(true) > targetHeight){
						//targetHeight=$(this).outerHeight(true);
						//}
						targetHeight = Math.max($(this).height(), targetHeight);
					}).height(targetHeight);
				}
			} else {
				if ($(this).css('height') != "inherit") $('[data-equalize=' + $(this).attr('data-equalize') + ']').css('height', 'inherit');
				return;
			}
		});
	}
}
function initCalendar() {
	//if(!checkMobile()){
	if ($('[data-toggle="datepicker"]').length > 0) {
		var $days = $recursos["cal_dias"].split(",");
		var $daysShort = $recursos["cal_dias2"].split(",");
		var $daysMin = $recursos["cal_dias3"].split(",");
		var $months = $recursos["cal_meses"].split(",");
		var $monthsShort = $recursos["cal_meses2"].split(",");

		$('[data-toggle="datepicker"]').each(function () {
			var elem = "#" + $(this).attr('id');
			var position = "top";
			var disabledDays = [];
			var startDate = null;
			var endDate = null;
			var $fullscreen = false;

			if ($(this).hasClass('bottom')) {
				position = "bottom";
			}
			if ($(this).hasClass('fullscreen')) {
				$fullscreen = true;
			}
			if ($(this).attr('data-disabledDays')) {
				disabledDays = $(this).attr('data-disabledDays').split(';');
			}

			if (!$(this).hasClass('all_dates') && !$(this).hasClass('maxToday')) {
				startDate = new Date();

				if (typeof $(this).attr('data-range-end') !== "undefined") {
					var diff_date = $(this).attr('data-diff');
					if (!diff_date) diff_date = 1;

					startDate = moment(new Date()).add(diff_date, 'days').format("DD/MM/YYYY");
				}
			}
			if ($(this).hasClass('maxToday')) {
				endDate = new Date();

				if (typeof $(this).attr('data-range-end') !== "undefined") {
					var diff_date = $(this).attr('data-diff');
					if (!diff_date) diff_date = 1;

					endDate = moment(new Date()).add(diff_date, 'days').format("DD/MM/YYYY");
				}
			}

			$(elem).on('keydown keyenter', function (e) {
				e.preventDefault();
				return false;
			});

			if (checkMobile() && !detectIOS()) {
				$(elem).attr('readonly', 'readonly');
			}

			$(elem).datepicker({
				position: position,
				format: _dateFormat, //vem do workers
				days: $days,
				daysShort: $daysShort,
				daysMin: $daysMin,
				months: $months,
				monthsShort: $monthsShort,
				startDate: startDate,
				endDate: endDate,
				disabledDates: disabledDays,
				autoHide: true,
				fullscreen: $fullscreen,
			});
		});
	}
	//}
}

function detectIOS() {
	var IS_IOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
	return IS_IOS;
}
function detectIE() {
	var ua = window.navigator.userAgent;

	var msie = ua.indexOf('MSIE ');
	if (msie > 0) {
		// IE 10 or older => return version number
		//return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
		return true;
	}

	var trident = ua.indexOf('Trident/');
	if (trident > 0) {
		// IE 11 => return version number
		var rv = ua.indexOf('rv:');
		//return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
		return true;
	}

	var edge = ua.indexOf('Edge/');
	if (edge > 0) {
		// Edge (IE 12+) => return version number
		//return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
		return true;
	}

	// other browser
	return false;
}

/***************** HELPERS *****************/
function subs_news(form) {
	var meuEmail = $('input[type="email"]', '#' + form);
	var primeiro_nome = $('#primeiro_subs', '#' + form).val();
	var ultimo_nome = $('#ultimo_subs', '#' + form).val()
	var nome = primeiro_nome + " " + ultimo_nome;

	$.post(_includes_path + "subs_obrigado.php", { meuEmail: meuEmail.val(), nome: nome }, function (data) {
		var info = data.split("###");
		if (info[0] == "1") {
			/*ntg_success(info[1]);
			$('input[type="email"]', '#'+form).val("");*/
			document.location = 'subscricao_obrigado.php';
		}
		else {
			ntg_error(info[1]);
		}

		$("label[for='termos_news']").click();
	});
}
function allowCookies() {
	'use strict';
	$.post(_includes_path + 'rpc.php', { op: 'allowCookies' }, function () {
		$('.div_cookies').fadeOut(500);
	});
}

document.addEventListener('scroll', initVoltarTopo, { passive: true });

$(window).on('load', function () {
	'use strict';
	if ($('.popup_container').length > 0) {
		setTimeout(function () {
			$('body').addClass('overHidden-popup');
			$('.popup_container').addClass('active');
		}, 1000);
	}
});
$('.popup_close').click(function (e) {
	'use strict';
	e.preventDefault();
	$.post(_includes_path + "rpc.php", { op: "fecha_popup" }, function (data) {
		$('body').removeClass('overHidden-popup');
		$('.popup_container').removeClass('active');
	});
});
$('.popup_container a').click(function (e) {
	'use strict';
	$.post(_includes_path + "rpc.php", { op: "fecha_popup" }, function (data) {
		$('body').removeClass('overHidden-popup');
		$('.popup_container').removeClass('active');
	});
});

function checkMobile() {
	'use strict';
	var onMobile = false;
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) { onMobile = true; }

	return onMobile;
}
function check_enter(e, el) {
	'use strict';
	//verifica o enter nos inputs sem forms EX: onKeyPress="check_enter(event, '.nome_elemento')"
	// o elemento sera o btn de submit
	if (e.keyCode === 13) {
		$(el).click();
	}
}
function changePassType(el) {
	'use strict';

	var input = $(el).parent().find('input');
	if (input.attr('type') == "text") {
		$(el).removeClass('show');
		input.attr('type', 'password');
	} else {
		$(el).addClass('show');
		input.attr('type', 'text');
	}
}
function checkPassword(str) {
	'use strict';
	// at least one number, one lowercase and one uppercase letter
	// at least six characters
	var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}/;
	return re.test(str);
}

/***************** FUNCTIONS *****************/
function getAltMenu() {
	'use strict';
	var alt_menu = $('.header').outerHeight();
	return alt_menu;
}

function goTo(obj, center) {
	'use strict';

	var stickys = 0;
	if ($('.to_sticky.is-sticky').length > 0) {
		stickys = 1;
	}

	var $window = $('html, body');

	if ($(obj).parents('[ntgmodal-content]').length > 0) {
		$window = $(obj).parents('[ntgmodal-content]');
	}
	if ($(obj).parents('#details-modal').length > 0) {
		$window = $(obj).parents('.detail-ajax');
	}

	if ($(obj).length > 0) {
		if (center == 1) {
			var elOffset = $(obj).offset().top;
			var elHeight = $(obj).height();
			var windowHeight = $(window).height();
			var offset;

			var cenas = (windowHeight / 2);
			var cenas2 = (elHeight / 2);

			if (elHeight < windowHeight) {
				offset = elOffset - (cenas - cenas2);
			}

			$window.stop(true, true).animate({
				scrollTop: offset
			}, 1500, 'easeInOutExpo', function () { });
		} else if (stickys == 1) {
			$window.stop(true, true).animate({
				scrollTop: $(obj).offset().top - getAltMenu()
			}, 1500, 'easeInOutExpo', function () { });
		} else {
			$window.stop(true, true).animate({
				scrollTop: $(obj).offset().top
			}, 1500, 'easeInOutExpo', function () { });
		}
	}
}
function setMenuHeight(element, max_height, on_load) {
	'use strict';
	if (element.length > 0) {
		var $windowH = $(window).height(),
			headerHeight = $('#header').outerHeight(true);

		var actualHeight = element.outerHeight();

		var newHeight = $windowH - headerHeight - 20;
		if (newHeight > max_height) newHeight = max_height;

		if (actualHeight > 0 && actualHeight >= newHeight) {
			element.css('height', newHeight + "px");
		} else {
			element.css('height', "auto");
		}

		if (on_load == 1) {
			element.css('max-height', newHeight + "px");
		}
	}
}
function breakHalf() {
	if ($('.half_string').length > 0) {
		var count = 0;
		$('.half_string:not(.breaked)').each(function () {
			count++;
			var s = $(this).text();
			if (s.indexOf(" ") !== -1) {
				var p = s.slice(s.length / 2).split(" ").slice(1).join(" ").length;

				if (p == 0) {
					p = s.slice(s.length / 3).split(" ").slice(1).join(" ").length;
				}

				var c = s.slice(0, s.length - p) + "<br>" + s.slice(s.length - p);

				$(this).html(c).addClass('breaked');
			}
		});
	}
}
function checkOverflow(element) {
	'use strict';
	if (element.offsetHeight < element.scrollHeight || element.offsetWidth < element.scrollWidth) {
		return true;
	} else {
		return false;
	}
}
function maskInputs() {
	//MASK
	$('.mask-date').attr('placeholder', "00-00-0000");
	$('.mask-postal').attr('placeholder', "0000-000");

}
function sortableTable(element, options, no_results) {
	if ($('#' + element).length > 0) {
		var table = new List(element, options);
		var tableParent = $('.' + element + '-container');

		if (tableParent.length > 0) {
			// if($(tableParent).find('.custom_table_search').length>0){
			// 		$(tableParent).find('.custom_table_search').on('blur keydown', function(){
			// 				var value = $(this).val();
			// 				console.log(value);
			// 				table.search(value);
			// 				console.log(table.search(value));
			// 		}); 
			// }
			$(".custom_table_search").keyup(function () {
				table.search($(this).val(), ['numero']);
			})
				.keyup();


			if ($(tableParent).find('.custom_table_filters').length > 0) {
				$(tableParent).find('.custom_table_filters').on('change', function () {
					table.filter(function (item) {
						var groupsStr = "";
						var groupsFilter = [];

						var $groups = $('.filter_container .groups');
						$groups.each(function (index, element) {
							var groupsInput = [];
							var type = $(element).attr('data-type');

							if (!$(this).hasClass('ranged')) { /*PARA VÀRIOS*/
								if ($('.custom_table_filters:checked', element).length > 0) {
									var $inputs = $('.custom_table_filters', element);

									$inputs.each(function () {
										var $this = $(this);

										if ($this.is(':checked') == true) {
											var value = $this.val();
											groupsInput.push(value);
										}
									});

									groupsStr += groupsInput;
									groupsFilter.push(_(groupsInput).contains(eval('item.values().' + type)));

								} else {
									groupsStr += groupsInput;
									groupsFilter.push(true);
								}
							} else { /*PARA INTERVALOS*/ //explode por / verifica um a um se é maior ou nao...
								var $inicio = $('[data-range-start]', element).val();
								var $fim = $('[data-range-end]', element).val();
								var $elEval = moment.unix(eval('item.values().' + type)).utc().format("DD/MM/YYYY");
								$elEval = moment($elEval, "DD/MM/YYYY");

								if ($inicio) {
									$inicio = moment($inicio, "DD/MM/YYYY");
								}
								if ($fim) {
									$fim = moment($fim, "DD/MM/YYYY");
								}


								if ($inicio || $fim) {
									if ($inicio && !$fim) {
										groupsStr += $inicio;
										groupsFilter.push($elEval >= $inicio);
									} else if (!$inicio && $fim) {
										groupsStr += $fim;
										groupsFilter.push($elEval <= $fim);
									} else {
										groupsStr += $inicio + " " + $fim;
										groupsFilter.push(($elEval >= $inicio) && ($elEval <= $fim));
									}
								} else {
									groupsFilter.push(true);
								}
							}
						});


						if ($groups.length == 1) {
							return groupsFilter[0];
						} else if ($groups.length == 2) {
							return groupsFilter[0] && groupsFilter[1];
						} else if ($groups.length == 3) {
							return groupsFilter[0] && groupsFilter[1] && groupsFilter[2];
						} else if ($groups.length == 4) {
							return groupsFilter[0] && groupsFilter[1] && groupsFilter[2] && groupsFilter[3];
						} else if ($groups.length == 5) {
							return groupsFilter[0] && groupsFilter[1] && groupsFilter[2] && groupsFilter[3] && groupsFilter[4];
						}
					});


					setTimeout(function () {
						table.update();
					}, 1000);
				});
			}
		}


		table.on('updated', function () {
			if (table.visibleItems.length > 0) {
				$(no_results).addClass('hidden');
			} else {
				$(no_results).removeClass('hidden');
			}
		});
	}

	$('#' + element + ' tr').on('click', function (e) {
		if (e.target === $('.pointerNull')[0] || $(e.target).parents('.pointerNull').length > 0) {
			e.preventDefault();
			e.stopPropagation();
		}
	});
}
function uploadFileProgress(input, form, folder, f) {
	$("progress", '#' + form).val(0);
	$("#" + input, '#' + form).removeClass('upl_error upl_completed');

	var file = $("#" + input, '#' + form)[0].files[0];
	// alert(file.name+" | "+file.size+" | "+file.type);
	var uplData = new FormData();
	uplData.append("op", "fileUpload");
	uplData.append("file", file);
	uplData.append("allowedSize", $("#" + input).attr('data-size'));
	uplData.append("folder", $("#" + input).attr('data-folder'));
	uplData.append("timestamp", $("#" + input).attr('data-timestamp'));
	uplData.append("token", $("#" + input).attr('data-token'));

	var ajax = new XMLHttpRequest();
	var caminho = _includes_path + $("#" + input).attr('data-file');

	ajax.upload.addEventListener("progress", function (event) {
		var percent = (event.loaded / event.total) * 100;

		if ($("progress", '#' + form).css('opacity') === 0) {
			$("progress", '#' + form).css('opacity', 1);
		}

		$("progress", '#' + form).val(Math.round(percent));
		//console.log(Math.round(percent) + "% uploaded... please wait");
	}, false);

	ajax.addEventListener("load", function (event) {
		console.log(ajax.responseText);
		//$("progress", '#'+form).val(0); //wil clear progress bar after successful upload

		if (ajax.responseText.indexOf("Error!") >= 0) {
			$("#" + input, '#' + form).addClass('upl_error');
			ntg_alert(ajax.responseText);
		} else {
			$("#" + input, '#' + form).addClass('upl_completed');

			setTimeout(function () {
				window[f](ajax.response, $("#" + input, '#' + form));
			}, 500);
		}
	}, false);

	ajax.addEventListener("error", function () {
		console.log("Upload Failed");
		$("#" + input, '#' + form).removeClass('upl_completed');
	}, false);

	ajax.addEventListener("abort", function () {
		console.log("Upload Aborted");
		$("#" + input, '#' + form).removeClass('upl_completed');
	}, false);

	ajax.open("POST", caminho);
	ajax.send(uplData);
}
function readFile(file, element) {
	if (window.File && window.FileReader && window.FormData) {
		if (file) {
			if (/^image\//i.test(file.type)) {
				var reader = new FileReader();
				var image;

				reader.onloadend = function () {
					var dataURL = reader.result;
					var fileType = file.type;

					var image = new Image();
					image.src = dataURL;

					$(element).append(image);
					if ($(element).parents('[steps-field]').length > 0) {
						$.doTimeout(500, function () {
							window.dispatchEvent(new Event('resize'));
						});
					}
				}

				reader.onerror = function () {
					console.log('There was an error reading the file!');
				}
			} else {
				console.log('Not a valid image!');
			}
		}
	}
}


/***************** GENERAL *****************/
function clearText(field) {
	if (field.defaultValue == field.value) field.value = '';
	else if (field.value == '') field.value = field.defaultValue;
}
function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
	return pattern.test(emailAddress);
}

function onlyNumber(obj) {
	var valor, val;

	var liberado = new Array('');
	var liberadoE = new Array(188, 190, 8);

	valor = obj.value;
	if (document.all) {
		if (!((e.keyCode > 47 && e.keyCode < 58) || (e.keyCode > 95 && e.keyCode < 106) || Array.find(liberadoE, e.keyCode) != '-1')) {
			obj.value = valor.substr(0, valor.length - 1);
		}
	} else {
		val = '';

		for (var x = 0; x < valor.length; x++) {
			if (!isNaN(valor[x]) || Array.find(liberado, valor[x]) != '-1') {
				val += valor[x];
			}
		}
		obj.value = val;
	}
}
Array.find = function (ary, element) {
	for (var i = 0; i < ary.length; i++) {
		if (ary == element) {
			return i;
		}
	}
	return -1;
}
function temCaracteres(obj) {
	var er = /[a-z]{1}/gim,
		pl;

	if (obj != "") {
		er.lastIndex = 0;
		pl = obj;
		pl = pl.toUpperCase();

		if (er.test(pl)) {
			return 1;
		} else {
			return 0;
		}
	}
}
function onlyDecimal(obj) {
	var inptVal = obj.value;
	var n = parseFloat(inptVal);

	if (temCaracteres(inptVal) == 1) {
		obj.value = '';
	} else {
		if (isNaN(n)) obj.value = '';
	}
}
function nl2br(str, is_xhtml) {
	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}
function encode_utf8(s) {
	return unescape(encodeURIComponent(s));
}
function decode_utf8(s) {
	try {
		return decodeURIComponent(escape(s));
	} catch (ex) {
		return s;
	}
}
function verifica_nome(nome, is_url) {
	if (!is_url) is_url = 0;

	var strlogin = nome;

	var caracteres = ["----", "---", "--", "º", "ª", "\"", "“", "”", "“", "”", ",", ";", "/", "<", ">", ":", "?", "~", "^", "]", "}", "´", "`", "[", "{", "=", "+", "-", ")", "\\", "(", "*", "&", "¨", "%", "$", "#", "@", "!", "|", "à", "è", "ì", "ò", "ù", "â", "ê", "î", "ô", "û", "ä", "ë", "ï", "ö", "ü", "á", "é", "í", "ó", "ú", "ã", "õ", "À", "È", "Ì", "Ò", "Ù", "Â", "Ê", "Î", "Ô", "Û", "Ä", "Ë", "Ý", "Ö", "Ü", "Ý", "É", "Ý", "Ó", "Ú", "Ã", "Õ", "ç", "Ç", " ", "'", "™", "©", "®", "«", "»", "ñ", "Ñ", "Æ", "“", "”", ",", "‚", "€", "–", "§", "£", "…", "ø", "Ø", "Å", "å", "æ", "•"];

	if (is_url == 1) {
		caracteres.push(".");
	}

	var replacer = "";


	for (var i = 0; i < caracteres.length; i++) {
		var strCompare = decode_utf8(caracteres[i]);

		if (caracteres[i] == "á" || caracteres[i] == "à" || caracteres[i] == "Ý" || caracteres[i] == "À" || caracteres[i] == "ã" || caracteres[i] == "Ã" || caracteres[i] == "â" || caracteres[i] == "Â" || caracteres[i] == "å" || caracteres[i] == "Å") {
			replacer = "a";
		} else if (caracteres[i] == "ó" || caracteres[i] == "ò" || caracteres[i] == "Ó" || caracteres[i] == "Ò" || caracteres[i] == "õ" || caracteres[i] == "Õ" || caracteres[i] == "ô" || caracteres[i] == "Ô" || caracteres[i] == "ø" || caracteres[i] == "Ø") {
			replacer = "o";
		} else if (caracteres[i] == "é" || caracteres[i] == "É" || caracteres[i] == "è" || caracteres[i] == "È" || caracteres[i] == "ê" || caracteres[i] == "Ê") {
			replacer = "e";
		} else if (caracteres[i] == "ç" || caracteres[i] == "Ç") {
			replacer = "c";
		} else if (caracteres[i] == "í" || caracteres[i] == "Ý") {
			replacer = "i";
		} else if (caracteres[i] == "ú" || caracteres[i] == "Ú") {
			replacer = "u";
		} else if (caracteres[i] == "ñ" || caracteres[i] == "Ñ") {
			replacer = "n";
		} else if (caracteres[i] == "æ" || caracteres[i] == "Æ") {
			replacer = "ae";
		}

		if (strlogin.indexOf(strCompare) >= 0) {
			strlogin = strlogin.replace(strCompare, replacer);
		}
	}

	return strlogin;
}

function number_format(number, decimals, decPoint, thousandsSep) { // eslint-disable-line camelcase
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
	var n = !isFinite(+number) ? 0 : +number
	var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
	var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
	var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
	var s = ''
	var toFixedFix = function (n, prec) {
		var k = Math.pow(10, prec)
		return '' + (Math.round(n * k) / k)
			.toFixed(prec)
	}
	// @todo: for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || ''
		s[1] += new Array(prec - s[1].length + 1).join('0')
	}
	return s.join(dec)
}


/***************** PLUGINS *****************/
(function ($) { //$(window).scrolled(function() {	 Utilizar em vez de window on scroll, corre o codigo apenas quando o utilizar para o scroll
	'use strict';
	var uniqueCntr = 0;
	$.fn.scrolled = function (waitTime, fn) {
		if (typeof waitTime === "function") {
			fn = waitTime;
			waitTime = 200;
		}
		var tag = "scrollTimer" + uniqueCntr++;
		this.scroll(function () {
			var self = $(this);
			var timer = self.data(tag);
			if (timer) {
				clearTimeout(timer);
			}
			timer = setTimeout(function () {
				self.removeData(tag);
				fn.call(self[0]);
			}, waitTime);
			self.data(tag, timer);
		});
	};
})(jQuery);

(function ($) { //$(window).resized(function() {	 Utilizar em vez de window on resize, corre o codigo apenas quando o utilizar para o resize
	'use strict';
	var uniqueCntr = 0;
	$.fn.resized = function (waitTime, fn) {
		if (typeof waitTime === "function") {
			fn = waitTime;
			waitTime = 200;
		}
		var tag = "resizeTimer" + uniqueCntr++;
		this.resize(function () {
			var self = $(this);
			var timer = self.data(tag);
			if (timer) {
				clearTimeout(timer);
			}
			timer = setTimeout(function () {
				self.removeData(tag);
				fn.call(self[0]);
			}, waitTime);
			self.data(tag, timer);
		});
	};
})(jQuery);




/***************** WINDOW EVENTS *****************/
$(document).ready(function () {
	'use strict';

	breakHalf();
	initIframes();

	FastClick.attach(document.body);

	$('img').each(function (index, element) {
		if (!$(this).attr('alt')) {
			$(this).attr('alt', document.title);
		}
		if (!$(this).attr('title')) {
			$(this).attr('title', document.title);
		}
	});

	if (detectIE()) {
		$('html').addClass('is-ie');
	}

	if (detectIOS()) {
		$('html').addClass('ios');
	}
});
$(window).resized(function () {
	initEqualizer();
});