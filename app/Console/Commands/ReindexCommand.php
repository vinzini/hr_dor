<?php

namespace App\Console\Commands;

use App\Staff;
use Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all staff to Elasticsearch';

    /** @var \Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
    }

    public function handle()
    {
        $this->info('Indexing all staff. This might take a while...');

        foreach (Staff::cursor() as $staff)
        {
            $this->elasticsearch->index([
                'index' => $staff->getSearchIndex(),
                'type' => $staff->getSearchType(),
                'id' => $staff->getKey(),
                'body' => $staff->toSearchArray(),
            ]);

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("\nDone!");
    }
}