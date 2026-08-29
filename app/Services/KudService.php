<?php

namespace App\Services;

use App\Repositories\Contracts\KudRepositoryInterface;
use App\Repositories\Contracts\TimbanganRepositoryInterface;

class KudService
{
    protected KudRepositoryInterface $kudRepo;
    protected TimbanganRepositoryInterface $timbanganRepo;

    public function __construct(
        KudRepositoryInterface $kudRepo,
        TimbanganRepositoryInterface $timbanganRepo
    ) {
        $this->kudRepo = $kudRepo;
        $this->timbanganRepo = $timbanganRepo;
    }

    public function getKudDashboardData(): array
    {
        $prices = $this->kudRepo->getAll();
        $allHarvests = $this->timbanganRepo->getAll();

        return [
            'prices' => $prices,
            'harvests' => $allHarvests,
        ];
    }
}
