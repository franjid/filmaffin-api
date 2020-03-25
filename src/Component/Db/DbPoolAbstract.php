<?php


namespace Component\Db;

abstract class DbPoolAbstract
{
    public const GLOBAL_READ = 'global_read_connection';
    public const GLOBAL_WRITE = 'global_write_connection';
    public const READ_MASTER = 'read_master_connection';
    public const READ_SLAVE = 'read_slave_connection';
    public const WRITE_DEFAULT = 'write_default_connection';
}
