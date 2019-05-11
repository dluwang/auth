<?php

namespace Dluwang\Auth\Services\PolicyTransformer;

class SimplePolicyTransformer implements Contract
{
    /**
     * Transfrom policy into abilities.
     * 
     * @param   mixed   $policy.
     * @param   string  $model.
     * 
     * @return  array
     */
    public function transform($policy, string $model): array
    {
        $abilities = [];
        $prefix = strtolower(str_replace('\\', '-', $model));
        $methods = get_class_methods($policy);

        foreach ($methods as $method) {
            $abilities[] = $prefix . '.' .$method;
        }
        
        return $abilities;
    }
}