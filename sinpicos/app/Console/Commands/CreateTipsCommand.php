<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateTipsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-tips-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'envia tips a los usuarios en función de las recomendaciones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = \App\Models\User::all();
        $recomendations = \App\Models\Recomendation::all();

        foreach ($users as $user) {
             $recomendation = \App\Models\Recomendation::inRandomOrder()->first();
            
                $tip = new \App\Models\Tip();
                $tip->showed = false;
                $tip->user_id = $user->id;
                $tip->recomendation_id = $recomendation->id;
                $tip->save();
            
        }

        $this->info('Tips creados correctamente.');
    }
}
