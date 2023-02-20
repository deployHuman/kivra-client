<?php

namespace DeployHuman\kivra\Dataclass\Content\Files;

class File
{
    protected string $name;

    protected string $data;

    protected string $content_type;

    public function __construct()
    {
    }

    /**
     * Arbritrary file-name that is shown alongside the File in the Kivra GUI
     *
     * @return Content_File
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Base64 encoded file-data
     *
     * @return Content_File
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): string
    {
        return $this->data;
    }

    /**
     * MIME-type of the file-data
     * The IANA media type corresponding to the file, e.g. "application/pdf"
     *
     * @return Content_File
     */
    public function setContentType(string $content_type): self
    {
        $this->content_type = $content_type;

        return $this;
    }

    public function getContentType(): string
    {
        return $this->content_type;
    }

    public function isValid(): bool
    {
        return ! in_array(null, array_values($this->toArray()));
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name ?? null,
            'data' => $this->data ?? null,
            'content_type' => $this->content_type ?? null,
        ];
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}
