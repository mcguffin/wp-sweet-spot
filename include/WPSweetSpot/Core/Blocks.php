<?php
/**
 *	@package WPSweetSpot\Core
 *	@version 1.0.1
 *	2018-09-22
 */

namespace WPSweetSpot\Core;

use WPSweetSpot\Asset;

class Blocks extends Singleton {

	/**
	 *	@inheritdoc
	 */
	protected function __construct() {

		add_filter( 'render_block_core/image', [ $this, 'render_image_block' ], 10, 3 );

		$args = func_get_args();
		parent::__construct( ...$args );
	}

	public function render_image_block( $block_content, $parsed_block, $instance ) {
		if ( $parsed_block['attrs']['id'] /* && $parsed_block['attrs']['aspectRatio'] */ ) {
			$sweet_spot_x = get_post_meta( $parsed_block['attrs']['id'], 'sweet_spot_x', true );
			$sweet_spot_y = get_post_meta( $parsed_block['attrs']['id'], 'sweet_spot_y', true );

			if ( '' !== $sweet_spot_x && '' !== $sweet_spot_y ) {
				$vars = sprintf(
					'--sweet-spot-x:%F;--sweet-spot-y:%F;',
					$sweet_spot_x,
					$sweet_spot_y
				);
				if ( strpos( $block_content, 'style=' ) !== false ) {
					$block_content = str_replace('style="', 'style="'.$vars, $block_content );
				} else {
					$block_content = str_replace('<img', '<img style="'.$vars .'"', $block_content );
				}
			}
		}
		return $block_content;
	}

}
