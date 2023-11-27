<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TokenHistory extends Model
{

    protected $table = 'tokens_histories';
    protected $guarded = [];
    public $timestamps = false;

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    const TYPE_ASSIGNED = 1;
    const TYPE_ACTIVATED = 2;
    const TYPE_DEACTIVATED = 3;
    const TYPE_UNIT_OFF = 4;
    const TYPE_UNIT_ON = 5;
    const TYPE_DISCARDED = 6;
    const TYPE_RECYCLED = 7;
    const TYPE_PERMANENTLY_DISCARDED = 8;
    const TYPE_MFOB_REQUESTED = 9;
    const TYPE_MFOB_ASSIGNED = 10;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function addUserTokenHistory($user_id, $unit_id, $building_id, $author_id) {
        $model = new Token;
        $tokens = $model->find('all',array('conditions'=>array('user_id' => $user_id)));

        foreach ($tokens as $token) {

            $this->TokenHistory = new TokenHistory;
            $fob_text = ($token['Token']['mfob_status'] == 0) ? 'FOB ' : 'mFOB ';

            $TokenHistory['token_id'] = $token['Token']['id'];
            $TokenHistory['unit_id'] = $unit_id;
            $TokenHistory['building_id'] = $building_id;
            $TokenHistory['user_id'] = $user_id;
            $TokenHistory['action_date'] = date("Y-m-d H:i:s");
            $TokenHistory['action_type'] = self::TYPE_DISCARDED;
            $TokenHistory['action']    = $fob_text.$token['card_id'].' Trashed ';
            $TokenHistory['loginId'] = $author_id;
            $this->TokenHistory->addHistory($TokenHistory);

        }

    }

    public function addHistory($data) {

        $add['token_id'] = $data['token_id'];
        $add['building_id'] = $data['building_id'];
        $add['unit_id'] = isset($data['unit_id']) ? $data['unit_id'] : null;
        $add['user_id'] = isset($data['user_id']) ? $data['user_id'] : null;
        $add['action_type'] = isset($data['action_type']) ? $data['action_type'] : null;
        $add['action_date'] = date('Y-m-d H:i:s');
        $add['action_by'] = $data['loginId'];
        $add['action'] = $data['action'];

        return self::create($add);
    }
}
