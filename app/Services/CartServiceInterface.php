<?php

namespace App\Services;

interface CartServiceInterface{
	const KEY = 'cart_session';

	/**
	 * get cart paper
	 * @return array
	 */
	function getCart();

	/**
	 * add paper cart
	 */
	function addCart(int $paper_id, $attribute = []);
}

?>