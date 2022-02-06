<?php

namespace DeployHuman\kivra\Dataclass\Content\Context;


class Booking
{


    protected string $title;
    protected string $start_time;
    protected string $end_time;
    protected string $location;
    protected string $description;
    protected string $info_url;


    public function __construct()
    {
    }

    /**
     * Booking name that is shown in the Kivra GUI
     * `required`
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Date and time for the booking to start. Must be in the future.
     *  `required`
     * @param string $start_time
     * @return self
     */
    public function setStartTime(string $start_time): self
    {
        $this->start_time = $start_time;
        return $this;
    }

    public function getStartTime(): string|null
    {
        return $this->start_time ?? null;
    }

    /**
     * Date and time for the booking to end. If present must be after start_time.
     * 
     *
     * @param string $end_time
     * @return self
     */
    public function setEndTime(string $end_time): self
    {
        $this->end_time = $end_time;
        return $this;
    }


    public function getEndTime(): string|null
    {
        return $this->end_time ?? null;
    }

    /**
     * Location for the appointment/booking. Address must contain city for full functionality.
     *
     * @return string
     */
    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getLocation(): string|null
    {
        return $this->location ?? null;
    }

    /**
     * Additional information
     *
     * @param string $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): string|null
    {
        return $this->description ?? null;
    }

    /**
     * Link to page with additional information
     *
     * @param string $info_url
     * @return self
     */
    public function setInfoUrl(string $info_url): self
    {
        $this->info_url = $info_url;
        return $this;
    }

    public function getInfoUrl(): string|null
    {
        return $this->info_url ?? null;
    }



    public function isValid(): bool
    {
        if (isset($this->start_time) && isset($this->end_time)) {
            if ($this->start_time > $this->end_time) {
                return false;
            }
        }
        return !in_array(null, array_values([
            'title' => $this->title,
            'start_time' => $this->start_time
        ]));
    }

    public function toArray(): array
    {

        return [
            'title' => $this->title,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'location' => $this->location,
            'description' => $this->description,
            'info_url' => $this->info_url
        ];
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
