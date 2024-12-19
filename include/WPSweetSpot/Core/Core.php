<?php
/**
 *	@package WPSweetSpot\Core
 *	@version 1.0.1
 *	2018-09-22
 */

namespace WPSweetSpot\Core;

use WPSweetSpot\Asset;

class Core extends Plugin implements CoreInterface {

	/**
	 *	@inheritdoc
	 */
	protected function __construct() {

		add_filter( 'wp_get_attachment_image_attributes', [ $this, 'add_position_style' ], 10, 2 );

		add_action( 'wp_enqueue_scripts', [ $this , 'enqueue_assets' ] );

		Blocks::instance();

		$args = func_get_args();
		parent::__construct( ...$args );
	}

	/**
	 *  @filter wp_get_attachment_image_attributes
	 */
	public function add_position_style( $attributes, $attachment ) {

		$sweet_spot_x = get_post_meta( $attachment->ID,'sweet_spot_x', true );
		$sweet_spot_y = get_post_meta( $attachment->ID,'sweet_spot_y', true );
		if ( '' !== $sweet_spot_x && '' !== $sweet_spot_y ) {
			$attributes = wp_parse_args( $attributes, [
				'style' => '',
			]);
			$attributes['style'] .= sprintf(
				'--sweet-spot-x:%F;--sweet-spot-y:%F;',
				$sweet_spot_x,
				$sweet_spot_y
			);
		}
		return $attributes;
	}

	/**
	 *	@action wp_enqueue_media
	 */
	public function enqueue_assets() {
		Asset\Asset::get('css/images.css')->enqueue();
	}
}
