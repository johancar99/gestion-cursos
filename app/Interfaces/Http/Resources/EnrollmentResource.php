<?php

namespace App\Interfaces\Http\Resources;

use App\Application\Enrollment\DTOs\EnrollmentResponseDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var EnrollmentResponseDTO $this->resource */
        return [
            'id' => $this->resource->id,
            'student_id' => $this->resource->studentId,
            'course_id' => $this->resource->courseId,
            'enrolled_at' => $this->resource->enrolledAt,
        ];
    }
} 