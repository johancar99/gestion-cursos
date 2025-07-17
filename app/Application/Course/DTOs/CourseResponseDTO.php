<?php

namespace App\Application\Course\DTOs;

class CourseResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $description,
        public readonly string $startDate,
        public readonly string $endDate
    ) {
    }
} 