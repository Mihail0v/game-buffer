<?php
declare(strict_types=1);

namespace App\Domain\Game;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


abstract class MultipleNamesDocument
{
    /**
     * @ODM\Id()
     */
    protected ?string $id = null;
    /**
     * @ODM\Field(type="string")
     */
    protected ?string $name = null;
    /**
     * @ODM\Field(type="collection")
     * @ODM\UniqueIndex()
     */
    protected array $nameVariations = [];

    public function id(): string
    {
        return $this->id;
    }
}
