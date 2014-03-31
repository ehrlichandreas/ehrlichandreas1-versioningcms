<?php 

/**
 * Library base exception
 * 
 * @author Ehrlich, Andreas <ehrlich.andreas@googlemail.com>
 */
class EhrlichAndreas_VersioningCms_Module extends EhrlichAndreas_AbstractCms_Module
{

    /**
     *
     * @var string
     */
    private $tableVersioning = 'versioning';
	
    /**
     * Constructor
     * 
     * @param array $options
     *            Associative array of options
     * @throws MiniPhp_CmsModule_Exception
     * @return void
     */
    public function __construct ($options = array())
    {
        $options = $this->_getCmsConfigFromAdapter($options);
        
        if (! isset($options['adapterNamespace']))
        {
            $options['adapterNamespace'] = 'EhrlichAndreas_VersioningCms_Adapter';
        }
		
        if (! isset($options['exceptionclass']))
        {
            $options['exceptionclass'] = 'EhrlichAndreas_VersioningCms_Exception';
        }
        
        parent::__construct($options);
    }
    
    /**
     * 
     * @return \MiniPhp_NewsletterCms_Module
     */
    public function install()
    {
        $this->adapter->install();
        
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getTableVersioning ()
    {
        return $this->adapter->getTableName($this->tableVersioning);
    }

    /**
     * 
     * @return array
     */
    public function getFieldsVersioning ()
    {
        return array
		(
            'versioning_id' => 'versioning_id',
            'published'     => 'published',
            'updated'       => 'updated',
            'enabled'       => 'enabled',
            'extern_id'     => 'extern_id',
            'key'           => 'key',
            'value'         => 'value',
            'diff'          => 'diff',
            'version'       => 'version',
            'version_prev'  => 'version_prev',
            'active'        => 'active',
		);
    }

    /**
     * 
     * @return array
     */
    public function getKeyFieldsVersioning ()
    {
        return array
		(
			'versioning_id' => 'versioning_id',
		);
    }

	/**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return mixed
     */
    public function addVersioning ($params = array(), $returnAsString = false)
    {
        if (count($params) == 0)
        {
            return false;
        }
		
        if (! isset($params['published']) || $params['published'] == '0000-00-00 00:00:00')
        {
            $params['published'] = date('Y-m-d H:i:s', time());
        }
        
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00')
        {
            $params['updated'] = '0001-01-01 00:00:00';
        }
        
        if (! isset($params['enabled']))
        {
            $params['enabled'] = '1';
        }
        
        if (! isset($params['extern_id']))
        {
            $params['extern_id'] = '';
        }
        
        if (! isset($params['key']))
        {
            $params['key'] = '';
        }
        
        if (! isset($params['value']))
        {
            $params['value'] = '';
        }
        
        if (! isset($params['diff']))
        {
            $params['diff'] = '';
        }
        
        if (! isset($params['version']))
        {
            $params['version'] = '0';
        }
        
        if (! isset($params['version_prev']))
        {
            $params['version_prev'] = '0';
        }
        
        if (! isset($params['active']))
        {
            $params['active'] = '0';
        }
        
        if (isset($params['key']))
        {
            if (is_array($params['key']))
            {
                $params['key'] = implode('::', $params['key']);
            }
        }
		
		$function = 'Versioning';
		
		return $this->_add($function, $params, $returnAsString);
    }
    
	/**
     *
     * @param array $params
     * @return mixed
     */
    public function addNewVersion ($params = array())
    {
        if (count($params) == 0)
        {
            return false;
        }
        
        if (! isset($params['published']) || $params['published'] == '0000-00-00 00:00:00')
        {
            $params['published'] = date('Y-m-d H:i:s', time());
        }
        
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00')
        {
            $params['updated'] = '0001-01-01 00:00:00';
        }
        
        if (! isset($params['enabled']))
        {
            $params['enabled'] = '1';
        }
        
        if (! isset($params['extern_id']))
        {
            $params['extern_id'] = '';
        }
        
        if (! isset($params['key']))
        {
            $params['key'] = '';
        }
        
        if (! isset($params['value']))
        {
            $params['value'] = '';
        }
        
        if (! isset($params['diff']))
        {
            $params['diff'] = '';
        }
        
        if (! isset($params['version']))
        {
            $params['version'] = '0';
        }
        
        if (! isset($params['version_prev']))
        {
            $params['version_prev'] = '0';
        }
        
        if (! isset($params['active']))
        {
            $params['active'] = '0';
        }
        
        if (isset($params['extern_id']))
        {
            if (is_array($params['extern_id']))
            {
                $params['extern_id'] = implode('::', $params['extern_id']);
            }
        }
        
        if (isset($params['key']))
        {
            if (is_array($params['key']))
            {
                $params['key'] = implode('::', $params['key']);
            }
        }
        
        $param = array
        (
            'cols' => array
            (
                'version_max'   => new EhrlichAndreas_Db_Expr('max(version)'),
            ),
            'where' => array
            (
                'extern_id' => $params['extern_id'],
                'key'       => $params['key'],
                'enabled'   => '1',
            ),
        );
        
        $rowset = $this->getVersioning($param);
        
        if (count($rowset) == 0)
        {
            $params['version'] = '1';
        }
        else
        {
            $params['version'] = $rowset[0]['version_max'] + 1;
        }
        
        return $this->addVersioning($params);
    }
    
	/**
     *
     * @param array $params
     * @return mixed
     */
    public function activateVersion($params = array())
    {
        if (count($params) == 0)
        {
            return false;
        }
        
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00')
        {
            $params['updated'] = '0001-01-01 00:00:00';
        }
        
        if (isset($params['extern_id']))
        {
            if (is_array($params['extern_id']))
            {
                $params['extern_id'] = implode('::', $params['extern_id']);
            }
        }
        
        if (isset($params['key']))
        {
            if (is_array($params['key']))
            {
                $params['key'] = implode('::', $params['key']);
            }
        }
        
        if (! isset($params['versioning_id']))
        {
            if (! isset($params['key']))
            {
                return false;
            }
            
            if (! isset($params['extern_id']))
            {
                return false;
            }
            
            if (! isset($params['version']))
            {
                $param = array
                (
                    'cols' => array
                    (
                        'version_max'   => new EhrlichAndreas_Db_Expr('max(version)'),
                    ),
                    'where' => array
                    (
                        'extern_id' => $params['extern_id'],
                        'key'       => $params['key'],
                        'enabled'   => '1',
                    ),
                );
        
                $rowset = $this->getVersioning($param);
        
                if (count($rowset) == 0)
                {
                    $params['version'] = '1';
                }
                else
                {
                    $params['version'] = $rowset[0]['version_max'] + 1;
                }
            }
            
            $param = array
            (
                'active'    => '0',
                'where'     => array
                (
                    'extern_id' => $params['extern_id'],
                    'key'       => $params['key'],
                    'enabled'   => '1',
                ),
            );
            
            $this->editVersioning($param);
            
            $param = array
            (
                'active'    => '1',
                'where'     => array
                (
                    'extern_id' => $params['extern_id'],
                    'key'       => $params['key'],
                    'version'   => $params['version'],
                    'enabled'   => '1',
                ),
            );
            
            return $this->editVersioning($param);
        }
        else
        {
            $param = array
            (
                'cols' => array
                (
                    'extern_id' => 'extern_id',
                    'key'       => 'key',
                ),
                'where' => array
                (
                    'versioning_id' => $params['versioning_id'],
                ),
            );

            $rowset = $this->getVersioning($param);

            if (count($rowset) == 0)
            {
                return false;
            }
            else
            {
                $params['extern_id'] = $rowset[0]['extern_id'];
                $params['key'] = $rowset[0]['key'];
            }
            
            $param = array
            (
                'active'    => '0',
                'where'     => array
                (
                    'extern_id' => $params['extern_id'],
                    'key'       => $params['key'],
                    'enabled'   => '1',
                ),
            );
            
            $this->editVersioning($param);
            
            $param = array
            (
                'active'    => '1',
                'where'     => array
                (
                    'versioning_id' => $params['versioning_id'],
                    'enabled'       => '1',
                ),
            );
            
            return $this->editVersioning($param);
        }
    }
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function deleteVersioning ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
        
        if (isset($params['extern_id']))
        {
            if (is_array($params['extern_id']))
            {
                $params['extern_id'] = implode('::', $params['extern_id']);
            }
        }
        
        if (isset($params['key']))
        {
            if (is_array($params['key']))
            {
                $params['key'] = implode('::', $params['key']);
            }
        }
		
		$function = 'Versioning';
		
		return $this->_delete($function, $params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function editVersioning ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        if (! isset($params['updated']) || $params['updated'] == '0000-00-00 00:00:00')
        {
            $params['updated'] = date('Y-m-d H:i:s', time());
        }
        
        if (isset($params['extern_id']))
        {
            if (is_array($params['extern_id']))
            {
                $params['extern_id'] = implode('::', $params['extern_id']);
            }
        }
        
        if (isset($params['key']))
        {
            if (is_array($params['key']))
            {
                $params['key'] = implode('::', $params['key']);
            }
        }
		
		$function = 'Versioning';
		
		return $this->_edit($function, $params, $returnAsString);
	}

    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
    public function getVersioning ($params = array(), $returnAsString = false)
    {
        if (isset($params['extern_id']))
        {
            if (is_array($params['extern_id']))
            {
                $params['extern_id'] = implode('::', $params['extern_id']);
            }
        }
        
        if (isset($params['key']))
        {
            if (is_array($params['key']))
            {
                $params['key'] = implode('::', $params['key']);
            }
        }
        
		$function = 'Versioning';
		
		return $this->_get($function, $params, $returnAsString);
    }

    /**
     *
     * @param array $where
     * @return array
     */
    public function getVersioningList ($where = array())
    {
        if (isset($where['extern_id']))
        {
            if (is_array($where['extern_id']))
            {
                $where['extern_id'] = implode('::', $where['extern_id']);
            }
        }
        
        if (isset($where['key']))
        {
            if (is_array($where['key']))
            {
                $where['key'] = implode('::', $where['key']);
            }
        }
        
		$function = 'Versioning';
		
		return $this->_getList($function, $where);
    }
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function disableVersioning ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        $params['enabled'] = '0';
		
		return $this->editVersioning($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function enableVersioning ($params = array(), $returnAsString = false)
	{
        if (count($params) == 0)
        {
            return false;
        }
		
        $params['enabled'] = '1';
		
		return $this->editVersioning($params, $returnAsString);
	}
	
    /**
     *
     * @param array $params
     * @param boolean $returnAsString
     * @return string
     */
	public function softDeleteVersioning ($params = array(), $returnAsString = false)
	{
		return $this->disableVersioning($params, $returnAsString);
	}
    
}

