<?php

namespace EmploybrandApply\Entity;


class CandidateEntity extends AbstractEntity
{

    public ?int $id = null;

    public ?string $firstName = null;

    public ?string $lastName = null;

    public ?string $fullName = null;

    public array $sources = [];

    public array $socials = [];

    public array $remarks = [];

    public array $phones = [];

    public array $files = [];

    public array $emails = [];

    public array $customFields = [];

    public array $applications = [];

    public array $activities = [];

    public ?string $updatedAt = null;

    public ?string $createdAt = null;

}
