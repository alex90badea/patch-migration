<?php

namespace AlexBadea\PatchMigration\Commands;

use AlexBadea\PatchMigration\PatchRepository;
use Illuminate\Console\Command;

/**
 * Class PatchInstall
 * @package AlexBadea\PatchMigrator\Commands
 */
class PatchInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the patches repository';

    /**
     * @var PatchRepository
     */
    protected $repository;

    /**
     * PatchInstall constructor.
     * @param PatchRepository $repository
     */
    public function __construct(PatchRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * @return bool
     */
    public function handle()
    {
        if ($this->repository->repositoryExists()) {
            $this->info('Patches table already created.');
            return false;
        }

        $this->repository->createRepository();

        $this->info('Patches table created successfully.');
    }

}
