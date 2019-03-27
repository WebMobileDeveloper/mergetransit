<?php
	/**
	 * The term template.
	 *
	 * [Last update: 2018-06-25]
	 */


//Whether the term is defined.
if ( isset( $term ) ) {


	//Load the link.
	$a = new A (

		$term->name,

		get_term_link (

			$term

		)

	);


	//Set up the class attribute.
	$a->set_class (

		'term h6' . ( get_queried_object()->term_id == $term->term_id ? ' current' : '' )

	);


	//Set up the title attribute.
	$a->set_title (

		$term->name

	);


	//Display the term link.
	$a->display();


}