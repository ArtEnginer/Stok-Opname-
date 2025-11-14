<?php

namespace App\Libraries;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Facade;

class Eloquent extends Manager
{
    protected $driver;

    public function __construct(Container $container = null)
    {
        parent::__construct($container);

        switch (config('Database')->default['DBDriver']) {
            case 'MySQLi':
                $this->driver = 'mysql';
                break;
            case 'Postgre':
                $this->driver = 'pgsql';
                break;
            case 'SQLite3':
                $this->driver = 'sqlite';
                break;
            case 'SQLSRV':
                $this->driver = 'sqlsrv';
                break;
            default:
                $this->driver = 'mysql';
                break;
        }

        $this->addConnection([
            'driver'    => $this->driver,
            'host'      => config('Database')->default['hostname'],
            'port'      => config('Database')->default['port'],
            'database'  => config('Database')->default['database'],
            'username'  => config('Database')->default['username'],
            'password'  => config('Database')->default['password'],
            'charset'   => config('Database')->default['charset'],
            'collation' => config('Database')->default['DBCollat'],
            'prefix'    => config('Database')->default['DBPrefix'],
            'strict'    => config('Database')->default['strictOn'],
            'schema'    => config('Database')->connect()->schema ?? 'public'
        ]);

        $this->getContainer()->singleton('events', function ($app) {
            return new Dispatcher($this->getContainer());
        });

        $this->getContainer()->singleton('db', function ($app) {
            return $this->getDatabaseManager();
        });

        $this->setAsGlobal();

        $this->bootEloquent();

        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this->getContainer());
    }
}
