<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ArrayObject;

class OpenApiFactory implements OpenApiFactoryInterface
{

    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        $schemas = $openApi->getComponents()->getSecuritySchemes();
        $schemas = $this->modifySchemas($schemas);

        $contentRequestBodyOperationToken = new ArrayObject([
            'application/json' => [
                'schema' => $schemas['Credentials']
            ]
        ]);
        $requestBodyOperationToken = new RequestBody('', $contentRequestBodyOperationToken);
        $operationToken = new Operation(
            "postApiLogin",
            ['Auth'],
            [
                '200' => [
                    'description' => 'Token JWT',
                    'content' => [
                        'application/json' => [
                            'schema' => $schemas['Token']
                        ]
                    ]
                ]
            ],
            '',
            '',
            null,
            [],
            $requestBodyOperationToken
        );

        // our own post operation to explain the api/login parameters we need
        $pathItemLogin = new PathItem(null, null, null, null, null, $operationToken);

        $openApi->getPaths()->addPath('/api/login_check', $pathItemLogin);

        return $openApi;
    }

    private function modifySchemas($schemas)
    {

        $schemas = $this->addSchemaAuthorizeBearer($schemas);
        $schemas = $this->addSchemaCredentials($schemas);
        $schemas = $this->addSchemaToken($schemas);

        return $schemas;
    }

    private function addSchemaAuthorizeBearer($schemas)
    {
        // add authorize bearer in OpenApi to test authorization in the doc directly (we need to add "security"={{"bearerAuth"={}}} in the IRI concerned)
        $schemas['bearerAuth'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT'
        ]);
        return $schemas;
    }
    private function addSchemaCredentials($schemas)
    {
        $schemas['Credentials'] = new \ArrayObject(
            [
                "type" => "object",
                "properties" => [
                    'username' => [
                        'type' => 'string',
                        'example' => 'Phone Enterprise'
                    ],
                    'password' => [
                        'type' => 'string',
                        'example' => '1234Jean%1234'
                    ]

                ]
            ]
        );
        return $schemas;
    }

    private function addSchemaToken($schemas)
    {
        $schemas['Token'] = new \ArrayObject(
            [
                "type" => "object",
                "properties" => [
                    'token' => [
                        'type' => 'string',
                        'readOnly' => true
                    ]
                ]
            ]
        );
        return $schemas;
    }
}
