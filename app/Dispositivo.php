<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'dispositivos';
     
         /**
          * The attributes that are mass assignable.
          *
          * @var array
          */
         protected $fillable = ['id', 'numero_reportes','tipo','salon'];
}
