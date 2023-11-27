<?php

namespace App\Services\Parsers;

use App\Models\AssignSignTemplate;
use App\Models\Building;
use App\Models\Signdoc;
use App\Models\Tasks;
use App\Models\Packets;
use App\Models\TemplatesLibrary;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserTypes;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class TaskParser{

     /**
     * @var Building
     */
    public $query;
    public $building;
    public $searchresult;

    /**
     * @param $request
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function parse($request)
    {
        // dd($request->all());
        $this->searchresult = $request->all() ?? [];
        $this->query = $this->getBaseQuery($request);
        $this->applyTaskFilters($request);
        $this->applySort($request);
        $data = $this->paginate($request);
        return $data;

    }           
    /**
     * applyTaskFilters
     *
     * @return void
     */
    public function applyTaskFilters($request){
        $searchresult = $this->searchresult;

        $user_id = $request->userId != "" ? $request->userId : Auth::id();
        $user = User::find($user_id);
        $usertype_id = $user->usertype_id ?? null;
        $selectedBuilding = $request->building;

        ## FILTER by search ##
        if (!empty($this->searchresult['search_item'])) $this->query->where('tasks.name','like','%' . $this->searchresult['search_item'] . '%');

        ## FILTER by type ##
        if (!empty($this->searchresult['name'])) $this->query->where('tasks.name',$this->searchresult['name']);
        // if (!empty($this->searchresult['name'])) $this->query->whereIn('tasks.name',$this->searchresult['name']);

        ## FILTER by task state ##
        if (!empty($this->searchresult['state'])) $this->query->where('tasks.task_state',$this->searchresult['state']);
        else $this->query->whereIn('tasks.task_state',["O","C"]);
        
        ## FILTER by date range ##
        if (isset($this->searchresult['date_filter']) && $this->searchresult['date_filter'] != "") {
            $datetime = explode("-", $this->searchresult['date_filter']);

            $dateStart = $datetime[0];

            $dateEnd = $datetime[1];
            $from = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $dateStart)));
            $to = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $dateEnd)));
            $this->query->whereBetween('tasks.created', [$from, $to]);

        }

         ## FILTER BY PRIORITY ##
        if (!empty($searchresult['priority'])) $this->query->where('tasks.priorities',$searchresult['priority']);
        // if (!empty($searchresult['priority'])) $this->query->whereIn('tasks.priorities',$searchresult['priority']);
    

        ## Filter by Building Id ##
        if (!empty($searchresult['building'])) $this->query->whereNotNull('tasks.building_id')->where('tasks.building_id',$searchresult['building']);

        if (isset($selectedBuilding) && $selectedBuilding != "") {
            $building = Building::find($selectedBuilding);

            if ($usertype_id == UserTypes::$owner) {
                $owner = $building->user;
                $buildings_array = Building::where('user_id', $owner->id)->pluck('id')->toArray();
            } else {
                $buildings_array = $user->buildingIds();
            }

        } else {
            if ($usertype_id == UserTypes::$owner) {
                $buildings_array = Building::where('user_id', $user->id)->pluck('id')->toArray();
            } else {
                $buildings_array = $user->buildingIds();
            }
        }

        ## Filter by Unit Id ##
        // if (!empty($searchresult['unit']) && !in_array('all',$searchresult['unit']) ) $this->query->whereNotNull('tasks.unit_id')->whereIn('tasks.unit_id',$searchresult['unit']);
        if (!empty($searchresult['unit']) ) $this->query->whereNotNull('tasks.unit_id')->where('tasks.unit_id',$searchresult['unit']);

        ## Filter by user Id ##
        if (!empty($searchresult['user'])) {
            $this->query->where('tasks.user_id',$searchresult['user'])->orWhere('tasks.target_id',$searchresult['user']);
        }
        
        if ($usertype_id != 1) {
            $this->query->whereIn('building_id', $buildings_array);
        }
 
    } 
    
    /**
     * getBaseQuery
     *
     * @return void
     */
    public function getBaseQuery()
    {
        return Tasks::with(['TaskTemplate','createdBy','modifiedBy','user','building','unit'])->where([
            'tasks.status'    => 1,
        ]);
    }
    
    /**
     * applySort
     *
     * @param  mixed $request
     * @return void
     */
    public function applySort($request)
    {
        if (isset($request->sortOrder) && isset($request->sortBy)) {
            $this->query->orderBy($request->sortBy, $request->sortOrder);
        } else {
            $this->query = $this->query->orderBy('id', 'desc');
        }
    }
    
    /**
     * paginate
     *
     * @param  mixed $request
     * @return void
     */
    public function paginate($request)
    {
        if($request->has('showAll') && $request->showAll == 1) $data = $this->query->get(); 
        else $data = $this->query->take(10)->get(); 


        // return $this->processData($data);   
        $perPage = $request->totalItems ??  getEntriesPerPage();
        $currentPage = (int)$request->current_page;

        $total = $this->query->count();
        $data = $this->query->skip($perPage * $currentPage)->take($perPage)->get();

        $returnObj['currentPage'] = $currentPage;
        $returnObj['request'] = $request;
        $returnObj['totalPage'] = ceil(($total / $perPage));
        $returnObj['perPage'] = $perPage;
        $returnObj['currentPageCount'] = count($data);
        $returnObj['total'] = $total;
        $returnObj['currentStart'] = ($perPage * $currentPage) + 1;
        $returnObj['currentEnd'] = ($perPage * $currentPage) + count($data);
        $returnObj['data'] = $this->processData($data);
        return $returnObj;
    }
    
    /**
     * processData
     *
     * @param  mixed $tasks
     * @return void
     */
    public function processData($tasks){
        $counter = 0;
        $tasksArray = [
            'created' => [],
            'working' => [],
            'stuck' => [],
            'completed' => [],
        ];
        foreach ($tasks as $index => $task) {
            if (!empty($task['json_message']) && !empty($task['TaskTemplate']['message'])) {
                $task_message = str_replace('{link}', '', $task['TaskTemplate']['message']);
                // $merged_message = $this->merge_template($task_message, json_decode($task['json_message'], true));
                $merged_message = $this->merge_template($task_message, $task['json_message']);
                $tasks[$counter++]['message'] = html_entity_decode($merged_message);
            }

        // }
        // for ($index = 0; $index < count($tasks); $index++) {
            // $task = $tasks[$index];
            if ($task['subject_type'] == "Document") {
                $signDoc = Signdoc::where('id',$task['subject_id'])->first();
                if (!empty($signDoc)){
                    $packet = Packets::find($signDoc['packet_id']);
                }else {
                    $packet = null;
                }
                $templateString = $this->getTemplateString($signDoc);
                if (!empty($packet))
                    $tasks[$index]['packet_name'] = $packet['packet_name'];
                $tasks[$index]['templateString'] = $templateString;
                // if (empty($signDoc)) {
                //     unset($tasks[$index]); // remove item at index 0
                // }
            }
            if ($task['subject_type'] == "User") {
                $user = User::find($task['subject_id']);
                $string = $user['firstname'] . ' ' . $user['lastname'];
                if ($task['name'] == "Approve Profile Image") {
                    $unit = Unit::where('guarantor_user_id',$user['id'])->first();
                    if ($unit)
                        $string .= '<br>'.$unit['unit_no'];
                }
                $tasks[$index]['userstring'] = $string;
                $tasks[$index]['image'] = $user['profile_picture'];
            }

        // }
        
        // foreach($tasks as $task){
            $createdAt = Carbon::parse($task['modified']);
            $now = Carbon::now();

            if ($task['task_state'] === 'C') {
                // Task is completed
                $tasksArray['completed'][] = $task;
            } elseif ($createdAt->isSameDay($now)) {
                // Task created today
                $tasksArray['created'][] = $task;
            } elseif ($createdAt->diffInDays($now) < 7) {
                // Task created within the past week
                $tasksArray['working'][] = $task;
            } else {
                // Task created more than a week ago
                $tasksArray['stuck'][] = $task;
            }
        }

        return $tasksArray;
    }
    
    /**
     * getTemplateString
     *
     * @param  mixed $signDoc
     * @return void
     */
    public function getTemplateString($signDoc)
    {
        $templateNames = [];
        if (!empty($signDoc)) {
            $assignTemplates = AssignSignTemplate::where('signdoc_id',$signDoc['id'])->get();
            foreach ($assignTemplates as $assignTemplate) {
                $template = TemplatesLibrary::find($assignTemplate['signtemplate_id']);
                if (isset($template['template_name'])) {
                    $templateNames[] = $template['template_name'];
                }
            }
        }
        return implode(",", $templateNames);
    }    
    /**
     * merge_template
     *
     * @param  mixed $template
     * @param  mixed $replace_data
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

    
    /**
     * insert
     *
     * @param  mixed $str
     * @param  mixed $data
     * @param  mixed $options
     * @return void
     */    
    /**
     * insert
     *
     * @param  mixed $str
     * @param  mixed $data
     * @param  mixed $options
     * @return void
     */
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
    /**
     * cleanInsert
     *
     * @param  mixed $str
     * @param  mixed $options
     * @return void
     */
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
