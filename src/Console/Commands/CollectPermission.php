<?php

namespace Dluwang\Auth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Container\Container;
use Dluwang\Auth\Services\PolicyTransformer\Contract as PolicyTransformer;

class CollectPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dluwang-auth:collect-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect permissions from gate and policies';

    /**
     * @var Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * @var Illuminate\Contracts\Auth\Access\Gate
     */
    protected $gate;

    /**
     * @var Dluwang\Auth\Services\PolicyTransformer\Contract
     */
    protected $policyTransformer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Container $container, Gate $gate, PolicyTransformer $policyTransformer)
    {
        parent::__construct();
        $this->container = $container;
        $this->gate = $gate;
        $this->policyTransformer = $policyTransformer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Registering abilities...');
        $this->registerAbilities();
        $this->info('Abilities registered.');
    }

    /**
     * Register abilities.
     * 
     * @return  void
     */
    protected function registerAbilities(): void
    {
        $permissionEntity = '\\'.ltrim(config('dluwang-auth.entities.permission'), '\\');
        
        $policies = $this->gate->policies();
        $abilities = array_merge(array_keys($this->gate->abilities()), $this->transformPolicies($policies));

        foreach($abilities as $ability) {
            $permission = $permissionEntity::find($ability);

            if($permission) {
                $this->info('Permission "'. $ability .'" has been registered, skipping.');    
                continue;
            }

            $this->info('Registering '. $ability .' ability...');

            $permission = new $permissionEntity;
            $permission->id = $ability;
            $permission->save();
        }
    }

    /**
     * Transfrom policies into abilities.
     * 
     * @param   array   $policies
     * 
     * @return  array
     */
    protected function transformPolicies(array $policies): array
    {
        $abilities = [];
        foreach($policies as $model => $policy) {
            $policy = $this->container->make($policy);
            $tranformed = $this->policyTransformer->transform($policy, $model);

            foreach($tranformed as $ability) {
                $abilities[] = $ability;
            }
        }
        
        return $abilities;
    }
}
