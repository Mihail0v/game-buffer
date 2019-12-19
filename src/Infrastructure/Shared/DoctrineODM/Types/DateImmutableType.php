<?php

namespace App\Infrastructure\Shared\DoctrineODM\Types;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ODM\MongoDB\Types\DateType;
use InvalidArgumentException;
use function sprintf;

final class DateImmutableType extends DateType
{
    public const DATE_IMMUTABLE = 'date_immutable';

    public static function getDateTime($value): DateTimeInterface
    {
        $dateTime = parent::getDateTime($value);
        if ($dateTime instanceof DateTimeImmutable) {
            return $dateTime;
        }
        if (!$dateTime instanceof DateTime) {
            throw new InvalidArgumentException(
                sprintf(
                    'Value has to be instance of DateTime, got %s',
                    get_class($dateTime))
            );
        }
        return DateTimeImmutable::createFromMutable($dateTime);
    }
}