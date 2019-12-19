<?php
declare(strict_types=1);

namespace App\Domain\Game;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="languages")
 */
class Language extends MultipleNamesDocument
{
    public static function create(string $name, array $names): Language
    {
        $instance = new self();
        $instance->name = $name;
        $instance->nameVariations = $names;

        return $instance;
    }
}
