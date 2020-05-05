<?php

namespace AlexBadea\PatchMigration\Commands;

use AlexBadea\PatchMigration\PatchRepository;
use AlexBadea\PatchMigration\PatchRunner;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class PatchRun extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:run {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the patches';

    /**
     * @var PatchRunner
     */
    protected $migrator;

    /**
     * @var PatchRepository
     */
    protected $repository;

    /**
     * PatchRun constructor.
     * @param PatchRunner $migrator
     * @param PatchRepository $repository
     */
    public function __construct(PatchRunner $migrator, PatchRepository $repository)
    {
        parent::__construct();

        $this->migrator = $migrator;
        $this->repository = $repository;
    }

    /**
     * Runs all new patches
     *
     * @return bool
     * @throws \Throwable
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return false;
        }

        if (!$this->migrator->repositoryExists()) {
            $this->error('Patches table not found.');
            return false;
        }

        $this->migrator->setOutput($this->output)->run($this->repository->folder);

        return true;
    }

}
