<?php

namespace Lonicera\core;

class Model
{
    protected $rule = ['pk' => 'id'];

    public function save()
    {
        $reflect = new \ReflectionClass($this);
        // 是获取 PUBLIC 字段
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        $sqlTemplate = 'insert into '.$this->getTableNameByPO($reflect).'(';
        $keyArray = array_column($props, 'name');
        $keys = implode(',', $keyArray);
        $prepareKeys = implode(',', array_map(function ($key) {
            return ':'.$key;
        }, $keyArray));

        $sqlTemplate = 'insert into '.$this->getTableNameByPO($reflect)."({$keys}) values ({$prepareKeys})";
        $data = [];
        foreach ($keyArray as $v) {
            $data[$v] = $reflect->getProperty($v)->getValue($this);
        }

        $db = Db::getInstance($GLOBALS['_config']['db']);
        $result = $db->execute($sqlTemplate, $data);

        return $result;
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

    public function getTableNameByPO(\ReflectionClass $reflect)
    {
        return $this->getRealTableName(strtolower($reflect->getShortName()));
    }

    // 生成 po 文件
    public function buildPO($tableName, $prefix = '')
    {
        $db = DB::getInstance($GLOBALS['_config']['db']);
        $sql = 'select * from `information_schema`.`columns`';
        $sql .= ' where TABLE_SCHEMA=:TABLE_SCHEMA AND TABLE_NAME =:TABLE_NAME';
        $ret = $db->query($sql, ['TABLE_NAME' => $this->getRealTableName($tableName, $prefix), 'TABLE_SCHEMA' => $db->getDbname()]);

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
