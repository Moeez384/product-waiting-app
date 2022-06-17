<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use App\Models\Customer;
use App\Mail\SendEmailCsv;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id;
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $customers = Customer::where('user_id', $this->user_id)->with('categories')->get();
        $headers = array(
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Disposition' => 'attachment; filename=download.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        );
        if (!File::exists(public_path() . "/files")) {
            File::makeDirectory(public_path() . "/files");
        }
        $file = time() . '.csv';

        $filename =  public_path('files/' . $file);
        $handle = fopen($filename, 'w');
        fputcsv($handle, [
            "Email",
            "Status",
            "Product Title",
        ]);
        foreach ($customers as $customer) {
            foreach ($customer->categories as $category) {
                fputcsv($handle, [
                    $customer->email,
                    $customer->status == 1 ? 'Active' : 'Not Active',
                    $category->title,
                ]);
            }
        }
        fclose($handle);
        $user = User::find($this->user_id);
        Mail::to('admin@gmail.com')->send(new \App\Mail\SendEmailCsv($file));
    }
}
