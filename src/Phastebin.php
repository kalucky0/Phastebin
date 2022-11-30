<?php

namespace Phastebin;

use Phastebin\KeyGenerators\Phonetic;
use Phastebin\KeyGenerators\Random;
use Phastebin\Stores\File;
use Phastebin\Stores\Redis;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Throwable;

class Phastebin
{
    public function __construct(private readonly App $app)
    {
        $errorMiddleware = $app->addErrorMiddleware(Config::displayErrorDetails, true, true);
        $errorMiddleware->setDefaultErrorHandler(fn($req, $e) => $this->getErrorHandler($req, $e));
        $this->addRoutes();
    }

    private function addRoutes(): void
    {
        $self = $this;
        $app = $this->app;

        /**
         * Reading a raw document
         */
        $app->get('/raw/{key}', function (Request $request, Response $response, array $args) use ($self): Response {
            $key = $args['key'];
            $store = $self->getStoreInstance();
            $document_content = $store->get($key);

            // If the document isn't found, return a JSON error
            if ($document_content === false) {
                $response->getBody()->write(json_encode([
                    'error' => 'Document not found'
                ]));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(404);
            }

            $response->getBody()->write($document_content);
            return $response->withHeader('Content-Type', 'text/plain');
        });

        /**
         * Reading a document + meta data
         */
        $app->get('/documents/{key}', function (Request $request, Response $response, array $args) use ($self): Response {
            $key = $args['key'];
            $store = $self->getStoreInstance();
            $document_content = $store->get($key);

            // If the document isn't found, return a JSON error
            if ($document_content === false) {
                $response->getBody()->write(json_encode([
                    'error' => 'Document not found'
                ]));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(404);
            }

            $response->getBody()->write(json_encode([
                'key' => $key,
                'data' => $document_content
            ], JSON_THROW_ON_ERROR));
            return $response->withHeader('Content-Type', 'application/json');
        });

        /**
         * Saving documents
         */
        $app->post('/documents', function (Request $request, Response $response) use ($self): Response {
            $key_gen = $self->getKeyGenInstance();
            $key = $key_gen->createKey();

            $store = $self->getStoreInstance();
            $doc_saved = $store->set($key, $request->getBody());

            if (!$doc_saved) {
                $response->getBody()->write(json_encode([
                    'error' => 'Could not save document'
                ]));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(500);
            }

            $response->getBody()->write(json_encode(['key' => $key], JSON_THROW_ON_ERROR));
            return $response->withHeader('Content-Type', 'application/json');
        });

        /**
         * Serving up the index page
         */
        $app->get('/[{id}]', function (Request $request, Response $response) use ($app, $self): Response {
            readfile(__DIR__ . '/../../public/main.html');
            return $response->withStatus(200)->withHeader('Content-Type', 'text/html');
        });
    }

    public function getErrorHandler(Request $request, Throwable $exception): Response
    {
        $response = $this->app->getResponseFactory()->createResponse();
        $response->withStatus(500)
            ->withHeader('Content-Type', 'text/plain')
            ->getBody()
            ->write($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
        return $response;
    }

    public function getStoreInstance(?string $type = null): Redis|File
    {
        if (!$type)
            $type = Config::storeType;

        return $type == 'File' ? new File() : new Redis();
    }


    public function getKeyGenInstance(?string $type = null): Random|Phonetic
    {
        if (!$type)
            $type = Config::keygenType;

        return $type == 'Random' ? new Random() : new Phonetic();
    }
}
