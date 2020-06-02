<?php

use App\Auth\Digest;
use App\FS\Directory;
use Sabre\DAV\Auth\Plugin as AuthPlugin;
use Sabre\DAV\Browser\Plugin as BrowserPlugin;
use Sabre\DAV\Locks\Backend\File as LocksFile;
use Sabre\DAV\Locks\Plugin as LocksPlugin;
use Sabre\DAV\Server;

require __DIR__ . '/../vendor/autoload.php';

$root = new Directory( '/mnt/dav' );
$server = new Server( $root );

$server->setBaseUri( '/' );

$server->addPlugin( new LocksPlugin( new LocksFile( __DIR__ . '/../.locks' ) ) );
$server->addPlugin( new AuthPlugin( new Digest() ) );
$server->addPlugin( new BrowserPlugin() );

$server->exec();
