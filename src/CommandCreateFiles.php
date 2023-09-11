<?php

namespace Linhnh95\LaravelLumenGenerate;

use Illuminate\Console\Command;

class CommandCreateFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lumen-generate:make {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Files Of Linh Nguyen Hong';

    /**
     * @var CommandHelpers
     */
    private $commandHelper;

    /**
     * CreateFileCommand constructor.
     *
     * @param CommandHelpers $commandHelper
     */
    public function __construct(CommandHelpers $commandHelper)
    {
        $this->commandHelper = $commandHelper;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $director = CommandHelpers::getDirectorAndFilename($this->argument('name'));
        $this->callSilent('lumen-generate:interface', ['name' => $this->argument('name')]);
        $this->callSilent('lumen-generate:repository', ['name' => $this->argument('name')]);
        $this->callSilent('lumen-generate:controller', ['name' => $this->argument('name')]);
        $this->callSilent('lumen-generate:resource-collection', ['name' => $this->argument('name')]);
        $this->callSilent('lumen-generate:resource-item', ['name' => $this->argument('name')]);
        $this->callSilent('lumen-generate:request', ['name' => $this->argument('name')]);
        $this->createProviders($director['name'], $director['folder']);
        $this->info('Create files success');
    }

    /**
     * @param $name
     * @param string $folder
     * @return void
     */
    private function createProviders($name, string $folder = '')
    {
        $file = $this->commandHelper->getAppPath() . '/Providers/RepositoryProvider.php';
        $contentFile = file_get_contents($file);

        $stringAddNew = '$this->app->bind(' . $name . 'Interface::class,' . "\n" . '            ' . $name . 'Repository::class);' . "\n" . '        ';
        $changeContent = substr($contentFile, strripos($contentFile, '/**BEGIN CONFIG**/'));
        $changeContent = substr($changeContent, 0, strripos($changeContent, '/**END CONFIG**/'));
        $stringReplace = $changeContent . $stringAddNew;
        $afterString = str_replace($changeContent, $stringReplace, $contentFile);


        $stringUse = 'use App\Abstraction' . ($folder && $folder !== '' ? '\\' . $folder : '') . '\\' . $name . 'Interface;' . "\n" . 'use App\Repositories' . ($folder && $folder !== '' ? '\\' . $folder : '') . '\\' . $name . 'Repository;' . "\n";
        $changeContentUse = substr($afterString, strripos($afterString, '/**BEGIN CONFIG USE**/'));
        $changeContentUse = substr($changeContentUse, 0, strripos($changeContentUse, '/**END CONFIG USE**/'));
        $stringReplaceUse = $changeContentUse . $stringUse;
        $afterStringUse = str_replace($changeContentUse, $stringReplaceUse, $afterString);

        file_put_contents($file, '');
        file_put_contents($file, $afterStringUse);
    }
}