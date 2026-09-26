<?php

namespace App\Contracts;

interface UserEventContract
{
    public function id(): string;

    public function type(): string;

    public function creatorType(): string;

    public function creatorId(): string;

    public function settings(): array;
}
