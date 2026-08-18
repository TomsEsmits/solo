<?php
/**
 * Template Part: Vehicle Search Form
 *
 * Cascading car finder form (make → model → year → engine type).
 * AJAX calls go to /ajax-call.php at the Magento site root.
 *
 * @package Expound
 */

$base_url = class_exists( 'Mage' ) ? Mage::getBaseUrl() : trailingslashit( home_url() );
?>
<style>
	:root {
		--rubik: "Rubik", sans-serif;
		--inter: "Inter", sans-serif;
	}

	#ajax-quick-search {
		background: none;
		z-index: 11;
		position: relative;
	}

	#ajax-quick-search .content {
		max-width: 1590px;
		width: 100% !important;
		border-radius: 136.111px;
		background: rgba(224, 229, 232, 0.60);
		box-shadow: 0 1.361px 34.028px 0 rgba(0, 0, 0, 0.05);
		backdrop-filter: blur(17.01388931274414px);
		padding: 22px 32px 22px 22px;
	}

	@media only screen and (max-width: 1350px) {
		#ajax-quick-search .content {
			border-radius: 0;
			padding: 22px 15px;
		}
	}

	#ajax-quick-search .content #front-form {
		padding: 0;
		display: flex;
		gap: 24px;
	}

	#ajax-quick-search .content #front-form::after {
		display: none;
	}

	@media only screen and (max-width: 1350px) {
		#ajax-quick-search .content #front-form {
			flex-direction: column;
		}
	}

	#ajax-quick-search .content #front-form .select-wrapper {
		float: unset;
		margin-right: unset;
		max-width: 300px;
		width: 100%;
		position: relative;
	}

	#ajax-quick-search .content #front-form .select-wrapper ul {
		transition: ease-in-out 0.3s;
		display: grid;
		grid-template-rows: 0fr;
	}

	#ajax-quick-search .content #front-form .select-wrapper ul li {
		overflow: hidden;
	}

	@media only screen and (max-width: 1350px) {
		#ajax-quick-search .content #front-form .select-wrapper {
			max-width: 100%;
		}
	}

	#ajax-quick-search .content #front-form .select-wrapper select {
		border-radius: 136px !important;
		border: 1px solid #E9E9E9 !important;
		background: #FFF;
		padding: 18px 9px 17px 25px;
		color: #636363;
		font-family: var(--rubik);
		font-size: 18px;
		font-style: normal;
		font-weight: 400;
		line-height: normal;
	}

	#ajax-quick-search .content #front-form .select-wrapper select.ajax {
		background-position: 97% 20px !important;
		background-repeat: no-repeat !important;
	}

	#ajax-quick-search .content #front-form .select-wrapper .select-dropdown {
		line-height: 0;
		width: fit-content;
		position: absolute;
		right: 22px;
		top: 26px;
		pointer-events: none;
		transition: ease-in-out 0.3s;
	}

	#ajax-quick-search .content #front-form .select-wrapper.active .select-dropdown {
		transform: rotate(-180deg);
	}

	#ajax-quick-search .content #front-form .select-wrapper .select-selected {
		border-radius: 136px !important;
		border: 1px solid #E9E9E9 !important;
		background: #FFF;
		padding: 18px 24px 17px 25px;
		color: #636363;
		font-family: var(--rubik);
		font-size: 18px;
		font-style: normal;
		font-weight: 400;
		line-height: normal;
		cursor: pointer;
	}

	#ajax-quick-search .content #front-form .select-wrapper .select-selected.active {
		border-radius: 10px 10px 0 0 !important;
	}

	#ajax-quick-search .content #front-form .select-wrapper .select-items {
		border-radius: 0 0 10px 10px;
		border: 1.361px solid #E9E9E9;
		background: #FFF;
		box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.10);
		position: absolute;
		width: 100%;
		z-index: 12;
		max-height: 300px;
		overflow-y: auto;
	}

	#ajax-quick-search .content #front-form .select-wrapper .select-items div {
		color: #212121;
		font-family: var(--inter);
		font-size: 16px;
		font-style: normal;
		font-weight: 400;
		line-height: 180%;
		padding: 16px 16px 16px 24px;
		cursor: pointer;
	}

	#ajax-quick-search .content #front-form .select-wrapper:last-child {
		max-width: 243px;
		width: 100% !important;
	}

	#ajax-quick-search .content #front-form .select-wrapper a {
		border-radius: 136px !important;
		background: #19BAE0;
		display: block;
		max-width: 243px;
		padding: 15px 0 18px 0 !important;
		font-size: 18px;
		font-weight: 600;
		text-align: center;
		text-decoration: none;
		color: #FFF;
	}

	#ajax-quick-search .content #front-form .select-wrapper a:hover {
		background: #119fc2;
	}

	@media only screen and (max-width: 992px) {
		#ajax-quick-search {
			padding: 0 24px;
		}

		#ajax-quick-search .content {
			border-radius: 24px;
			padding: 22px 27px 36px 22px;
		}

		#ajax-quick-search .content #front-form .select-wrapper:last-child {
			max-width: 100%;
		}

		#ajax-quick-search .content #front-form .select-wrapper a {
			color: #FFF;
			text-align: center;
			font-family: var(--rubik);
			font-size: 18px;
			font-style: normal;
			font-weight: 600;
			line-height: normal;
			letter-spacing: 0.18px;
			text-transform: capitalize;
			width: 100% !important;
			max-width: 100%;
		}
	}
