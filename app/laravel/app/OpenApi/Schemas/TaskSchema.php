<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Task',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Task Title'),
        new OA\Property(property: 'description', type: 'string', example: 'Task Description'),
        new OA\Property(
            property: 'status',
            type: 'string',
            enum: ['new', 'pending', 'waiting', 'stop', 'completed'],
            example: 'new'
        ),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class TaskSchema
{
}
