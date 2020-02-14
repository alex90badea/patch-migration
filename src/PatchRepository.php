<?php

namespace AlexBadea\PatchMigration;

use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PatchRepository
{
    /**
     * The database connection resolver instance.
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $resolver;

    /**
     * The name of the migration table.
     *
     * @var string
     */
    protected $table = 'patches';

    /**
     * The name of the database connection to use.
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * The folder name where the patch files are located
     *
     * @var string
     */
    public $folder = 'patches';

    /**
     * DatabaseMigrationRepository constructor.
     * @param Resolver $resolver
     */
    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @return array
     */
    public function getRan()
    {
        return $this->table()
            ->orderBy('done', 'desc')
            ->orderBy('patch', 'asc')
            ->pluck('patch')->all();
    }

    /**
     * @param string $file
     * @param int $done
     */
    public function log($file, $done)
    {
        $record = ['patch' => $file, 'done' => $done];

        $this->table()->insert($record);
    }

    /**
     * Creates the patches table
     */
    public function createRepository()
    {
        /** @var Schema $schema */
        $schema = $this->getConnection()->getSchemaBuilder();

        $schema->create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('patch');
            $table->boolean('done')->default(false);
        });
    }

    /**
     * @return bool
     */
    public function repositoryExists()
    {
        /** @var Schema $schema */
        $schema = $this->getConnection()->getSchemaBuilder();

        return $schema->hasTable($this->table);
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table()
    {
        return $this->getConnection()->table($this->table)->useWritePdo();
    }

    /**
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function getConnection()
    {
        return $this->resolver->connection($this->connection);
    }
}
