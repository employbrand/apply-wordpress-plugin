<?php

namespace EmploybrandApply\Entity;


class WebhookEntity extends AbstractEntity
{

    public ?int $id = null;

    public ?string $name = null;

    public array $types = ['all'];

    public ?string $url = null;

}
