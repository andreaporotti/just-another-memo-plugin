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

<div class="meta-field meta-scope">
	<span><?php _e( 'Seleziona l\'ambito della nota.', 'jamp' ); ?></span>
	<br>
	<label for="scope-global">
		<input type="radio" name="scope" id="scope-global" value="global" <?php if ( isset ( $jamp_meta['scope'] ) ) { checked( $jamp_meta['scope'][0], 'global' ); } ?>>
		<?php _e( 'Globale', 'jamp' ); ?>
	</label>
	<br>
	<label for="scope-section">
		<input type="radio" name="scope" id="scope-section" value="section" <?php if ( isset ( $jamp_meta['scope'] ) ) { checked( $jamp_meta['scope'][0], 'section' ); } ?>>
		<?php _e( 'Sezione', 'jamp' ); ?>
	</label>
	<br>
	<label for="scope-entity">
		<input type="radio" name="scope" id="scope-entity" value="entity" <?php if ( isset ( $jamp_meta['scope'] ) ) { checked( $jamp_meta['scope'][0], 'entity' ); } ?>>
		<?php _e( 'Entità', 'jamp' ); ?>
	</label>
</div>

<div class="meta-field meta-section">
	<label for="section"><?php _e( 'Scegli la Sezione.', 'jamp' )?></label>
    <select name="section" id="section">
		<option value="">seleziona...</option>
		
		<?php foreach ($args['args'] as $section): ?>
		
			<option value="<?php echo $section['file'] ?>" <?php if ( isset ( $jamp_meta['target'] ) ) { selected( $jamp_meta['target'][0], wp_kses_decode_entities ( $section['file'] ) ); } echo(!$section['is_submenu']) ? 'disabled' : ''; ?>>
				<?php echo $section['name']; ?>
			</option>
		
		<?php endforeach; ?>
    </select>
</div>