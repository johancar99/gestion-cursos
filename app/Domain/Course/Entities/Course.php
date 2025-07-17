<?php

namespace App\Domain\Course\Entities;

use App\Domain\Course\ValueObjects\CourseId;
use App\Domain\Course\ValueObjects\Title;
use App\Domain\Course\ValueObjects\Description;
use App\Domain\Course\ValueObjects\StartDate;
use App\Domain\Course\ValueObjects\EndDate;

class Course
{
    public function __construct(
        private CourseId $id,
        private Title $title,
        private Description $description,
        private StartDate $startDate,
        private EndDate $endDate
    ) {
    }

    public function id(): CourseId
    {
        return $this->id;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function startDate(): StartDate
    {
        return $this->startDate;
    }

    public function endDate(): EndDate
    {
        return $this->endDate;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'title' => $this->title->value(),
            'description' => $this->description->value(),
            'start_date' => $this->startDate->value(),
            'end_date' => $this->endDate->value(),
        ];
    }
} 