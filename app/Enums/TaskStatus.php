<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Pending = 'Pending';
    case Progress = 'In Progress';
    case Completed = 'Completed';
}
