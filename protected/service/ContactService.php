<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 下午 05:07
 */
class ContactService
{
    public function findContact()
    {
        $result = Contact::model()->findAll();
        return $result;
    }

    /**
     * @param array $input
     * @return contact
     */
    public function create(array $inputs)
    {
        $contact = new Contact();
        $contact->name = $inputs['name'];
        $contact->phone = $inputs['phone'];
        $contact->address = $inputs['address'];
        $contact->gender= $inputs['gender'];
        $contact->createtime = date('Y-m-d H:i:s');
        $contact->type = 0;


        if (!$contact->validate()) {
            return $contact;
        }


        if (!$contact->hasErrors()) {
            $success = $contact->save();
        }

        if ($success === false) {
            $contact->addError('save_fail', '登入活動失敗');
            return $contact;
        }

        return $contact;
    }

    /**
     * @param array $inputs
     * @return CActiveRecord
     */
    public function updateContact(array $inputs)
    {
        $contact = Contact::model()->findByPk($inputs["id"]);

        $contact->id = $contact->id;
        $contact->name = $inputs['name'];
        $contact->email = $inputs['email'];
        $contact->phone = $inputs['phone'];
        $contact->detail = $inputs['detail'];
        $contact->createtime = $inputs['createtime'];
        $contact->type = $inputs['type'];

        if ($contact->validate()) {
            $contact->update();
        }

        return $contact;
    }
}