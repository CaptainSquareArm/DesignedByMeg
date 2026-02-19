<?php

if(!function_exists('bridge_core_add_report_sheet_shortcodes')) {
	function bridge_core_add_report_sheet_shortcodes($shortcodes_class_name) {
		$shortcodes = array(
			'Bridge\Shortcodes\ReportSheet\ReportSheet'
		);
		
		$shortcodes_class_name = array_merge($shortcodes_class_name, $shortcodes);
		
		return $shortcodes_class_name;
	}
	
	add_filter('bridge_core_filter_add_vc_shortcode', 'bridge_core_add_report_sheet_shortcodes');
}

if( ! function_exists('bridge_core_report_sheet_wpml_wpb_decode_urlencoded_json') ) {
	function bridge_core_report_sheet_wpml_wpb_decode_urlencoded_json( $string, $encoding, $original_string ) {
		if ( 'urlencoded_json' === $encoding ) {
			$rows = json_decode( urldecode( $original_string ), true );
			$string = array();
			foreach ( $rows as $i => $row ) {
				foreach ( $row as $key => $value ) {
					if ( in_array( $key, array(
						'column_one_text',
						'column_one_sub_text',
						'column_two_text',
						'column_two_sub_text',
						'column_three_text',
						'column_three_sub_text',
						'column_four_text',
						'column_four_sub_text',
						'column_five_text',
						'column_five_sub_text',
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

	add_filter( 'wpml_pb_shortcode_decode', 'bridge_core_report_sheet_wpml_wpb_decode_urlencoded_json', 10, 3 );
}
