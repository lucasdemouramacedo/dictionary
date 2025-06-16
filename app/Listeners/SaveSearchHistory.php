<?php

namespace App\Listeners;

use App\Events\SearchProcessed;
use App\Services\HistoryService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveSearchHistory
{
    /**
     * Create the event listener.
     */
    public function __construct(
        public HistoryService $historyService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(SearchProcessed $event): void
    {
        $this->historyService->create($event->word);
    }
}
