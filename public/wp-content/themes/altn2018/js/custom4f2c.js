// JavaScript Document

jQuery(document).ready(function(){

	copyButton();
	corporateSite();
	emailContactConsent();
	emailFormValidate();
	faqs();
	fixedHeight();	
	parallaxAnimate();
	responsive();
	responsiveVideo();
	supportMenu();	
	zTricks();
	
	if( jQuery('body.home').length )
	{
		if(parseInt( getCookie('closefixed') ) !== 1)
		{
			jQuery('#fixed-information').fadeIn();
		}
	}
});

jQuery(document).on('pageinit', function(){ 

	emailContactConsent();

});

function changeArrow(id)
{
	if(jQuery(id).hasClass('close'))
	{
		jQuery(id).removeClass('close');
	}
	else
	{
		jQuery(id).addClass('close');
	}
}

function closeOnHoverFranchise()
{
	jQuery('#franchise-info .thefranchise').fadeOut();
	
	jQuery('#franchise-info .hover').click(function(){
	
		jQuery('#franchise-info .thefranchise').fadeIn();
		jQuery('.ppc-content .preferred .items .location .extra').addClass('hide');
		
	});
}

function copyButton()
{
	if( jQuery( '#page .test-categories .allbutton' ).length )
	{
		var allbutton = jQuery( '#page .test-categories .allbutton' ).clone();
		
		jQuery( '#page .banner .box' ).append( allbutton );
		
		if( jQuery( '#page .search-cov .info' ).length )
		{
			var href = jQuery( '#page .search-cov .info a' ).attr( 'href' );
			
			jQuery( '#page .banner .allbutton a' ).attr( 'href', href );
		}
	}
}

function corporateSite()
{
	if(jQuery('#second-nav.franchise .corporate-site').length)
	{
		jQuery('#second-nav.franchise .corporate-site').click(function(e){
			
			e.preventDefault();
			
			document.cookie = 'cookie_blog_id' + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
			
			jQuery.get(php_vars.ajax_url, {'action' : 'destroy_cookie'})
				.done(function( data ) {
				
					location.href = data;
				
			});
		
		});
	}	
	
	if(jQuery('#responsive-menu .corporate-site').length)
	{
		jQuery('#responsive-menu .corporate-site').click(function(e){
			
			e.preventDefault();
			
			document.cookie = 'cookie_blog_id' + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
			
			jQuery.get(php_vars.ajax_url, {'action' : 'destroy_cookie'})
				.done(function( data ) {
				
					location.href = data;
				
			});
		
		});
	}
	
	if( parseInt(php_vars.blogid) > 1 )
	{
		jQuery('span.corporate').hide();
		jQuery('.link-model-orange#request-employer').each(function(){
		
			var parameter = getAllUrlParams(jQuery(this).attr('href')).employer; 
			jQuery(this).attr('href', php_vars.franchise_url +'/request-an-employer-quote/?e='+ parameter);
		});
	}
}

function createCookie( name, value, days ) 
{
	var expires = "";
	if (days) 
	{
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		expires = "; expires=" + date.toUTCString();
	}
	document.cookie = name + "=" + value + expires + "; path=/";
}

