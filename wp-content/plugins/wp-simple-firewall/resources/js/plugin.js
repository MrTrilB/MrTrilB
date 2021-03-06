var iCWP_WPSF_OptionsPages = new function () {

	var showWaiting = function ( event ) {
		iCWP_WPSF_BodyOverlay.show();
	};

	this.initialise = function () {
		jQuery( document ).ready( function () {
			jQuery( document ).on( 'click', 'a.nav-link.module', showWaiting );

			/** Track active tab */
			jQuery( document ).on( 'click', '#ModuleOptionsNav a.nav-link', function ( e ) {
				e.preventDefault();
				jQuery( this ).tab( 'show' );
				jQuery( 'html,body' ).scrollTop( 0 );
			} );
			jQuery( document ).on( 'shown.bs.tab', '#ModuleOptionsNav a.nav-link', function ( e ) {
				window.location.hash = jQuery( e.target ).attr( "href" ).substr( 1 );
			} );
		} );
	};
}();

let iCWP_WPSF_Modals = new function () {
	let workingData = {};

	let renderModalIpAnalysis = function ( ip ) {
		iCWP_WPSF_BodyOverlay.show();
		let reqData = workingData.modal_ip_analysis.ajax.render_ip_analysis;
		reqData.ip = ip;
		jQuery.ajax(
			{
				type: "POST",
				url: ajaxurl,
				data: reqData,
				dataType: "json",
				success: function ( raw ) {
					iCWP_WPSF_Modals.display( raw.data );
				},
			}
		).fail( function () {
		} ).always( function () {
			iCWP_WPSF_BodyOverlay.hide();
		} );
	};

	this.display = function ( params ) {
		let modal = document.getElementById( 'ShieldGeneralPurposeDialog' );
		jQuery( '.modal-dialog', modal ).addClass( 'modal-xl' );
		jQuery( '.modal-title', modal ).html( params.title );
		jQuery( '.modal-body .col', modal ).html( params.body );
		(new bootstrap.Modal( modal )).show();
	};

	this.setData = function ( key, data ) {
		workingData[ key ] = data;
	};

	this.initialise = function () {
		jQuery( document ).on( 'click', '.modal_ip_analysis', function ( evt ) {
			evt.preventDefault();
			renderModalIpAnalysis( jQuery( evt.currentTarget ).data( 'ip' ) );
			return false;
		} );
	};
}();

var iCWP_WPSF_Toaster = new function () {

	let toasterContainer;

	this.showMessage = function ( msg, success ) {
		let $toaster = jQuery( toasterContainer )
		let $toastBody = jQuery( '.toast-body', $toaster );
		$toastBody.html( '' );

		jQuery( '<span></span>' ).html( msg )
								 .addClass( success ? 'text-dark' : 'text-danger' )
								 .appendTo( $toastBody );

		$toaster.css( 'z-index', 100000000 );
		$toaster.on( 'hidden.bs.toast', function () {
			$toaster.css( 'z-index', -10 )
		} );
		bootstrap.Toast.getInstance( toasterContainer ).show();
	};

	this.initialise = function () {
		jQuery( document ).ready( function () {
			toasterContainer = document.getElementById( 'icwpWpsfOptionsToast' );
			new bootstrap.Toast( toasterContainer, {
				autohide: true,
				delay: 3000
			} );
		} );
	};
}();
iCWP_WPSF_Toaster.initialise();

