<?php

namespace ForGravity\LivePopulation;

use GFAddOn;
use GFAPI;
use GFCommon;
use GFFormDisplay;
use GFForms;
use GFFormsModel;

GFForms::include_addon_framework();

/**
 * Live Population for Gravity Forms.
 *
 * @since     1.0
 * @author    ForGravity
 * @copyright Copyright (c) 2017, Travis Lopes
 */
class Live_Population extends GFAddOn {

	/**
	 * Contains an instance of this class, if available.
	 *
	 * @since  1.0
	 * @access private
	 * @var    object $_instance If available, contains an instance of this class.
	 */
	private static $_instance = null;

	/**
	 * Defines the version of Live Population for Gravity Forms.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_version Contains the version, defined from livepopulation.php
	 */
	protected $_version = FG_LIVEPOPULATION_VERSION;

	/**
	 * Defines the minimum Gravity Forms version required.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_min_gravityforms_version The minimum version required.
	 */
	protected $_min_gravityforms_version = '2.1.3.7';

	/**
	 * Defines the plugin slug.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_slug The slug used for this plugin.
	 */
	protected $_slug = 'forgravity-livepopulation';

	/**
	 * Defines the main plugin file.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_path The path to the main plugin file, relative to the plugins folder.
	 */
	protected $_path = 'forgravity-livepopulation/livepopulation.php';

	/**
	 * Defines the full path to this class file.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_full_path The full path.
	 */
	protected $_full_path = __FILE__;

	/**
	 * Defines the URL where this Add-On can be found.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string The URL of the Add-On.
	 */
	protected $_url = 'http://forgravity.com/plugins/live-population/';

	/**
	 * Defines the title of this Add-On.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_title The title of the Add-On.
	 */
	protected $_title = 'Live Population for Gravity Forms';

	/**
	 * Defines the short title of the Add-On.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_short_title The short title.
	 */
	protected $_short_title = 'Live Population';

	/**
	 * Defines the capability needed to access the Add-On settings page.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_capabilities_settings_page The capability needed to access the Add-On settings page.
	 */
	protected $_capabilities_settings_page = 'forgravity_livepopulation';

	/**
	 * Defines the capability needed to access the Add-On form settings page.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_capabilities_form_settings The capability needed to access the Add-On form settings page.
	 */
	protected $_capabilities_form_settings = 'forgravity_livepopulation';

	/**
	 * Defines the capability needed to uninstall the Add-On.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string $_capabilities_uninstall The capability needed to uninstall the Add-On.
	 */
	protected $_capabilities_uninstall = 'forgravity_livepopulation_uninstall';

	/**
	 * Defines the capabilities needed for Live Population.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    array $_capabilities The capabilities needed for the Add-On
	 */
	protected $_capabilities = array( 'forgravity_livepopulation', 'forgravity_livepopulation_uninstall' );

	/**
	 * Get instance of this class.
	 *
	 * @since  1.0
	 * @access public
	 * @static
	 *
	 * @return Live_Population
	 */
	public static function get_instance() {

		if ( null === self::$_instance ) {
			self::$_instance = new self;
		}

		return self::$_instance;

	}

	/**
	 * Register needed hooks.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function init() {

		parent::init();

		add_action( 'gform_register_init_scripts', array( $this, 'init_frontend_script' ), 10, 3 );
		add_filter( 'auto_update_plugin', array( $this, 'maybe_auto_update' ), 10, 2 );

		add_filter( 'gform_pre_render', array( $this, 'populate_form_on_render' ), 10 );
		add_filter( 'gform_pre_validation', array( $this, 'populate_form_on_render' ), 10 );
		add_filter( 'gform_review_page', array( $this, 'populate_review_page' ), 999, 3 );

	}

	/**
	 * Register needed admin hooks.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function init_admin() {

		parent::init_admin();

		add_action( 'gform_editor_js', array( $this, 'initialize_field_settings' ) );
		add_action( 'gform_field_advanced_settings', array( $this, 'add_field_settings_fields' ), 10, 2 );
		add_filter( 'gform_tooltips', array( $this, 'add_tooltips' ) );

	}

	/**
	 * Add AJAX callback for Live Population.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function init_ajax() {

		parent::init_ajax();

		add_action( 'wp_ajax_forgravity_livepopulation_populate', array( $this, 'populate_form_on_ajax' ) );
		add_action( 'wp_ajax_nopriv_forgravity_livepopulation_populate', array( $this, 'populate_form_on_ajax' ) );

	}

	/**
	 * Enqueue needed scripts.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return array
	 */
	public function scripts() {

		$scripts = array(
			array(
				'handle'  => 'forgravity_livepopulation_form_editor',
				'deps'    => array( 'jquery', 'gform_chosen' ),
				'src'     => $this->get_base_url() . '/js/form_editor.js',
				'version' => $this->_version,
				'enqueue' => array( array( 'admin_page' => array( 'form_editor' ) ) ),
				'strings' => array(
					'tab_name'      => esc_html__( 'Live Population', 'forgravity_livepopulation' ),
					'select_target' => esc_html__( 'Select a Field', 'forgravity_livepopulation' ),
				),
			),
			array(
				'handle'  => 'forgravity_livepopulation_frontend',
				'deps'    => array( 'jquery', 'gform_gravityforms' ),
				'src'     => $this->get_base_url() . '/js/frontend.js',
				'version' => $this->_version,
				'enqueue' => array( array( $this, 'enqueue_frontend_script' ) ),
				'strings' => array(
					'ajaxurl'      => admin_url( 'admin-ajax.php' ),
					'throttle_url' => $this->get_base_url() . '/js/vendor/jquery.ba-throttle-debounce.min.js',
				),
			),
		);

		return array_merge( parent::scripts(), $scripts );

	}

	/**
	 * Enqueue needed styles.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return array
	 */
	public function styles() {

		$styles = array(
			array(
				'handle'  => 'forgravity_livepopulation_form_editor',
				'deps'    => array( 'gform_chosen' ),
				'src'     => $this->get_base_url() . '/css/form_editor.css',
				'version' => $this->_version,
				'enqueue' => array( array( 'admin_page' => array( 'form_editor' ) ) ),
			),
		);

		return array_merge( parent::styles(), $styles );

	}





	// # FORM SETTINGS -------------------------------------------------------------------------------------------------

	/**
	 * Add Live Population Javascript initialization to the form settings page.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @uses   Live_Population::supported_field_types()
	 */
	public function initialize_field_settings() {

		// Prepare Javascript options.
		$options = array(
			'fieldTypes' => $this->supported_field_types(),
		);

		?>

        <script type="text/javascript">

			for ( var field_type in fieldSettings ) {

				if ( 'page' === field_type ) {
					continue;
				}

				fieldSettings[ field_type ] += ', .livePopulation_setting_enable';

			}

			jQuery( document ).ready( function () {
				window.FGLivePopulation = new FGLivePopulationSettings( <?php echo json_encode( $options ); ?> );
			} );

        </script>

		<?php
	}

