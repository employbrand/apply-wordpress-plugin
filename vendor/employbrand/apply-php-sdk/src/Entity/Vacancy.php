<?php

namespace EmploybrandApply\Entity;


class Vacancy extends AbstractEntity
{

    public ?int $id = null;

    public ?string $function = null;

    public string $status = 'concept';

    public ?string $slug = null;

    public ?string $version = null;

    public array $funnelSteps = [];

    public array $customFields = [];

    public array $availableCustomFields = [];

    public ?Environment $environment = null;

    public ?string $publicationEndDate = null;

    public ?string $publicationStartDate = null;

    public ?string $updatedAt = null;

    public ?string $createdAt = null;


    public function build(array $parameters): void
    {
        if($parameters[ 'environment' ] != null)
            $this->environment = new Environment($parameters[ 'environment' ]);

        parent::build($parameters);
    }

}
