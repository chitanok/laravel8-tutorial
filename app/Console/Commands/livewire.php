<?php

namespace App\Console\Commands;

use Livewire\Commands\MakeCommand;
use Livewire\Commands\ComponentParser;

class livewire extends MakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'livewire:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->parser = new ComponentParser(
            config('livewire.class_namespace', 'App\\Http\\Ats'),
            config('livewire.view_path', resource_path('views/ats')),
            $this->argument('name')
        );

        if($this->isReservedClassName($name = $this->parser->className())) {
            $this->line("<options=bold,reverse;fg=red> WHOOPS! </> 😳 \n");
            $this->line("<fg=red;options=bold>Class is reserved:</> {$name}");
            return;
        }

        $force = $this->option('force');
        $inline = $this->option('inline');

        $showWelcomeMessage = $this->isFirstTimeMakingAComponent();

        $class = $this->createClass($force, $inline);
        $view = $this->createView($force, $inline);

        $this->refreshComponentAutodiscovery();

        if($class || $view) {
            $this->line("<options=bold,reverse;fg=green> COMPONENT CREATED </> 🤙\n");
            $class && $this->line("<options=bold;fg=green>CLASS:</> {$this->parser->relativeClassPath()}");

            if (! $inline) {
                $view && $this->line("<options=bold;fg=green>VIEW:</>  {$this->parser->relativeViewPath()}");
            }

            if ($showWelcomeMessage && ! app()->environment('testing')) {
                $this->writeWelcomeMessage();
            }
        }
    }
}
