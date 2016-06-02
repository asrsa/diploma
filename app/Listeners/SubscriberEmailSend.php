<?php

namespace App\Listeners;

use App\Jobs\SendSubscriptionMail;
use App\News;
use App\Events\NewsWasCreated;
use App\Subscription;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SubscriberEmailSend implements ShouldQueue
{
    use DispatchesJobs;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewsWasCreated  $event
     * @return void
     */
    public function handle(NewsWasCreated $event)
    {
        $news = $event->news;
        $catId = News::getCatForNews($news->id)->id;
        $subs = Subscription::where('category_id', '=', $catId)->get();


        foreach($subs as $sub) {
            $user = $sub->user;

            if($sub->subscribed == 1) {
                $this->dispatch(new SendSubscriptionMail($user, $news));
            }
        }

    }
}
