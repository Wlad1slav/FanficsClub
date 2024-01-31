<?php

namespace App\Http\Controllers;

use App\Models\Collection;

class CollectionController extends Controller
{
    public function certainCollection(int $id)
    {   // certainCollection
        // /collection/{id}
        // Сторінка з колекцією певних фанфіків

        $collection = Collection::find($id);

        $data = [
            'title' => $collection->title,
            'metaDescription' => $collection->description,
            'collection' => $collection
        ];

        return view('collection', $data);
    }

}
