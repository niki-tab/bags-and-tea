<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Criteria;

use InvalidArgumentException;
use Src\Shared\Domain\ValueObject\Enum;

/**
 * @method static FilterOperator gt()
 * @method static FilterOperator lt()
 * @method static FilterOperator like()
 */
final class FilterOperator extends Enum
{
    public const EQUAL        = '=';
    public const NOT_EQUAL    = '!=';
    public const GT           = '>';
    public const GTE          = '>=';
    public const LT           = '<';
    public const LTE          = '<=';
    public const LIKE         = 'like';
    public const IN           = 'in';
    public const CONTAINS     = 'CONTAINS';
    public const NOT_CONTAINS = 'NOT_CONTAINS';
    private static array $containing = [self::CONTAINS, self::NOT_CONTAINS];

    public static function equal(): self
    {
        return new self('=');
    }

    public function isContaining(): bool
    {
        return in_array($this->value(), self::$containing, true);
    }

    protected function throwExceptionForInvalidValue($value): void
    {
        throw new InvalidArgumentException(sprintf('The filter <%s> is invalid', $value));
    }
}
