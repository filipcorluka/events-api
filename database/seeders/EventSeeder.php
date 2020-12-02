<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class EventSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$user = User::find(1);
    	$test = User::find(2);

    	if($user) {
			$event = Event::create([
	            'name' 		=> 'Event 1',
	            'venue'		=> 'Location 1',
	            'lat' 		=> '48.135124',
	            'lon' 		=> '11.581981',
	            'startDate' => Carbon::now()->addDays(1),
	            'endDate'   => Carbon::now()->addDays(2),
	            'userId' 	=> $user->id
			]);
			$event->save();
			$event = Event::create([
	            'name' 		=> 'Event 2',
	            'venue'		=> 'Location 2',
	            'lat' 		=> '48.135124',
	            'lon' 		=> '11.581981',
	            'startDate' => Carbon::now(),
	            'endDate'   => Carbon::now()->addDays(3),
	            'userId' 	=> $user->id
			]);
			$event->save();
		}
		if ($test) {
			$event = Event::create([
	            'name' 		=> 'Event 3',
	            'venue'		=> 'Location 3',
	            'lat' 		=> '48.135124',
	            'lon' 		=> '11.581981',
	            'startDate' => Carbon::now()->addDays(5),
	            'endDate'   => Carbon::now()->addDays(7),
	            'userId' 	=> $test->id
			]);
			$event->save();
			$event = Event::create([
	            'name' 		=> 'Event 4',
	            'venue'		=> 'Location 4',
	            'lat' 		=> '48.135124',
	            'lon' 		=> '11.581981',
	            'startDate' => Carbon::now()->addDays(4),
	            'endDate'   => Carbon::now()->addDays(6),
	            'userId' 	=> $test->id
			]);
			$event->save();
		}
    }
}