function emailContactConsent()
{
	if( jQuery( '#sign-up-email' ).length )
	{
		jQuery( '#sign-up-email' ).validate({
			   
			rules: {
				email: {
					required: 	true,
					email: 		true,
				},
			},			
			messages: {
			},
			submitHandler: function(form) {
				
				jQuery( 'label.error' ).remove();
				jQuery( 'div.goal' ).remove();				
				
        		grecaptcha.ready(function() {
          			grecaptcha.execute('6LeKWd4hAAAAAFCwJC-dxpps-W3JhqtJiC0nw6Cj', {action: 'submit'}).then(function(token) {
             
			 			jQuery( '#sign-up-email' ).after( '<img src="'+ php_vars.template_url +'/img/loading-gif-orange-1.gif" width="40" id="load-sign-up" />' );
				
						jQuery.post( php_vars.ajax_url, jQuery( '#sign-up-email' ).serialize() )
							.done(function( data ) {					
								
								if( 'OK' == data )
								{
									jQuery( '#sign-up-email' ).trigger( "reset" );
									
									jQuery( '#load-sign-up' ).remove();
									
									jQuery( '#sign-up-email' ).after( '<div class="goal">The email was registered.</div>' );
									
									jQuery( 'div.goal' ).hide( 15000 );							
									
								}
								else
								{
									emailContactConsentError();
								}
								
							}).fail(function() {
								
								emailContactConsentError();					
						});
			 
				  	});
				});				
			}
		});
	}
	
	if( jQuery( '#sign-up-email-mobile' ).length )
	{
		jQuery( '#sign-up-email-mobile' ).validate({
			   
			rules: {
				email: {
					required: 	true,
					email: 		true,
				},
			},			
			messages: {
			},
			submitHandler: function(form) {
				
				jQuery( 'label.error' ).remove();
				jQuery( 'div.goal' ).remove();				
				
        		grecaptcha.ready(function() {
          			grecaptcha.execute('6LeKWd4hAAAAAFCwJC-dxpps-W3JhqtJiC0nw6Cj', {action: 'submit'}).then(function(token) {
             
			 			jQuery( '#sign-up-email-mobile' ).after( '<img src="'+ php_vars.template_url +'/img/loading-gif-orange-1.gif" width="40" id="load-sign-up-mobile" />' );
				
						jQuery.post( php_vars.ajax_url, jQuery( '#sign-up-email-mobile' ).serialize() )
							.done(function( data ) {					
								
								if( 'OK' == data )
								{
									jQuery( '#sign-up-email-mobile' ).trigger( "reset" );
									
									jQuery( '#load-sign-up-mobile' ).remove();
									
									jQuery( '#sign-up-email-mobile' ).after( '<div class="goal">The email was registered.</div>' );
									
									jQuery( 'div.goal' ).hide( 15000 );							
									
								}
								else
								{
									emailContactConsentErrorMobile();
								}
								
							}).fail(function() {
								
								emailContactConsentErrorMobile();					
						});
			 
				  	});
				});				
			}
		});
	}
}

function emailContactConsentError()
{
	jQuery( '#sign-up-email' ).after( '<label id="email-error" class="error" for="email">We are having technical difficulties and are actively working on a fix. Please try again in a few minutes.</label>' );
	
	jQuery( '#load-sign-up' ).remove();
}

function emailContactConsentErrorMobile()
{
	jQuery( '#sign-up-email-mobile' ).after( '<label id="email-error" class="error" for="email">We are having technical difficulties and are actively working on a fix. Please try again in a few minutes.</label>' );
	
	jQuery( '#load-sign-up-mobile' ).remove();
}

function emailFormOpen( num )
{
	if( 0 == num )
	{	
		if( jQuery( '#email-form-footer' ).hasClass( 'open' ) )
		{
			jQuery( '#email-form-footer' ).removeClass( 'open' );
			jQuery( '#email-icon-footer' ).removeClass( 'open' );
		}
		else
		{
			jQuery( '#email-form-footer' ).addClass( 'open' );
			jQuery( '#email-icon-footer' ).addClass( 'open' );
		}
	}
	else
	{
		if( jQuery( '#email-form-footer-mob' ).hasClass( 'open' ) )
		{
			jQuery( '#email-form-footer-mob' ).removeClass( 'open' );
			jQuery( '#email-icon-footer-mob' ).removeClass( 'open' );
		}
		else
		{
			jQuery( '#email-form-footer-mob' ).addClass( 'open' );
			jQuery( '#email-icon-footer-mob' ).addClass( 'open' );
		}
	}
}