var iCWP_WPSF_OptionsFormSubmit = new function () {

	let workingData;
	let requestRunning = false;

	this.submit = function ( msg, success ) {
		let theDiv = createDynDiv( success ? 'success' : 'failed' );
		theDiv.fadeIn().html( msg );
		setTimeout( function () {
			theDiv.fadeOut( 5000 );
			theDiv.remove();
		}, 4000 );
	};

	/**
	 * First try with base64 and failover to lz-string upon abject failure.
	 * This works around mod_security rules that even unpack b64 encoded params and look
	 * for patterns within them.
	 */
	var sendForm = function ( $form, useCompression = false ) {

		let formData = $form.serialize();
		if ( useCompression ) {
			formData = LZString.compress( formData );
		}

		/** Required since using dynamic AJAX loaded page content **/
		if ( !$form.data( 'mod_slug' ) ) {
			alert( 'Missing form data' );
			return false;
		}

		let reqs = jQuery.extend(
			workingData.ajax.mod_options_save,
			{
				'form_params': Base64.encode( formData ),
				'enc_params': useCompression ? 'lz-string' : 'b64',
				'apto_wrap_response': 1
			}
		);

		jQuery.ajax(
			{
				type: "POST",
				url: ajaxurl,
				data: reqs,
				dataType: "text",
				success: function ( raw ) {
					handleResponse( raw );
				},
			}
		).fail( function () {
			if ( useCompression ) {
				handleResponse( raw );
			}
			else {
				iCWP_WPSF_Toaster.showMessage( 'The request was blocked. Retrying an alternative...', false );
				sendForm( $form, true );
			}

		} ).always( function () {
			requestRunning = false;
		} );
	};

	let handleResponse = function ( raw ) {
		let response = iCWP_WPSF_ParseAjaxResponse.parseIt( raw );
		let msg;
		if ( response === null || typeof response.data === 'undefined'
			|| typeof response.data.message === 'undefined' ) {
			msg = response.success ? 'Success' : 'Failure';
		}
		else {
			msg = response.data.message;
		}
		iCWP_WPSF_Toaster.showMessage( msg, response.success );

		setTimeout( function () {
			// window.location.replace( response.data.redirect_to );
			window.location.reload();
		}, 1000 );
	};

	let submitOptionsForm = function ( event ) {
		iCWP_WPSF_BodyOverlay.show();

		if ( requestRunning ) {
			return false;
		}
		requestRunning = true;
		event.preventDefault();

		let $form = jQuery( this );

		var $passwordsReady = true;
		jQuery( 'input[type=password]', $form ).each( function () {
			let $pass = jQuery( this );
			let $confirm = jQuery( '#' + $pass.attr( 'id' ) + '_confirm', $form );
			if ( typeof $confirm.attr( 'id' ) !== 'undefined' ) {
				if ( $pass.val() && !$confirm.val() ) {
					$confirm.addClass( 'is-invalid' );
					alert( 'Form not submitted due to error: password confirmation field not provided.' );
					$passwordsReady = false;
				}
			}
		} );

		if ( $passwordsReady ) {
			sendForm( $form, false );
		}
	};

	this.initialise = function ( data ) {
		workingData = data;
		jQuery( document ).on( "submit", 'form.icwpOptionsForm', submitOptionsForm );
	};
}();

iCWP_WPSF_OptionsPages.initialise();

