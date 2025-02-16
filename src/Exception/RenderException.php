<?php

declare(strict_types=1);

namespace Mosaic\Exception;

use Closure;
use Exception;
use ReflectionException;
use ReflectionFunction;
use Throwable;

final class RenderException extends Exception
{
    /**
     * @param mixed $view
     * @return RenderException
     */
    public static function forInvalidView(mixed $view): RenderException
    {
        return new RenderException(
            sprintf(
                'View of type "%s" could not be rendered.',
                self::getType($view),
            )
        );
    }

    /**
     * @param mixed $view
     * @param int $level
     * @return RenderException
     */
    public static function forMaxNestingLevel(mixed $view, int $level): RenderException
    {
        return new RenderException(
            sprintf(
                'Max nesting level of %s reached in view "%s".',
                $level,
                self::getType($view),
            )
        );
    }

    /**
     * @param Throwable $throwable
     * @param mixed $view
     * @return RenderException
     */
    public static function forThrowableInView(Throwable $throwable, mixed $view): RenderException
    {
        if ($throwable instanceof self) {
            return $throwable;
        }
        $type = self::getType($view);
        $error = get_class($throwable);
        return new RenderException("$error in $type ({$throwable->getFile()}:{$throwable->getLine()}): " . $throwable->getMessage(), 0, $throwable);
    }

    /**
     * @param mixed $var
     * @return string
     */
    private static function getType(mixed $var): string
    {
        $type = gettype($var);
        if ($type === 'object') {
            $type = get_class($var);
        }

        if ($var instanceof Closure) {
            try {
                $reflection = new ReflectionFunction($var);
                $type .= sprintf(' %s:%s:%s', $reflection->getFileName(), $reflection->getStartLine(), $reflection->getEndLine());
            } catch (ReflectionException $e) {
                $type .= ' ' . $e->getMessage();
            }
        }

        return $type;
    }
}