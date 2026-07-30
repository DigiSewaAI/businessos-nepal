<?php
namespace App\Services\AI\Agent\Contracts;

interface ToolInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function getParameters(): array;
    public function execute(array $params): array;
}