<?php


namespace Mooore\eCurring\Endpoint;


use Mooore\eCurring\Resource\Collection;

abstract class AbstractCollectionEndpoint extends AbstractEndpoint
{

    /**
     * Get the collection object that is used by this API endpoint. Every API endpoint uses one type of
     * collection object.
     *
     * @param int $count
     * @param object $links
     *
     * @return Collection
     */
    abstract protected function getResourceCollectionObject(int $count, object $links);
}