<?php

namespace App\Services;

use App\Repositories\Contracts\TambakRepositoryInterface;
use App\Repositories\Contracts\IkanRepositoryInterface;
use App\Repositories\Contracts\TimbanganRepositoryInterface;

class TambakService
{
    protected TambakRepositoryInterface $tambakRepo;
    protected IkanRepositoryInterface $ikanRepo;
    protected TimbanganRepositoryInterface $timbanganRepo;

    public function __construct(
        TambakRepositoryInterface $tambakRepo,
        IkanRepositoryInterface $ikanRepo,
        TimbanganRepositoryInterface $timbanganRepo
    ) {
        $this->tambakRepo = $tambakRepo;
        $this->ikanRepo = $ikanRepo;
        $this->timbanganRepo = $timbanganRepo;
    }

    public function getDashboardSummary(string $idTambak): array
    {
        $tambak = $this->tambakRepo->findByIdTambak($idTambak);
        $recentSensors = $this->ikanRepo->getLatestByTambak($idTambak, 15);
        $recentHarvests = $this->timbanganRepo->getByTambak($idTambak);

        return [
            'tambak' => $tambak,
            'sensors' => $recentSensors,
            'harvests' => $recentHarvests,
        ];
    }
}
