<?php

namespace EmploybrandApply\Entity;


class FileEntity extends AbstractEntity
{

    public ?int $id = null;

    public ?string $name = null;

    public ?string $url = null;

    public ?string $fileType = null;

    public ?int $fileSize = null;

    public bool $private = false;

    public ?string $x300ImageUrl = null;

    public ?string $x800ImageUrl = null;

    public ?string $x1500ImageUrl = null;

    public ?string $version = null;

    public ?int $imageWidth = null;

    public ?int $imageHeight = null;

    public ?string $updatedAt = null;

    public ?string $createdAt = null;


}
