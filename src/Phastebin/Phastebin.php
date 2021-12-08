<?php

namespace Phastebin;

class Phastebin
{
	private $app, $container;

	public function __construct($app)
	{
		$this->app = $app;
		$this->container = $app->getContainer();

		$this->addRoutes();
	}


	private function addRoutes()
	{
		$self = $this;
		$app = $this->app;

		/**
		 * Reading a raw document
		 */
		$app->get('/raw/{key}', function ($request, $response, $args) use ($self) {
			$key = $args['key'];
			$store = $self->getStoreInstance();
			$document_content = $store->get($key);

			// If the document isn't found, return a JSON error
			if ($document_content === false) {
				return $response->withJson(['error' => 'Document not found'], 404);
			}

			return $response->withHeader('Content-Type', 'text/plain')
				->write($document_content);
		});


		/**
		 * Reading a document + meta data
		 */
		$app->get('/documents/{key}', function ($request, $response, $args) use ($self) {
			$key = $args['key'];
			$store = $self->getStoreInstance();
			$document_content = $store->get($key);

			// If the document isn't found, return a JSON error
			if ($document_content === false) {
				return $response->withJson(['error' => 'Document not found'], 404);
			}

			return $response->withJson([
				'key' => $key,
				'data' => $document_content
			]);
		});


		/**
		 * Saving documents
		 */
		$app->post('/documents', function ($request, $response) use ($self) {
			$key_gen = $self->getkeyGenInstance();
			$key = $key_gen->createKey();

			$store = $self->getStoreInstance();
			$doc_saved = $store->set($key, $request->getBody());

			if (!$doc_saved) {
				return $response->withJson(['error' => 'Could not save document'], 500);
			}

			return $response->withJson([
				'key' => $key
			]);
		});

		/**
		 * Serving up the index page
		 */
		$app->get('/[{id}]', function () use ($app, $self) {
			readfile('main.html');
		});

		$this->container['errorHandler'] = function ($c) {
			return function ($request, $response, $exception) use ($c) {
				return $response->withStatus(500)
					->withHeader('Content-Type', 'text/plain')
					->write($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
			};
		};
	}

	public function getStoreInstance($type = null)
	{
		if (!$type)
			$type = $this->container->get('settings')['store_type'];

		$store_class = '\\Phastebin\\Stores\\' . ucfirst($type);
		$store_config = 'store_' . strtolower($type) . '_config';
		return new $store_class($this->container->get('settings')[$store_config]);
	}


	public function getkeyGenInstance($type = null)
	{
		if (!$type)
			$type = $this->container->get('settings')['keygen_type'];

		$type = "Phonetic";

		$store_class = '\\Phastebin\\KeyGenerators\\' . ucfirst($type);
		return new $store_class();
	}
}
