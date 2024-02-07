<?php

namespace App\Models;

use App\Models\Traits\Methods\EmailMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use EmailMethod, HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'subject', 'content'];

    public static $emails = [
        'default' => [
            'body' => '',
            'desc' => 'Body One Section',
            'subject' => '-',
            'to' => '-',
            'variables' => ['Main Content'],
        ],
        'users.include.default' => [
            'body' => 'default',
            'desc' => 'Body One Section',
            'subject' => '-',
            'to' => '-',
            'variables' => ['Main Content'],
        ],
        'users.success_register' => [
            'body' => 'users.include.default',
            'desc' => 'Body One Section',
            'subject' => 'Nayan Raval: Successfully Register #[Full Name]!',
            'to' => '',
            'variables' => ['Full Name', 'Activation Link', 'Email'],
        ],
    ];
}
