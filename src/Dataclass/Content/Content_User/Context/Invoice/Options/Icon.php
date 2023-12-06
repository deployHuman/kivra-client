<?php

namespace DeployHuman\kivra\Dataclass\Content\Content_User\Context\Invoice\Options;

use DeployHuman\kivra\Validation;

class Icon
{
    protected string $name;

    protected string $data;

    protected string $content_type = 'image/png';

    public function __construct()
    {
    }

    /**
     * Arbritrary file-name that is shown alongside the File in the Kivra GUI
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
     * Base64 encoded file-data.
     * Max imagedata is 134 kB when encoded which is roughly equivalent to 100 kB before encoding.
     * The image format must be PNG.
     * The image must be quadratic (that is, width and height must be the same).
     * The sides must be at least 256 pixels long, and at most 512 pixels long.
     * The image must have an alpha channel.
     */
    public function setData(string $data): bool
    {
        if ($this->validateIcon($data) !== true) {
            return false;
        }  //throw new Exception("Icon data is not not correct formatted");
        $this->data = $data;
        $this->setContentType('image/png');                     //why required a PNG and still makes us set the type

        return true;
    }

    /**
     * Returns the base64 encoded file-data.
     */
    public function getData(): string
    {
        return $this->data ?? '';
    }

    /**
     * Validates an base64 encoded icon data string.
     * Can be used either to get a True if its valid, or fetch returnstring to whats wrong with the image.
     *
     * @return string|bool True if valid, string with error message if not.
     */
    public function validateIcon(string $data): string|bool
    {
        if ($data === '') {
            return 'Icon data is empty';
        }
        if (! Validation::base64($data)) {
            return 'base64 is not valid';
        }
        $data = base64_decode($data);
        //check image Dimensions
        $imagedata = getimagesizefromstring($data);
        if (! $imagedata) {
            return 'image is not valid';
        }
        if ($imagedata[0] != $imagedata[1]) {
            return 'image is not quadratic';
        }
        if ($imagedata[0] < 256 || $imagedata[0] > 512) {
            return 'image is not betweeen 256x256 and 512x512';
        }
        //check if image is png
        if ($imagedata[2] != IMAGETYPE_PNG) {
            return 'image is not png, found : ' . $imagedata[2];
        }
        //check image fileimagedata
        if (mb_strlen($data) > 134000) {
            return 'image is too big';
        }
        //check if image has alpha channel
        $im = imagecreatefromstring($data);
        if (! imagecolortransparent($im)) {
            return 'image has no alpha channel';
        }

        return true;
    }

    /**
     * MIME-type of the file-data
     * The IANA media type corresponding to the file, Always "image/png"
     * Should not be used, as it can only be PNG and its set as default
     */
    public function setContentType(string $content_type): self
    {
        $this->content_type = $content_type;

        return $this;
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

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
