<?php
declare(strict_types=1);

namespace App\Logging;


use Monolog\Formatter\LineFormatter;
use Monolog\Level;
use Monolog\LogRecord;


class LevelColoredFormatter extends LineFormatter {

    public function __construct(?string $format = null, ?string $dateFormat = null, bool $allowInlineLineBreaks = false,
        bool $ignoreEmptyContextAndExtra = false, private readonly bool $enableColors = true)
    {
        parent::__construct($format, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra);
    }


    public function format(LogRecord $record): string {
        $extra = $record->extra;
        $source = $record->channel;

        // Если IntrospectionProcessor определил класс, берем его вместо канала [app]
        if (isset($extra['class']) && is_string($extra['class'])) {
            $source = $this->shortenClassName($extra['class']);
        }

        // Убираем служебные поля IntrospectionProcessor из $extra, чтобы они не дублировались в конце строки
        unset($extra['file'], $extra['line'], $extra['class'], $extra['function'], $extra['callType']);

        // Подменяем channel на имя класса и передаем очищенный $extra в родительский форматтер
        $modifiedRecord = $record->with(channel: $source, extra: $extra);
        $output = parent::format($modifiedRecord);

        if (!$this->enableColors) {
            return $output;
        }

        $color = match ($record->level) {
            Level::Debug => "\033[34m",     // Синий
            Level::Info => "\033[32m",      // Зеленый
            Level::Notice => "\033[36m",    // Голубой
            Level::Warning => "\033[33m",   // Желтый
            Level::Error => "\033[31m",     // Красный
            Level::Critical, Level::Alert, Level::Emergency => "\033[41;37m",
        };

        $levelName = $record->level->getName();

        return str_replace($levelName, $color . $levelName . "\033[0m", $output);
    }


    /**
     * Аналог %c{1.} в Log4j2: App\Controller\HomeController -> A\C\HomeController
     * (Если нужно полное имя класса, просто верните $fqcn без изменений)
     */
    private function shortenClassName(string $fqcn): string {
        $parts = explode('\\', $fqcn);
        $className = array_pop($parts);

        $shortNamespaces = array_map(
            static fn (string $part): string => mb_substr($part, 0, 1),
            $parts
        );

        return $shortNamespaces !== []
            ? implode('\\', $shortNamespaces) . '\\' . $className
            : $className;
    }
}