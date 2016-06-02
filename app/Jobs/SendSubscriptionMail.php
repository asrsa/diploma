<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\News;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

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
        $user = $this->user;
        $news = $this->news;

        Mail::queue('emails.subscription', ['categoryName' => $news->subcategory->category->desc, 'newsId' => $news->id, 'newsTitle' => $news->title], function($message) use($user, $news) {
            $message
                ->to($user->email, $user->name)
                ->subject(trans('emails\subscriptions.emailTitle') . $news->subcategory->category->desc);
        });
    }
}
