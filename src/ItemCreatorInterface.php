<?php

namespace Foamycastle\Collection;

interface ItemCreatorInterface
{
    public static function Create(int $index, string $name, mixed $data): static;

    public static function CreateImmutable(int $index, string $name, mixed $data): static;

    public static function Anon(int $index): static;
}