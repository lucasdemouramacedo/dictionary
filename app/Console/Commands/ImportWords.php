<?php

namespace App\Console\Commands;

use App\Services\UserService;
use App\Services\WordService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\progress;

class ImportWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-words';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->newLine(1);
            $this->info("- Buscando JSON");

            $url = "https://raw.githubusercontent.com/dwyl/english-words/refs/heads/master/words_dictionary.json";
            $response = Http::get($url);

            if (!$response->successful()) {
                $this->error("Erro ao acessar o arquivo: " . $response->status());
                return;
            }

            $json = $response->json();
            $words = array_keys($json);
            $chunks = array_chunk($words, 1000);
            $totalWords = 0;

            $this->info("- Importando " . count($words) . " palavras");
            $this->newLine(2);

            $bar = $this->output->createProgressBar(count($words));
            $bar->start();

            foreach ($chunks as $chunk) {
                $wordService = new WordService();
                $now = now();
                $wordService->bulkRegistration(array_map(fn($w) => ['word' => $w, 'created_at' => $now, 'updated_at' => $now], $chunk));
                $totalWords += count($chunk);
                $now = null;
                $progress = count($chunk);
                $bar->advance($progress);
                $progress = 0;
            }

            $bar->finish();
            $this->newLine(2);
            $this->info("- Importação finalizada! Palavras inseridas: " . $totalWords);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
