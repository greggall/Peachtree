var FGLivePopulationSettings = function( args ) {

	var self = this,
	    $    = jQuery;

	self.init = function() {

		self.options = args;

		// Define needed elements.
		self.$elem = {
			fieldSettings:  $( '#field_settings' ),
			settingsFields: {
				enable:  $( '.livePopulation_setting_enable' ),
				target:  $( '.livePopulation_setting_target' ),
				replace: {
					replace:       $( '.livePopulation_setting_replace' ),
					label:         $( '#livePopulation_replace_label' ),
					content:       $( '#livePopulation_replace_content' ),
					value:         $( '#livePopulation_replace_value' ),
					description:   $( '#livePopulation_replace_description' ),
					placeholder:   $( '#livePopulation_replace_placeholder' ),
					choices:       $( '#livePopulation_replace_choices' ),
					choicesType:   $( '#livePopulation_replace_choicesType' ),
					choicesColumn: $( '#livePopulation_replace_choicesColumn' ),
				}
			}
		};

		self.bindFieldLoad();

		self.bindFieldEvents();

	}

	/**
	 * Add "Live Population" field settings tab.
	 *
	 * @since 1.0
	 */
	self.addTab = function() {

		// Destroy current tabs.
		self.$elem.fieldSettings.tabs( 'destroy' );

		// Destroy Chosen.
		if ( self.$elem.settingsFields.target.find( 'select' ).data( 'chosen' ) ) {
			self.$elem.settingsFields.target.find( 'select' ).chosen( 'destroy' );
		}

		// Get field settings tabs.
		self.$elem.fieldSettingsTabs = self.$elem.fieldSettings.find( 'ul' ).eq( 0 );

		// Get existing tab.
		var existingTab = self.$elem.fieldSettingsTabs.find( '.livepopulation_tab' );

		// Add new tab.
		if ( existingTab.length == 0 ) {
			self.$elem.fieldSettingsTabs.append(
				'<li style="width:114px; padding:0px;" class="livepopulation_tab"> \
					<a href="#livepopulation_tab">' + forgravity_livepopulation_form_editor_strings.tab_name + '</a> \
				</li>'
			);
		}

		// Initialize tabs.
		self.$elem.fieldSettings.tabs();

	}

	/**
	 * Remove "Live Population" field settings tab.
	 *
	 * @since 1.0
	 */
	self.removeTab = function() {

		// Destroy current tabs.
		self.$elem.fieldSettings.tabs( 'destroy' );

		// Get field settings tabs.
		self.$elem.fieldSettingsTabs = self.$elem.fieldSettings.find( 'ul' ).eq( 0 );

		// Remove tab.
		self.$elem.fieldSettingsTabs.find( '.livepopulation_tab' ).remove();

		// Hide settings.
		$( '#livepopulation_tab' ).hide();

		// Initialize tabs.
		self.$elem.fieldSettings.tabs();

	}

	/**
	 * Bind field settings events.
	 *
	 * @since 1.0
	 */
	self.bindFieldEvents = function() {

		self.$elem.settingsFields.enable.on( 'change', function( e ) {

			// Get selected field.
			var field = self.getSelectedField();

			// Update field property.
			field.livePopulation.enable = e.target.checked;

			// Toggle other settings fields.
			self.toggleSettingsFields();

		} );

		self.$elem.settingsFields.target.on( 'change', function( e ) {

			// Get selected field.
			var field = self.getSelectedField();

			// Update field property.
			field.livePopulation.target = $( e.target ).val();

			// Toggle other settings fields.
			self.toggleSettingsFields();

		} );

		self.$elem.settingsFields.replace.label.on( 'change', function( e ) {

			// Get selected field.
			var field = self.getSelectedField();

			// Update field property.
			field.livePopulation.replace.label = e.target.checked;

		} );

		self.$elem.settingsFields.replace.content.on( 'change', function( e ) {

			// Get selected field.
			var field = self.getSelectedField();

			// Update field property.
			field.livePopulation.replace.content = e.target.checked;

		} );

		self.$elem.settingsFields.replace.value.on( 'change', function( e ) {

			// Get selected field.
			var field = self.getSelectedField();

			// Update field property.
			field.livePopulation.replace.value = e.target.checked;

		} );

		self.$elem.settingsFields.replace.description.on( 'change', function( e ) {

			// Get selected field.
			var field = self.getSelectedField();

			// Update field property.
			field.livePopulation.replace.description = e.target.checked;

		} );

		self.$elem.settingsFields.replace.placeholder.on( 'change', function( e ) {

			// Get selected field.
			var field = self.getSelectedField();

			// Update field property.
			field.livePopulation.replace.placeholder = e.target.checked;

		} );

		self.$elem.settingsFields.replace.choices.on( 'change', function( e ) {

			// Get selected field.
			var field = self.getSelectedField();

			// Update field property.
			field.livePopulation.replace.choices = e.target.checked;

			// Toggle other settings fields.
			self.toggleSettingsFields();

		} );

		self.$elem.settingsFields.replace.choicesType.on( 'change', function( e ) {

			// Get selected field.
			var field = self.getSelectedField();

			// Update field property.
			field.livePopulation.replace.choicesType = e.target.value;

		} );

		self.$elem.settingsFields.replace.choicesColumn.on( 'change', function( e ) {

			// Get selected field.
			var field = self.getSelectedField();

			// Update field property.
			field.livePopulation.replace.choicesColumn = e.target.value;

		} );

	}

	/**
	 * Bind field settings initialize functions:
	 * 	- Add Live Population tab
	 *	- Load field target choices
	 *	- Load current field settings
	 *	- Toggle appearance of field settings
	 *
	 * @since 1.0
	 */
	self.bindFieldLoad = function() {

		$( document ).bind( 'gform_load_field_settings', function( event, field ) {

			// Get selected field.
			var field = self.getSelectedField( field );

			// Get supported replacements for field type.
			var replacements = self.options.fieldTypes[ field.type ];

			// If this field is not supported, remove tab and exit.
			if ( ! replacements ) {

				self.removeTab();
				return;

			}

			// Add tab.
			self.addTab();

			// Load form field choices.
			self.loadTargetChoices( field['id'] );

			// Set settings fields values.
			self.$elem.settingsFields.enable.find( 'input' ).prop( 'checked', field.livePopulation.enable );
			self.$elem.settingsFields.target.find( 'select' ).val( field.livePopulation.target );
			self.$elem.settingsFields.replace.label.prop( 'checked', field.livePopulation.replace.label );
			self.$elem.settingsFields.replace.content.prop( 'checked', field.livePopulation.replace.content );
			self.$elem.settingsFields.replace.value.prop( 'checked', field.livePopulation.replace.value );
			self.$elem.settingsFields.replace.description.prop( 'checked', field.livePopulation.replace.description );
			self.$elem.settingsFields.replace.placeholder.prop( 'checked', field.livePopulation.replace.placeholder );
			self.$elem.settingsFields.replace.choices.prop( 'checked', field.livePopulation.replace.choices );
			self.$elem.settingsFields.replace.choicesType.val( field.livePopulation.replace.choicesType );
			self.$elem.settingsFields.replace.choicesColumn.val( field.livePopulation.replace.choicesColumn );

			// Initialize Chosen.
			self.$elem.settingsFields.target.find( 'select' ).chosen(
				{
					placeholder_text_single:   forgravity_livepopulation_form_editor_strings.select_target,
					placeholder_text_multiple: forgravity_livepopulation_form_editor_strings.select_target
				}
			);

			// Toggle other settings fields.
			self.toggleSettingsFields();

		} );

	}

	/**
	 * Load list field columns as Live Population choices column choices.
	 *
	 * @since 1.1
	 */
	self.loadColumnChoices = function( fieldId ) {

		// Remove current options.
		self.$elem.settingsFields.replace.choicesColumn.find( 'option:not( :first )' ).remove();

		// Get selected field.
		var selectedField = self.getField( fieldId );

		// Loop through field columns.
		for ( var i = 0; i < selectedField.choices.length; i++ ) {

			// Get column.
			var column = selectedField.choices[ i ];

			// Add option.
			self.$elem.settingsFields.replace.choicesColumn.append( '<option value="' + column.text + '">' + column.text + '</option>' );

		}

	}

	/**
	 * Load form fields as Live Population target choices.
	 *
	 * @since 1.0
	 */
	self.loadTargetChoices = function( fieldId ) {

		// Remove current options.
		self.$elem.settingsFields.target.find( 'select' ).find( 'option' ).remove();

		// Loop through form fields.
		for ( var i = 0; i < form['fields'].length; i++ ) {

			// Get field.
			var field = form['fields'][ i ];

			// If field is current field, skip it.
			if ( fieldId == field.id ) {
				continue;
			}

			// If field is a banned type, skip it.
			if ( $.inArray( field.type, [ 'creditcard', 'dropbox', 'fileupload', 'page', 'section' ] ) > -1 ) {
				continue;
			}

			// Get field label.
			var label = field.adminLabel && field.adminLabel.length > 0 ? field.adminLabel : field.label;

			// If field label is still empty, show field ID.
			if ( label.length < 1 ) {
				label = '(Field ID: ' + field.id + ')';
			}

			// Add option.
			self.$elem.settingsFields.target.find( 'select' ).append( '<option value="' + field.id + '">' + label + '</option>' );

		}

	}

	/**
	 * Control the appearance of Live Population settings fields.
	 *
	 * @since 1.0
	 */
	self.toggleSettingsFields = function() {

		// Get current field.
		var field = self.getSelectedField();

		// Get supported replacement settings fields.
		var replacements = self.options.fieldTypes[ field.type ];

		// If replacement field settings are defined for this field type, toggle appropriately.
		if ( replacements ) {

			// Loop through replace settings fields.
			for ( var replace_type in self.$elem.settingsFields.replace ) {

				// If this is the main replacements container, skip it.
				if ( 'replace' === replace_type ) {
					continue;
				}

				// Get replacement type container.
				var replaceContainer = self.$elem.settingsFields.replace[ replace_type ].parent();

				// Check if this replacement type is supported.
				var isSupported = $.inArray( replace_type, replacements ) > -1;

				// If this is the choices replacement, determine support based on target field.
				if ( 'choices' === replace_type ) {

					// Set supported state based on if field has choices.
					isSupported = self.getSelectedField().choices.length > 0;

				}

				// If this is the choices replacement, determine support based on target field.
				if ( 'choicesType' === replace_type ) {

					// Get selected field ID.
					var selectedFieldId = field.livePopulation.target;

					// Set supported state based on if target is a list field.
					isSupported = self.isListField( selectedFieldId ) && field.livePopulation.replace.choices;

				}

				// If this is the choices column replacement, determine support based on target field.
				if ( 'choicesColumn' === replace_type ) {

					// Get selected field.
					var selectedField = self.getField( field.livePopulation.target );

					// Set supported state based on if target is a list field.
					isSupported = selectedField && self.isListField( selectedField.id ) && selectedField.enableColumns;

					// Load choices.
					if ( isSupported ) {
						self.loadColumnChoices( selectedField.id );
					}

				}

				// If this replacement type is supported, show it.
				if ( isSupported && 'choices' === replace_type ) {
					replaceContainer.css( 'display', 'inline' );
				} else if ( isSupported ) {
					replaceContainer.show();
				} else {
					replaceContainer.hide();
				}

			}

		} else {

			// Loop through replace settings fields.
			for ( var replace_type in self.$elem.settingsFields.replace ) {

				// If this is the main replacements container, skip it.
				if ( 'replace' === replace_type ) {
					continue;
				}

				// Get replacement type container.
				var replaceContainer = self.$elem.settingsFields.replace[ replace_type ].parent();

				// Show it.
				replaceContainer.show();

			}

		}

		// If enabled, show settings fields.
		if ( field.livePopulation.enable && form.fields.length > 1 ) {
			self.$elem.settingsFields.target.show();
			self.$elem.settingsFields.replace.replace.show();
		} else if ( field.livePopulation.enable && form.fields.length <= 1 ) {
			self.$elem.settingsFields.target.hide();
			self.$elem.settingsFields.replace.replace.show();
		} else {
			self.$elem.settingsFields.target.hide();
			self.$elem.settingsFields.replace.replace.hide();
		}

	}






	// # HELPER METHODS ------------------------------------------------------------------------------------------------

	/**
	 * Get a specific form field.
	 *
	 * @since 1.1
	 *
	 * @param int fieldId Field ID.
	 *
	 * @return object|null
	 */
	self.getField = function( fieldId ) {

		// If we cannot get the form object, return false.
		if ( ! form ) {
			return null;
		}

		// Loop through the form fields.
		for ( var i = 0; i < form.fields.length; i++ ) {

			// If this is not the target field, skip it.
			if ( fieldId == form.fields[ i ].id ) {
				return form.fields[ i ];
			}

		}

		return null;

	}

	/**
	 * Retrieve currently selected field in form editor.
	 *
	 * @since 1.0
	 *
	 * @param object field Currently selected field.
	 *
	 * @return object
	 */
	self.getSelectedField = function( field ) {

		// Get selected field.
		var field = field == null ? GetSelectedField() : field;

		// Initialize field property, if not defined.
		if ( ! field.livePopulation ) {

			field.livePopulation = {
				enable: false,
				target:  null,
				replace: {
					label:         false,
					content:       false,
					value:         false,
					description:   false,
					placeholder:   false,
					choices:       false,
					choicesType:   'append',
					choicesColumn: 'all',
				}
			};

		}

		// Add choices field property, if not defined.
		if ( ! field.livePopulation.replace.choices ) {
			field.livePopulation.replace.choices       = false;
			field.livePopulation.replace.choicesType   = 'append';
			field.livePopulation.replace.choicesColumn = 'all';
		}

		return field;

	}

	/**
	 * Determine if form has list fields.
	 *
	 * @since 1.1
	 *
	 * @return bool
	 */
	self.hasListFields = function() {

		// If we cannot get the form object, return false.
		if ( ! form ) {
			return false;
		}

		// Loop through the form fields.
		for ( var i = 0; i < form.fields.length; i++ ) {

			// If this is a list field, return true.
			if ( 'list' === form.fields[ i ].type ) {
				return true;
			}

		}

		return false;

	}

	/**
	 * Determine if field is a list field.
	 *
	 * @since 1.1
	 *
	 * @param int fieldId Target field ID.
	 *
	 * @return bool
	 */
	self.isListField = function( fieldId ) {

		// Get field.
		var field = self.getField( fieldId );

		// If field was not found, return.
		if ( ! field ) {
			return false;
		}

		return 'list' === field.type;

	}

	self.init();

}
