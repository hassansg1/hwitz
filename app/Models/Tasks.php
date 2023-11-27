<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Tasks extends Model
{
    use HasFactory;

    protected $guarded = [];

	const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    use LogsActivity;

    protected static $logName = __CLASS__;
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
    protected static $logAttributesToIgnore = ['created_at', 'updated_at'];
    protected $casts = [
        'json_message' => 'json',
    ];
    protected $appends = ['target_user'];

    /**
     * Get related model
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    /**
     * Get Related Building
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function building()
    {
        return $this->belongsTo('App\Models\Building');
    }

    /**
     * Get Related User
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    /**
     * Get Related User
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User','created_by');
    }
	public function modifiedBy()
    {
        return $this->belongsTo('App\Models\User','modified_by');
    }
    public function TaskTemplate(){
        return $this->hasOne(TaskTemplate::class,'id','task_template_id');
    }

    public function getTargetUserAttribute(){
        $user = null;
        if($this->target_type == 'User'){
            $user = User::find($this->target_id);
        }
        return $user;
    }

    /** merge_template method
     * @Description It accepts the template/string and data to replace in template/string
     * and return the merged template/string.
     * @param string $template,
     * array $replace_data data to replace in template,
     * @return mixed
     * string on success, false on failure
     */
    public function merge_template($template, $replace_data) { 
        try {
            $type = $this->type;
            $before = $this->before;
            $after = $this->after;
            $options = array(
                'clean' => array(
                    'method' => $type, // or html
                ),
                'before' => $before,
                'after' => $after
            );
            //Merging of template with dynamic data
            $merged_string = self::insert($template, $replace_data, $options);
            return $merged_string;
        } catch (Exception $ex) {
            return false;
        }
    }


    public function insert($str, $data, $options = array()) {
		$defaults = array(
			'before' => ':', 'after' => null, 'escape' => '\\', 'format' => null, 'clean' => false
		);
		$options += $defaults;
		$format = $options['format'];
		$data = (array)$data;
		if (empty($data)) {
			return ($options['clean']) ? self::cleanInsert($str, $options) : $str;
		}

		if (!isset($format)) {
			$format = sprintf(
				'/(?<!%s)%s%%s%s/',
				preg_quote($options['escape'], '/'),
				str_replace('%', '%%', preg_quote($options['before'], '/')),
				str_replace('%', '%%', preg_quote($options['after'], '/'))
			);
		}

		if (strpos($str, '?') !== false && is_numeric(key($data))) {
			$offset = 0;
			while (($pos = strpos($str, '?', $offset)) !== false) {
				$val = array_shift($data);
				$offset = $pos + strlen($val);
				$str = substr_replace($str, $val, $pos, 1);
			}
			return ($options['clean']) ? self::cleanInsert($str, $options) : $str;
		}

		asort($data);

		$dataKeys = array_keys($data);
		$hashKeys = array_map('crc32', $dataKeys);
		$tempData = array_combine($dataKeys, $hashKeys);
		krsort($tempData);

		foreach ($tempData as $key => $hashVal) {
			$key = sprintf($format, preg_quote($key, '/'));
			$str = preg_replace($key, $hashVal, $str);
		}
		$dataReplacements = array_combine($hashKeys, array_values($data));
		foreach ($dataReplacements as $tmpHash => $tmpValue) {
			$tmpValue = (is_array($tmpValue)) ? '' : $tmpValue;
			$str = str_replace($tmpHash, $tmpValue, $str);
		}

		if (!isset($options['format']) && isset($options['before'])) {
			$str = str_replace($options['escape'] . $options['before'], $options['before'], $str);
		}
		return ($options['clean']) ? self::cleanInsert($str, $options) : $str;
	}
    public static function cleanInsert($str, $options) {
		$clean = $options['clean'];
		if (!$clean) {
			return $str;
		}
		if ($clean === true) {
			$clean = array('method' => 'text');
		}
		if (!is_array($clean)) {
			$clean = array('method' => $options['clean']);
		}
		switch ($clean['method']) {
			case 'html':
				$clean = array_merge(array(
					'word' => '[\w,.]+',
					'andText' => true,
					'replacement' => '',
				), $clean);
				$kleenex = sprintf(
					'/[\s]*[a-z]+=(")(%s%s%s[\s]*)+\\1/i',
					preg_quote($options['before'], '/'),
					$clean['word'],
					preg_quote($options['after'], '/')
				);
				$str = preg_replace($kleenex, $clean['replacement'], $str);
				if ($clean['andText']) {
					$options['clean'] = array('method' => 'text');
					$str = self::cleanInsert($str, $options);
				}
				break;
			case 'text':
				$clean = array_merge(array(
					'word' => '[\w,.]+',
					'gap' => '[\s]*(?:(?:and|or)[\s]*)?',
					'replacement' => '',
				), $clean);

				$kleenex = sprintf(
					'/(%s%s%s%s|%s%s%s%s)/',
					preg_quote($options['before'], '/'),
					$clean['word'],
					preg_quote($options['after'], '/'),
					$clean['gap'],
					$clean['gap'],
					preg_quote($options['before'], '/'),
					$clean['word'],
					preg_quote($options['after'], '/')
				);
				$str = preg_replace($kleenex, $clean['replacement'], $str);
				break;
		}
		return $str;
	}
}