	/**
	 * Add Live Population settings field to the field settings tab.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param int $position The position that the settings will be displayed.
	 * @param int $form_id  The ID of the form being edited.
	 */
	public function add_field_settings_fields( $position, $form_id ) {

		// If this is not the end of the advanced settings, exit.
		if ( -1 !== $position ) {
			return;
		}

		?>
        </ul>
        </div>
        <div id="livepopulation_tab" style="display:none;">
        <ul class="gform_field_settings">
        <li class="field_setting livePopulation_setting_enable">
            <label for="livePopulation_enable" class="section_label">
				<?php esc_attr_e( 'Enable Live Population', 'forgravity_livepopulation' ); ?>
            </label>
            <label for="livePopulation_enable" class="inline">
                <input type="checkbox" id="livePopulation_enable"/>
				<?php esc_attr_e( 'Allow merge tags to be replaced in field properties', 'forgravity_livepopulation' ); ?>
            </label>
        </li>

        <li class="field_setting livePopulation_setting_replace">
            <label class="section_label">
				<?php esc_attr_e( 'Replace Merge Tags In', 'forgravity_livepopulation' ); ?>
				<?php gform_tooltip( $this->_slug . '_replace' ); ?>
            </label>
            <label for="livePopulation_replace_label">
                <input type="checkbox" id="livePopulation_replace_label"/>
				<?php esc_attr_e( 'Field Label', 'forgravity_livepopulation' ); ?>
            </label>
            <label for="livePopulation_replace_content">
                <input type="checkbox" id="livePopulation_replace_content"/>
				<?php esc_attr_e( 'Field Content', 'forgravity_livepopulation' ); ?>
            </label>
            <label for="livePopulation_replace_value">
                <input type="checkbox" id="livePopulation_replace_value"/>
				<?php esc_attr_e( 'Field Value', 'forgravity_livepopulation' ); ?>
            </label>
            <label for="livePopulation_replace_description">
                <input type="checkbox" id="livePopulation_replace_description"/>
				<?php esc_attr_e( 'Field Description', 'forgravity_livepopulation' ); ?>
            </label>
            <label for="livePopulation_replace_placeholder">
                <input type="checkbox" id="livePopulation_replace_placeholder"/>
				<?php esc_attr_e( 'Field Placeholder', 'forgravity_livepopulation' ); ?>
            </label>
            <div class="livePopulation_replace_choices_container">
                <label for="livePopulation_replace_choices">
                    <input type="checkbox" id="livePopulation_replace_choices"/>
					<?php esc_attr_e( 'Field Choices', 'forgravity_livepopulation' ); ?>
                </label>
                <span class="livePopulation_replace_choicesType" style="display:none;">
			        		<?php esc_attr_e( 'and', 'forgravity_livepopulation' ); ?>&nbsp;
				        	<select id="livePopulation_replace_choicesType" style="vertical-align: baseline;">
					        	<option value="append"><?php esc_html_e( 'append them', 'forgravity_livepopulation' ); ?></option>
					        	<option value="replace"><?php esc_html_e( 'replace them', 'forgravity_livepopulation' ); ?></option>
				        	</select>
		        		</span>
                <span class="livePopulation_replace_choicesColumn" style="display:none;">
			        		&nbsp;<?php esc_attr_e( 'using', 'forgravity_livepopulation' ); ?>&nbsp;
				        	<select id="livePopulation_replace_choicesColumn" style="vertical-align: baseline;">
					        	<option value="all"><?php esc_html_e( 'all columns', 'forgravity_livepopulation' ); ?></option>
				        	</select>
		        		</span>
            </div>
        </li>

        <li class="target_setting livePopulation_setting_target">
            <label for="livePopulation_target" class="section_label">
				<?php esc_attr_e( 'Replace Merge Tags When Field Is Changed', 'forgravity_livepopulation' ); ?>
				<?php gform_tooltip( $this->_slug . '_target' ); ?>
            </label>
            <select id="livePopulation_target" name="livePopulation_target[]" multiple></select>
        </li>

		<?php
	}

	/**
	 * Register Live Population tooltips.
	 *
	 * @since  1.2
	 * @access public
	 *
	 * @param array $tooltips Gravity Forms tooltips.
	 *
	 * @return array
	 */
	public function add_tooltips( $tooltips = array() ) {

		$tooltips[ $this->_slug . '_replace' ] = sprintf(
			'<h6>%s</h6>%s<br /><br />%s',
			esc_html__( 'Replace Merge Tags In', 'forgravity_livepopulation' ),
			esc_html__( 'Select the field properties you want Live Population to replace the merge tags in.', 'forgravity_livepopulation' ),
			esc_html__( 'To have Live Population replace field choices, you must select a List field from the drop down below.', 'forgravity_livepopulation' )
		);

		$tooltips[ $this->_slug . '_target' ] = sprintf(
			'<h6>%s</h6>%s<br /><br />%s',
			esc_html__( 'Replace Merge Tags When Field Is Changed', 'forgravity_livepopulation' ),
			esc_html__( "To have this field's merge tags replaced when another field's value is changed (e.g. text entered into an input, a checkbox selected, etc.), select that field from the drop down.", 'forgravity_livepopulation' ),
			esc_html__( 'Merge tags will be replaced on page load regardless of whether or not a field is chosen.', 'forgravity_livepopulation' )
		);


		return $tooltips;

	}

	/**
	 * Prepare supported field types.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @uses   GFAddOn::get_current_form()
	 *
	 * @return array
	 */
	public function supported_field_types() {

		// Define supported field types.
		$supported_field_types = array(

			// Gravity Forms fields
			'address'                  => array( 'label', 'description', 'value', 'placeholder' ),
			'checkbox'                 => array( 'label', 'description', 'choices' ),
			'date'                     => array( 'label', 'description', 'value', 'placeholder' ),
			'email'                    => array( 'label', 'description', 'value', 'placeholder' ),
			'fileupload'               => array( 'label', 'description' ),
			'hidden'                   => array( 'label', 'value' ),
			'html'                     => array( 'content' ),
			'list'                     => array( 'label', 'description' ),
			'multiselect'              => array( 'label', 'description', 'choices' ),
			'name'                     => array( 'label', 'description', 'value', 'placeholder' ),
			'number'                   => array( 'label', 'description', 'value', 'placeholder' ),
			'option'                   => array( 'label', 'description', 'value', 'placeholder' ),
			'password'                 => array( 'label', 'description', 'value', 'placeholder' ),
			'phone'                    => array( 'label', 'description', 'value', 'placeholder' ),
			'post_category'            => array( 'label', 'description' ),
			'post_content'             => array( 'label', 'description', 'value', 'placeholder' ),
			'post_custom_field'        => array( 'label', 'description', 'value', 'placeholder' ),
			'post_excerpt'             => array( 'label', 'description', 'value', 'placeholder' ),
			'post_image'               => array( 'label', 'description' ),
			'post_tags'                => array( 'label', 'description' ),
			'post_title'               => array( 'label', 'description', 'value', 'placeholder' ),
			'product'                  => array( 'description' ),
			'quantity'                 => array( 'label', 'description', 'value', 'placeholder' ),
			'radio'                    => array( 'label', 'description', 'choices' ),
			'section'                  => array( 'label', 'description' ),
			'select'                   => array( 'label', 'description', 'value', 'placeholder', 'choices' ),
			'shipping'                 => array( 'label', 'description' ),
			'text'                     => array( 'label', 'description', 'value', 'placeholder' ),
			'textarea'                 => array( 'label', 'description', 'value', 'placeholder' ),
			'time'                     => array( 'label', 'description', 'value', 'placeholder' ),
			'total'                    => array( 'label', 'description' ),
			'username'                 => array( 'label', 'description', 'value', 'placeholder' ),
			'website'                  => array( 'label', 'description', 'value', 'placeholder' ),

			// Gravity Flow fields
			'workflow_assignee_select' => array( 'label', 'description', 'value', 'placeholder' ),
			'workflow_discussion'      => array( 'label', 'description', 'value', 'placeholder' ),
			'workflow_role'            => array( 'label', 'description', 'value', 'placeholder' ),
			'workflow_user'            => array( 'label', 'description', 'value', 'placeholder' ),

		);

		/**
		 * Modify the field types supported by Live Population.
		 *
		 * @since 1.0
		 *
		 * @param array $supported_field_types An array of field types and the replacements they support.
		 * @param array $form                  The current form.
		 */
		$supported_field_types = apply_filters( 'fg_livepopulation_field_types', $supported_field_types, $this->get_current_form() );

		return $supported_field_types;

	}





	// # FORM DISPLAY --------------------------------------------------------------------------------------------------