jQuery.fn.icwpWpsfAjaxTable = function ( aOptions ) {

	this.reloadTable = function () {
		renderTableRequest();
	};

	var createTableContainer = function () {
		$oTableContainer = jQuery( '<div />' ).appendTo( $oThis );
		$oTableContainer.addClass( 'icwpAjaxTableContainer' );
	};

	var refreshTable = function ( evt ) {
		evt.preventDefault();

		var query = this.search.substring( 1 );
		var aTableRequestParams = {
			paged: extractQueryVars( query, 'paged' ) || 1,
			order: extractQueryVars( query, 'order' ) || 'desc',
			orderby: extractQueryVars( query, 'orderby' ) || 'created_at',
			tableaction: jQuery( evt.currentTarget ).data( 'tableaction' )
		};

		renderTableRequest( aTableRequestParams );
	};

	var extractQueryVars = function ( query, variable ) {
		var vars = query.split( "&" );
		for ( var i = 0; i < vars.length; i++ ) {
			var pair = vars[ i ].split( "=" );
			if ( pair[ 0 ] === variable ) {
				return pair[ 1 ];
			}
		}
		return false;
	};

	this.renderTableFromForm = function ( $oForm ) {
		renderTableRequest( { 'form_params': $oForm.serialize() } );
	};

	var renderTableRequest = function ( aTableRequestParams ) {
		if ( bReqRunning ) {
			return false;
		}
		bReqRunning = true;
		iCWP_WPSF_BodyOverlay.show();

		jQuery.post( ajaxurl, jQuery.extend( aOpts[ 'ajax_render' ], aOpts[ 'req_params' ], aTableRequestParams ),
			function ( oResponse ) {
				$oTableContainer.html( oResponse.data.html )
			}
		).always(
			function () {
				bReqRunning = false;
				iCWP_WPSF_BodyOverlay.hide();
			}
		);
	};

	var setHandlers = function () {
		$oThis.on( "click", 'a.tableActionRefresh', refreshTable );
		$oThis.on( 'click', '.tablenav-pages a, .manage-column.sortable a, .manage-column.sorted a', refreshTable );

		var timer;
		var delay = 1000;
		jQuery( document ).on( 'keyup', 'input[name=paged]', function ( event ) {
			// If user hit enter, we don't want to submit the form
			// We don't preventDefault() for all keys because it would
			// also prevent to get the page number!
			if ( 13 === event.which )
				event.preventDefault();

			// This time we fetch the variables in inputs
			var $eThis = jQuery( event.currentTarget );
			var aTableRequestParams = {
				paged: isNaN( $eThis.val() ) ? 1 : $eThis.val(),
				order: jQuery( 'input[name=order]', $eThis ).val() || 'desc',
				orderby: jQuery( 'input[name=orderby]', $eThis ).val() || 'created_at'
			};
			// Now the timer comes to use: we wait a second after
			// the user stopped typing to actually send the call. If
			// we don't, the keyup event will trigger instantly and
			// thus may cause duplicate calls before sending the intended
			// value
			renderTableRequest( aTableRequestParams );
		} );
	};

	var initialise = function () {
		jQuery( document ).ready( function () {
			createTableContainer();
			renderTableRequest();
			setHandlers();
		} );
	};

	var $oThis = this;
	var $oTableContainer;
	var bReqRunning = false;
	var aOpts = jQuery.extend( {}, aOptions );
	initialise();

	return this;
};

if ( typeof icwp_wpsf_vars_plugin !== 'undefined' ) {

	jQuery( document ).ready( function () {
		jQuery( document ).on( 'click', 'a.shield_file_download, a.shield_file_download ', function ( evt ) {
			evt.preventDefault();
			/** Cache busting **/
			let url = jQuery( this ).attr( 'href' ) + '&rand='
				+ Math.floor( 10000 * Math.random() );
			jQuery.fileDownload( url, {
				preparingMessageHtml: icwp_wpsf_vars_plugin.strings.downloading_file,
				failMessageHtml: icwp_wpsf_vars_plugin.strings.downloading_file_problem
			} );
			return false;
		} );
	} );
}

let iCWP_WPSF_ProgressMeters = new function () {

	let data;
	let $canvas;
	let analysisContainer;

	this.renderAnalysis = function ( meter ) {
		iCWP_WPSF_BodyOverlay.show();
		let reqData = data.ajax.render_meter_analysis;
		reqData.meter = meter;

		$canvas.html( '<div class="d-flex justify-content-center align-items-center"><div class="spinner-border text-success m-5" role="status"><span class="visually-hidden">Loading...</span></div></div>' );
		analysisContainer.show();

		jQuery.ajax(
			{
				type: "POST",
				url: ajaxurl,
				data: reqData,
				dataType: "text",
				success: function ( raw ) {
					let response = iCWP_WPSF_ParseAjaxResponse.parseIt( raw );
					$canvas.html( response.data.html );
				}
			}
		).always(
			function () {
				iCWP_WPSF_BodyOverlay.hide();
			}
		);
	};

	this.initialise = function ( workingData ) {
		data = workingData;
		$canvas = jQuery( '#ShieldProgressMeterOffcanvas' );
		analysisContainer = new bootstrap.Offcanvas( document.getElementById( 'ShieldProgressMeterOffcanvas' ) );

		const circle = new CircularProgressBar( 'pie' );
		circle.initial();
	};
}();

