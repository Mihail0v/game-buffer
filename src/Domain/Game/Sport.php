<?php
declare(strict_types=1);

namespace App\Domain\Game;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="sports")
 */
class Sport extends MultipleNamesDocument
{
    public static function create(string $name, array $names): Sport
    {
        $instance = new self();
        $instance->name = $name;
        $instance->nameVariations = $names;

        return $instance;
    }
}
