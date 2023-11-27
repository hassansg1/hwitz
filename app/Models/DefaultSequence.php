<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultSequence extends Model
{
    //
    protected $guarded = [];

    protected $table = "default_sequences";

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sequenceItems()
    {
        return $this->hasMany(DefaultSequenceItems::class, 'sequence_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
