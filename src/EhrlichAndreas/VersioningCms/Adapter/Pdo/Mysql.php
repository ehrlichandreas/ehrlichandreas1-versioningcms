<?php 

/**
 *
 * @author Ehrlich, Andreas <ehrlich.andreas@googlemail.com>
 */
class EhrlichAndreas_VersioningCms_Adapter_Pdo_Mysql extends EhrlichAndreas_AbstractCms_Adapter_Pdo_Mysql
{

    /**
     *
     * @var string
     */
    private $tableVersioning = 'versioning';
	
	/**
	 *
	 * @var string 
	 */
	protected $tableVersion = 'versioning_version';
	
    /**
     * 
     * @return \EhrlichAndreas_VersioningCms_Adapter_Pdo_Mysql
     */
	public function install ()
    {
        $this->_install_version_10000();
        
        return $this;
    }
	
	/**
	 * 
	 * @return EhrlichAndreas_VersioningCms_Adapter_Pdo_Mysql
	 */
	protected function _install_version_10000 ()
	{
		$version = '10000';
		
		$dbAdapter = $this->getConnection();
        
        $tableVersion = $this->getTableName($this->tableVersion);
		
		$versionDb = $this->_getVersion($dbAdapter, $tableVersion);
		
		if ($versionDb >= $version)
		{
			return $this;
		}
		
        $tableVersioning = $this->getTableName($this->tableVersioning);
		
        $query = array();

        $query[] = 'CREATE TABLE IF NOT EXISTS `%table%` ';
        $query[] = '( ';
        $query[] = '`num` BIGINT(19) NOT NULL AUTO_INCREMENT, ';
        $query[] = '`count` BIGINT(19) NOT NULL DEFAULT \'0\', ';
        $query[] = 'PRIMARY KEY (`num`) ';
        $query[] = ') ';
        $query[] = 'ENGINE = InnoDB ';
        $query[] = 'DEFAULT CHARACTER SET = utf8 ';
        $query[] = 'COLLATE = utf8_unicode_ci ';
        $query[] = 'AUTO_INCREMENT = 1; ';

        $query = implode("\n", $query);
        
        $queryVersion = $query;
		
		$queryVersion = str_replace('%table%', $tableVersion, $queryVersion);

        
        $query = array();

        $query[] = 'CREATE TABLE IF NOT EXISTS `%table%` ';
        $query[] = '( ';
        $query[] = '`versioning_id` BIGINT(19) unsigned NOT NULL AUTO_INCREMENT, ';
        $query[] = '`published` DATETIME NOT NULL DEFAULT \'0001-01-01 00:00:00\', ';
        $query[] = '`updated` DATETIME NOT NULL DEFAULT \'0001-01-01 00:00:00\', ';
        $query[] = '`enabled` INT(5) NOT NULL DEFAULT \'0\', ';
        $query[] = '`extern_id` varchar(255) NOT NULL, ';
        $query[] = '`key` varchar(255) NOT NULL, ';
        $query[] = '`value` TEXT NOT NULL, ';
        $query[] = '`diff` TEXT NOT NULL, ';
        $query[] = '`version` BIGINT(19) unsigned NOT NULL DEFAULT \'0\', ';
        $query[] = '`version_prev` BIGINT(19) unsigned NOT NULL DEFAULT \'0\', ';
        $query[] = '`active` INT(5) NOT NULL DEFAULT \'0\', ';
        $query[] = 'PRIMARY KEY (`﻿versioning_id`), ';
        $query[] = 'KEY `idx_key_version` (`key` (230), `version` (19)) ';
        $query[] = ') ';
        $query[] = 'ENGINE = InnoDB ';
        $query[] = 'DEFAULT CHARACTER SET = utf8 ';
        $query[] = 'COLLATE = utf8_unicode_ci ';
        $query[] = 'AUTO_INCREMENT = 1; ';

        $query = implode("\n", $query);
        
        $queryVersioning = $query;
		
		$queryVersioning = str_replace('%table%', $tableVersioning, $queryVersioning);
		
		
		if ($versionDb < $version)
		{
			$query = $queryVersion;
			
			$stmt = $dbAdapter->query($query);
            
			$stmt->closeCursor();
			
            
			$query = $queryVersioning;
			
			$stmt = $dbAdapter->query($query);
            
			$stmt->closeCursor();
			
            
			$this->_setVersion($dbAdapter, $tableVersion, $version);
		}
		
		return $this;
	}
}

