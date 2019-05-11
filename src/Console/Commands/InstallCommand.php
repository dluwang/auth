<?php

namespace Dluwang\Auth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dluwang-auth:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install dluwang-auth';

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * 
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return  void
     */
    public function handle(): void
    {
        $this->info('Generating migration files...');
        $this->generateMigrationFiles();
        $this->info('Migration files generated successfully.');
        
        $this->info('Generating eloquent models...');
        $this->generateModelFiles();
        $this->info('Eloquent models generated successfully.');
    }

    /**
     * Generate migration files.
     * 
     * @return  void
     */
    protected function generateMigrationFiles(): void
    {
        $migrationPath = app()->databasePath().DIRECTORY_SEPARATOR.'migrations';
        $dataPrefix = date('Y_m_d_His');
        $destinationPrefix = $migrationPath.DIRECTORY_SEPARATOR.$dataPrefix;

        $stubs = [
            [
                'base' => 'create_roles_table.php',
                'stub' => __DIR__.'/stubs/migrations/create_roles_table.stub',
                'destination' => $destinationPrefix.'_create_roles_table.php'
            ],
            [
                'base' => 'create_permissions_table.php',
                'stub' => __DIR__.'/stubs/migrations/create_permissions_table.stub',
                'destination' => $destinationPrefix.'_create_permissions_table.php'
            ],
            [
                'base' => 'create_permission_role_table.php',
                'stub' => __DIR__.'/stubs/migrations/create_permission_role_table.stub',
                'destination' => $destinationPrefix.'_create_permission_role_table.php'
            ],
            [
                'base' => 'create_role_user_table.php',
                'stub' => __DIR__.'/stubs/migrations/create_role_user_table.stub',
                'destination' => $destinationPrefix.'_create_role_user_table.php'
            ],
        ];

        foreach($stubs as $stub) {
            if($this->migrationFileExists($stub['base'])) {
                $this->info('File *_'. $stub['base'] .' exists, skipping.');
            } else {
                $this->files->copy($stub['stub'], $stub['destination']);
            }
        }
    }
    
    /**
     * Determine if migration file exists.
     * 
     * @param   string  $base
     * 
     * @return  bool
     */
    protected function migrationFileExists(string $base): bool
    {
        $files = glob(database_path('migrations') . '/*_' . $base);

        return count($files) ? true : false;
    }


    /**
     * Generate model files.
     * 
     * @return  void
     */
    protected function generateModelFiles(): void
    {
        $stubs = [
            [
                'class' => 'Role',
                'stub' => __DIR__.'/stubs/Role.stub',
                'destination' => app_path('Role.php'),
            ],
            [
                'class' => 'Permission',
                'stub' => __DIR__.'/stubs/Permission.stub',
                'destination' => app_path('Permission.php'),
            ],
        ];

        foreach ($stubs as $stub) {
            if(class_exists(app()->getNamespace() . $stub['class'])) {
                $this->info('Class ' . $stub['class'] . ' exists, skipping.');
            } else {
                $this->files->put($stub['destination'], $this->buildClass($stub['stub']));
            }
        }
    }

    /**
     * Build the class with the given stub.
     *
     * @param  string   $stub
     *
     * @return string
     */
    protected function buildClass(string $stub): string
    {
        $stub = $this->files->get($stub);
        
        return $this->replaceNamespace($stub);
    }
    
    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * 
     * @return string
     */
    protected function replaceNamespace(string $stub): string
    {
        $stub = str_replace(
            ['DummyNamespace'],
            [rtrim(app()->getNamespace(), '\\')],
            $stub
        );

        return $stub;
    }
}
