<?php
declare(strict_types=1);

namespace App\Application\Command\MergeBuffer;

final class MergeBufferCommand
{
    public string $bufferId;

    public function __construct(string $bufferId)
    {
        $this->bufferId = $bufferId;
    }
}
