<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendDriverAllocation implements ShouldQueue
{
    use Queueable; // Properti $queue sudah diatur secara internal di sini

    protected $allocationData;

    public function __construct(array $allocationData)
    {
        $this->allocationData = $allocationData;
    }

    public function handle(): void
    {
        // Kosongkan karena tugas utamanya hanya melempar data ke RabbitMQ
    }

    public function displayName()
    {
        return json_encode($this->allocationData);
    }
}