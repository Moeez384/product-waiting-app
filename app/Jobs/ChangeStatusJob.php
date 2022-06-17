<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ChangeStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $customer_id;

    public function __construct($customer_id)
    {
        $this->customer_id = $customer_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $customer = Customer::find($this->customer_id);
        Mail::to($customer->email)->send(new \App\Mail\SendStatusConfirmationMail($customer->status));
    }
}
