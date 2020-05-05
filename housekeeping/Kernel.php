<?php

namespace Housekeeping;

use Dotenv\Dotenv;
use Exception;
use Housekeeping\Database\Database;
use Housekeeping\Routing\RouteLoader;
use Housekeeping\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\ContextProvider\CliContextProvider;
use Symfony\Component\VarDumper\Dumper\ContextProvider\SourceContextProvider;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Dumper\ServerDumper;
use Symfony\Component\VarDumper\VarDumper;

class Kernel
{
    private String $envFilePath;
    private Bool $debug;
    public function __construct(String $envFilePath = __DIR__ . '/../', Bool $debug = false)
    {
        $this->envFilePath = $envFilePath;
        $this->debug = $debug;
    }

    private function setDumpServer()
    {
        $cloner = new VarCloner();
        $fallbackDumper = in_array(\PHP_SAPI, ['cli', 'phpdbg']) ? new CliDumper() : new HtmlDumper();
        $dumper = new ServerDumper('tcp://127.0.0.1:9912', $fallbackDumper, [
            'cli' => new CliContextProvider(),
            'source' => new SourceContextProvider(),
        ]);

        VarDumper::setHandler(function ($var) use ($cloner, $dumper) {
            $dumper->dump($cloner->cloneVar($var));
        });
    }

    public function boot()
    {
        $dotenv = Dotenv::createImmutable($this->envFilePath);
        $dotenv->load();

        if ($this->debug) {
            $this->setDumpServer();
            error_reporting(E_ALL);
            ini_set('display_errors', true);
        }

        Database::boot();

        RouteLoader::loadRoutes(__DIR__ . '/../routes/');
    }

    public function handleRequest()
    {
        $request = Request::createFromGlobals();
        $response = new Response();
        try {
            $route = Router::match($request);
            $response = $route->run($request);
        } catch (Exception $e) {
            // TODO: Handle custom exceptions
            dump($e);
        }
        $response->send();
    }
}
