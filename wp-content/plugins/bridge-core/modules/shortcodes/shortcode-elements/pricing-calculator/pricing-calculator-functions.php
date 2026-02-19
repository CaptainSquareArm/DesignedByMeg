<?php

if(!function_exists('bridge_core_check_is_pricing_calculator_item_checked')) {
	function bridge_core_check_is_pricing_calculator_item_checked($value) {

		if($value === 'yes') {
			return 'checked';
		}

		return '';
	}
}

if(!function_exists('bridge_core_add_pricing_calculator_shortcodes')) {
	function bridge_core_add_pricing_calculator_shortcodes($shortcodes_class_name) {
		$shortcodes = array(
			'Bridge\Shortcodes\PricingCalculator\PricingCalculator'
		);

		$shortcodes_class_name = array_merge($shortcodes_class_name, $shortcodes);

		return $shortcodes_class_name;
	}

	add_filter('bridge_core_filter_add_vc_shortcode', 'bridge_core_add_pricing_calculator_shortcodes');
}

if( ! function_exists('bridge_core_pricing_calculator_wpml_wpb_decode_urlencoded_json') ) {
	function bridge_core_pricing_calculator_wpml_wpb_decode_urlencoded_json( $string, $encoding, $original_string ) {
		if ( 'urlencoded_json' === $encoding ) {
			$rows = json_decode( urldecode( $original_string ), true );
			$string = array();
			foreach ( $rows as $i => $row ) {
				foreach ( $row as $key => $value ) {
					if ( in_array( $key, array(
						'item_title',
						'item_price',
					) ) ) {
						$string[ $key . '_' . $i ] = array( 'value' => $value, 'translate' => true );
					} else {
						$string[ $key . '_' . $i ] = array( 'value' => $value, 'translate' => false );
					}
				}
			}
		}

		return $string;
	}

	add_filter( 'wpml_pb_shortcode_decode', 'bridge_core_pricing_calculator_wpml_wpb_decode_urlencoded_json', 10, 3 );
}
