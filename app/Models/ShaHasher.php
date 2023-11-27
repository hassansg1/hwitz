<?php
namespace App\Models;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class ShaHasher implements HasherContract {

    public function info($hashedValue){
        return false;
    }
    public function make($value, array $options = array()) {
        $value = config('services.salt', '').$value;
        // dde($value, 'password.log');
        return sha1($value);
    }

    public function check($value, $hashedValue, array $options = array()) {
        return $this->make($value) === $hashedValue;
    }

    public function needsRehash($hashedValue, array $options = array()) {
        return false;
    }

}
