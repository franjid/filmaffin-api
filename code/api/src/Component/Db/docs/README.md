DB component
--------------

* Pending things: We will need include logger in query class to separate files queries by read/write and kind of connection. 


* Main config files

 * app/config/config.yml
 
     doctrine:
         dbal:
             default_connection: crowd_read_slave # database_connection alias for "doctrine.dbal.crowd_read_slave_connection"
             connections:
                 crowd_read_slave:
                     dbname:               "%database_name%"
                     host:                 "%database_host%"
                     port:                 "%database_port%"
                     user:                 "%database_user%"
                     password:             "%database_password%"
                     driver:               "%database_driver%"
                     logging:              true
                 crowd_read_master:
                     dbname:               "%database_name%"
                     host:                 "%database_host%"
                     port:                 "%database_port%"
                     user:                 "%database_user%"
                     password:             "%database_password%"
                     driver:               "%database_driver%"
                     logging:              true
                 crowd_write_default:
                     dbname:               "%database_name%"
                     host:                 "%database_host%"
                     port:                 "%database_port%"
                     user:                 "%database_user%"
                     password:             "%database_password%"
                     driver:               "%database_driver%"
                     logging:              true
                     
 * app/config/services_db.yml
 
    services:
        CrowdBundle.Component.Db.ReadMasterQuery:
            class: CrowdBundle\Component\Db\ReadMasterQuery
            arguments: [@doctrine.dbal.crowd_read_master_connection]
        CrowdBundle.Component.Db.ReadSlaveQuery:
            class: CrowdBundle\Component\Db\ReadSlaveQuery
            arguments: [@doctrine.dbal.crowd_read_slave_connection]
        CrowdBundle.Component.Db.WriteDefaultQuery:
            class: CrowdBundle\Component\Db\WriteDefaultQuery
            arguments: [@doctrine.dbal.crowd_write_default_connection]                    
                                

* Use a different pool connections

    * Example, we want use Slave connection:
    
        class GetUserQuery extends ReadSlaveQuery
        {
            const DIC_NAME = 'CrowdBundle.Query.Example.GetUserQuery';
        
            public function getResult($iMemberId)
            {
                $_sQuery  = 'SELECT';
                $_sQuery .=  ' member_id';
                $_sQuery .= ', login';
                $_sQuery .= ' FROM';
                $_sQuery .=  ' trivago.member';
                $_sQuery .= ' WHERE';
                $_sQuery .=  ' member_id = ' . (int) $iMemberId;
        
                return $this->oConnection->fetchAll($_sQuery);
            }
        }
        
    * To set up service GetUserQuery in src/Crowd/CrowdBundle/Resources/config/queries.yml
        
        services:
            CrowdBundle.Query.Example.GetUserQuery:
                class: Query\Example\GetUserQuery
                parent: Component.Db.ReadSlaveQuery
                
    * To execute results from this query you can execute the command:
        
        app/console crowd:test you can see a result like this:
        
            Test command
            array(1) {
              [0] =>
              array(2) {
                'member_id' =>
                string(6) "417379"
                'login' =>
                string(6) "john_b"
              }
            }
            array(3) {
              'Db' =>
              string(7) "trivago"
              'Host' =>
              string(14) "192.168.200.31"
              'Port' =>
              int(3306)
            }        
            


* Log system. Dependency with component Component\Log. Read more info in this component
        
* DBAL connections
        
    http://www.doctrine-project.org/api/dbal/2.1/class-Doctrine.DBAL.Connection.html




