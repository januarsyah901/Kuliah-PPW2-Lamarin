<?php

namespace App\Jobs;

use App\Mail\JobAppliedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendApplicationMailJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $application_id, $job_id, $user_id;
    /**
     * Create a new job instance.
     */
    public function __construct($application_id, $job_id, $user_id)
    {
        $this->application_id = $application_id;
        $this->job_id = $job_id;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $job = \App\Models\Job::find($this->job_id);
        $user = \App\Models\User::find($this->user_id);
        $application = \App\Models\Application::find($this->application_id);
        Mail::to($user->email)->send(new JobAppliedMail($job, $user, $application));
    }
}
