<?php
/**
 * Plugin name: Caldera Forms Date Experiments
 */

/**
 * If start date is set to a future day, make sure that's not a weeekend, if so, advance to monday
 */
add_filter( 'caldera_forms_field_attributes', function( $attrs, $field, $form ){
	include_once __DIR__ . '/vendor/autoload.php';
	if( 'date_picker' === Caldera_Forms_Field_Util::get_type( $field, $form ) ){

		if(
			//start date exists
			isset( $attrs[ 'data-date-start-date' ] )
			//and stars with a plus
			&& 0 === strpos( $attrs[ 'data-date-start-date' ], '+' )
			//we can get a number of days to add from it
			&& is_numeric( $days = str_replace( [ '+', 'd' ], '', $attrs[ 'data-date-start-date' ] ) )
		){
			//Create time from not + how many days
			$time = \Carbon\Carbon::now();
			$time->addDay( $days );

			if( $time->isSaturday() ){
				//update +Nd with 2 more days on saturday...
				$attrs[ 'data-date-start-date' ] = str_replace( $days, $days + 2, $attrs[ 'data-date-start-date' ] );
			}elseif( $time->isSunday() ) {
				//update +Nd with 1 more days on saturday...
				$attrs[ 'data-date-start-date' ] = str_replace( $days, $days + 1, $attrs[ 'data-date-start-date' ] );

			}


		}
	}
	return $attrs;
}, 20, 3 );

/**
 * Clean up attrs array
 */
add_filter( 'caldera_forms_field_attributes', function( $attrs, $field, $form  ){
	if( 'date_picker' !== Caldera_Forms_Field_Util::get_type( $field, $form ) ){
		return $attrs;
	}
	$final = [];
	foreach ($attrs as $k => $attr ){
		$final[ trim($k) ] = $attr;
	}

	return $final;
}, 1, 3 );