let iCWP_WPSF_Helpscout = new function () {
	this.initialise = function ( workingData ) {
		beaconInit();
		window.Beacon( 'init', workingData.beacon_id );
		Beacon( 'navigate', '/' );

		jQuery( document ).on( 'click', 'a.beacon-article', function ( evt ) {
			evt.preventDefault();
			let link = jQuery( evt.currentTarget );
			let id = link.data( 'beacon-article-id' );
			if ( id ) {
				let format = '';
				if ( link.data( 'beacon-article-format' ) ) {
					format = link.data( 'beacon-article-format' );
				}
				Beacon( 'article', String( id ), { type: format } );
			}
			return false;
		} );
	};

	let beaconInit = function () {
		!function ( e, t, n ) {
			function a() {
				var e = t.getElementsByTagName( "script" )[ 0 ], n = t.createElement( "script" );
				n.type = "text/javascript", n.async = !0, n.src = "https://beacon-v2.helpscout.net", e.parentNode.insertBefore( n, e )
			}

			if ( e.Beacon = n = function ( t, n, a ) {
				e.Beacon.readyQueue.push( { method: t, options: n, data: a } )
			}, n.readyQueue = [], "complete" === t.readyState ) return a();
			e.attachEvent ? e.attachEvent( "onload", a ) : e.addEventListener( "load", a, !1 )
		}( window, document, window.Beacon || function () {
		} );
	};
}();

let jQueryDoc = jQuery( 'document' );

jQueryDoc.ready( function () {

	if ( typeof icwp_wpsf_vars_insights.vars.meters !== 'undefined' ) {
		iCWP_WPSF_ProgressMeters.initialise( icwp_wpsf_vars_insights.vars.meters );
	}

	if ( typeof icwp_wpsf_vars_plugin.components.mod_options !== 'undefined' ) {
		iCWP_WPSF_OptionsFormSubmit.initialise( icwp_wpsf_vars_plugin.components.mod_options );
	}

	if ( typeof icwp_wpsf_vars_plugin.components.helpscout !== 'undefined' ) {
		iCWP_WPSF_Helpscout.initialise( icwp_wpsf_vars_plugin.components.helpscout );
	}

	iCWP_WPSF_Modals.initialise();
	if ( typeof icwp_wpsf_vars_ips.components.modal_ip_analysis !== 'undefined' ) {
		iCWP_WPSF_Modals.setData( 'modal_ip_analysis', icwp_wpsf_vars_ips.components.modal_ip_analysis );

		if ( typeof jQueryDoc.icwpWpsfIpAnalyse !== 'undefined' ) {
			jQueryDoc.icwpWpsfIpAnalyse( icwp_wpsf_vars_ips.components.ip_analysis.ajax );
		}
	}

	jQuery( document ).ajaxComplete( function () {
		let popoverTriggerList = [].slice.call( document.querySelectorAll( '[data-bs-toggle="popover"]' ) )
		popoverTriggerList.map( function ( popoverTriggerEl ) {
			return new bootstrap.Popover( popoverTriggerEl );
		} );

		let tooltipTriggerList = [].slice.call( document.querySelectorAll( '[data-bs-toggle="tooltip"]' ) )
		tooltipTriggerList.map( function ( tooltipTriggerEl ) {
			return new bootstrap.Tooltip( tooltipTriggerEl );
		} );
	} );

	jQuery( document ).icwpWpsfTours();
	jQuery( document ).icwpWpsfPluginNavigation();
	jQuery( '.select2picker.static' ).select2( {
		width: 'resolve'
	} );
	jQuery( '#SearchDialog select' ).select2( {
		dropdownParent: jQuery( "#SearchDialog" )
	} );
	jQuery( '#IpReviewSelect' ).select2( {
		minimumInputLength: 2,
		ajax: {
			url: ajaxurl,
			method: 'POST',
			data: function ( params ) {
				let reqParams = jQuery( this ).data( 'ajaxparams' );
				reqParams.search = params.term;
				return reqParams;
			},
			processResults: function ( data ) {
				return {
					results: data.data.ips
				};
			}
		}
	} );
} );