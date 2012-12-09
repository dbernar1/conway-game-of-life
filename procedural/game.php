<?php

$initial_pattern = array( '1, 2', '2, 1', '2, 2' );

$initial_pattern = array( '3, 2', '3, 3', '3, 4' );

$currently_alive_cells = $initial_pattern;

do {
	var_dump( $currently_alive_cells );
	$next_generation = get_cells_that_should_be_alive_in_next_generation( $currently_alive_cells, get_cells_with_alive_neighbors_with_count( $currently_alive_cells ) );
	
	$cells_are_same_in_next_generation_as_in_current_generation =
		0 === count( array_diff( $currently_alive_cells, $next_generation ) ) &&
		0 === count( array_diff( $next_generation, $currently_alive_cells ) );
		
	if ( $cells_are_same_in_next_generation_as_in_current_generation ) {
		echo 'repeat forever';
		break;
	}

	$currently_alive_cells = $next_generation;

} while( ! empty( $currently_alive_cells ) );
