<?php

namespace Dluwang\Auth\Services\PolicyTransformer;

interface Contract
{
    /**
     * Transfrom policy into abilities.
     * 
     * @param   object   $policy.
     * @param   string   $model.
     * 
     * @return  array
     */
    public function transform(object $policy, string $model): array;
}