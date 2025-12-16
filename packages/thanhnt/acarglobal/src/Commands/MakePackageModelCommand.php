<?php

namespace Thanhnt\Acarglobal\Commands; // Adjust namespace

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakePackageModelCommand extends Command
{
	/**
	 * php artisan acar:make-model Acar
	 * php artisan acar:make-model Home ahomeglobal
	 */
	protected $signature = 'acar:make-model {name} {package?}';
	protected $description = 'Create a new Eloquent model in the package.';

	/**
	 * this function for create model file
	 */
	public function handle()
	{
		/**
		 * input argument
		 */
		$name = $this->argument('name');
		$package = $this->argument('package') ?: 'acarglobal';

		/**
		 * define model attribute
		 */
		$modelPath = base_path('packages/thanhnt/'.$package.'/src/Models/' . $name . '.php'); // Adjust path
		$namespace = 'Thanhnt\\'.ucfirst($package).'\\Models'; // Adjust namespace

		// Create directories if they don't exist
		File::ensureDirectoryExists(dirname($modelPath));

		$stub = <<<EOT
<?php

namespace {$namespace};

use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    //
}
EOT;

		File::put($modelPath, $stub);

		$this->info("Model '{$name}' created successfully in your package.");
	}
}
