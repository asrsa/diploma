<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\News;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSubscriptionMail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;
    protected $news;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, News $news)
    {
        $this->user = $user;
        $this->news = $news;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
