<?php

namespace EmploybrandApply\Entity;


class EnvironmentEntity extends AbstractEntity
{

    protected $exclude = [
        'environmentType'
    ];


    public ?int $id = null;

    public ?string $name = null;

    public ?string $email = null;

    public ?string $slug = null;

    public ?string $fullSlug = null;

    public ?string $address = null;

    public ?string $postalCode = null;

    public ?string $city = null;

    public ?string $country = null;

    public ?string $countryName = null;

    public ?string $latLng = null;

    public ?string $location = null;

    public ?float $lat = null;

    public ?float $lng = null;

    public ?bool $isMain = false;

    public ?string $version = null;

    public ?int $usersCount = null;

    public ?int $invitesCount = null;

    public ?EnvironmentTypeEntity $environmentType = null;

    public array $customFields = [];

    public array $availableCustomFields = [];

    public ?string $updatedAt = null;

    public ?string $createdAt = null;


    public function build(array $parameters): void
    {
        if($parameters[ 'environment_type' ] != null)
            $this->environmentType = new EnvironmentTypeEntity($parameters[ 'environment_type' ]);

        parent::build($parameters);
    }

}
