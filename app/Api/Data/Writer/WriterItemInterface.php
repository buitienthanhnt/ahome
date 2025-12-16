<?php

namespace App\Api\Data\Writer;

use App\Api\Data\AttributeInterface;

interface WriterItemInterface extends AttributeInterface
{
	const ID = "id";
	const NAME = "name";
	const EMAIL = "email";
	const PHONE = "phone";
	const IMAGE_PATH = "image";
	const ACTIVE = "active";
	const RATING = "rating";
	const DATE_OF_BIRTH = "dateOfBirth";

	/**
	 * @param int $id
	 * @return $this
	 */
	public function setId(int $id);

	/**
	 * @return int
	 */
	public function getId();

	/**
	 * @param string $name
	 * @return $this
	 */
	function setName(string $name);

	/**
	 * @return string
	 */
	function getName();

	/**
	 * @param string $email
	 * @return $this
	 */
	function setEmail(string $email);

	/**
	 * @return string
	 */
	function getEmail();

	/**
	 * @param string $phone
	 * @return $this
	 */
	function setPhone(string $phone);

	/**
	 * @return string
	 */
	function getPhone();

	/**
	 * @param string $image_path
	 * @return $this
	 */
	function setImagePath(string $image_path);

	/**
	 * @return string
	 */
	function getImagePath();

	/**
	 * @param bool $active
	 * @return $this
	 */
	function setActive(bool $active);

	/**
	 * @return bool
	 */
	function getActive();

	/**
	 * @param int $rating
	 * @return $this
	 */
	function setRating(int $rating);

	/**
	 * @return int
	 */
	function getRating();

	/**
	 * @param string $date_of_birth
	 * @return $this
	 */
	function setDateOfBirth(string $date_of_birth);

	/**
	 * @return string
	 */
	function getDateOfBirth();
}
