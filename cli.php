<?php
class CommandLine {
	
	public function run() {
		
		$container = require 'config/container.php';
		$dm = $container->get('documentManager');
		$helperSet = new \Symfony\Component\Console\Helper\HelperSet(
			[
				'dm' => new \Doctrine\ODM\MongoDB\Tools\Console\Helper\DocumentManagerHelper($dm),
			]
		);
		
		

		$app = new Symfony\Component\Console\Application('Doctrine MongoDB ODM');
		$app->setHelperSet($helperSet);
		$app->addCommands(
			[
				new \Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateHydratorsCommand(),
				new \Doctrine\ODM\MongoDB\Tools\Console\Command\GenerateProxiesCommand(),
				new \Doctrine\ODM\MongoDB\Tools\Console\Command\QueryCommand(),
				new \Doctrine\ODM\MongoDB\Tools\Console\Command\ClearCache\MetadataCommand(),
				new \Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\CreateCommand(),
				new \Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\DropCommand(),
				new \Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\UpdateCommand(),
				new \Doctrine\ODM\MongoDB\Tools\Console\Command\Schema\ShardCommand(),
			]
		);

		$app->run();
	}
}

$loader = require 'vendor/autoload.php';

$console = new CommandLine();
$console->run();