	/**
	 * Initialize live merge tag replacement script.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array $form         Form object.
	 * @param array $field_values The current field values.
	 * @param bool  $is_ajax      Whether form is being loaded using AJAX. Defaults to false.
	 *
	 * @uses   GFFormDisplay::add_init_script()
	 * @uses   Live_Population::exclude_from_request()
	 * @uses   Live_Population::get_list_field_ids()
	 * @uses   Live_Population::live_population_fields()
	 */
	public function init_frontend_script( $form, $field_values, $is_ajax = false ) {

		// Get Live Population for form.
		$fields = $this->live_population_fields( $form );

		// If no Live Populations fields exist for this form, exit.
		if ( empty( $fields ) ) {
			return;
		}

		// Prepare Javascript options.
		$options = array(
			'formId'     => $form['id'],
			'fields'     => $fields,
			'exclude'    => $this->exclude_from_request( $form ),
			'listFields' => $this->get_list_field_ids( $form ),
		);

		// Prepare script.
		$script = 'new FGLivePopulation(' . json_encode( $options ) . ');';

		// Initialize script.
		GFFormDisplay::add_init_script( $form['id'], 'forgravity_livepopulation_frontend', GFFormDisplay::ON_PAGE_RENDER, $script );

	}

	/**
	 * Determine if Live Population frontend script can be enqueued.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array $form    Form object.
	 * @param bool  $is_ajax Whether form is being loaded using AJAX. Defaults to false.
	 *
	 * @uses   Live_Population::live_population_fields()
	 *
	 * @return bool
	 */
	public function enqueue_frontend_script( $form, $is_ajax = false ) {

		// Get Live Population fields.
		$fields = $this->live_population_fields( $form );

		return ! empty( $fields );

	}





	// # LIVE POPULATION -----------------------------------------------------------------------------------------------

	/**
	 * Prepare Live Populations.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @uses   GFAPI::get_form()
	 * @uses   GFCommon::replace_variables()
	 * @uses   GFFormsModel::create_lead()
	 * @uses   GFFormsModel::get_field()
	 * @uses   Live_Population::get_request_form_id()
	 */
	public function populate_form_on_ajax() {

		// Disable Gravitate Encryption.
		remove_filter( 'gform_save_field_value', 'gds_encryption_gform_save_field_value' );

		// Initialize populate array.
		$populate = array();

		// Get form ID.
		$form_id = $this->get_request_form_id();

		// Get form.
		$form = GFAPI::get_form( $form_id );

		// If form could not be retrieved, exit.
		if ( ! $form ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Could not get form object.', 'forgravity_livepopulation' ) ) );
			die();
		}

		// Create entry from AJAX request.
		$entry = GFFormsModel::create_lead( $form );

		// Get replacement fields.
		$replacements = rgpost( 'replacements' );

		// Loop through fields to populate.
		foreach ( $replacements as $field_id => $targets ) {

			// Get field.
			$field = GFFormsModel::get_field( $form, $field_id );

			// Loop through targets.
			foreach ( $targets as $target ) {

				// Populate based on target.
				if ( is_callable( array( $this, 'live_populate_' . $target ) ) ) {
					$populate = call_user_func( array(
						$this,
						'live_populate_' . $target,
					), $populate, $field, $form, $entry );
				}

			}

		}

		// Enable Gravitate Encryption.
		if ( function_exists( 'gds_encryption_gform_save_field_value' ) ) {
			add_filter( 'gform_save_field_value', 'gds_encryption_gform_save_field_value', 10, 4 );
		}

		wp_send_json_success( array( 'populate' => $populate ) );
		die();

	}

	/**
	 * Prepare replacement field choices for Live Population.
	 *
	 * @string 1.1
	 * @access public
	 *
	 * @param array     $populate Live Population replacements.
	 * @param \GF_Field $field    The field being populated.
	 * @param array     $form     The current form object.
	 * @param array     $entry    The current entry object.
	 *
	 * @uses   GFFormsModel::get_field()
	 *
	 * @return array
	 */
	public function live_populate_choices( $populate, $field, $form, $entry ) {

		// Initialize List field variable.
		$list_field = false;

		// Ensure target is an array.
		$field->livePopulation['target'] = is_array( $field->livePopulation['target'] ) ? $field->livePopulation['target'] : array( $field->livePopulation['target'] );

		// Loop through target fields.
		foreach ( $field->livePopulation['target'] as $target_field ) {

			// Get target field.
			$target_field = GFFormsModel::get_field( $form, $target_field );

			// If this is a List field, break.
			if ( 'list' === $target_field->type ) {
				$list_field = $target_field;
				break;
			}

		}

		// If a List field is not the target, replace existing merge tags.
		if ( ! $list_field ) {

			/**
			 * Determine if field choices should be removed if merge tags are used and the choice text or value is empty.
			 *
			 * @since 1.3.5
			 *
			 * @param bool  $suppress_empty_choices If field choices should be removed.
			 * @param array $form                   The current Form object.
			 * @param array $form                   The current Field object.
			 */
			$suppress_empty_choices = gf_apply_filters( array(
				'fg_livepopulation_suppress_empty_choices',
				$form['id'],
				$field->id,
			), true, $form, $field );

			// Loop through field choices.
			foreach ( $field->choices as $i => $choice ) {

				// Check if choice text and value have merge tags.
				$text_has_merge_tag  = GFCommon::has_merge_tag( $choice['text'] );
				$value_has_merge_tag = GFCommon::has_merge_tag( $choice['value'] );

				// Replace merge tags.
				$choice['text']  = trim( GFCommon::replace_variables( $choice['text'], $form, $entry, false, true, false, 'text' ) );
				$choice['value'] = trim( GFCommon::replace_variables( $choice['value'], $form, $entry, false, true, false, 'text' ) );

				// Remove choice if empty.
				if ( $suppress_empty_choices && ( ( $text_has_merge_tag && rgblank( $choice['text'] ) ) || ( $value_has_merge_tag && rgblank( $choice['value'] ) ) ) ) {
					unset( $field->choices[ $i ] );
					continue;
				}

				// Save merge tags.
				$field->choices[ $i ] = $choice;

			}

			// Get field choices string based on field type.
			switch ( $field->type ) {

				case 'checkbox':
					$field_choices = $field->get_checkbox_choices( rgar( $entry, $field->id ), '' );
					break;

				case 'radio':
					$field_choices = $field->get_radio_choices( rgar( $entry, $field->id ), '' );
					break;

				default:
					$field_choices = $field->get_choices( rgar( $entry, $field->id ) );
					break;

			}

			// Add choices to populate array.
			$populate[ $field->id ][ 'choices-' . $field->type ] = $field_choices;

			return $populate;

		}

		// Get list field value.
		$list_values = rgar( $entry, $list_field->id );
		$list_values = maybe_unserialize( $list_values );
		$list_values = is_array( $list_values ) ? $list_values : explode( ', ', $list_values );

		// Remove empty values.
		if ( is_array( $list_values ) ) {
			$list_values = array_filter( $list_values );
		}

		// Prepare list values for replacement.
		if ( $list_field->enableColumns ) {

			// Get target list column.
			$target_column = $field->livePopulation['replace']['choicesColumn'];

			// If we are getting all columns, merge values together.
			if ( 'all' === $target_column ) {

				// Initialize replacement values array.
				$replacement_values = array();

				// Loop through list values.
				foreach ( $list_values as $list_value ) {

					// Remove array keys.
					$list_value = array_values( $list_value );
					$list_value = array_filter( $list_value );

					// Add to replacement values array.
					$replacement_values = array_merge( $replacement_values, $list_value );

				}


			} else {

				// Get column values.
				$replacement_values = wp_list_pluck( $list_values, $target_column );

				// Remove empty values.
				$replacement_values = array_filter( $replacement_values );

			}

		} else {

			// Set the replacement values as the list values.
			$replacement_values = $list_values;

		}

		// If no list value was found, return.
		if ( empty( $replacement_values ) ) {

			// Return field choices based on field type.
			switch ( $field->type ) {

				case 'checkbox':
					$field_choices = $field->get_checkbox_choices( rgar( $entry, $field->id ), '' );
					break;

				case 'radio':
					$field_choices = $field->get_radio_choices( rgar( $entry, $field->id ), '' );
					break;

				default:
					$field_choices = $field->get_choices( rgar( $entry, $field->id ) );
					break;

			}

			// Add choices to populate array.
			$populate[ $field->id ][ 'choices-' . $field->type ] = $field_choices;

			return $populate;

		}

		// If we are replacing field choices, remove existing choices.
		if ( 'replace' === $field->livePopulation['replace']['choicesType'] ) {
			$field->choices = array();
		}

		// Add each list value as choice.
		foreach ( $replacement_values as $replacement_value ) {

			// Trim replacement value.
			$replacement_value = trim( $replacement_value );

			// If replacement value is empty, skip it.
			if ( empty( $replacement_value ) ) {
				continue;
			}

			$field->choices[] = array(
				'text'       => sanitize_text_field( $replacement_value ),
				'value'      => sanitize_text_field( $replacement_value ),
				'isSelected' => false,
			);

		}

		// Return field choices based on field type.
		switch ( $field->type ) {

			case 'checkbox':
				$field_choices = $field->get_checkbox_choices( rgar( $entry, $field->id ), '' );
				break;

			case 'radio':
				$field_choices = $field->get_radio_choices( rgar( $entry, $field->id ), '' );
				break;

			default:
				$field_choices = $field->get_choices( rgar( $entry, $field->id ) );
				break;

		}

		// Add choices to populate array.
		$populate[ $field->id ][ 'choices-' . $field->type ] = $field_choices;

		return $populate;

	}

