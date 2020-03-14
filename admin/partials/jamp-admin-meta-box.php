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
	wp_nonce_field( 'jamp_meta_box_nonce', 'jamp_meta_box_nonce' );
	
	// Gets post's meta data
	$jamp_meta = get_post_meta( $post->ID );
?>

<div class="meta-field meta-scope no-margin-top">
	<span><?php _e( 'Seleziona l\'ambito della nota.', 'jamp' ); ?></span>
	<br>
	<label for="scope-global">
		<input type="radio" name="scope" id="scope-global" value="global" <?php if ( isset ( $jamp_meta['jamp_scope'] ) ) { checked( $jamp_meta['jamp_scope'][0], 'global' ); } ?>>
		<?php _e( 'Globale', 'jamp' ); ?>
	</label>
	<br>
	<label for="scope-section">
		<input type="radio" name="scope" id="scope-section" value="section" <?php if ( isset ( $jamp_meta['jamp_scope'] ) ) { checked( $jamp_meta['jamp_scope'][0], 'section' ); } ?>>
		<?php _e( 'Sezione', 'jamp' ); ?>
	</label>
	<br>
	<label for="scope-entity">
		<input type="radio" name="scope" id="scope-entity" value="entity" <?php if ( isset ( $jamp_meta['jamp_scope'] ) ) { checked( $jamp_meta['jamp_scope'][0], 'entity' ); } ?>>
		<?php _e( 'Entità', 'jamp' ); ?>
	</label>
</div>

<div class="meta-field meta-section">
	<label for="section" class="display-block"><?php _e( 'Scegli la Sezione.', 'jamp' )?></label>
    <select name="section" id="section">
		<option value=""><?php _e( 'seleziona...' ); ?></option>
		
		<?php foreach ($args['args']['sections'] as $section): ?>
		
			<option value="<?php echo $section['file'] ?>" <?php if ( isset ( $jamp_meta['jamp_target'] ) ) { selected( $jamp_meta['jamp_target'][0], $section['file'] ); } echo(!$section['is_submenu']) ? 'disabled' : ''; ?>>
				<?php echo $section['name']; ?>
			</option>
		
		<?php endforeach; ?>
    </select>
</div>

<div class="meta-field meta-target-type">
	<label for="target-type" class="display-block"><?php _e( 'Scegli il tipo di Entità.', 'jamp' )?></label>
	<select name="target-type" id="target-type">
		<option value=""><?php _e( 'seleziona...' ); ?></option>
		
		<?php foreach ($args['args']['target_types'] as $target_type): ?>
		
			<option value="<?php echo $target_type['name']; ?>" <?php if ( isset ( $jamp_meta['jamp_target_type'] ) ) { selected( $jamp_meta['jamp_target_type'][0], $target_type['name'] ); } ?>>
				<?php echo $target_type['label']; ?>
			</option>
		
		<?php endforeach; ?>
    </select>
</div>

<div class="meta-field meta-target">
	<label for="target" class="display-block"><?php _e( 'Scegli l\'Entità.', 'jamp' )?></label>
	<select name="target" id="target">
		<option value=""><?php _e( 'seleziona...' ); ?></option>
	</select>
</div>