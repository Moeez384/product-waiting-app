<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use App\Models\Rule;

class SendMailForRuleJob implements ShouldQueue
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
        $rules = Rule::where('user_id', $this->user_id)->with('categories')->get();
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
            "Rule Title",
            "product",
            "start Date",
            "End Date",
            "Status",
            "No of Customers"
        ]);
        foreach ($rules as $rule) {
            foreach ($rule->categories as $category) {
                fputcsv($handle, [
                    $rule->title,
                    $category->title,
                    $rule->start_date,
                    $rule->end_date,
                    $rule->status,
                    $rule->no_of_customers,
                ]);
            }
        }
        fclose($handle);
        Mail::to('your_receiver_email@gmail.com')->send(new \App\Mail\SendEmailCsvForRules($file));
    }
}