	/**
	 * Prepare replacement field content for Live Population.
	 *
	 * @string 1.1
	 * @access public
	 *
	 * @param array          $populate Live Population replacements.
	 * @param \GF_Field_HTML $field    The field being populated.
	 * @param array          $form     The current form object.
	 * @param array          $entry    The current entry object.
	 *
	 * @uses   GFCommon::replace_variables()
	 *
	 * @return array
	 */
	public function live_populate_content( $populate, $field, $form, $entry ) {

		// Get field content.
		$field_content = $field->content;

		// Process shortcodes.
		$field_content = do_shortcode( $field_content );

		// Replace variables in field content.
		$field_content = GFCommon::replace_variables( $field_content, $form, $entry, false, true, true, 'html' );
		$field_content = GFCommon::replace_variables( $field_content, $form, $entry, false, true, true, 'html' );

		// Add field content to populate array.
		$populate[ $field->id ]['content'] = trim( $field_content );

		return $populate;

	}

	/**
	 * Prepare replacement field description for Live Population.
	 *
	 * @string 1.1
	 * @access public
	 *
	 * @param array     $populate Live Population replacements.
	 * @param \GF_Field $field    The field being populated.
	 * @param array     $form     The current form object.
	 * @param array     $entry    The current entry object.
	 *
	 * @uses   GFCommon::replace_variables()
	 *
	 * @return array
	 */
	public function live_populate_description( $populate, $field, $form, $entry ) {

		// Get field description.
		$field_description = $field->description;

		// Process shortcodes.
		$field_description = do_shortcode( $field_description );

		// Replace variables in field description.
		$field_description = GFCommon::replace_variables( $field_description, $form, $entry, false, true, true, 'html' );

		// Add field description to populate array.
		$populate[ $field->id ]['description'] = trim( $field_description );

		return $populate;

	}

	/**
	 * Prepare replacement field label for Live Population.
	 *
	 * @string 1.1
	 * @access public
	 *
	 * @param array     $populate Live Population replacements.
	 * @param \GF_Field $field    The field being populated.
	 * @param array     $form     The current form object.
	 * @param array     $entry    The current entry object.
	 *
	 * @uses   GFCommon::replace_variables()
	 *
	 * @return array
	 */
	public function live_populate_label( $populate, $field, $form, $entry ) {

		// Get field inputs.
		$inputs = $field->inputs;

		// Get field label.
		$field_label = $field->label;

		// Replace variables in field label.
		$field_label = GFCommon::replace_variables( $field_label, $form, $entry, false, true, false, 'text' );

		// Add field label to populate array.
		if ( 'section' === $field->type ) {
			$populate[ $field->id ]['section-label'] = trim( $field_label );
		} else {
			$populate[ $field->id ]['label'] = trim( $field_label );
		}

		// Add sub-labels.
		if ( ! empty( $inputs ) ) {

			// Loop through inputs.
			foreach ( $inputs as $input ) {

				// If input is hidden, skip it.
				if ( rgar( $input, 'isHidden' ) ) {
					continue;
				}

				// Get input ID.
				$input_id = str_replace( '.', '_', $input['id'] );

				// Get input label.
				$input_label = rgar( $input, 'customLabel' ) ? $input['customLabel'] : $input['label'];

				// Replace variables in input label.
				$input_label = GFCommon::replace_variables( $input_label, $form, $entry, false, true, false, 'text' );

				// Add sub-label to populate array.
				$populate[ $field->id ]['sub-label'][ $input_id ] = trim( $input_label );

			}

		}

		// Add List column labels.
		if ( 'list' === $field->type && $field->enableColumns ) {

			// Loop through columns.
			foreach ( $field->choices as $i => $column ) {

				// Get column label.
				$column_label = rgar( $column, 'text' );

				// Replace variables in column label.
				$column_label = GFCommon::replace_variables( $column_label, $form, $entry, false, true, false, 'html' );

				// Add column label to populate array.
				$populate[ $field->id ]['column-label'][ $i ] = trim( $column_label );

			}

		}

		return $populate;

	}

	/**
	 * Prepare replacement field placeholder for Live Population.
	 *
	 * @string 1.1
	 * @access public
	 *
	 * @param array     $populate Live Population replacements.
	 * @param \GF_Field $field    The field being populated.
	 * @param array     $form     The current form object.
	 * @param array     $entry    The current entry object.
	 *
	 * @uses   GFCommon::replace_variables()
	 *
	 * @return array
	 */
	public function live_populate_placeholder( $populate, $field, $form, $entry ) {

		// Add drop down placeholder.
		if ( 'select' === $field->type ) {

			// Get field placeholder.
			$field_placeholder = $field->placeholder;

			// Replace variables in field placeholder.
			$field_placeholder = GFCommon::replace_variables( $field_placeholder, $form, $entry, false, true, false, 'text' );

			// Add field placeholder to populate array.
			$populate[ $field->id ]['placeholder-select'] = trim( $field_placeholder );

			return $populate;

		}

		// Get field inputs.
		$inputs = $field->inputs;

		// Add input placeholders.
		if ( ! empty( $inputs ) ) {

			// Loop through inputs.
			foreach ( $inputs as $input ) {

				// If input is hidden or does not have a placeholder, skip it.
				if ( rgar( $input, 'isHidden' ) || ! rgar( $input, 'placeholder' ) ) {
					continue;
				}

				// Get input ID.
				$input_id = str_replace( '.', '_', $input['id'] );

				// Get input placeholder.
				$input_placeholder = $input['placeholder'];

				// Replace variables in input placeholder.
				$input_placeholder = GFCommon::replace_variables( $input_placeholder, $form, $entry, false, true, false, 'text' );

				// Add sub-label to populate array.
				$populate[ $input_id ]['placeholder'] = trim( $input_placeholder );

			}

		} else {

			// Get field placeholder.
			$field_placeholder = $field->placeholder;

			// Replace variables in field placeholder.
			$field_placeholder = GFCommon::replace_variables( $field_placeholder, $form, $entry, false, true, false, 'text' );

			// Add field placeholder to populate array.
			$populate[ $field->id ]['placeholder'] = trim( $field_placeholder );

		}

		return $populate;

	}

