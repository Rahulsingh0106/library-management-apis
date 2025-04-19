<?php

namespace App\Listeners;

use App\Events\BookBorrowed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogBorrowedBook
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookBorrowed $event): void
    {
        Log::info("Book '{$event->book->title}' was borrowed by {$event->user->name}");
    }
}
