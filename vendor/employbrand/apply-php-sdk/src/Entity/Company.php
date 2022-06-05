<?php

namespace EmploybrandApply\Entity;


class Company extends AbstractEntity
{

    public ?int $id = null;

    public ?string $name = null;

    public ?string $email = null;

    public array $environmentTypes = [];

    public ?string $defaultLanguage = null;

    public ?bool $isDemo = false;

    public array $langauges = [];

    public ?string $mainEnvironmentName = null;

    public ?string $vacancySlug = null;

    public ?string $websiteUrl = null;


    public function build(array $parameters): void
    {
        parent::build($parameters);

        $this->environmentTypes = \array_map(function ($entity) {
            return new EnvironmentType($entity);
        }, $parameters[ 'environment_types' ]);
    }

}
