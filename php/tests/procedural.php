<?php
require_once(dirname(__FILE__) . '/simpletest/autorun.php');

require dirname( __FILE__ ) . '/procedural_implementation.php';

// Two examples per test to avoid loophole implementations

class TestGameOfLife extends UnitTestCase {
	function test_get_neighbors_of_a_cell_returns_the_eight_adjacent_cells_of_a_cell() {
		$examples = array(
			array(
				'cell_coords' => '1, 1',
				'adjacent_cell_coords' => array(
					'0, 0',
					'0, 1',
					'0, 2',
					'1, 0',
					'1, 2',
					'2, 0',
					'2, 1',
					'2, 2',
				),
			),
			array(
				'cell_coords' => '3, 8',
				'adjacent_cell_coords' => array(
					'2, 7',
					'2, 8',
					'2, 9',
					'3, 7',
					'3, 9',
					'4, 7',
					'4, 8',
					'4, 9',
				),
			),
		);

		foreach ( $examples as $example ) {
			$this->assertEqual( get_neighbors_of_a_cell( $example[ 'cell_coords' ] ), $example[ 'adjacent_cell_coords' ] );
		}
	}

	function test_get_cells_with_alive_neighbors_with_count_returns_set_of_neighbors_of_alive_cells_with_number_of_alive_neighbors() {
		$examples = array(
			array(
				'description' => 'Set of neighbors of alive cells for set of alive cells that share no neighbors is just the combined set of all neighbors of all cells',
				'alive_cells' => array(
					'1, 1',
					'3, 8',
				),
				'expected_neighbors_and_counts' => array(
					'0, 0' => 1,
					'0, 1' => 1,
					'0, 2' => 1,
					'1, 0' => 1,
					'1, 2' => 1,
					'2, 0' => 1,
					'2, 1' => 1,
					'2, 2' => 1,
					'2, 7' => 1,
					'2, 8' => 1,
					'2, 9' => 1,
					'3, 7' => 1,
					'3, 9' => 1,
					'4, 7' => 1,
					'4, 8' => 1,
					'4, 9' => 1,
				),
			),
			array(
				'description' => "If alive cells share neighbor, that cell's number of alive neighbors is equal to number of alive cells that have it as a neighbor",
				'grid_drawing' =>
					'
					  | 0 | 1 | 2 | 3 |
					0 |   |   |   |   |
					1 |   |   | x |   |
					2 |   | x | x |   |
					3 |   |   |   |   |
					',
				'alive_cells' => array(
					'1, 2',
					'2, 1',
					'2, 2',
				),
				'expected_neighbors_and_counts' => array(
					'0, 1' => 1,
					'0, 2' => 1,
					'0, 3' => 1,
					'1, 0' => 1,
					'1, 1' => 3,
					'1, 2' => 2,
					'1, 3' => 2,
					'2, 0' => 1,
					'2, 1' => 2,
					'2, 2' => 2,
					'2, 3' => 2,
					'3, 0' => 1,
					'3, 1' => 2,
					'3, 2' => 2,
					'3, 3' => 1,
				),
			),
		);
		foreach ( $examples as $example ) {
			$this->assertEqual( $example[ 'expected_neighbors_and_counts' ], get_cells_with_alive_neighbors_with_count( $example[ 'alive_cells' ] ) );
		}
	}

	function test_get_cells_that_should_be_alive_in_next_generation_returns_alive_cells_with_2_alive_neighbors() {
		$currently_alive_cells = array( '1, 2' );
		$coords_of_alive_cell_with_2_alive_neighbors = '1, 2';

		$cells_with_alive_neighbors_with_count = array( 
			$coords_of_alive_cell_with_2_alive_neighbors => 2,
		);

		$this->assertTrue(
			in_array(
				$coords_of_alive_cell_with_2_alive_neighbors,
				get_cells_that_should_be_alive_in_next_generation( $currently_alive_cells, $cells_with_alive_neighbors_with_count )
			)
		);
	}

	function test_get_cells_that_should_be_alive_in_next_generation_returns_alive_cells_with_3_alive_neighbors() {
		$currently_alive_cells = array( '1, 2' );
		$coords_of_alive_cell_with_3_alive_neighbors = '1, 2';

		$cells_with_alive_neighbors_with_count = array( 
			$coords_of_alive_cell_with_3_alive_neighbors => 3,
		);

		$this->assertTrue(
			in_array(
				$coords_of_alive_cell_with_3_alive_neighbors,
				get_cells_that_should_be_alive_in_next_generation( $currently_alive_cells, $cells_with_alive_neighbors_with_count )
			)
		);
	}

	function test_get_cells_that_should_be_alive_in_next_generation_does_not_return_currently_alive_cells_with_less_than_2_neighbors() {
		$coords_of_alive_cell_with_1_alive_neighbors = '1, 2';
		$currently_alive_cells = array(
			$coords_of_alive_cell_with_1_alive_neighbors,
		);

		$cells_with_alive_neighbors_with_count = array( 
			$coords_of_alive_cell_with_1_alive_neighbors => 1,
		);

		$this->assertFalse(
			in_array(
				$coords_of_alive_cell_with_1_alive_neighbors,
				get_cells_that_should_be_alive_in_next_generation( $currently_alive_cells, $cells_with_alive_neighbors_with_count )
			)
		);
	}

	function test_get_cells_that_should_be_alive_in_next_generation_does_not_return_currently_alive_cells_with_more_than_3_neighbors() {
		$coords_of_alive_cell_with_4_alive_neighbors = '1, 2';
		$currently_alive_cells = array(
			$coords_of_alive_cell_with_4_alive_neighbors,
		);

		$cells_with_alive_neighbors_with_count = array( 
			$coords_of_alive_cell_with_4_alive_neighbors => 4,
		);

		$this->assertFalse(
			in_array(
				$coords_of_alive_cell_with_4_alive_neighbors,
				get_cells_that_should_be_alive_in_next_generation( $currently_alive_cells, $cells_with_alive_neighbors_with_count )
			)
		);
	}

	function test_get_cells_that_should_be_alive_in_next_generation_returns_currently_dead_cells_with_3_neighbors() {
		$coords_of_dead_cell_with_3_alive_neighbors = '1, 2';
		$currently_alive_cells = array();

		$cells_with_alive_neighbors_with_count = array( 
			$coords_of_dead_cell_with_3_alive_neighbors => 3,
		);

		$this->assertTrue(
			in_array(
				$coords_of_dead_cell_with_3_alive_neighbors,
				get_cells_that_should_be_alive_in_next_generation( $currently_alive_cells, $cells_with_alive_neighbors_with_count )
			)
		);
	}

	function test_get_cells_that_should_be_alive_in_next_generation_does_not_return_currently_dead_cells_with_more_or_less_than_3_neighbors() {
		$currently_alive_cells = array();

		$cells_with_alive_neighbors_with_count = array( 
			'1, 2' => 2,
			'2, 2' => 4,
		);

		$cells_that_should_be_alive_in_next_generation = get_cells_that_should_be_alive_in_next_generation( $currently_alive_cells, $cells_with_alive_neighbors_with_count );

		foreach ( array_keys( $cells_with_alive_neighbors_with_count ) as $coords_of_dead_cell_with_more_or_less_than_3_neighbors ) {
			$this->assertFalse(
				in_array(
					$coords_of_dead_cell_with_more_or_less_than_3_neighbors,
					$cells_that_should_be_alive_in_next_generation
				)
			);
		}
	}
}
