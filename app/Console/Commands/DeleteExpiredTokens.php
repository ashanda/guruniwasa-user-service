<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired tokens older than 1 day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = Carbon::now()->subDay(); // Calculate the threshold (1 day ago)
        
        // Replace 'Token' with your actual model representing tokens
        DB::table('oauth_access_tokens')
            ->where('created_at', '<', $threshold)
            ->delete();

        $this->info('Expired OAuth access tokens older than 1 day have been deleted.');
    }
}