function emailFormValidate()
{
	if( jQuery( '#email-form-send' ).length )
	{
		jQuery( '#email-form-send' ).validate({
			   
			rules: {
				name: 		"required",	
				email: {
					required: 	true,
					email: 		true,
				},
				message: 	"required",
				subject: 	"required"
			},			
			messages: {
			},
			submitHandler: function(form) {
				
				jQuery( 'label.error' ).remove();
				
				jQuery( '#email-form-button' ).after( '<img src="'+ php_vars.template_url +'/img/loading-gif-orange-1.gif" width="40" id="image-load-plus" />' );
				
				jQuery.post( php_vars.ajax_url, jQuery( '#email-form-send' ).serialize() )
					.done(function( data ) {					
						
						if( 'OK' == data )
						{
							jQuery( '#email-form-send' ).trigger( "reset" );
							
							jQuery( '#image-load-plus' ).remove();
							
							jQuery( '#email-form-button' ).after( '<label id="email-ok" class="goal">The email was sent.</label>' );
						}
						else
						{
							emailFormValidateError();
						}
						
					}).fail(function() {
						
						emailFormValidateError();					
				});
				
				
			}
		});
	}
	
	if( jQuery( '#email-form-send-mob' ).length )
	{
	
		jQuery( '#email-form-send-mob' ).validate({
			   
			rules: {
				name: 		"required",	
				email: {
					required: 	true,
					email: 		true,
				},
				message: 	"required",
				subject: 	"required"
			},			
			messages: {
			},
			submitHandler: function(form) {
				
				jQuery( 'label.error' ).remove();
				jQuery( 'label.goal' ).remove();
				
				jQuery( '#email-form-button-mob' ).after( '<img src="'+ php_vars.template_url +'/img/loading-gif-orange-1.gif" width="40" id="image-load-plus-mob" />' );
				
				jQuery.post( php_vars.ajax_url, jQuery( '#email-form-send-mob' ).serialize() )
					.done(function( data ) {					
						
						if( 'OK' == data )
						{
							jQuery( '#email-form-send-mob' ).trigger( "reset" );
							
							jQuery( '#image-load-plus-mob' ).remove();
							
							jQuery( '#email-form-button-mob' ).after( '<label id="email-ok" class="goal">The email was sent.</label>' );
						}
						else
						{
							emailFormValidateError();
						}
						
					}).fail(function() {
						
						emailFormValidateError();					
				});
				
				
			}
		});
	}
}

function emailFormValidateError()
{
	jQuery( '#email-form-button' ).after( '<label id="email-error" class="error" for="email">We are having technical difficulties and are actively working on a fix. Please try again in a few minutes.</label>' );
	
	jQuery( '#image-load-plus' ).remove();
}

function faqs()
{
	if(jQuery('.accordion').length)
	{
		jQuery('.accordion').accordion({
			active: false,
			collapsible: true	
		});
	}
}

function fixedClose()
{	
	jQuery('#fixed-information').hide();
	
	if( parseInt( getCookie( 'closefixed' ) ) !== 1 )
	{
		setCookie('closefixed', 1, 1);
	}
}

function fixedHeight()
{
	if(jQuery('#home .column-middle').length)
	{
		var maxHeight = 0;
		
		jQuery('#home .column-middle .middle').each(function(){
			
			var height = jQuery(this).find('.thecontent').height();
			
			if(height > maxHeight)
			{
				maxHeight = height;
			}
			
		});
		
		jQuery('#home .column-middle .middle').each(function(){
			
			jQuery(this).find('.thecontent').height(maxHeight);
			
		});
	}
}

function getAllUrlParams(url) 
{
  // get query string from url (optional) or window
  var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

  // we'll store the parameters here
  var obj = {};

  // if query string exists
  if (queryString) {

    // stuff after # is not part of query string, so get rid of it
    queryString = queryString.split('#')[0];

    // split our query string into its component parts
    var arr = queryString.split('&');

    for (var i = 0; i < arr.length; i++) {
      // separate the keys and the values
      var a = arr[i].split('=');

      // set parameter name and value (use 'true' if empty)
      var paramName = a[0];
      var paramValue = typeof (a[1]) === 'undefined' ? true : a[1];

      // (optional) keep case consistent
      paramName = paramName.toLowerCase();
      if (typeof paramValue === 'string') paramValue = paramValue.toLowerCase();

      // if the paramName ends with square brackets, e.g. colors[] or colors[2]
      if (paramName.match(/\[(\d+)?\]$/)) {

        // create key if it doesn't exist
        var key = paramName.replace(/\[(\d+)?\]/, '');
        if (!obj[key]) obj[key] = [];

        // if it's an indexed array e.g. colors[2]
        if (paramName.match(/\[\d+\]$/)) {
          // get the index value and add the entry at the appropriate position
          var index = /\[(\d+)\]/.exec(paramName)[1];
          obj[key][index] = paramValue;
        } else {
          // otherwise add the value to the end of the array
          obj[key].push(paramValue);
        }
      } else {
        // we're dealing with a string
        if (!obj[paramName]) {
          // if it doesn't exist, create property
          obj[paramName] = paramValue;
        } else if (obj[paramName] && typeof obj[paramName] === 'string'){
          // if property does exist and it's a string, convert it to an array
          obj[paramName] = [obj[paramName]];
          obj[paramName].push(paramValue);
        } else {
          // otherwise add the property
          obj[paramName].push(paramValue);
        }
      }
    }
  }

  return obj;
}

