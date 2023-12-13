<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobStatus extends Model
{
    use HasFactory;

    protected $table = 'job_statuses';

    protected $connection = 'mysql';

    protected $fillable = [
        'job_id', 'type', 'queue', 'attempts', 'progress_now', 'progress_max', 'status', 'input', 'output', 'started_at', 'finished_at'
    ];
}
