<?php

//namespace Controllers;
//use Models\User;

include "Models/User.php";
include "Models/Invoice.php";
include "Controllers/BaseController.php";

class Controller extends BaseController
{
    public function addUser($request)
    {
        if( !$request['name'] || strlen($request['name']) < 3) {
            $this->response(['message' => 'Имя должен состоять из не менее 3 символов'], 400);
        }
        if( !$request['email'] || !filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            $this->response(['message' => 'Некорректный Email'], 400);
        }

        $userModel = new User($request);
        if ( !$userModel->save() ) {
            $this->response(['message' => 'Не удалось добавить пользователья'], 400);
        }

        $invoiceModel = Invoice::findById($userModel->invoice_id);
        if ( !$invoiceModel->recalculate() ) {
            $this->response(['message' => 'Не удалось пересчитать сумму оплаты'], 400);
        }

        $this->response([
            'message' => 'Пользователь успешно добавлено',
            'data' => [
                'user' => $userModel,
                'invoice' => $invoiceModel
            ]
        ]);
    }

    public function reset($request)
    {
        if ( !$request['invoice_id'] || !is_numeric($request['invoice_id'])) {
            $this->response(['message' => 'invoice_id объязательное поле'], 400);
        }

        if (User::removeUsersByInvoiceId($request['invoice_id'])) {
            $this->response(['message' => 'Успешно удалено']);
        }
        $this->response(['message' => 'Не удалось удалить'], 400);
    }

    public function firstLoad($request)
    {
        if ( !$request['invoice_id'] || !is_numeric($request['invoice_id'])) {
            $this->response(['message' => 'invoice_id объязательное поле'], 400);
        }

        $invoice = Invoice::findById($request['invoice_id']);
        $users = User::getUsersByInvoiceId($request['invoice_id']);

        $this->response([
            'message' => 'success',
            'data' => [
                'invoice' => $invoice,
                'users' => $users
            ]
        ]);
    }
}

