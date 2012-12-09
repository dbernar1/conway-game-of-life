<?php

function get_cells_that_should_be_alive_in_next_generation( $currently_alive_cells, $cells_with_alive_neighbors_with_count ) {
	$cells_that_should_be_alive_in_next_generation = array();

	foreach ( $cells_with_alive_neighbors_with_count as $cell => $num_alive_neighbors ) {
		$cell_is_currently_alive = in_array( $cell, $currently_alive_cells );
		if ( $cell_is_currently_alive ) {
			if ( 2 === $num_alive_neighbors || 3 === $num_alive_neighbors ) {
				$cells_that_should_be_alive_in_next_generation[] = $cell;
			}
		} else {
			if ( 3 === $num_alive_neighbors ) {
				$cells_that_should_be_alive_in_next_generation[] = $cell;
			}
		}
	}

	return $cells_that_should_be_alive_in_next_generation;
}

function get_cells_with_alive_neighbors_with_count( $alive_cells ) {
	$cells_with_alive_neighbors_with_count = array();

	foreach ( $alive_cells as $cell_coords ) {
		foreach( get_neighbors_of_a_cell( $cell_coords ) as $coords_of_neighbor_of_alive_cell ) {
			if ( ! isset( $cells_with_alive_neighbors_with_count[ $coords_of_neighbor_of_alive_cell ] ) ) {
				$cells_with_alive_neighbors_with_count[ $coords_of_neighbor_of_alive_cell ] = 0;
			}
			$cells_with_alive_neighbors_with_count[ $coords_of_neighbor_of_alive_cell ] += 1;
		}
	}

	return $cells_with_alive_neighbors_with_count;
}

function get_neighbors_of_a_cell( $cell_coords ) {
	list( $x_coord, $y_coord ) = explode( ', ', $cell_coords );

	return array(
		($x_coord - 1 ) . ', ' . ( $y_coord - 1 ),
		($x_coord - 1 ) . ', ' . ( $y_coord ),
		($x_coord - 1 ) . ', ' . ( $y_coord + 1 ),
		($x_coord ) . ', ' . ( $y_coord - 1 ),
		($x_coord ) . ', ' . ( $y_coord + 1 ),
		($x_coord + 1 ) . ', ' . ( $y_coord - 1 ),
		($x_coord + 1 ) . ', ' . ( $y_coord ),
		($x_coord + 1 ) . ', ' . ( $y_coord + 1 ),
	);
}
