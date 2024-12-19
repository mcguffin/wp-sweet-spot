<?php
/**
 *	@package WPSweetSpot\Core
 *	@version 1.0.1
 *	2018-09-22
 */

namespace WPSweetSpot\Admin;

use WPSweetSpot\Asset;
use WPSweetSpot\Core;

class Attachment extends Core\Singleton {
	/**
	 *	@inheritdoc
	 */
	protected function __construct() {

		add_filter( 'attachment_fields_to_edit', [ $this, 'attachment_fields' ], 10, 2 );
		add_filter( 'attachment_fields_to_save', [ $this, 'attachment_save' ], 10, 2 );

		add_action( 'wp_enqueue_media', [ $this , 'enqueue_assets' ] );

		$args = func_get_args();
		parent::__construct( ...$args );
	}

	/**
	 *	@filter attachment_fields_to_edit
	 */
	public function attachment_fields( $fields, $post ) {

		if ( 0 !== strpos( $post->post_mime_type, 'image/' ) ) {
			return $fields;
		}

		$range = '<input type="range" name="%1$s" value="%2$f" min="0" max="1" step="0.01" data-sweetspot-direction="%3$s" />';
		$sweet_spot_x = get_post_meta( $post->ID,'sweet_spot_x', true );
		$sweet_spot_y = get_post_meta( $post->ID,'sweet_spot_y', true );
		if ( '' === $sweet_spot_x ) {
			$sweet_spot_x = 0.5;
		}
		if ( '' === $sweet_spot_y ) {
			$sweet_spot_y = 0.5;
		}
		$fields['sweet_spot_x'] = [
			'label' => __('Sweet Spot X','wp-sweet-spot'),
			'input' => 'html',
			'html'  => sprintf(
				$range,
				'sweet_spot_x',
				$sweet_spot_x,
				'x'
			),
		];
		$fields['sweet_spot_y'] = [
			'label' => __('Sweet Spot Y','wp-sweet-spot'),
			'input' => 'html',
			'html'  => sprintf(
				$range,
				'sweet_spot_y',
				$sweet_spot_y,
				'y'
			),
		];
		return $fields;
	}

	/**
	 *	@filter attachment_fields_to_save
	 */
	public function attachment_save( $post, $attachment ) {
		// nonce?
		foreach ( ['sweet_spot_x', 'sweet_spot_y'] as $field ) {
			if ( isset( $_POST[$field] ) ) {
				if ( ! isset( $post['meta_input'] ) ) {
					$post['meta_input'] = [];
				}
				$post['meta_input'][$field] = (float) wp_unslash( $_POST[$field] );
			}
		}
		return $post;
	}

	/**
	 *	@action wp_enqueue_media
	 */
	public function enqueue_assets() {
		Asset\Asset::get('css/admin/attachment.css')->enqueue();
		Asset\Asset::get('js/admin/attachment.js')->deps('media-grid')->enqueue();
	}
}
