<?php

declare(strict_types=1);

namespace app\models;

use app\core\Model;

class Event extends Model {
    private string $name;
    private string $short_description;
    private string $long_description;
    private string $place;
    private string $date;
    private string $time;
    private string $image;
    private int $user_id;

    /**
     * @param int|null $id
     * @param string $name
     * @param string $short_description
     * @param string $long_description
     * @param string $place
     * @param string $date
     * @param string $time
     * @param string $image
     * @param int $user_id
     */
    public function __construct(?int $id, string $name, string $short_description, string $long_description, string $place, string $date, string $time, string $image, int $user_id)
    {
        parent::__construct($id);
        $this->name = $name;
        $this->short_description = $short_description;
        $this->long_description = $long_description;
        $this->place = $place;
        $this->date = $date;
        $this->time = $time;
        $this->image = $image;
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->short_description;
    }

    /**
     * @param string $short_description
     */
    public function setShortDescription(string $short_description): void
    {
        $this->short_description = $short_description;
    }

    /**
     * @return string
     */
    public function getLongDescription(): string
    {
        return $this->long_description;
    }

    /**
     * @param string $long_description
     */
    public function setLongDescription(string $long_description): void
    {
        $this->long_description = $long_description;
    }

    /**
     * @return string
     */
    public function getPlace(): string
    {
        return $this->place;
    }

    /**
     * @param string $place
     */
    public function setPlace(string $place): void
    {
        $this->place = $place;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime(string $time): void
    {
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getUserID(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserID(int $user_id): void
    {
        $this->user_id = $user_id;
    }
}