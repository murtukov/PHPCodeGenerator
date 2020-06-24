<?php

declare(strict_types=1);

namespace Murtukov\PHPCodeGenerator\ControlStructures;

use Murtukov\PHPCodeGenerator\BlockInterface;
use Murtukov\PHPCodeGenerator\DependencyAwareGenerator;
use Murtukov\PHPCodeGenerator\ScopedContentTrait;

class Loop extends DependencyAwareGenerator implements BlockInterface
{
    use ScopedContentTrait;

    public const TYPE_WHILE = 'while';
    public const TYPE_FOR = 'for';
    public const TYPE_FOREACH = 'foreach';
    public const TYPE_DO_WHILE = 'doWhile';

    private string $condition;
    private string $type;

    public function __construct(string $condition = '', $type = self::TYPE_WHILE)
    {
        $this->condition = $condition;
        $this->type = $type;
    }

    public function generate(): string
    {
        // do ... while
        if (self::TYPE_DO_WHILE === $this->type) {
            return <<<CODE
            do {
            {$this->generateContent()}
            } while ($this->condition)
            CODE;
        }

        // Other loop types
        return <<<CODE
        $this->type ($this->condition) {
        {$this->generateContent()}
        }
        CODE;
    }

    public static function while(string $condition)
    {
        return new self($condition);
    }

    public static function for(string $condition)
    {
        return new self($condition, self::TYPE_FOR);
    }

    public static function foreach(string $condition)
    {
        return new self($condition, self::TYPE_FOREACH);
    }

    public static function doWhile(string $condition)
    {
        return new self($condition, self::TYPE_DO_WHILE);
    }
}
