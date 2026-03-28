<?php

namespace Caimari\LaraFlex\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    protected $table = 'general_settings';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $fillable = [
        'site_title',
        'site_url',
        'site_description',
        'site_phone',
        'keywords',
        'content_type',
        'content_id',
        'update_length',
        'status_page',
        'email_verification',
        'email_no_reply',
        'email_admin',
        'site_email',
        'facebook',
        'twitter',
        'linkedin',
        'google_plus',
        'github',
        'pinterest',
        'instagram',
        'rss',
        'youtube',
        'vimeo',
        'tiktok',
        'snapchat',
        'reddit',
        'telegram',
        'whatsapp',
        'use_cookies_page',
    ];
}
