<?php
declare(strict_types=1);

namespace App\Domain\Game;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="league")
 */
class League extends MultipleNamesDocument
{
    /**
     * @ODM\Field(type="collection")
     * @ODM\UniqueIndex(keys={"nameVariations":"asc","sport":"asc"})
     */
    protected array $nameVariations = [];
    /**
     * @ODM\ReferenceOne(targetDocument=Sport::class)
     */
    protected Sport $sport;

    /**
     * @param Sport $sport
     * @param string $name
     * @param string[] $names
     * @return League
     */
    public static function create(Sport $sport, string $name, array $names): League
    {
        $instance = new self();
        $instance->name = $name;
        $instance->sport = $sport;
        $instance->nameVariations = $names;

        return $instance;
    }
}