	/**
	 * Prepare replacement field value for Live Population.
	 *
	 * @string 1.1
	 * @access public
	 *
	 * @param array     $populate Live Population replacements.
	 * @param \GF_Field $field    The field being populated.
	 * @param array     $form     The current form object.
	 * @param array     $entry    The current entry object.
	 *
	 * @uses   GFCommon::replace_variables()
	 *
	 * @return array
	 */
	public function live_populate_value( $populate, $field, $form, $entry ) {

		// Get field inputs.
		$inputs = $field->inputs;

		// Add sub-labels.
		if ( ! empty( $inputs ) ) {

			// Loop through inputs.
			foreach ( $inputs as $input ) {

				// If input is hidden, skip it.
				if ( rgar( $input, 'isHidden' ) ) {
					continue;
				}

				// Get input ID.
				$input_id = str_replace( '.', '_', $input['id'] );

				// Get input default value.
				$input_value = rgar( $input, 'defaultValue' );

				// Replace variables in input value.
				$input_value = GFCommon::replace_variables( $input_value, $form, $entry, false, false, false, 'text' );
				$input_value = sanitize_text_field( $input_value );

				// Add input to populate array.
				$populate[ $input_id ]['value'] = trim( $input_value );

			}

		} else {

			// Get default field value.
			$field_value = $field->defaultValue;

			// Replace variables in field value.
			$field_value = GFCommon::replace_variables( $field_value, $form, $entry, false, false, false, 'text' );
			$field_value = sanitize_text_field( $field_value );

			// Add field value to populate array.
			$populate[ $field->id ]['value'] = trim( $field_value );

		}

		return $populate;

	}





	// # POPULATION ON RENDER ------------------------------------------------------------------------------------------

	/**
	 * Prepare form object with Live Populations.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array $form The current form object.
	 *
	 * @uses   GFAPI::get_entry()
	 * @uses   GFCommon::is_entry_detail_edit()
	 * @uses   GFCommon::replace_variables()
	 * @uses   GFFormsModel::get_current_lead()
	 * @uses   Live_Population::live_population_fields()
	 *
	 * @return array
	 */
	public function populate_form_on_render( $form ) {

		// Disable Gravitate Encryption.
		remove_filter( 'gform_save_field_value', 'gds_encryption_gform_save_field_value' );

		// If this form has been populated already, return.
		if ( isset( $form['livePopulation']['populated'] ) ) {
			return $form;
		}

		// Get current entry.
		$entry = GFCommon::is_entry_detail_edit() ? GFAPI::get_entry( rgget( 'lid' ) ) : GFFormsModel::get_current_lead();

		// Loop through form fields.
		foreach ( $form['fields'] as &$field ) {

			// If no Live Population settings exist for this field, skip it.
			if ( ! rgobj( $field, 'livePopulation' ) ) {
				continue;
			}

			// If Live Population is not enabled for this field, skip it.
			if ( ! $field->livePopulation['enable'] ) {
				continue;
			}

			// Initialize replacements array.
			$replacements = array();

			// Loop through Live Population replacement settings.
			foreach ( $field->livePopulation['replace'] as $replacement_type => $enabled ) {

				// If replacement type isn't enabled, skip it.
				if ( ! $enabled ) {
					continue;
				}

				// Add replacement type to replacements array.
				$replacements[] = $replacement_type;

			}

			// Loop through replacements.
			foreach ( $replacements as $replace ) {

				// Populate based on replacement.
				if ( is_callable( array( $this, 'render_populate_' . $replace ) ) ) {
					$field = call_user_func( array( $this, 'render_populate_' . $replace ), $field, $form, $entry );
				}

			}

		}

		// Set populated flag.
		$form['livePopulation']['populated'] = true;

		// Enable Gravitate Encryption.
		if ( function_exists( 'gds_encryption_gform_save_field_value' ) ) {
			add_filter( 'gform_save_field_value', 'gds_encryption_gform_save_field_value', 10, 4 );
		}

		return $form;

	}

	/**
	 * Prepare replacement content for form review page.
	 *
	 * @since  1.2.1
	 * @access public
	 *
	 * @param array $review_page The review page to be created.
	 * @param array $form        The current form object.
	 * @param array $entry       The current entry object.
	 *
	 * @uses   GFCommon::replace_variables()
	 *
	 * @return array
	 */
	public function populate_review_page( $review_page, $form, $entry ) {

		// If review page is not enabled, return.
		if ( ! rgar( $review_page, 'is_enabled' ) ) {
			return $review_page;
		}

		// Replace merge tags in content.
		$review_page['content'] = GFCommon::replace_variables( rgar( $review_page, 'content' ), $form, $entry, false, true, false, 'html' );
		$review_page['content'] = GFCommon::replace_variables( rgar( $review_page, 'content' ), $form, $entry, false, true, false, 'html' );

		return $review_page;

	}

