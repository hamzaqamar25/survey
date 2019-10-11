<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyTracking extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'survey_trackings';

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
        'survey_id',
        'user_id',
        'publisher_id',
        'user_agent',
        'user_ip',
        'user_referer',
        'landing_time',
        'final_status',
        'final_update_time',
    ];
}
