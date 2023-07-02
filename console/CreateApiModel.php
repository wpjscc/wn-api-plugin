<?php namespace Wpjscc\Api\Console;

use Winter\Storm\Support\Str;
use System\Console\BaseScaffoldCommand;
use Winter\Storm\Support\Facades\Twig;

class CreateApiModel extends BaseScaffoldCommand
{
    /**
     * @var string|null The default command name for lazy loading.
     */
    protected static $defaultName = 'create:apimodel';

    /**
     * @var string The name and signature of this command.
     */
    protected $signature = 'create:apimodel
        {plugin : The name of the plugin. <info>(eg: Winter.Blog)</info>}
        {model : The name of the model to generate. <info>(eg: Post)</info>}
        {--f|force : Overwrite existing files with generated files.}

        {--a|all : Generate a controller, migration, & seeder for the model}
        {--c|controller : Create a new controller for the model}
        {--s|seed : Create a new seeder for the model}
        {--p|pivot : Indicates if the generated model should be a custom intermediate table model}
        {--no-migration : Don\'t create a migration file for the model}
    ';

    /**
     * @var string The console command description.
     */
    protected $description = 'Creates a new model.';

    /**
     * @var array List of commands that this command replaces (aliases)
     */
    protected $replaces = [
        'make:model',
    ];

    /**
     * @var string The type of class being generated.
     */
    protected $type = 'Model';

    /**
     * @var string The argument that the generated class name comes from
     */
    protected $nameFrom = 'model';

    /**
     * @var array A mapping of stubs to generated files.
     */
    protected $stubs = [
        'scaffold/model/model.stub'        => 'models/{{studly_name}}.php',
        'scaffold/model/fields.stub'       => 'models/{{lower_name}}/fields_api.yaml',
        'scaffold/model/columns.stub'      => 'models/{{lower_name}}/columns_api.yaml',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false && !$this->option('force')) {
            return false;
        }
    }

    public function makeStubs(): void
    {
        $stubs = array_keys($this->stubs);

        foreach ($stubs as $stub) {
            $destinationFile = $this->getDestinationForStub($stub);
            if ($this->files->exists($destinationFile)) {
                $this->info("Cannot create the {$this->type}:\r\n$destinationFile already exists.\r\n");
                continue;
            }
            $this->makeStub($stub);
        }
    }

    /**
     * Adds controller & model lang helpers to the vars
     */
    protected function processVars($vars): array
    {
        $vars = parent::processVars($vars);

        $vars['table_name'] = "{$vars['lower_author']}_{$vars['lower_plugin']}_{$vars['snake_plural_name']}";

        return $vars;
    }

    /**
     * Gets the localization keys and values to be stored in the plugin's localization files
     * Can reference $this->vars and $this->laravel->getLocale() internally
     */
    protected function getLangKeys(): array
    {
        return [
            'models.general.id' => 'ID',
            'models.general.created_at' => 'Created At',
            'models.general.updated_at' => 'Updated At',
        ];
    }

    /**
     * Create a migration for the model.
     */
    public function createMigration()
    {
        $this->call('create:migration', [
            'plugin'  => $this->getPluginIdentifier(),
            '--model' => $this->getNameInput(),
        ]);
    }

    /**
     * Create a seeder for the model.
     */
    public function createSeeder()
    {
        $this->call('create:seeder', [
            'plugin'  => $this->getPluginIdentifier(),
            'model' => $this->getNameInput(),
        ]);
    }

    /**
     * Create a controller for the model.
     */
    public function createController()
    {
        $this->call('create:controller', [
            'plugin'  => $this->getPluginIdentifier(),
            'controller' => Str::pluralize($this->argument('model')),
            '--model' => $this->getNameInput(),
        ]);
    }
}
