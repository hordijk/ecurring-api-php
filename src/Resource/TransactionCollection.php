<?php

declare(strict_types=1);

namespace Mooore\eCurring\Resource;

class TransactionCollection extends CursorCollection
{

    /**
     * @inheritDoc
     */
    protected function createResourceObject()
    {
        return new Transaction($this->client);
    }
}