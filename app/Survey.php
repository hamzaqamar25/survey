<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'surveys';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'survey_name',
        'survey_uuid',
        'survey_cookie',
        'client_id',
        'link',
        'status',
    ];

    /**
     * A profile belongs to a client.
     *
     * @return mixed
     */
    public function client()
    {
        return $this->hasOne('App\Client','id','client_id');
    }
}