function goToAppoinment(evt, sku, url)
{
	evt.preventDefault();
	
	location.href = url + '?sku='+ sku +'#thesteps-body-1';
}

function openUniqueDetails( unique, id )
{
	var tempoption =  jQuery('#location-'+ unique +'-'+ id).hasClass('hide');
	
	if( tempoption )
	{
		jQuery('#location-'+ unique +'-'+ id).removeClass('hide');
		jQuery('#franchise-'+ id +' .thephone').addClass('hide');
		jQuery('#franchise-'+ id +' .info.more').addClass('open');
	}
	else
	{
		jQuery('#location-'+ unique +'-'+ id).addClass('hide');
		jQuery('#franchise-'+ id +' .thephone').removeClass('hide');
		jQuery('#franchise-'+ id +' .info.more').removeClass('open');
	}
}

function openUniqueLocation( unique )
{
	if( jQuery('#selector-'+ unique).hasClass('open') )
	{
		jQuery('#selector-'+ unique).removeClass('open');
		jQuery('#items-'+ unique).addClass('hide');
		jQuery('#items-'+ unique +' .extra').addClass('hide');
		jQuery('#items-'+ unique +' .info.more').removeClass('open');
		jQuery('#items-'+ unique +' .thephone').removeClass('hide');
	}
	else
	{
		jQuery('#selector-'+ unique).addClass('open');
		jQuery('#items-'+ unique).removeClass('hide');
	}
}

function parallaxAnimate()
{
	jQuery('.parallax-window').parallax();
	
	jQuery('.parallax-mirror .parallax-slider').prop('alt', '');
}

function responsive()
{
	if(jQuery('.footer-information.responsive').length)
	{		
		jQuery('.footer-information.responsive .support li.title a').click(function(e){
		
			e.preventDefault();
			
			jQuery('.footer-information.responsive .support li').each(function(){
				
				if(jQuery(this).hasClass('show'))
				{
					jQuery(this).removeClass('show');
					
				}
				else
				{
					jQuery(this).addClass('show');
				}			
			});
			
		});
		
		jQuery('.footer-information.responsive .company li.title a').click(function(e){
		
			e.preventDefault();
			
			jQuery('.footer-information.responsive .company li').each(function(){
				
				if(jQuery(this).hasClass('show'))
				{
					jQuery(this).removeClass('show');
					
				}
				else
				{
					jQuery(this).addClass('show');
				}			
			});
			
		});
	}
		
	var widthwindow = parseInt(jQuery( window ).width());
	if(widthwindow < 768)
	{
		if(jQuery('#location_page').length)
		{
			jQuery('#location_page #map iframe').attr('height', '300')
			
			var map = jQuery('#location_page #map').clone();
			jQuery('#location_page #map').remove();
			
			jQuery('#location_page #form-location').after(map);
			
		}
		
		if(jQuery('#responsive-menu').length)
		{
			jQuery('#responsive-menu li').each(function(){
			
				var aname = jQuery(this).find('a').text();
			
				if(aname == 'Franchisee Oportunities' || aname == 'ALTN es español' || aname == 'ALTN en español')
				{
					jQuery(this).addClass('lighter');
				}
				
				if(aname == 'ALTN es español')
				{
					jQuery(this).find('a').text('ALTN en español');
				}
				
			});
		}
	}
}

function responsiveVideo()
{
	if( jQuery('.videos iframe').length )
	{
		var parent = jQuery('.videos iframe').parent();
		parent.addClass('iframe');
	}
}

function setFranchise(id)
{
	jQuery.get(php_vars.ajax_url, {'action' : 'set_cookie', 'id' : id})
		.done(function( data ) {
		
			location.reload();
	});
}

function showhideID(id)
{
	if(jQuery(id).hasClass('hide'))
	{
		jQuery(id).show(200);
		jQuery(id).removeClass('hide');
	}
	else
	{
		jQuery(id).hide(200);
		jQuery(id).addClass('hide');
	}
}

