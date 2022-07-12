<?php

namespace EmploybrandApply\Entity;


class CompanyEntity extends AbstractEntity
{

    protected $exclude = [
        'environmentTypes',
        'applicationFormFields'
    ];

    public ?int $id = null;

    public ?string $name = null;

    public ?string $email = null;

    public array $environmentTypes = [];

    public array $applicationFormFields = [];

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
            return new EnvironmentTypeEntity($entity);
        }, $parameters[ 'environment_types' ]);

        $this->applicationFormFields = \array_map(function ($formField) {
            return new ApplicationFormField($formField);
        }, $parameters[ 'application_form_fields' ]);
    }

}
