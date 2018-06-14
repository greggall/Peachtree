var FGLivePopulation = function( args ) {

	var self = this,
	    $    = jQuery;

	self.init = function() {

		self.options = args;
		self.strings = forgravity_livepopulation_frontend_strings;

		$( document ).ready( self.bindUpdateEvents );

	}

	/**
	 * Bind field on blur events.
	 *
	 * @since 1.0
	 */
	self.bindUpdateEvents = function() {

		// Loop through replacement targets.
		for ( var targetFieldId in self.options.fields ) {

			// Prepare jQuery target.
			var target = $.inArray( parseInt( targetFieldId ), self.options.listFields ) > -1 ? '[name="input_' + targetFieldId + '[]"]' : '[name="input_' + targetFieldId + '"]';

			// If target cannot be found, skip.
			if ( $( document ).find( target ).length == 0 ) {
				continue;
			}

			// Get target DOM item.
			var $target = $( document ).find( target ).eq( 0 );

			// Get target input type.
			var targetType = $target.attr( 'type' ) ? $target.attr( 'type' ) : $target.prop( 'tagName' ).toLowerCase();

			// Prepare event type.
			switch ( targetType ) {

				case 'checkbox':
				case 'radio':
				case 'select':
					var eventType = 'change';
					break;

				default:
					var eventType = 'blur';
					break;

			}

			// Filter event type.
			eventType = gform.applyFilters( 'fg_livepopulation_event_type', eventType, self.getFieldId( $( target ) ), self.options.formId );

			// Bind on change event.
			switch ( eventType ) {

				case 'keyup':

					// Load throttle plugin.
					self.loadThrottlePlugin();

					// Get delay time.
					var delay = gform.applyFilters( 'fg_livepopulation_delay', 1000, self.getFieldId( $( target ) ), self.options.formId );

					// Bind change event.
					$( document ).on( eventType, target, $.throttle( delay, function() {

						// Get field and input IDs.
						var fieldId = self.getFieldId( $( this ) ),
						    inputId = self.getInputId( $( this ) );

						self.doLivePopulation( fieldId, inputId );

					} ) );
					break;

				default:

					// Bind change event.
					$( document ).on( eventType, target, function() {

						// Get field and input IDs.
						var fieldId = self.getFieldId( $( this ) ),
						    inputId = self.getInputId( $( this ) );

						self.doLivePopulation( fieldId, inputId );

					} );

					break;

			}

		}

		// Run Live Population after deleting list item.
		if ( typeof gform !== 'undefined' ) {

			gform.addAction( 'gform_list_post_item_delete', function( $container ) {

				// Get first list input.
				var input = $container.find( 'input:eq(0)' );

				// Get field ID.
				var fieldId = self.getFieldId( input );

				// If field ID is depended upon by Live Population, run Live Population.
				if ( $.inArray( parseInt( fieldId ), self.options.listFields ) > -1 ) {
					self.doLivePopulation( fieldId );
				}

			} );

		}

	}

	/**
	 * Run a Live Population AJAX request.
	 *
	 * @since 1.0
	 */
	self.doLivePopulation = function( targetFieldId, targetInputId ) {

	    gform.doAction( 'fg_livepopulation_pre_population', self.options.formId, targetFieldId, targetInputId );

		$.ajax(
			{
				async:    true,
				type:     'POST',
				url:      self.strings.ajaxurl,
				dataType: 'json',
				data:     self.getRequestData( targetFieldId, targetInputId ),
				success:  function( response ) {
					if ( response.success ) {
						self.populateFields( response.data.populate );
					}
				}
			}
		);

	}

	/**
	 * Get field ID from input element.
	 *
	 * @since 1.1.7
	 */
	self.getFieldId = function( element ) {

		// If element does not exist, return.
		if ( ! element ) {
			return false;
		}

		// Get field ID.
		var fieldId = element.attr( 'name' );
		    fieldId = fieldId.replace( 'input_', '' );
		    fieldId = fieldId.replace( '[]', '' );
		    fieldId = fieldId.replace( /(\.[0-9]*)/g, '' );

		return fieldId;

	}

	/**
	 * Get input ID from input element.
	 *
	 * @since 1.1.7
	 */
	self.getInputId = function( element ) {

		// Get input ID.
		var inputId = element.attr( 'name' );
		    inputId = inputId.replace( 'input_', '' );
		    inputId = inputId.replace( '[]', '' );

		return inputId;

	}

	/**
	 * Prepare request data for Live Population AJAX request.
	 *
	 * @since 1.0
	 */
	self.getRequestData = function( targetFieldId, targetInputId ) {

		// Initialize data.
		var data = {};

		// Get form data.
		$.each( $( '#gform_' + self.options.formId ).serializeArray(), function( i, field ) {

			// Prepare fields to be excluded.
			var blacklistedFields = [ 'gform_submit', 'gform_ajax' ];

			// Do not include the blacklisted fields.
			if ( $.inArray( field.name, blacklistedFields ) > -1 ) {
				return;
			}

			// Prepare excluded field name for search.
			var fieldId = field.name.replace( 'input_', '' );

			// If field is an excluded field, do not include it.
			if ( $.inArray( fieldId, self.options.exclude ) > -1 ) {
				return;
			}

			// If this is a list field, store as array.
			if ( fieldId.endsWith( '[]' ) ) {

				// Remove brackets from field ID.
				var fieldName = field.name.replace( '[]', '' );

				// Initialize array.
				if ( ! data[ fieldName ] ) {
					data[ fieldName ] = [];
				}

				// Store data.
				data[ fieldName ].push( field.value );

			} else {

				data[ field.name ] = field.value;

			}

		} );

		// Add action.
		data.action = 'forgravity_livepopulation_populate';

		// Add replacements.
		data.replacements = self.options.fields[ targetInputId ] ? self.options.fields[ targetInputId ] : self.options.fields[ targetFieldId ];

		return data;

	}

	/**
	 * Load Throttle/Debounce jQuery plugin.
	 *
	 * @since 1.1.7
	 */
	self.loadThrottlePlugin = function() {

		// Initialize script element.
		var s      = document.createElement( 'script' );
		    s.type = 'text/javascript';
		    s.src  = self.strings.throttle_url;

		// Add to header.
		$( 'head' ).append( s );

	}

	/**
	 * Populate field with generated replacements.
	 *
	 * @since 1.0
	 */
	self.populateFields = function( populate ) {

		// Loop through population fields.
		for ( var fieldId in populate ) {

			// Loop through targets.
			for ( var target in populate[ fieldId ] ) {

				// Render population based on target.
				switch ( target ) {

					case 'choices-checkbox':
						self.updateCheckboxChoices( fieldId, populate[ fieldId ][ target ] );
						break;

					case 'choices-multiselect':
					case 'choices-select':
						self.updateSelectChoices( fieldId, populate[ fieldId ][ target ] );
						break;

					case 'choices-radio':
						self.updateRadioChoices( fieldId, populate[ fieldId ][ target ] );
						break;

					case 'column-label':
						self.updateColumnLabel( fieldId, populate[ fieldId ][ target ] );
						break;

					case 'content':
						self.updateContent( fieldId, populate[ fieldId ][ target ] );
						break;

					case 'description':
						self.updateDescription( fieldId, populate[ fieldId ][ target ] );
						break;

					case 'label':
						self.updateLabel( fieldId, populate[ fieldId ][ target ] );
						break;

					case 'placeholder':
						self.updatePlaceholder( fieldId, populate[ fieldId ][ target ] );
						break;

					case 'placeholder-select':
						self.updateSelectPlaceholder( fieldId, populate[ fieldId ][ target ] );
						break;

					case 'section-label':
						self.updateSectionLabel( fieldId, populate[ fieldId ][ target ] );
						break;

					case 'sub-label':
						for ( var inputId in populate[ fieldId ][ target ] ) {
							self.updateSubLabel( inputId, populate[ fieldId ][ target ][ inputId ] );
						}
						break;

					case 'value':
						self.updateValue( fieldId, populate[ fieldId ][ target ] );
						break;

					default:
						break;

				}

			}

		}

	    gform.doAction( 'fg_livepopulation_post_population', self.options.formId, populate );

	}





	// # UPDATE METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Update a checkbox field's choices.
	 *
	 * @since 1.1
	 *
	 * @param int    fieldId Field ID.
	 * @param string choices New field choices.
	 */
	self.updateCheckboxChoices = function( fieldId, choices ) {

		$( '#field_' + self.options.formId + '_' + fieldId + ' .gfield_checkbox' ).html( choices );

	}

	/**
	 * Update a multiple column List field's column labels.
	 *
	 * @since 1.3.11
	 *
	 * @param int    fieldId Field ID.
	 * @param string content New field content.
	 */
	self.updateColumnLabel = function( fieldId, labels ) {

		// Loop through labels.
		for ( var i = 0; i < labels.length; i++ ) {

			// Get column label.
			var columnLabel = labels[ i ];

			// Replace column label.
			$( '#field_' + self.options.formId + '_' + fieldId + ' .gfield_list thead th:eq( ' + i + ' )' ).html( columnLabel );

		}

	}

	/**
	 * Update an HTML field's content.
	 *
	 * @since 1.0
	 *
	 * @param int    fieldId Field ID.
	 * @param string content New field content.
	 */
	self.updateContent = function( fieldId, content ) {

		$( '#field_' + self.options.formId + '_' + fieldId ).html( content );

	}

	/**
	 * Update a field's description.
	 *
	 * @since 1.0
	 *
	 * @param int    fieldId     Field ID.
	 * @param string description New field description.
	 */
	self.updateDescription = function( fieldId, description ) {

		// Get field element and description selector.
		var $field    = $( '#field_' + self.options.formId + '_' + fieldId );
		    isSection = $field.hasClass( 'gsection' ),
		    selector  = isSection ? 'gsection_description' : 'gfield_description';

		// If description element exists, populate description.
		if ( $( '.' + selector, $field ).length > 0 ) {
			$( '.' + selector, $field ).html( description );
			return;
		}

		// Get description position.
		var position = $field.hasClass( 'field_description_above' ) ? 'above' : 'below';

		// Insert description.
		switch ( position ) {

			case 'above':
				$( '<div class="' + selector + '">' + description + '</div>' ).insertAfter( $( '.gfield_label', $field ) );
				break;

			default:
				$field.append( $( '<div class="' + selector + '">' + description + '</div>' ) );
				break;

		}

	}

	/**
	 * Update a field's label.
	 *
	 * @since 1.0
	 *
	 * @param int    fieldId Field ID.
	 * @param string label   New field label.
	 */
	self.updateLabel = function( fieldId, label ) {

		$( '#field_' + self.options.formId + '_' + fieldId + ' > .gfield_label' ).html( label );

	}

	/**
	 * Update a field's placeholder.
	 *
	 * @since 1.1
	 *
	 * @param int    inputId Input ID.
	 * @param string label   New field placeholder.
	 */
	self.updatePlaceholder = function( inputId, placeholder ) {

		$( '#input_' + self.options.formId + '_' + inputId ).attr( 'placeholder', placeholder );

	}

	/**
	 * Update a radio field's choices.
	 *
	 * @since 1.1
	 *
	 * @param int    fieldId Field ID.
	 * @param string choices New field choices.
	 */
	self.updateRadioChoices = function( fieldId, choices ) {

		$( '#field_' + self.options.formId + '_' + fieldId + ' .gfield_radio' ).html( choices );

	}

	/**
	 * Update a select field's choices.
	 *
	 * @since 1.1
	 *
	 * @param int    fieldId Field ID.
	 * @param string choices New field choices.
	 */
	self.updateSelectChoices = function( fieldId, choices ) {

		$( '#input_' + self.options.formId + '_' + fieldId ).html( choices ).trigger( 'change' );

	}

	/**
	 * Update a select field's placeholder.
	 *
	 * @since 1.1
	 *
	 * @param int    fieldId Field ID.
	 * @param string label   New field placeholder.
	 */
	self.updateSelectPlaceholder = function( fieldId, placeholder ) {

		$( '#input_' + self.options.formId + '_' + fieldId + ' option.gf_placeholder' ).html( placeholder );

	}

	/**
	 * Update a section field label.
	 *
	 * @since 1.0
	 *
	 * @param int    fieldId Field ID.
	 * @param string label   New field label.
	 */
	self.updateSectionLabel = function( fieldId, label ) {

		$( '#field_' + self.options.formId + '_' + fieldId + ' > .gsection_title' ).html( label );

	}

	/**
	 * Update an input's label.
	 *
	 * @since 1.0
	 *
	 * @param int    inputId Input ID.
	 * @param string label   New field label.
	 */
	self.updateSubLabel = function( inputId, label ) {

		$( '#input_' + self.options.formId + '_' + inputId + '_container label[for="input_' + self.options.formId + '_' + inputId + '"]' ).html( label );

	}

	/**
	 * Update an input's value.
	 *
	 * @since 1.0
	 *
	 * @param int    inputId Input ID.
	 * @param string value   New field value.
	 */
	self.updateValue = function( inputId, value ) {

		$( '#input_' + self.options.formId + '_' + inputId ).val( value ).trigger( 'change' );

	}

	self.init();

}
