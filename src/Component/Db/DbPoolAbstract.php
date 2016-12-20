<?php


namespace Component\Db;

abstract class DbPoolAbstract
{
    const GLOBAL_READ = 'global_read_connection';
    const GLOBAL_WRITE = 'global_write_connection';
    const READ_MASTER = 'read_master_connection';
    const READ_SLAVE = 'read_slave_connection';
    const WRITE_DEFAULT = 'write_default_connection';
}
