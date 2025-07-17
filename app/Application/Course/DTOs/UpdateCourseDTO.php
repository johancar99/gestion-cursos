<?php

namespace App\Application\Course\DTOs;

class UpdateCourseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null
    ) {
    }
} 