<?php

namespace App\Scheduler;

use App\Service\TimeProgressionService;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

class UpdatePlayersTask
{
    private TimeProgressionService $timeProgressionService;

    public function __construct(TimeProgressionService $timeProgressionService)
    {
        $this->timeProgressionService = $timeProgressionService;
    }

    #[AsPeriodicTask(15)]
    public function run(): void
    {
        $this->timeProgressionService->updateAllPlayers();
    }
}