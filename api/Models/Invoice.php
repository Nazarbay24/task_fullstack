<?php

class Invoice extends BaseModel
{
    public $id;
    public $amount_total;
    public $user_count;
    public $everyone_payment;

    public function __construct($data = [])
    {
        $this->id = array_key_exists('id', $data) ? $data['id'] : null;
        $this->amount_total = array_key_exists('amount_total', $data) ? $data['amount_total'] : null;
        $this->user_count = array_key_exists('user_count', $data) ? $data['user_count'] : null;
        $this->everyone_payment = array_key_exists('everyone_payment', $data) ? $data['everyone_payment'] : null;
    }

    public static function findById($id)
    {
        $row = self::query("SELECT * FROM ".self::getTable()." WHERE id = ?", [$id])[0];

        if ($row) {
            return new self($row);
        }
        return false;
    }

    public function recalculate()
    {
//        self::query(
//            "UPDATE ".self::getTable()." i
//                SET i.user_count = (SELECT COUNT(*) FROM ".User::getTable()." u WHERE u.invoice_id = i.id),
//                    i.everyone_payment = ROUND(i.amount_total / (SELECT COUNT(*) FROM ".User::getTable()." u WHERE u.invoice_id = i.id), 2)
//                WHERE id = 1;",
//            [$this->id]
//        );

        $this->user_count = User::getUserCountByInvoiceId($this->id);
        $this->everyone_payment = round($this->amount_total / $this->user_count, 2);
        return $this->save();
    }

    public function save()
    {
        if ($this->id) {
            $res = self::query(
                "UPDATE ".self::getTable()." SET amount_total = ?, user_count = ?, everyone_payment = ? WHERE id = ?",
                [$this->amount_total, $this->user_count, $this->everyone_payment, $this->id]
            );
        }
        else {
            $res = self::query(
                "INSERT INTO ".self::getTable()." (`amount_total`, `user_count`, `everyone_payment`) VALUES (?, ?, ?)",
                [$this->amount_total, $this->user_count, $this->everyone_payment]
            );
            $this->id = Db::getInstance()->getLastInsertId();
        }

        return $res;
    }

}