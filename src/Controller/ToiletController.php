<?php

namespace App\Controller;

use App\Model\Toilet;
use Symfony\Component\HttpFoundation\Request;

class ToiletController
{
    public function findToilet(Request $request)
    {
        $id = $request->attributes->getInt('id');
        return Toilet::find($id)->toArray();
    }

    public function addToilet(Request $request)
    {
        $body = $request->getContent();
        $json = json_decode($body, true);
        $toilet = Toilet::from([
            'name' => $json['name'],
            'price' => $json['price']
        ]);
        $toilet->save();
        return 'Toilet: ' . $toilet->getID();
    }

    public function updateToilet(Request $request)
    {
        $body = $request->getContent();
        $json = json_decode($body, true);
        $toilet = Toilet::from([
            'name' => $json['name'],
            'price' => $json['price'],
            'id' => $request->attributes->get('id')
        ]);
        $toilet->save();
        return 'Toilet updated';
    }

    public function deleteToilet(Request $request)
    {
        $id = $request->attributes->get('id');
        $toilet = Toilet::from(['id' => $id]);
        $toilet->delete();
        return 'Toilet deleted';
    }

    public function getAll()
    {
        return Toilet::findAll();
    }
}
