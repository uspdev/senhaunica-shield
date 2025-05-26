<?php

namespace Uspdev\SenhaunicaShield\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Shield\Commands\Setup\ContentReplacer;

class SenhaUnicaSetup extends BaseCommand
{
    protected $group       = 'Shield';
    protected $name        = 'auth:senhaunica-setup';
    protected $description = 'Executa o setup personalizado da autenticação da senha única USP';

    public function run(array $params)
    {
        // Verifica se o Shield foi instalado
        if (! file_exists(APPPATH . 'Config/Auth.php')) {
            CLI::error("Você precisa rodar 'php spark shield:setup' antes.");
            return;
        }

        CLI::write("Migrate senha única USP...");
        service('commands')->run('migrate', ['-n', 'Uspdev\SenhaunicaShield\Resources\Migrations']);

        CLI::write("Configurando rota para loginusp...");
        $this->setupRoutes();

        CLI::write("Não se esqueça e inserir as configurações de acordo com a documentação!");
    }

    /**
     * @param string $code Code to add.
     * @param string $file Relative file path like 'Controllers/BaseController.php'.
     */
    protected function add(string $file, string $code, string $pattern, string $replace): void
    {
        $path      = APPPATH . $file;
        $cleanPath = clean_path($path);

        $content = file_get_contents($path);

        $replacer = new ContentReplacer();

        $output = $replacer->add($content, $code, $pattern, $replace);

        if ($output === true) {
            CLI::error("  Ignorado {$cleanPath}. Já está atualizado.",  'light_red');
            return;
        }
        if ($output === false) {
            CLI::error("  Erro verificando {$cleanPath}.",  'light_red');
            return;
        }

        if (write_file($path, $output)) {
            CLI::write("  Atualizado: {$cleanPath}", 'green');
        } else {
            CLI::error("  Erro ao atualizar {$cleanPath}.",  'light_red');
        }
    }

    private function setupRoutes(): void
    {
        $file = 'Config/Routes.php';

        $check   = "\$routes->get('loginusp', '\\Uspdev\\SenhaunicaShield\\Controllers\\Loginusp::loginusp', ['as' => 'auth.loginusp']);";
        $pattern = '/(.*)(\n' . preg_quote('$routes->', '/') . '[^\n]+?;\n)/su';
        $replace = '$1$2' . "\n" . $check . "\n";

        $this->add($file, $check, $pattern, $replace);
    }
}
