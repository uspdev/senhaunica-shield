<?php
namespace Uspdev\SenhaunicaShield\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class InstitucionalSetup extends BaseCommand
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

        CLI::write("Não se esqueça e inserir as configurações de acordo com a documentação!");
    }

}
