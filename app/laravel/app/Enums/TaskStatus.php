<?php

namespace App\Enums;

enum TaskStatus: string
{
    case NEW = 'new';
    case PENDING = 'pending';
    case WAITING = 'waiting';
    case STOP = 'stop';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Nowe',
            self::PENDING => 'Oczekujące',
            self::WAITING => 'W trakcie',
            self::STOP => 'Zatrzymane',
            self::COMPLETED => 'Zakończone',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NEW => 'blue',
            self::PENDING => 'yellow',
            self::WAITING => 'orange',
            self::STOP => 'red',
            self::COMPLETED => 'green',
        };
    }
}
