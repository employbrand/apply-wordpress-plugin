<?php

namespace EmploybrandApply\Entity;


class ApplicationFormField extends AbstractEntity
{

    public ?int $id = null;

    public ?string $name = null;

    public bool $isSystem = false;

    public bool $required = false;

    public array $options = [];

    public int $position = 0;

    public ?string $systemType = null;

    public ?string $type = null;


}