</style>
<section id="ajax-quick-search">
	<div class="content">
		<div id="front-form" class="box">
			<div class="select-wrapper">
				<select id="make" onchange="getCategoryAjax( this.options[this.selectedIndex].value, 'model' );" style="display: none;">
					<option value="~" disabled="disabled" selected="selected">1 | Select auto make</option>
				</select>
				<figure class="select-dropdown">
					<svg xmlns="http://www.w3.org/2000/svg" width="12" height="7" viewBox="0 0 12 7" fill="none">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M5.3729 6.38346L0.559914 1.57047L1.76295 0.367439L5.97442 4.57891L10.1859 0.367439L11.3889 1.57047L6.57593 6.38346C6.41638 6.54296 6.20002 6.63256 5.97442 6.63256C5.74881 6.63256 5.53245 6.54296 5.3729 6.38346Z" fill="#636363"/>
					</svg>
				</figure>
			</div>
			<div class="select-wrapper">
				<select id="model" class="l-1" onchange="getCategoryAjax( 'year', 'year' );" style="display: none;">
					<option value="~" disabled="disabled" selected="selected">2 | Select model</option>
				</select>
				<figure class="select-dropdown">
					<svg xmlns="http://www.w3.org/2000/svg" width="12" height="7" viewBox="0 0 12 7" fill="none">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M5.3729 6.38346L0.559914 1.57047L1.76295 0.367439L5.97442 4.57891L10.1859 0.367439L11.3889 1.57047L6.57593 6.38346C6.41638 6.54296 6.20002 6.63256 5.97442 6.63256C5.74881 6.63256 5.53245 6.54296 5.3729 6.38346Z" fill="#636363"/>
					</svg>
				</figure>
			</div>
			<div class="select-wrapper">
				<select id="year" class="l-1 l-2" onchange="getCategoryAjax( 'engine_type', 'engine-type' );" style="display: none;">
					<option value="~" disabled="disabled" selected="selected">3 | Select year</option>
				</select>
				<figure class="select-dropdown">
					<svg xmlns="http://www.w3.org/2000/svg" width="12" height="7" viewBox="0 0 12 7" fill="none">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M5.3729 6.38346L0.559914 1.57047L1.76295 0.367439L5.97442 4.57891L10.1859 0.367439L11.3889 1.57047L6.57593 6.38346C6.41638 6.54296 6.20002 6.63256 5.97442 6.63256C5.74881 6.63256 5.53245 6.54296 5.3729 6.38346Z" fill="#636363"/>
					</svg>
				</figure>
			</div>
			<div class="select-wrapper">
				<select id="engine-type" class="l-1 l-2 l-3" style="display: none;">
					<option value="~" disabled="disabled" selected="selected">4 | Select engine type</option>
				</select>
				<figure class="select-dropdown">
					<svg xmlns="http://www.w3.org/2000/svg" width="12" height="7" viewBox="0 0 12 7" fill="none">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M5.3729 6.38346L0.559914 1.57047L1.76295 0.367439L5.97442 4.57891L10.1859 0.367439L11.3889 1.57047L6.57593 6.38346C6.41638 6.54296 6.20002 6.63256 5.97442 6.63256C5.74881 6.63256 5.53245 6.54296 5.3729 6.38346Z" fill="#636363"/>
					</svg>
				</figure>
			</div>
			<div class="select-wrapper">
				<a href="#" id="front-form-submit">Search</a>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var soloVehicleBaseUrl = <?php echo json_encode( $base_url ); ?>;

		jQuery( document ).ready( function() {
			jQuery( "#front-form-submit" ).unbind( "click" ).bind( "click", function( e ) {
				e.preventDefault();
				var make        = ( jQuery( "#make" ).val() != "~" )        ? "category=" + jQuery( "#make" ).val() : "";
				var model       = ( jQuery( "#model" ).val() != "~" )       ? "model=" + jQuery( "#model option:selected" ).attr( "alt-value" ) : "";
				var year        = ( jQuery( "#year" ).val() != "~" )        ? "year[]=" + jQuery( "#year" ).val() : "";
				var engine_type = ( jQuery( "#engine-type" ).val() != "~" ) ? "engine_type[]=" + jQuery( "#engine-type" ).val() : "";

				if ( jQuery( "#make" ).val() !== null && jQuery( "#model" ).val() !== null ) {
					var __query = [ make, model, year, engine_type ];
					__query = __query.filter( function( n ) { return n.indexOf( "null" ) === -1; } );
					window.location.href = soloVehicleBaseUrl + "catalogsearch/advanced/result/?" + __query.join( "&" );
				} else {
					alert( "Please choose make and model of the car." );
				}
			});

			getCategoryAjax( 111, "make" );

			rebuildCustomSelect( "#make" );
			rebuildCustomSelect( "#model" );
			rebuildCustomSelect( "#year" );
			rebuildCustomSelect( "#engine-type" );
		});

		function getCategoryAjax( id, location ) {
			jQuery( "#" + location ).addClass( "ajax" );
			switch ( location ) {
				case "make":
					jQuery.get( soloVehicleBaseUrl + "ajax-call.php?action=get&category=" + id, function( data ) {
						jQuery( "#" + location ).html( data ).removeClass( "ajax" );
						rebuildCustomSelect( "#make" );
					});
					jQuery( "#model" ).html( '<option value="" disabled="disabled" selected="selected">2 | Select model</option>' );
					jQuery( "#year" ).html( '<option value="" disabled="disabled" selected="selected">3 | Select year</option>' );
					jQuery( "#engine-type" ).html( '<option value="" disabled="disabled" selected="selected">4 | Select engine type</option>' );
					resetSelect( "#model", "2 | Select model" );
					resetSelect( "#year", "3 | Select year" );
					resetSelect( "#engine-type", "4 | Select engine type" );
				break;
				case "model":
					jQuery.get( soloVehicleBaseUrl + "ajax-call.php?action=get&category=" + id, function( data ) {
						jQuery( "#" + location ).html( data ).removeClass( "ajax" );
						rebuildCustomSelect( "#model" );
					});
					jQuery( "#year" ).html( '<option value="" disabled="disabled" selected="selected">3 | Select year</option>' );
					jQuery( "#engine-type" ).html( '<option value="" disabled="disabled" selected="selected">4 | Select engine type</option>' );
					resetSelect( "#year", "3 | Select year" );
					resetSelect( "#engine-type", "4 | Select engine type" );
				break;
				case "year":
					jQuery.get( soloVehicleBaseUrl + "ajax-call.php?action=get&attribute=" + id + "&model=" + jQuery( "#model" ).val(), function( data ) {
						jQuery( "#" + location ).html( data ).removeClass( "ajax" );
						rebuildCustomSelect( "#year" );
					});
					jQuery( "#engine-type" ).html( '<option value="" disabled="disabled" selected="selected">4 | Select engine type</option>' );
					resetSelect( "#engine-type", "4 | Select engine type" );
				break;
				case "engine-type":
					jQuery.get( soloVehicleBaseUrl + "ajax-call.php?action=get&attribute=" + id + "&model=" + jQuery( "#model" ).val() + "&year=" + jQuery( "#year" ).val(), function( data ) {
						jQuery( "#" + location ).html( data ).removeClass( "ajax" );
						rebuildCustomSelect( "#engine-type" );
					});
				break;
			}
		}

		function rebuildCustomSelect( selector ) {
			var $wrapper = jQuery( selector ).closest( ".select-wrapper" );
			var select = $wrapper.find( "select" )[ 0 ];
			if ( ! select ) return;

			$wrapper.find( ".select-selected, .select-items" ).remove();

			var selected = document.createElement( "div" );
			selected.classList.add( "select-selected" );
			var selectedOption = select.options[ select.selectedIndex ];
			selected.textContent = selectedOption ? selectedOption.textContent : "";
			$wrapper[ 0 ].appendChild( selected );

			var optionsList = document.createElement( "div" );
			optionsList.classList.add( "select-items" );
			optionsList.style.display = "none";

			Array.from( select.options ).forEach( function( opt, index ) {
				var optionDiv = document.createElement( "div" );
				optionDiv.textContent = opt.textContent;
				optionDiv.addEventListener( "click", function() {
					if ( opt.disabled ) {
						select.selectedIndex = 0;
						selected.textContent = select.options[ 0 ].textContent;
						optionsList.style.display = "none";
						selected.classList.remove( "active" );
						selected.parentElement.classList.remove( "active" );
						return;
					}
					if ( select.selectedIndex !== index ) {
						select.selectedIndex = index;
						selected.textContent = opt.textContent;
						select.dispatchEvent( new Event( "change" ) );
					}
					optionsList.style.display = "none";
					selected.parentElement.classList.remove( "active" );
					selected.classList.remove( "active" );
				});
				optionsList.appendChild( optionDiv );
			});

			$wrapper[ 0 ].appendChild( optionsList );

			selected.addEventListener( "click", function( e ) {
				e.stopPropagation();
				if ( selected.classList.contains( "active" ) ) {
					selected.classList.remove( "active" );
					selected.parentElement.classList.remove( "active" );
					optionsList.style.display = "none";
				} else {
					closeAllCustomSelects();
					selected.classList.add( "active" );
					selected.parentElement.classList.add( "active" );
					optionsList.style.display = "block";
				}
			});
		}

		function closeAllCustomSelects() {
			document.querySelectorAll( ".select-selected.active" ).forEach( function( sel ) {
				sel.classList.remove( "active" );
				sel.parentElement.classList.remove( "active" );
			});
			document.querySelectorAll( ".select-items" ).forEach( function( list ) {
				list.style.display = "none";
			});
		}

		document.addEventListener( "click", function( e ) {
			if ( ! e.target.closest( ".select-wrapper" ) ) {
				closeAllCustomSelects();
			}
		});

		function resetSelect( selector, placeholderText ) {
			var $select = jQuery( selector );
			$select.html( '<option value="" disabled="disabled" selected="selected">' + placeholderText + '</option>' );
			rebuildCustomSelect( selector );
		}
	</script>
</section>
