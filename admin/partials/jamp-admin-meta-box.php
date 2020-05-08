<?php
/**
 * Provides HTML code for the meta box
 *
 * @link       https://www.andreaporotti.it
 * @since      1.0.0
 *
 * @package    Jamp
 * @subpackage Jamp/admin/partials
 */

?>

<?php
// Cretes nonce field.
wp_nonce_field( 'jamp_meta_box_nonce_secret_action', 'jamp-meta-box-nonce' );

$screen = get_current_screen();

// Build array for the meta box fields.
if ( 'add' === $screen->action ) { // Creating a new note.

	// Extract parameters from querystring.
	$current_url = self::get_current_page_url();
	$querystring = wp_parse_url( $current_url, PHP_URL_QUERY );
	parse_str( $querystring, $params );

	$jamp_meta                     = array();
	$jamp_meta['jamp_scope']       = array( ( isset( $params['jamp_scope'] ) ) ? $params['jamp_scope'] : '' );
	$jamp_meta['jamp_target_type'] = array( ( isset( $params['jamp_target_type'] ) ) ? $params['jamp_target_type'] : '' );
	$jamp_meta['jamp_target']      = array( ( isset( $params['jamp_target'] ) ) ? $params['jamp_target'] : '' );

	// The scope must be set. If it's empty or wrong, let's set it to "global".
	if ( ! in_array( $jamp_meta['jamp_scope'][0], array( 'global', 'section', 'entity' ), true ) ) {
		$jamp_meta['jamp_scope'] = array( 'global' );
	}
} else { // Editing a note.

	// Gets post's meta data.
	$jamp_meta = get_post_meta( $post->ID );

}
?>

<input type="hidden" id="saved-target" value="<?php echo ( isset( $jamp_meta['jamp_target'] ) ) ? esc_attr( $jamp_meta['jamp_target'][0] ) : ''; ?>">

<div class="meta-field meta-scope no-margin-top">
	<span><?php esc_html_e( 'Select the Note scope.', 'jamp' ); ?></span>
	<br>
	<label for="scope-global">
		<input type="radio" name="scope" id="scope-global" value="global" <?php ( isset( $jamp_meta['jamp_scope'] ) ) ? checked( $jamp_meta['jamp_scope'][0], 'global' ) : ''; ?>>
		<?php esc_html_e( 'Global', 'jamp' ); ?>
	</label>
	<br>
	<label for="scope-section">
		<input type="radio" name="scope" id="scope-section" value="section" <?php ( isset( $jamp_meta['jamp_scope'] ) ) ? checked( $jamp_meta['jamp_scope'][0], 'section' ) : ''; ?>>
		<?php esc_html_e( 'Section', 'jamp' ); ?>
	</label>
	<br>
	<label for="scope-entity">
		<input type="radio" name="scope" id="scope-entity" value="entity" <?php ( isset( $jamp_meta['jamp_scope'] ) ) ? checked( $jamp_meta['jamp_scope'][0], 'entity' ) : ''; ?>>
		<?php esc_html_e( 'Item', 'jamp' ); ?>
	</label>
</div>

<div class="meta-field meta-section">
	<label for="section" class="display-block"><?php esc_html_e( 'Select the Section.', 'jamp' ); ?></label>
	<select name="section" id="section">
		<option value=""><?php esc_html_e( 'select...', 'jamp' ); ?></option>

		<?php foreach ( $args['args']['sections'] as $section ) : ?>

			<option value="<?php echo esc_attr( $section['url'] ); ?>" <?php ( isset( $jamp_meta['jamp_target'] ) ) ? selected( $jamp_meta['jamp_target'][0], $section['url'] ) : ''; ?> <?php echo( ! $section['is_submenu'] ) ? 'disabled' : ''; ?>>
				<?php echo esc_html( $section['name'] ); ?>
			</option>

		<?php endforeach; ?>
	</select>
</div>

<div class="meta-field meta-target-type">
	<label for="target-type" class="display-block"><?php esc_html_e( 'Select the item type.', 'jamp' ); ?></label>
	<select name="target-type" id="target-type">
		<option value=""><?php esc_html_e( 'select...', 'jamp' ); ?></option>

		<?php foreach ( $args['args']['target_types'] as $target_type ) : ?>

			<option value="<?php echo esc_attr( $target_type['name'] ); ?>" <?php ( isset( $jamp_meta['jamp_target_type'] ) ) ? selected( $jamp_meta['jamp_target_type'][0], $target_type['name'] ) : ''; ?>>
				<?php echo esc_html( $target_type['label'] ); ?>
			</option>

		<?php endforeach; ?>
	</select>
</div>

<div class="meta-field meta-target">
	<label for="target" class="display-block"><?php esc_html_e( 'Select the item.', 'jamp' ); ?></label>
	<select name="target" id="target">
		<option value=""><?php esc_html_e( 'select...', 'jamp' ); ?></option>
	</select>
</div>
