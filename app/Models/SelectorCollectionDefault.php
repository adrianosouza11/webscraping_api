<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectorCollectionDefault extends Model
{
    protected $table = 'selector_collection_default';

    protected $attributes = [
      'id',
      'domain_url',
      'name',
      'collection_selector_json'
    ];
}