	/**
	 * Prepare replacement field choices for form render.
	 *
	 * @string 1.1
	 * @access public
	 *
	 * @param \GF_Field $field The field being populated.
	 * @param array     $form  The current form object.
	 * @param array     $entry The current entry object.
	 *
	 * @uses   GFCommon::replace_variables()
	 *
	 * @return \GF_Field
	 */
	public function render_populate_choices( $field, $form, $entry ) {

		// Initialize List field variable.
		$list_field = false;

		// Ensure target is an array.
		$field->livePopulation['target'] = is_array( $field->livePopulation['target'] ) ? $field->livePopulation['target'] : array( $field->livePopulation['target'] );

		// Loop through target fields.
		foreach ( $field->livePopulation['target'] as $target_field ) {

			// Get target field.
			$target_field = GFFormsModel::get_field( $form, $target_field );

			// If this is a List field, break.
			if ( 'list' === $target_field->type ) {
				$list_field = $target_field;
				break;
			}

		}

		// If a List field is not the target, replace existing merge tags.
		if ( ! $list_field ) {

			/**
			 * Determine if field choices should be removed if merge tags are used and the choice text or value is empty.
			 *
			 * @since 1.3.5
			 *
			 * @param bool  $suppress_empty_choices If field choices should be removed.
			 * @param array $form                   The current Form object.
			 * @param array $form                   The current Field object.
			 */
			$suppress_empty_choices = gf_apply_filters( array(
				'fg_livepopulation_suppress_empty_choices',
				$form['id'],
				$field->id,
			), true, $form, $field );

			// Loop through field choices.
			foreach ( $field->choices as $i => $choice ) {

				// Check if choice text and value have merge tags.
				$text_has_merge_tag  = GFCommon::has_merge_tag( $choice['text'] );
				$value_has_merge_tag = GFCommon::has_merge_tag( $choice['value'] );

				// Replace merge tags.
				$choice['text']  = trim( GFCommon::replace_variables( $choice['text'], $form, $entry, false, true, false, 'text' ) );
				$choice['value'] = trim( GFCommon::replace_variables( $choice['value'], $form, $entry, false, true, false, 'text' ) );

				// Remove choice if empty.
				if ( $suppress_empty_choices && ( ( $text_has_merge_tag && rgblank( $choice['text'] ) ) || ( $value_has_merge_tag && rgblank( $choice['value'] ) ) ) ) {
					unset( $field->choices[ $i ] );
					continue;
				}

				// Save merge tags.
				$field->choices[ $i ] = $choice;

			}

			// Get field choices string based on field type.
			switch ( $field->type ) {

				case 'checkbox':
					$field_choices = $field->get_checkbox_choices( rgar( $entry, $field->id ), '' );
					break;

				case 'radio':
					$field_choices = $field->get_radio_choices( rgar( $entry, $field->id ), '' );
					break;

				default:
					$field_choices = $field->get_choices( rgar( $entry, $field->id ) );
					break;

			}

			return $field;

		}

		// Get list field value.
		$list_values = rgar( $entry, $list_field->id );
		$list_values = maybe_unserialize( $list_values );
		$list_values = is_array( $list_values ) ? $list_values : explode( ', ', $list_values );

		// Remove empty values.
		if ( is_array( $list_values ) ) {
			$list_values = array_filter( $list_values );
		}

		// If no list value was found, return.
		if ( empty( $list_values ) ) {
			return $field;
		}

		// Prepare list values for replacement.
		if ( $list_field->enableColumns ) {

			// Get target column.
			$target_column = $field->livePopulation['replace']['choicesColumn'];

			// If we are getting all columns, merge values together.
			if ( 'all' === $target_column ) {

				// Initialize replacement values array.
				$replacement_values = array();

				// Loop through list values.
				foreach ( $list_values as $list_value ) {

					// Remove array keys.
					$list_value = array_values( $list_value );
					$list_value = array_filter( $list_value );

					// Add to replacement values array.
					$replacement_values = array_merge( $replacement_values, $list_value );

				}

			} else {

				// Get column values.
				$replacement_values = wp_list_pluck( $list_values, $target_column );

				// Remove empty values.
				$replacement_values = array_filter( $replacement_values );

			}

		} else {

			// Set the replacement values as the list values.
			$replacement_values = $list_values;

		}

		// If no list value was found, return.
		if ( empty( $replacement_values ) ) {
			return $field;
		}

		// If we are replacing field choices, remove existing choices.
		if ( isset( $field->livePopulation['replace']['choicesType'] ) && 'replace' === $field->livePopulation['replace']['choicesType'] ) {

			$field->choices = array();

			// If this is a checkbox field, reset the inputs.
			if ( 'checkbox' === $field->type ) {
				$field->inputs = array();
			}

		}

		// Add each list value as choice.
		foreach ( $replacement_values as $replacement_value ) {

			// Trim replacement value.
			$replacement_value = trim( $replacement_value );

			// If replacement value is empty, skip it.
			if ( empty( $replacement_value ) ) {
				continue;
			}

			$field->choices[] = array(
				'text'       => sanitize_text_field( $replacement_value ),
				'value'      => sanitize_text_field( $replacement_value ),
				'isSelected' => false,
			);

			// Add input.
			if ( 'checkbox' === $field->type ) {

				// Get input ID.
				$input_id = count( $field->inputs );

				// Skip multiple of 10 on checkbox ID.
				if ( 0 === $input_id % 10 ) {
					$input_id++;
				}

				$field->inputs[] = array(
					'id'    => $field->id . '.' . $input_id,
					'name'  => sanitize_text_field( $replacement_value ),
					'label' => '',
				);

			}

		}

		return $field;

	}

	/**
	 * Prepare replacement field content for form render.
	 *
	 * @string 1.1
	 * @access public
	 *
	 * @param \GF_Field $field The field being populated.
	 * @param array     $form  The current form object.
	 * @param array     $entry The current entry object.
	 *
	 * @uses   GFCommon::replace_variables()
	 *
	 * @return \GF_Field
	 */
	public function render_populate_content( $field, $form, $entry ) {

		// Process shortcodes.
		$field->content = do_shortcode( $field->content );

		// Replace variables in field content.
		$field->content = GFCommon::replace_variables( $field->content, $form, $entry, false, true, true, 'html' );
		$field->content = GFCommon::replace_variables( $field->content, $form, $entry, false, true, true, 'html' );

		// Trim field content.
		$field->content = trim( $field->content );

		return $field;

	}

	/**
	 * Prepare replacement field description for form render.
	 *
	 * @string 1.1
	 * @access public
	 *
	 * @param \GF_Field $field The field being populated.
	 * @param array     $form  The current form object.
	 * @param array     $entry The current entry object.
	 *
	 * @uses   GFCommon::replace_variables()
	 *
	 * @return \GF_Field
	 */
	public function render_populate_description( $field, $form, $entry ) {

		// Process shortcodes.
		$field->description = do_shortcode( $field->description );

		// Replace variables in field description.
		$field->description = GFCommon::replace_variables( $field->description, $form, $entry, false, true, true, 'html' );

		// Trim field description.
		$field->description = trim( $field->description );

		return $field;

	}

	/**
	 * Prepare replacement field label for form render.
	 *
	 * @string 1.1
	 * @access public
	 *
	 * @param \GF_Field $field The field being populated.
	 * @param array     $form  The current form object.
	 * @param array     $entry The current entry object.
	 *
	 * @uses   GFCommon::replace_variables()
	 *
	 * @return \GF_Field
	 */
	public function render_populate_label( $field, $form, $entry ) {

		// Get field inputs.
		$inputs = $field->inputs;

		// Replace variables in field label.
		$field->label = GFCommon::replace_variables( $field->label, $form, $entry, false, true, false, 'text' );

		// Trim field label.
		$field->label = trim( $field->label );

		// Add sub-labels.
		if ( ! empty( $inputs ) ) {

			// Loop through inputs.
			foreach ( $field->inputs as &$input ) {

				// If input is hidden, skip it.
				if ( rgar( $input, 'isHidden' ) ) {
					continue;
				}

				// Replace custom label if exists.
				if ( rgar( $input, 'customLabel' ) ) {
					$input['customLabel'] = GFCommon::replace_variables( $input['customLabel'], $form, $entry, false, true, false, 'text' );
					$input['customLabel'] = trim( $input['customLabel'] );
				}

			}

		}

		// Add List column labels.
		if ( 'list' === $field->type && $field->enableColumns ) {

			// Loop through columns.
			foreach ( $field->choices as &$column ) {

				// Replace variables in column label.
				$column['text'] = GFCommon::replace_variables( $column['text'], $form, $entry, false, true, false, 'html' );

				// Trim column label.
				$column['text'] = trim( $column['text'] );

			}

		}

		return $field;

	}

	/**
	 * Prepare replacement field placeholder for form render.
	 *
	 * @string 1.1
	 * @access public
	 *
	 * @param \GF_Field $field The field being populated.
	 * @param array     $form  The current form object.
	 * @param array     $entry The current entry object.
	 *
	 * @uses   GFCommon::replace_variables()
	 *
	 * @return \GF_Field
	 */
	public function render_populate_placeholder( $field, $form, $entry ) {

		// Get field inputs.
		$inputs = $field->inputs;

		// Replace variables in field placeholder.
		$field->placeholder = GFCommon::replace_variables( $field->placeholder, $form, $entry, false, true, false, 'text' );

		// Trim field placeholder.
		$field->placeholder = trim( $field->placeholder );

		// Add input placeholders.
		if ( ! empty( $inputs ) ) {

			// Loop through inputs.
			foreach ( $field->inputs as &$input ) {

				// If input is hidden or input does not have a placeholder, skip it.
				if ( rgar( $input, 'isHidden' ) || ! rgar( $input, 'placeholder' ) ) {
					continue;
				}

				// Replace placeholder.
				$input['placeholder'] = GFCommon::replace_variables( $input['placeholder'], $form, $entry, false, true, false, 'text' );
				$input['placeholder'] = trim( $input['placeholder'] );

			}

		}

		return $field;

	}

