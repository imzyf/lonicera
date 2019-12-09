<?php

namespace Lonicera\core;

class Model
{
    public function save()
    {
    }

    public function deleteById()
    {
    }

    public function update()
    {
    }

    public function find()
    {
    }

    public function buildPrimaryWhere()
    {
    }

    public function getRealTableName($tableName, $prefix = '')
    {
        if (!empty($prefix)) {
            $realTableName = $prefix."_{$tableName}";
        } elseif (isset($GLOBALS['_config']['db']['prefix']) && !empty($GLOBALS['_config']['db']['prefix'])) {
            $realTableName = $GLOBALS['_config']['db']['prefix']."_{$tableName}";
        } else {
            $realTableName = $tableName;
        }

        return $realTableName;
    }

    public function buildPO($tableName, $prefix = '')
    {
        $db = DB::getInstance($GLOBALS['_config']['db']);
        $ret = $db->query('select * from `information_schema`.`columns` where TABLE_SCHEMA=:TABLE_SCHEMA AND TABLE_NAME =:TABLE_NAME', ['TABLE_NAME' => $this->getRealTableName($tableName, $prefix), 'TABLE_SCHEMA' => $db->getDbname()]);

        $className = ucfirst($tableName);
        $file = _APP.'model'.DIRECTORY_SEPARATOR.$className.'.php';
        $classString = <<<heredoc
<?php

namespace app\model;

use Lonicera\core\Model;

class {$className} extends Model
{

heredoc;

        foreach ($ret as $col) {
            if ('' !== $col['COLUMN_COMMENT']) {
                $classString .= "    // {$col['COLUMN_COMMENT']}\n";
            }
            $classString .= "    public \${$col['COLUMN_NAME']};\n";
        }
        $classString .= "}\n";

        file_put_contents($file, $classString);
    }
}
