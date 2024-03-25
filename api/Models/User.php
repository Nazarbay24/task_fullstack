<?php

class User extends BaseModel
{
    public $id;
    public $name;
    public $email;
    public $invoice_id;

    public function __construct($data = [])
    {
        $this->id = array_key_exists('id', $data) ? $data['id'] : null;
        $this->name = array_key_exists('name', $data) ? $data['name'] : null;
        $this->email = array_key_exists('email', $data) ? $data['email'] : null;
        $this->invoice_id = array_key_exists('invoice_id', $data) ? $data['invoice_id'] : null;
    }

    public static function getUserCountByInvoiceId($id)
    {
        $row = self::query("SELECT COUNT(*) as user_count FROM ".self::getTable()." WHERE invoice_id = ?", [$id])[0];

        return $row['user_count'];
    }

    public static function getUsersByInvoiceId($id)
    {
        return self::query("SELECT * FROM ".self::getTable()." WHERE invoice_id = ?", [$id]);
    }

    public function save()
    {
        if ($this->id) {
            $res = self::query(
                "UPDATE ".self::getTable()." SET name = ?, email = ?, invoice_id = ? WHERE id = ?",
                [$this->name, $this->email, $this->invoice_id, $this->id]
            );
        }
        else {
            $res = self::query(
                "INSERT INTO ".self::getTable()." (`name`, `email`, `invoice_id`) VALUES (?, ?, ?)",
                [$this->name, $this->email, $this->invoice_id]
            );
            $this->id = Db::getInstance()->getLastInsertId();
        }

        return $res;
    }

    public static function removeUsersByInvoiceId($invoice_id)
    {
        return self::query("DELETE FROM ".self::getTable()." WHERE invoice_id = ?", [$invoice_id]);
    }
}