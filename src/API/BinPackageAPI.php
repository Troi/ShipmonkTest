<?php

namespace App\API;

use App\Exception\NoSuitableBoxException;
use App\Exception\WronglyPreparedAPICallException;
use GuzzleHttp\Client;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * populate request by authorization and translates application data to request object
 */
class BinPackageAPI
{

    public function __construct(
        private readonly Client $httpClient,
        private readonly array $endpointConfiguration = [],
        private readonly ValidatorInterface $validator
    )
    {

    }

    /**
     * @param RequestDataBuilder $requestDataBuilder
     * @return int|null Packaging ID or null in case of timeout or other error
     * @throws WronglyPreparedAPICallException
     */
    public function callApi(RequestDataBuilder $requestDataBuilder): ?int
    {
        if (0 < $this->validator->validate($requestDataBuilder)->count()) {
            throw new WronglyPreparedAPICallException();
        }
        // TODO:
        //$response = $this->httpClient->post('url from configuration', ['body' => $requestDataBuilder->build()]);
        $ids = [3]; // TODO: extract ids

        if (count($ids) != 1) {
            throw new NoSuitableBoxException('There is too many or no boxes to send');
        }
        return $ids[0];
    }
}
