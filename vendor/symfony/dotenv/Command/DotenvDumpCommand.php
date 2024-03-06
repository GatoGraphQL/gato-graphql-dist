<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\Dotenv\Command;

use PrefixedByPoP\Symfony\Component\Console\Attribute\AsCommand;
use PrefixedByPoP\Symfony\Component\Console\Command\Command;
use PrefixedByPoP\Symfony\Component\Console\Input\InputArgument;
use PrefixedByPoP\Symfony\Component\Console\Input\InputInterface;
use PrefixedByPoP\Symfony\Component\Console\Input\InputOption;
use PrefixedByPoP\Symfony\Component\Console\Output\OutputInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use PrefixedByPoP\Symfony\Component\Dotenv\Dotenv;
/**
 * A console command to compile .env files into a PHP-optimized file called .env.local.php.
 *
 * @internal
 */
#[Autoconfigure(bind: ['$projectDir' => '%kernel.project_dir%', '$defaultEnv' => '%kernel.environment%'])]
#[AsCommand(name: 'dotenv:dump', description: 'Compile .env files to .env.local.php')]
final class DotenvDumpCommand extends Command
{
    private string $projectDir;
    private ?string $defaultEnv;
    public function __construct(string $projectDir, ?string $defaultEnv = null)
    {
        $this->projectDir = $projectDir;
        $this->defaultEnv = $defaultEnv;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setDefinition([new InputArgument('env', null === $this->defaultEnv ? InputArgument::REQUIRED : InputArgument::OPTIONAL, 'The application environment to dump .env files for - e.g. "prod".')])->addOption('empty', null, InputOption::VALUE_NONE, 'Ignore the content of .env files')->setHelp(<<<'EOT'
The <info>%command.name%</info> command compiles .env files into a PHP-optimized file called .env.local.php.

    <info>%command.full_name%</info>
EOT
);
    }
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $config = [];
        if (\is_file($projectDir = $this->projectDir)) {
            $config = ['dotenv_path' => \basename($projectDir)];
            $projectDir = \dirname($projectDir);
        }
        $composerFile = $projectDir . '/composer.json';
        $config += (\is_file($composerFile) ? \json_decode(\file_get_contents($composerFile), \true) : [])['extra']['runtime'] ?? [];
        $dotenvPath = $projectDir . '/' . ($config['dotenv_path'] ?? '.env');
        $env = $input->getArgument('env') ?? $this->defaultEnv;
        $envKey = $config['env_var_name'] ?? 'APP_ENV';
        if ($input->getOption('empty')) {
            $vars = [$envKey => $env];
        } else {
            $vars = $this->loadEnv($dotenvPath, $env, $config);
            $env = $vars[$envKey];
        }
        $vars = \var_export($vars, \true);
        $vars = <<<EOF
<?php

// This file was generated by running "php bin/console dotenv:dump {$env}"

return {$vars};

EOF;
        \file_put_contents($dotenvPath . '.local.php', $vars, \LOCK_EX);
        $output->writeln(\sprintf('Successfully dumped .env files in <info>.env.local.php</> for the <info>%s</> environment.', $env));
        return 0;
    }
    private function loadEnv(string $dotenvPath, string $env, array $config) : array
    {
        $envKey = $config['env_var_name'] ?? 'APP_ENV';
        $testEnvs = $config['test_envs'] ?? ['test'];
        $dotenv = new Dotenv($envKey);
        $globalsBackup = [$_SERVER, $_ENV];
        unset($_SERVER[$envKey]);
        $_ENV = [$envKey => $env];
        $_SERVER['SYMFONY_DOTENV_VARS'] = \implode(',', \array_keys($_SERVER));
        try {
            $dotenv->loadEnv($dotenvPath, null, 'dev', $testEnvs);
            unset($_ENV['SYMFONY_DOTENV_VARS']);
            return $_ENV;
        } finally {
            [$_SERVER, $_ENV] = $globalsBackup;
        }
    }
}
