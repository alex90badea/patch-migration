<?php

namespace AlexBadea\PatchMigration\Commands;

use AlexBadea\PatchMigration\PatchRepository;
use AlexBadea\PatchMigration\PatchRunner;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PatchStatus
 * @package AlexBadea\Commands
 */
class PatchStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all patches status';

    /**
     * @var PatchRunner
     */
    protected $migrator;

    /**
     * @var PatchRepository
     */
    protected $repository;

    /**
     * PatchStatus constructor.
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
     * @return bool
     */
    public function handle()
    {
        if (!$this->migrator->repositoryExists()) {
            $this->error('Patches table not found.');
            return false;
        }

        $ran = $this->migrator->getRepository()->getRan();

        if (count($migrations = $this->getStatusFor($ran)) > 0) {
            $this->table(['Ran?', 'Patch'], $migrations);
        } else {
            $this->error('No patches found');
        }
    }

    /**
     * Get the status for the given ran migrations.
     *
     * @param  array $ran
     * @return \Illuminate\Support\Collection
     */
    protected function getStatusFor(array $ran)
    {
        return Collection::make($this->migrator->getPatchFiles($this->repository->folder))
            ->map(function ($migration) use ($ran) {
                $migrationName = $this->migrator->getMigrationName($migration);

                return in_array($migrationName, $ran)
                    ? ['<info>Yes</info>', $migrationName]
                    : ['<fg=red>No</fg=red>', $migrationName];
            });
    }

}
