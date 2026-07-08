<?php

namespace App\Events;

use App\Models\DaftarUlangSiswa;
use App\Models\DaftarUlangChecklist;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DaftarUlangChecklistUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $siswaId;
    public $checklist;
    public $stats;

    /**
     * Create a new event instance.
     */
    public function __construct($siswaId, $checklist, $stats)
    {
        $this->siswaId = $siswaId;
        $this->checklist = $checklist;
        $this->stats = $stats;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('daftar-ulang'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'checklist.updated';
    }

    /**
     * Data to broadcast (minimal payload untuk efisiensi).
     */
    public function broadcastWith(): array
    {
        return [
            'siswa_id' => $this->siswaId,
            'raport' => $this->checklist['raport'],
            'kartu_keluarga' => $this->checklist['kartu_keluarga'],
            'akte_kelahiran' => $this->checklist['akte_kelahiran'],
            'ijazah' => $this->checklist['ijazah'],
            'status' => $this->checklist['status'],
            'verified_by_name' => $this->checklist['verified_by_name'] ?? '-',
            'verified_at' => $this->checklist['verified_at'] ?? null,
            'stats' => $this->stats,
        ];
    }
}
