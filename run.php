<?php

use App\Application;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

/** @var EntityManager $entityManager */
$entityManager = require __DIR__ . '/src/bootstrap.php';
$validator = \Symfony\Component\Validator\Validation::createValidator();
$binAPI = new \App\API\BinPackageAPI(new \GuzzleHttp\Client([]), [], $validator);
$packagingService = new \App\Service\RemotePackagingService($entityManager, $binAPI);
$packagingGuesserService = new \App\Service\PackagingGuesserService($entityManager);
$boxingService = new \App\Service\BoxingService($packagingService, $packagingGuesserService);

$request = new Request('POST', new Uri('http://localhost/pack'), ['Content-Type' => 'application/json'], $argv[1]);

$application = new Application($boxingService, $validator);
$response = $application->run($request);

echo "<<< In:\n" . Message::toString($request) . "\n\n";
echo ">>> Out:\n" . Message::toString($response) . "\n\n";
