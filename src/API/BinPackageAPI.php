<?php

namespace App\API;

use App\Exception\NoSuitableBoxException;
use App\Exception\WronglyPreparedAPICallException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * populate request by authorization and translates application data to request object
 */
class BinPackageAPI
{

    public function __construct(
        private readonly Client $httpClient,
        private readonly array $endpointConfiguration,
        private readonly ValidatorInterface $validator
    )
    {

    }

    /**
     * @param RequestDataBuilder $requestDataBuilder
     * @return int|null Packaging ID or null in case of timeout or other error
     * @throws NoSuitableBoxException
     * @throws WronglyPreparedAPICallException
     */
    public function callApi(RequestDataBuilder $requestDataBuilder): ?int
    {
        $requestDataBuilder->setAuth(
            $this->endpointConfiguration['username'],
            $this->endpointConfiguration['apiKey'],
        );

        if (0 < $this->validator->validate($requestDataBuilder)->count()) {
            throw new WronglyPreparedAPICallException();
        }
        try {
            $response = $this->httpClient->send(
                new Request(
                    'POST',
                    $this->endpointConfiguration['url'],
                    [],
                    json_encode($requestDataBuilder->build())
                )
            );
        } catch (GuzzleException $e) {
            var_dump($e);
            // TODO: log error
            return null;
        }
        $responseData = json_decode($response->getBody()->getContents(), true);
        if ($responseData === null || $responseData === false || !empty($responseData['response'])) {
            // TODO: log error
            return null;
        }
        try {
            $responseObject = PackingResponse::deserialize($responseData['response']);
        } catch (\InvalidArgumentException $exception) {
            // TODO: logg error
            return null;
        }

        if ($responseObject->criticalError) {
            throw new NoSuitableBoxException("There is critical error in response");
        }
        if (count($responseObject->errors) > 0) {
            throw new NoSuitableBoxException("There are some errors in response: ".implode("\n", $responseObject->getErrorTexts()));
        }
        $ids = $responseObject->getProductIds();

        if (count($ids) != 1) {
            throw new NoSuitableBoxException('There is too many or no boxes to send');
        }
        return $ids[0];
    }
}
