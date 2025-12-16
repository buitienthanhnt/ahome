<?php
namespace App\Api\Data\Writer;

use App\Api\Data\Attribute;

class WriterItem extends Attribute implements WriterItemInterface
{
	public function setId(int $id)
    {
        return $this->setData(self::ID, $id);
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

	function setName(string $name)
	{
		return $this->setData(self::NAME, $name);
	}

	function getName()
	{
		return $this->getData(self::NAME);
	}

	function setEmail(string $email)
	{
		return $this->setData(self::EMAIL, $email);
	}

	function getEmail()
	{
		return $this->getData(self::EMAIL);
	}

	function setPhone(string $phone)
	{
		return $this->setData(self::PHONE, $phone);
	}

	function getPhone()
	{
		return $this->getData(self::PHONE);
	}

	function setImagePath(string $image_path)
	{
		return $this->setData(self::IMAGE_PATH, $image_path);
	}

	function getImagePath()
	{
		return $this->getData(self::IMAGE_PATH);
	}

	function setActive(bool $active)
	{
		return $this->setData(self::ACTIVE, $active);
	}

	function getActive()
	{
		return $this->getData(self::ACTIVE);
	}

    /**
     * @param int|null $rating
     * @return WriterItem|m.\App\Api\Data\Writer\WriterItem.setData
     */
	function setRating($rating)
	{
		return $this->setData(self::RATING, $rating);
	}

	function getRating()
	{
		return $this->getData(self::RATING);
	}

	function setDateOfBirth(string $date_of_birth)
	{
		return $this->setData(self::DATE_OF_BIRTH, $date_of_birth);
	}

	function getDateOfBirth()
	{
		return $this->getData(self::DATE_OF_BIRTH);
	}
}
