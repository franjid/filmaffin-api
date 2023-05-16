<?php

namespace App\Infrastructure\Component\Db;

abstract class DbPoolAbstract
{
    final public const GLOBAL_READ = 'global_read_connection';
    final public const GLOBAL_WRITE = 'global_write_connection';
    final public const READ_MASTER = 'read_master_connection';
    final public const READ_SLAVE = 'read_slave_connection';
    final public const WRITE_DEFAULT = 'write_default_connection';
}
