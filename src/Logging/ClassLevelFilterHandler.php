<?php
declare(strict_types=1);

namespace App\Logging;


use Monolog\Handler\AbstractHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use Monolog\LogRecord;


class ClassLevelFilterHandler extends AbstractHandler {

    /**
     * @param array<string, string|Level> $rules Карта: 'Класс\Или\Неймспейс' => 'debug'|'info'|'warning'|'error'
     */
    public function __construct(private readonly HandlerInterface $innerHandler, private readonly array $rules = [],
        string|int|Level $defaultLevel = Level::Error)
    {
        parent::__construct($defaultLevel);
    }


    public function handle(LogRecord $record): bool {
        $className = $record->extra['class'] ?? null;
        $minLevel = $this->resolveMinLevel(is_string($className) ? $className : null);

        // Если уровень сообщения ниже разрешенного для этого класса — игнорируем лог
        if ($record->level->value < $minLevel->value) {
            return false;
        }

        return $this->innerHandler->handle($record);
    }


    // Обязательно возвращаем true, чтобы Monolog не отбросил лог ДО запуска IntrospectionProcessor
    public function isHandling(LogRecord $record): bool {
        return true;
    }


    private function resolveMinLevel(?string $className): Level {
        if ($className === null) {
            return $this->level;
        }

        $matchedLevel = null;
        $longestMatch = -1;

        foreach ($this->rules as $prefix => $level) {
            if (str_starts_with($className, $prefix)) {
                $length = strlen($prefix);
                if ($length > $longestMatch) {
                    $longestMatch = $length;
                    $matchedLevel = $level instanceof Level ? $level : Level::fromName((string) $level);
                }
            }
        }

        return $matchedLevel ?? $this->level;
    }
}