	/**
	 * Prepare replacement field value for form render.
	 *
	 * @string 1.1
	 * @access public
	 *
	 * @param \GF_Field $field The field being populated.
	 * @param array     $form  The current form object.
	 * @param array     $entry The current entry object.
	 *
	 * @uses   GFCommon::replace_variables()
	 *
	 * @return \GF_Field
	 */
	public function render_populate_value( $field, $form, $entry ) {

		// Get field inputs.
		$inputs = $field->inputs;

		// Add sub-labels.
		if ( ! empty( $inputs ) ) {

			// Loop through inputs.
			foreach ( $field->inputs as &$input ) {

				// If input is hidden, skip it.
				if ( rgar( $input, 'isHidden' ) ) {
					continue;
				}

				// Replace default value if exists.
				if ( rgar( $input, 'defaultValue' ) ) {
					$input['defaultValue'] = GFCommon::replace_variables( $input['defaultValue'], $form, $entry, false, false, false, 'text' );
					$input['defaultValue'] = sanitize_text_field( $input['defaultValue'] );
					$input['defaultValue'] = trim( $input['defaultValue'] );
				}

			}

		} else {

			// Replace variables in field value.
			$field->defaultValue = GFCommon::replace_variables( $field->defaultValue, $form, $entry, false, false, false, 'text' );
			$field->defaultValue = sanitize_text_field( $field->defaultValue );

			// Trim default value.
			$field->defaultValue = trim( $field->defaultValue );

		}

		return $field;

	}





	// # HELPERS -------------------------------------------------------------------------------------------------------

	/**
	 * Get form fields requiring Live Population.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array $form Form object.
	 *
	 * @uses   GFFormsModel::get_field()
	 *
	 * @return array|bool
	 */
	public function live_population_fields( $form ) {

		// If form is not an array, exit.
		if ( ! is_array( $form ) ) {
			return false;
		}

		// Initialize fields array.
		$fields = array();

		// Loop through form fields.
		foreach ( $form['fields'] as $field ) {

			// If Live Population object does not exist for this field, skip it.
			if ( ! isset( $field->livePopulation ) ) {
				continue;
			}

			// If Live Population is not enabled for this field, skip it.
			if ( ! rgar( $field->livePopulation, 'enable' ) || ! rgar( $field->livePopulation, 'replace' ) || ! rgar( $field->livePopulation, 'target' ) ) {
				continue;
			}

			// Initialize replacements array.
			$replacements = array();

			// Loop through Live Population replacements.
			foreach ( $field->livePopulation['replace'] as $replace => $enabled ) {

				// If this replacement is not enabled, skip it.
				if ( ! $enabled || in_array( $replace, array( 'choicesType', 'choicesColumn' ) ) ) {
					continue;
				}

				// Add replacement to replacements array.
				$replacements[] = $replace;

			}

			// If no replacements are enabled, skip this field.
			if ( empty( $replacements ) ) {
				continue;
			}

			// Get targets.
			$targets = is_array( $field->livePopulation['target'] ) ? $field->livePopulation['target'] : array( $field->livePopulation['target'] );

			// Loop through targets.
			foreach ( $targets as $target_id ) {

				// Get target field.
				$target = GFFormsModel::get_field( $form, $target_id );

				// If target field does not exist, skip it.
				if ( ! $target ) {
					continue;
				}

				// Get target field inputs.
				$target_inputs = $target->inputs;

				// If target field as inputs, bind events to the inputs.
				if ( ! empty( $target_inputs ) ) {

					// Loop through target inputs.
					foreach ( $target_inputs as $input ) {

						// If input is hidden, skip it.
						if ( rgar( $input, 'isHidden' ) ) {
							continue;
						}

						// Get input ID.
						$input_id = $input['id'];

						// Add field to the fields array.
						$fields[ $input_id ][ $field->id ] = $replacements;

					}

				} else {

					// Add field to the fields array.
					$fields[ $target->id ][ $field->id ] = $replacements;

				}

			}

		}

		return $fields;

	}

	/**
	 * Get form fields to exclude from Live Population AJAX request.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array $form Form object.
	 *
	 * @return array|bool
	 */
	public function exclude_from_request( $form ) {

		// If form is not an array, exit.
		if ( ! is_array( $form ) ) {
			return false;
		}

		// Initialize fields array.
		$fields = array();

		// Loop through form fields.
		foreach ( $form['fields'] as $field ) {

			// If this is not an excluded field type, skip it.
			if ( 'creditcard' !== $field->type ) {
				continue;
			}

			// Get field inputs.
			$inputs = $field->inputs;

			// If field has inputs, add them to the field array.
			if ( ! empty( $inputs ) ) {

				// Loop through inputs.
				foreach ( $inputs as $input ) {

					// Add input to fields array.
					$fields[] = $input['id'];

				}

			}

			// Add field to fields array.
			$fields[] = $field->id;

		}

		return $fields;

	}

	/**
	 * Get IDs of all list form fields.
	 *
	 * @since  1.1
	 * @access public
	 *
	 * @param array $form Form object.
	 *
	 * @return array
	 */
	public function get_list_field_ids( $form ) {

		// Initialize field IDs array.
		$field_ids = array();

		// Get list fields.
		$list_fields = GFAPI::get_fields_by_type( $form, 'list' );

		// If no list fields exist, return.
		if ( empty( $list_fields ) ) {
			return array();
		}

		// Loop through list fields.
		foreach ( $list_fields as $list_field ) {

			// Add field ID to array.
			$field_ids[] = $list_field->id;

		}

		return $field_ids;

	}

	/**
	 * Get the form ID from Live Population AJAX request.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return int|false
	 */
	public function get_request_form_id() {

		// Get request array keys.
		$keys = array_keys( $_POST );

		// Loop through array keys.
		foreach ( $keys as $key ) {

			// If key does not start with "is_submit", skip it.
			if ( 0 !== strpos( $key, 'is_submit' ) ) {
				continue;
			}

			// Get the form ID.
			$form_id = str_replace( 'is_submit_', '', $key );

			return $form_id;

		}

		return false;

	}





	// # PLUGIN SETTINGS -----------------------------------------------------------------------------------------------

	/**
	 * Prepare plugin settings fields.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return array
	 */
	public function plugin_settings_fields() {

		return array(
			array(
				'fields' => array(
					array(
						'name'                => 'license_key',
						'label'               => esc_html__( 'License Key', 'forgravity_livepopulation' ),
						'type'                => 'text',
						'class'               => 'medium',
						'default_value'       => '',
						'error_message'       => esc_html__( 'Invalid License', 'forgravity_livepopulation' ),
						'feedback_callback'   => array( $this, 'license_feedback' ),
						'validation_callback' => array( $this, 'license_validation' ),
					),
					array(
						'name'          => 'background_updates',
						'label'         => esc_html__( 'Background Updates', 'forgravity_livepopulation' ),
						'type'          => 'radio',
						'horizontal'    => true,
						'default_value' => true,
						'choices'       => array(
							array(
								'label' => esc_html__( 'On', 'forgravity_livepopulation' ),
								'value' => true,
							),
							array(
								'label' => esc_html__( 'Off', 'forgravity_livepopulation' ),
								'value' => false,
							),
						),
					),
				),
			),
		);

	}

	/**
	 * Get license validity for plugin settings field.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param string $value Plugin setting value.
	 * @param array  $field Plugin setting field.
	 *
	 * @uses   Live_Population::check_license()
	 *
	 * @return null|bool
	 */
	public function license_feedback( $value, $field ) {

		// If no license key is provided, return.
		if ( empty( $value ) ) {
			return null;
		}

		// Get license data.
		$license_data = $this->check_license( $value );

		// If no license data was returned or license is invalid, return false.
		if ( empty( $license_data ) || 'invalid' === $license_data->license ) {
			return false;
		} else if ( 'valid' === $license_data->license ) {
			return true;
		}

		return false;

	}

