<?php

namespace Dluwang\Auth\Services\PolicyTransformer;

interface Contract
{
    /**
     * Transfrom policy into abilities.
     * 
     * @param   mixed   $policy.
     * @param   string  $model.
     * 
     * @return  array
     */
    public function transform($policy, string $model): array;
}