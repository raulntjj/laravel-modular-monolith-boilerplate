<?php

declare(strict_types=1);

namespace Modules\Shared\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

final class ModuleTestCommand extends Command
{
    protected $signature = 'module:test 
                            {module : Nome do módulo (ex: User)} 
                            {--filter= : Filtrar testes específicos}
                            {--unit : Rodar apenas testes unitários}
                            {--integration : Rodar apenas testes de integração}
                            {--feature : Rodar apenas testes de feature}';

    protected $description = 'Executa os testes de um módulo específico';

    public function handle(): int
    {
        $module = Str::studly($this->argument('module'));
        $filter = $this->option('filter');
        $unit = $this->option('unit');
        $integration = $this->option('integration');
        $feature = $this->option('feature');

        $modulePath = base_path("modules/{$module}");
        $testsPath = "{$modulePath}/Tests";

        if (!is_dir($modulePath)) {
            $this->error("❌ Módulo '{$module}' não encontrado!");
            return self::FAILURE;
        }

        if (!is_dir($testsPath)) {
            $this->error("❌ Pasta de testes não encontrada em: {$testsPath}");
            return self::FAILURE;
        }

        // Determina o caminho específico baseado nas opções
        $testPath = $testsPath;
        if ($unit) {
            $testPath = "{$testsPath}/Unit";
            $this->info("🧪 Executando testes UNITÁRIOS do módulo {$module}...");
        } elseif ($integration) {
            $testPath = "{$testsPath}/Integration";
            $this->info("🔗 Executando testes de INTEGRAÇÃO do módulo {$module}...");
        } elseif ($feature) {
            $testPath = "{$testsPath}/Feature";
            $this->info("🎯 Executando testes de FEATURE do módulo {$module}...");
        } else {
            $this->info("🧪 Executando TODOS os testes do módulo {$module}...");
        }

        if (!is_dir($testPath)) {
            $this->error("❌ Pasta de testes não encontrada: {$testPath}");
            return self::FAILURE;
        }

        // Monta o comando PHPUnit
        $command = [
            './vendor/bin/phpunit',
            $testPath,
            '--colors=always',
        ];

        if ($filter) {
            $command[] = "--filter={$filter}";
            $this->info("🔍 Filtro aplicado: {$filter}");
        }

        $this->newLine();

        // Executa o comando
        $process = proc_open(
            implode(' ', $command),
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
            base_path()
        );

        if (is_resource($process)) {
            fclose($pipes[0]);

            while ($line = fgets($pipes[1])) {
                $this->output->write($line);
            }

            while ($line = fgets($pipes[2])) {
                $this->output->write($line);
            }

            fclose($pipes[1]);
            fclose($pipes[2]);

            $returnCode = proc_close($process);

            $this->newLine();

            if ($returnCode === 0) {
                $this->info("✅ Testes executados com sucesso!");
                return self::SUCCESS;
            }

            $this->error("❌ Alguns testes falharam.");
            return self::FAILURE;
        }

        $this->error("❌ Erro ao executar os testes.");
        return self::FAILURE;
    }
}