	/**
	 * Activate license on plugin settings save.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array  $field         Plugin setting field.
	 * @param string $field_setting Plugin setting value.
	 *
	 *
	 * @uses   GFAddOn::get_plugin_setting()
	 * @uses   GFAddOn::log_debug()
	 * @uses   Live_Population::activate_license()
	 * @uses   Live_Population::process_license_request()
	 */
	public function license_validation( $field, $field_setting ) {

		// Get old license.
		$old_license = $this->get_plugin_setting( 'license_key' );

		// If an old license key exists and a new license is being saved, deactivate old license.
		if ( $old_license && $field_setting != $old_license ) {

			// Deactivate license.
			$deactivate_license = $this->process_license_request( 'deactivate_license', $old_license );

			// Log response.
			$this->log_debug( __METHOD__ . '(): Deactivate license: ' . print_r( $deactivate_license, true ) );

		}

		// If field setting is empty, return.
		if ( empty( $field_setting ) ) {
			return;
		}

		// Activate license.
		$this->activate_license( $field_setting );

	}





	// # LICENSE METHODS -----------------------------------------------------------------------------------------------

	/**
	 * Activate a license key.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param string $license_key The license key.
	 *
	 * @uses   Live_Population::process_license_request()
	 *
	 * @return array
	 */
	public function activate_license( $license_key ) {

		// Activate license.
		$license = $this->process_license_request( 'activate_license', $license_key );

		// Clear update plugins transient.
		set_site_transient( 'update_plugins', null );

		// Delete plugin version info cache.
		$cache_key = md5( 'edd_plugin_' . sanitize_key( $this->_path ) . '_version_info' );
		delete_transient( $cache_key );

		return json_decode( wp_remote_retrieve_body( $license ) );

	}

	/**
	 * Check the status of a license key.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param string $license_key The license key.
	 *
	 * @uses   GFAddOn::get_plugin_setting()
	 * @uses   Live_Population::process_license_request()
	 *
	 * @return object
	 */
	public function check_license( $license_key = '' ) {

		// If license key is empty, get the plugin setting.
		if ( empty( $license_key ) ) {
			$license_key = $this->get_plugin_setting( 'license_key' );
		}

		// Perform a license check request.
		$license = $this->process_license_request( 'check_license', $license_key );

		return json_decode( wp_remote_retrieve_body( $license ) );

	}

	/**
	 * Process a request to the ForGravity store.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param string $action    The action to process.
	 * @param string $license   The license key.
	 * @param string $item_name The EDD item name.
	 *
	 * @return array|\WP_Error
	 */
	public function process_license_request( $action, $license, $item_name = FG_LIVEPOPULATION_EDD_ITEM_NAME ) {

		// Prepare the request arguments.
		$args = array(
			'method'    => 'POST',
			'timeout'   => 10,
			'sslverify' => false,
			'body'      => array(
				'edd_action' => $action,
				'license'    => trim( $license ),
				'item_name'  => urlencode( $item_name ),
				'url'        => home_url(),
			),
		);

		return wp_remote_request( FG_EDD_STORE_URL, $args );

	}





	// # BACKGROUND UPDATES --------------------------------------------------------------------------------------------

	/**
	 * Display activate license message on Plugins list page.
	 *
	 * @since 1.3.5
	 * @acces public
	 *
	 * @uses  GFAddOn::display_plugin_message()
	 * @uses  GFAddOn::get_plugin_setting()
	 * @uses  Live_Populaton::check_license()
	 */
	public function plugin_row() {

		parent::plugin_row();

		// Get license key.
		$license_key = $this->get_plugin_setting( 'license_key' );

		// If no license key is installed, display message.
		if ( rgblank( $license_key ) ) {

			// Prepare message.
			$message = sprintf(
				esc_html__( '%sRegister your copy%s of Live Population to receive access to automatic upgrades and support. Need a license key? %sPurchase one now.%s', 'forgravity_livepopulation' ),
				'<a href="' . admin_url( 'admin.php?page=gf_settings&subview=' . $this->_slug ) . '">',
				'</a>',
				'<a href="' . esc_url( $this->_url ) . '" target="_blank">',
				'</a>'
			);

			// Add activate license message.
			self::display_plugin_message( $message );

			return;

		}

		// Get license data.
		$license_data = $this->check_license( $license_key );

		// If license key is invalid, display message.
		if ( empty( $license_data ) || 'valid' !== $license_data->license ) {

			// Prepare message.
			$message = sprintf(
				esc_html__( 'Your license is invalid or expired. %sEnter a valid license key%s or %spurchase a new one.%s', 'forgravity_livepopulation' ),
				'<a href="' . admin_url( 'admin.php?page=gf_settings&subview=' . $this->_slug ) . '">',
				'</a>',
				'<a href="' . esc_url( $this->_url ) . '" target="_blank">',
				'</a>'
			);

			// Add invalid license message.
			self::display_plugin_message( $message );

			return;

		}

	}

	/**
	 * Determines if automatic updating should be processed.
	 *
	 * @since  Unknown
	 * @access 1.0
	 *
	 * @param bool   $update Whether or not to update.
	 * @param object $item   The update offer object.
	 *
	 * @uses   GFAddOn::log_debug()
	 * @uses   Live_Population::is_auto_update_disabled()
	 *
	 * @return bool
	 */
	public function maybe_auto_update( $update, $item ) {

		// If this is not the Live Population Add-On, exit.
		if ( ! isset( $item->slug ) || 'livepopulation' !== $item->slug ) {
			return $update;
		}

		// Log that we are starting auto update.
		$this->log_debug( __METHOD__ . '(): Starting auto-update for Live Population.' );

		// Check if automatic updates are disabled.
		$auto_update_disabled = $this->is_auto_update_disabled();

		// Log automatic update disabled state.
		$this->log_debug( __METHOD__ . '(): Automatic update disabled: ' . var_export( $auto_update_disabled, true ) );

		// If automatic updates are disabled or if the installed version is the newest version or earlier, exit.
		if ( $auto_update_disabled || version_compare( $this->_version, $item->new_version, '=>' ) ) {
			$this->log_debug( __METHOD__ . '(): Aborting update.' );

			return false;
		}

		$current_major = implode( '.', array_slice( preg_split( '/[.-]/', $this->_version ), 0, 1 ) );
		$new_major     = implode( '.', array_slice( preg_split( '/[.-]/', $item->new_version ), 0, 1 ) );

		$current_branch = implode( '.', array_slice( preg_split( '/[.-]/', $this->_version ), 0, 2 ) );
		$new_branch     = implode( '.', array_slice( preg_split( '/[.-]/', $item->new_version ), 0, 2 ) );

		if ( $current_major == $new_major && $current_branch == $new_branch ) {
			$this->log_debug( __METHOD__ . '(): OK to update.' );

			return true;
		}

		$this->log_debug( __METHOD__ . '(): Skipping - not current branch.' );

		return $update;

	}

	/**
	 * Determine if automatic updates are disabled.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @uses   GFAddOn::get_plugin_setting()
	 * @uses   GFAddOn::log_debug()
	 *
	 * @return bool
	 */
	public function is_auto_update_disabled() {

		// WordPress background updates are disabled if you do not want file changes.
		if ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ) {
			return true;
		}

		// Do not run auto update during install.
		if ( defined( 'WP_INSTALLING' ) ) {
			return true;
		}

		// Get automatic updater disabled state.
		$wp_updates_disabled = defined( 'AUTOMATIC_UPDATER_DISABLED' ) && AUTOMATIC_UPDATER_DISABLED;
		$wp_updates_disabled = apply_filters( 'automatic_updater_disabled', $wp_updates_disabled );

		// If WordPress automatic updates are disabled, return.
		if ( $wp_updates_disabled ) {
			$this->log_debug( __METHOD__ . '(): WordPress background updates are disabled.' );

			return true;
		}

		// Get background updates plugin setting.
		$enabled = $this->get_plugin_setting( 'background_updates' );

		// Log setting.
		$this->log_debug( __METHOD__ . '(): Background updates setting: ' . var_export( $enabled, true ) );

		return $enabled;

	}

}
