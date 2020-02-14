<?php

namespace AlexBadea\PatchMigration\Commands;

use AlexBadea\PatchMigration\PatchCreator;
use AlexBadea\PatchMigration\PatchRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Composer;

class PatchMake extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'patch:make {name : The name of the patch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new patch file';

    /**
     * @var \AlexBadea\PatchMigration\PatchCreator
     */
    protected $creator;

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * @var PatchRepository
     */
    protected $repository;

    /**.
     * PatchMake constructor.
     * @param PatchCreator $creator
     * @param Composer $composer
     * @param PatchRepository $repository
     */
    public function __construct(PatchCreator $creator, Composer $composer, PatchRepository $repository)
    {
        parent::__construct();

        $this->creator = $creator;
        $this->composer = $composer;
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        // It's possible for the developer to specify the tables to modify in this
        // schema operation. The developer may also specify if this table needs
        // to be freshly created so we can create the appropriate patches.
        $name = Str::snake(trim($this->input->getArgument('name')));

        // Now we are ready to write the patch out to disk. Once we've written
        // the patch out, we will dump-autoload for the entire framework to
        // make sure that the patches are registered by the class loaders.

        if (!is_dir($this->laravel->basePath() . DIRECTORY_SEPARATOR . $this->repository->folder)) {
            mkdir($this->laravel->basePath() . DIRECTORY_SEPARATOR .  $this->repository->folder);
        }

        $file = $this->creator->create(
            $name, $this->laravel->basePath() . DIRECTORY_SEPARATOR .  $this->repository->folder
        );

        $this->line("<info>Created Patch:</info> {$file}");

        $this->composer->dumpAutoloads();
    }

}
