<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class RepositoryCommand extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository
                            {model : The name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'ResourceRepository';

    /**
     * Insert the model's attributes into the save() method, get from the $fillable array.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function insertAttributes(&$stub, $name)
    {
        $class = $this->getModelClass($name);
        $attributes = $this->buildAttributes($class);
        $stub = str_replace('//[Save attributes]', $attributes, $stub);
        $stub = str_replace('dummy_id', $class->getKeyName(), $stub);

        return $this;
    }

    /**
     * Build a string of PHP code for the settings of attributes in the save() method.
     *
     * @param  object  $class
     * @return string
     */
    protected function buildAttributes($class)
    {
        $attributes = '';
        foreach ($class->getFillable() as $attribute)
        {
            $attributes .= "        \$model->$attribute = \$inputs['$attribute'];\n";
        }

        return $attributes;
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
                    ->insertAttributes($stub, $this->argument('model'))
                    ->replaceClass($stub, $name);
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);
        return str_replace('DummyModel', $this->argument('model'), $stub);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('model')).'Repository';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/repository.stub';
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {

        $model = $this->argument('model');
        if(!class_exists('App\\Models\\'.$model))
        {
            $this->error('Class App\\Models\\'.$model.' doesn\'t exist!');
            return false;
        }

        $this->type = $this->getNameInput();
        return parent::handle();
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return class_exists($this->rootNamespace().'Repositories\\'.$rawName);
    }

    /**
     * Instantiate the model class from its name.
     *
     * @param  string  $name
     * @return object
     */
    protected function getModelClass($name)
    {
        $class = 'App\\Models\\'.$name;
        return new $class();
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories';
    }
}
