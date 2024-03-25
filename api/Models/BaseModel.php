<?php

class BaseModel
{
    protected static function query($sql, $params = [])
    {
        return Db::getInstance()->query($sql, $params);
    }


    public static function getTable()
    {
        if(property_exists(static::class, 'table')) {
            return static::$table;
        }
        else {
            return strtolower(static::class.'s');
        }
    }
}