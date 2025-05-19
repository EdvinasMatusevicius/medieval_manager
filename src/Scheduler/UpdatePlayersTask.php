<?php

namespace App\Scheduler;

use App\Service\TimeProgressionService;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;

class UpdatePlayersTask
{
    private const UPDATE_CHARACTERS_INTERVAL_SECONDS = 20;

    public function __construct(private TimeProgressionService $timeProgressionService) {}

    #[AsPeriodicTask(self::UPDATE_CHARACTERS_INTERVAL_SECONDS)]
    public function run(): void
    {
        $this->timeProgressionService->updateAllPlayers(self::UPDATE_CHARACTERS_INTERVAL_SECONDS);
    }
}