function showMoreNews(theid, total)
{
	var numdisplay = jQuery('#news'+ theid +' a').length;
	
	jQuery.post(php_vars.ajax_url, { action : 'get_news_blogs', 'numdisplay' : numdisplay })
			.done(function( data ) {
				
				jQuery('#news'+ theid).append(data);
				
			});
	
	numdisplay = numdisplay + 6;
	
	if(total <= numdisplay)
	{
		jQuery('#show'+ theid).hide();
	}
}

function showMorePost(theid, total, part, id)
{
	var numdisplay = jQuery('#posts'+ theid +' a').length;
	
	jQuery.post(php_vars.ajax_url, { action : 'get_post_blogs', 'numdisplay' : numdisplay, 'part' : part, 'id' : id })
			.done(function( data ) {
				
				jQuery('#posts'+ theid).append(data);
				
			});
	
	numdisplay = numdisplay + part;
	
	if(total <= numdisplay)
	{
		jQuery('#show'+ theid).hide();
	}
}

function stringtonumber(text)
{
	var tmp = text.split("");
 	var map = tmp.map(function(current){
    	if (!isNaN(parseInt(current))) 
		{
      		return current;
    	}
  	});

  	var numbers = map.filter(function(value) {
    	return value != undefined;
  	});

  	return numbers.join("");
}

function supportMenu()
{	
	if(php_vars.contacturl != '')
	{
		if(jQuery('#menu-support .franchise').length)
		{
			jQuery('#menu-support .franchise a').attr('href', php_vars.contacturl);
		}
		
		if(jQuery('.footer-information.responsive .support .franchise').length)
		{
			jQuery('.footer-information.responsive .support .franchise a').attr('href', php_vars.contacturl);
		}
	}	
	
	if(php_vars.principal_url != php_vars.franchise_url)
	{
		if(jQuery('#responsive-menu').length)
		{
			jQuery('#responsive-menu li').each(function(){
			
				if(jQuery(this).hasClass('the-franchise'))
				{
					var linko = jQuery(this).find('a').attr('href');
					
					var newlinko = linko.replace(php_vars.principal_url, php_vars.franchise_url);
					
					jQuery(this).find('a').attr('href', newlinko);
				}
				
			});
		}
	}
	
	if(jQuery('#menu-main-menu').length)
	{
		jQuery('#menu-main-menu a').each(function(){
		
			var href = jQuery(this).attr('href');
			
			if(href.indexOf('frequently-asked-questions') != -1)
			{
				jQuery(this).text('FAQs');
				jQuery(this).attr('title', 'FAQs');
			}			
			
		});
	}
}

function testInformation(id)
{
	if(jQuery('#content-test-'+ id).hasClass('hide'))
	{
		jQuery('#button-information-'+ id).addClass('down');
		jQuery('#content-test-'+ id).removeClass('hide');
	}
	else
	{
		jQuery('#button-information-'+ id).removeClass('down');
		jQuery('#content-test-'+ id).addClass('hide');
	}
}

function zTricks()
{
	if(jQuery('.page-id-2862 .banner-short h2 span').length)
	{
		jQuery('.page-id-2862 .banner-short h2 span').html(php_vars.phone);
	}
	
	jQuery('h1').each(function(){	
		var temp = jQuery(this).html();				
		temp = temp.replace('IgG', '<span>IgG</span>');	
		temp = temp.replace('IgM', '<span>IgM</span>');		
		temp = temp.replace('IgA', '<span>IgA</span>');	
		jQuery(this).html(temp);		
	});
	
	if( jQuery( '.gform_body select .gf_placeholder' ).length )
	{
		jQuery( '.gform_body select .gf_placeholder' ).attr( 'disabled', true );
	}
}

/** COOKIES **/

function deleteCookie(cname)
{ 
	document.cookie = cname +"=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}

function getCookie(cname) 
{
  	var name = cname + "=";
  	var ca = document.cookie.split(';');
  	for(var i = 0; i < ca.length; i++) {
    	var c = ca[i];
    	while (c.charAt(0) == ' ') {
      		c = c.substring(1);
    	}
    	if (c.indexOf(name) == 0) {
      	return c.substring(name.length, c.length);
    	}
  	}
  	return "";
}

function setCookie(cname, cvalue, exdays) 
{
  	var d = new Date();
  	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  	var expires = "expires="+d.toUTCString();
  	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
