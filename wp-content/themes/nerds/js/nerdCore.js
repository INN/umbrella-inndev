jQuery(document).ready(function($) {

	//html5 placeholders
	$('input[placeholder], textarea[placeholder]').placeholder();

	// dim sidebar
	$(window).scroll(function(){
		if (($(this).scrollTop() > 300) && (document.documentElement.clientWidth >= 767)) {
			$('.showey-hidey').animate({opacity: '0.5'}, 100);
		} else if (($(this).scrollTop() < 300)) {
			$('.showey-hidey').animate({opacity: '1'}, 100);
		}
	});

	// make the sidebar visible if you hover over it
	if (document.documentElement.clientWidth >= 767) {
		$('.showey-hidey').hover(function() {
			$(this).animate({opacity: '1'}, 100);
			}, function() {
				if ($(window).scrollTop() < 300) {
					$(this).animate({opacity: '1'}, 100);
				} else {
				    $(this).animate({opacity: '0.5'}, 100);
				}
		});
	}

	//get the correct sized image for the header, replace it with a new one if the window is resized
	$('.header_img').attr('src', banner_img_src);
	$(window).resize(function() {
		$('.header_img').attr('src', whichHeader());
	});

	//homepage alert & hero CSS hacks
	if ( $('.max-wide').length ) {
		var $wrapper = $('.max-wide'), $container = $('#content');
		$(window).on('throttledresize', function() {
			var $marginWidth = ( $(window).width() - $container.width() ) / -2;
			$marginWidth = ( $marginWidth > 0 ) ? 0 : parseInt( $marginWidth ) ;
			$wrapper.css( {marginLeft: $marginWidth, marginRight: $marginWidth} );
		});
		$(window).trigger('throttledresize');
	}

	//enable "clean read" functionality
	$('a.clean-read').on('click', function() {
		$('body').addClass('clean-read').append('<a class="clean-read-close" href="#">Exit "Clean Read" mode</a>');
		$('.clean-read-container').append('<a class="clean-read-close" href="#">Exit "Clean Read" mode</a>');
		$('a.clean-read').hide();
		return false;
	});

	//close "clean read"
	$(document).on('click', '.clean-read-close', function() {
		$('body').removeClass('clean-read');
		$('a.clean-read').show();
		$('.clean-read-close').remove();
		return false;
	});

	//ESC triggers "clean read" close
	$(document).keyup(function(e) {
    if (e.keyCode == 27 && $('body').hasClass('clean-read')) $('.clean-read-close').trigger('click');
  });

  //GA event tracking for image-widget items
  $('a.image-click-track').on('click', function() {
	  if (typeof _gaq == 'object') _gaq.push(['_trackEvent', 'Click', 'Image Widget', this.getAttribute('title')]);
  });

  // Touch enable the drop-down menus
  (function() {
	  if ( Modernizr.touch ) {

	  	// iOS Safari works with touchstart, the rest work with click
			mobileEvent = /Mobile\/.+Safari/.test(navigator.userAgent) ? 'touchstart' : 'click';

			// Open the drop down
			var openMenu = false;

			// Handle the tap for the drop down
		  $('ul.nav').on( mobileEvent+'.largo', 'li.dropdown', function( event ) {
		  	var li = $(event.currentTarget);
		  	console.log(event.target, event.currentTarget);

		  	if ( !li.is('.open') ) {
		  		// The link when the menu is closed
		  		closeOpenMenu();
		  		li.addClass('open');
		  		openMenu = li;

		  		event.preventDefault();
		  		event.stopPropagation();

		  	} else if ( $(event.target).is('b.caret') ) {
		  		// The caret when the menu is open
		  		li.removeClass('open');
		  		openMenu = false;

		  		event.preventDefault();
		  		event.stopPropagation();
		  	}
		  });

		  // Call this to call the open menu
		  var closeOpenMenu = function() {
		  	if ( openMenu ) {
		  		openMenu.removeClass('open');
		  		openMenu = false;
		  	}
		  }

		  // Close the open menu when the user taps elsewhere
		  $('body').on( mobileEvent, closeOpenMenu);
		}
	})();

	// Sticky footer
	(function(){
		// Check if there is a sticky footer
		var stickyFooterEl = $( '.sticky-footer-holder' );
		if ( stickyFooterEl.length ) {
			// Show the sticky footer by default
			stickyFooterEl.addClass( 'show' );

			$('#site-footer').waypoint( function( direction ) {
				stickyFooterEl.toggleClass( 'show', direction == 'up' );
			}, { offset: '100%' } );

			$('.dismiss a').on( 'click', function() {
				stickyFooterEl.remove();	//so it never comes back
				return false;
			});
		}
	})();

	// Custom share buttons
	(function() {
		var sharer = {
			// Initialize the singleton object
			init: function() {
				this.buttons = $('.custom-share-button');

				if ( this.buttons.length == 0 ) {
					// Abort if no buttons
					return;
				}

				this.buttons.on( 'click', $.proxy( this, 'onClick' ) );
			},

			// Get the url, title, and description of the page
			getPageData: function() {
				if ( !this._data ) {
					// Cache the data after the first get
					var metaElements = $('meta');

					this._data = {};
					this._data.title = metaElements.filter('[property="og:title"]').attr('content') || document.title;
					this._data.url = metaElements.filter('[property="og:url"]').attr('content') || document.location;
					this._data.description = metaElements.filter('[property="og:description"]').attr('content') || metaElements.filter('[name="description"]').attr('content');
					// this._data.siteName = ; // Print the value as a Javascript var
				}

				return this._data;
			},

			// Event handler for the share buttons
			onClick: function( event ) {
				var button = $(event.target);
				var service = button.data('service');

				if ( this['do_'+service] ) {
					this['do_'+service]( this.getPageData() );
				}

				return false;
			},

			// Handle the Twitter service
			do_twitter: function( data ) {

				var url = 'https://twitter.com/intent/tweet?' + $.param({
					original_referer: document.title,
					text: data.title,
					url: data.url
				});

				this.popup({
					url: url,
					name: 'twitter_share'
				});
			},

			// Handle the Facebook service
			do_facebook: function( data ) {
				var url = 'https://www.facebook.com/sharer/sharer.php?' + $.param({
					u: data.url
				});

				this.popup({
					url: url,
					name: 'facebook_share'
				});
			},

			// Handle the email service
			do_email: function( data ) {
				var url = 'mailto:friend@example.com?' + $.param({
					subject: data.title,
					body: data.description + "\n" + data.url
				});

				this.popup({
					url: url,
					name: 'email_share'
				});
			},

			// Handle the Google+ service
			do_googleplus: function( data ) {
				var url = 'https://plus.google.com/share?' + $.param({
					url: data.url
				});

				this.popup({
					url: url,
					name: 'googleplus_share'
				});
			},

			// Handle the LinkedIn service
			do_linkedin: function( data ) {
				var url = 'http://www.linkedin.com/shareArticle?' + $.param({
					mini: 'true',
					url: data.url,
					title: data.title,
					summary: data.description
					// source: data.siteName
				});

				this.popup({
					url: url,
					name: 'linkedin_share'
				});
			},

			// Create and open a popup
			popup: function( data ) {
				if ( !data.url ) {
					return;
				}

				$.extend( data, {
					name: '_blank',
					height: 608,
					width: 845,
					menubar: 'no',
					status: 'no',
					toolbar: 'no',
					resizable: 'yes',
					left: Math.floor(screen.width/2 - 845/2),
					top: Math.floor(screen.height/2 - 608/2)
				});

				var specNames = 'height width menubar status toolbar resizable left top'.split( ' ' );
				var specs = [];
				for( var i=0; i<specNames.length; ++i ) {
					specs.push( specNames[i] + '=' + data[specNames[i]] );
				}
				return window.open( data.url, data.name, specs.join(',') );
			}
		};

		sharer.init();
	})();

	// Search slide out for mobile
	(function() {
		var searchForm = $('.sticky-nav-holder .form-search');
		var toggle = searchForm.find( '.toggle' );
    toggle.on( 'click', function() {
    	searchForm.toggleClass( 'show' );
    });

	})();

	// Responsive navigation
	$('.navbar .toggle-nav-bar').each( function() {
		var toggleButton = $(this);
		var navbar = toggleButton.closest('.navbar');

		// Support both touch and click events
		toggleButton.on( 'touchstart.toggleNav', function() {
			// If it is a touch event, get rid of the click events.
			toggleButton.off( 'click.toggleNav' );
			navbar.toggleClass( 'open' );

			// Close all the open sub navigation upon closing the menu
			if ( !navbar.hasClass( 'open' ) ) {
				navbar.find( '.nav-shelf li.open' ).removeClass( 'open' );
			}
		});

		toggleButton.on( 'click.toggleNav', function() {
			navbar.toggleClass( 'open' );
		});

		// Secondary nav
		navbar.on( 'touchstart.toggleNav click.toggleNav', '.nav-shelf .caret', function( event ) {
			// Only handle when
			if ( toggleButton.css( 'display') == 'none' ) {
				return;
			}

			if ( event.type == 'touchstart' ) {
				navbar.off( 'click.toggleNav', '.nav-shelf .caret' );
			}

			var li = $( event.target ).closest('li');

			// Close the others if we are opening
			if ( !li.hasClass('open') ) {
				navbar.find( '.nav-shelf li.open' ).removeClass( 'open' );
			}

			// Open ours
			li.toggleClass( 'open' );

			event.preventDefault();
		});
	} );

	// Everything below is from WCIJ
	var $body = $('body'),
		$sb = $('#sidebar'),
		$content = $('.entry-content'),
		ratio = window.devicePixelRatio || 1;

	// Things to do on single post pages
	if ( $('.single-post').length ) {

		//get the offset of the sidebar
		var sb_bottom = $sb.offset().top + $sb.outerHeight();

		// check big images and extend them
		$('.wp-caption.center > img, img.aligncenter').each(function() {
			if ( this.naturalWidth > ($content.width() * ratio) && $(this).offset().top > sb_bottom + 30 ) {
				$(this).removeAttr('width').removeAttr('height');
				if ( $(this).closest('.wp-caption', $content).length ) {
					$(this).closest('.wp-caption').addClass('extra-wide').css('max-width','none');
				} else {
					$(this).addClass('extra-wide').css('max-width', '130%');
				}
			}
		});

		// check anything hanging left and move it back
		$('.left, .alignleft, .align-left, .align-center.full.type-embed', $('.entry-content')).each( function() {
			if ( $(this).offset().top < sb_bottom + 30 ) {	// 30px buffer just to keep things looking clean
				$(this).addClass('no-bleed');
			} else {
				return false; //once we're passed the boundary, we can stop checking
			}
		});

	}

});
