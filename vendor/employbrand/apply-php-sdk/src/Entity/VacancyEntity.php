<?php

namespace EmploybrandApply\Entity;


class VacancyEntity extends AbstractEntity
{

    protected $exclude = [
        'environment',
        'formFields'
    ];

    public ?int $id = null;

    public ?string $function = null;

    public string $status = 'concept';

    public ?string $slug = null;

    public ?string $version = null;

    public array $funnelSteps = [];

    public array $customFields = [];

    public array $availableCustomFields = [];

    public ?EnvironmentEntity $environment = null;

    public array $formFields = [];

    public ?string $publicationEndDate = null;

    public ?string $publicationStartDate = null;

    public ?string $updatedAt = null;

    public ?string $createdAt = null;


    public function build(array $parameters): void
    {
        if($parameters[ 'environment' ] != null)
            $this->environment = new EnvironmentEntity($parameters[ 'environment' ]);

        $this->formFields = \array_map(function ($formField) {
            return new ApplicationFormField($formField);
        }, $parameters[ 'form_fields' ]);

        parent::build($parameters);
    }

}
