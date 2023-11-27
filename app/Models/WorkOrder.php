<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;

class WorkOrder extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'workorders';

    public static $global_service_providers = array('1136'=>array('feature_1','feature_3','feature_6'),'1077'=>array('feature_2'),'948'=>array('feature_4','feature_5'));

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'modified';

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function maintainer(){
        return $this->belongsTo(User::class, 'assign_maint');
    }

    public function watcher(){
        return $this->belongsTo(User::class,'watcher');
    }
    public function resident(){
        return $this->belongsTo(User::class,'resident_id');
    }

    public function getImagesAttribute($value){
        try{
            if($value){
                $imagesArray = [];
                $images = unserialize($value);
                foreach($images as $key => $image){
                    if($image) $imagesArray[$key] = Storage::disk('s3_workorder')->url($image);
    
                }
                return $imagesArray;
            }
            return $value;
        } catch (Exception $e){
            return [];
        }
    }

    public function comments(){
        return $this->hasMany(WorkOrderComment::class,'workorder_id');
    }

    public function getTimeAvailableAttribute($value){
        return unserialize($value);
    }

    public function log(){
        return $this->hasOne(WorkOrderLog::class,'workorder_id');
    }
}
