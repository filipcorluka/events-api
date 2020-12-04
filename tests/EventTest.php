<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Event;
use Carbon\Carbon;

class EventTest extends TestCase
{

    public function test_get_event_routes_without_token()
    {
        $response = $this->json('GET', '/api/event');
        $this->assertEquals(401, $this->response->status());
        $response = $this->json('GET', '/api/event/1');
        $this->assertEquals(401, $this->response->status());
        $response = $this->json('POST', '/api/event/');
        $this->assertEquals(401, $this->response->status());
        $response = $this->json('PUT', '/api/event/1');
        $this->assertEquals(401, $this->response->status());
        $response = $this->json('DELETE', '/api/event/1');
        $this->assertEquals(401, $this->response->status());
    }

    public function test_create_event_without_data()
    {
        $eventData = [];

        $response = $this->json('POST', '/api/event', array_merge($eventData, ['api_token' => $this->getToken()]));
        $this->assertEquals(422, $this->response->status());
        $this->assertObjectHasAttribute('name', json_decode($this->response->content()));
        $this->assertObjectHasAttribute('startDate', json_decode($this->response->content()));
        $this->assertObjectHasAttribute('endDate', json_decode($this->response->content()));
    }

    public function test_create_event_incorrect_date()
    {
        $eventData = [
            'name'      => 'Event x',
            'venue'     => 'Location x',
            'lat'       => '48.135124',
            'lon'       => '11.581981',
            'startDate' => '123',
            'endDate'   => 'test'
        ];

        $response = $this->json('POST', '/api/event', array_merge($eventData, ['api_token' => $this->getToken()]));
        $this->assertEquals(422, $this->response->status());
        $this->assertObjectHasAttribute('startDate', json_decode($this->response->content()));
        $this->assertObjectHasAttribute('endDate', json_decode($this->response->content()));

        $eventData = [
            'name'      => 'Event x',
            'venue'     => 'Location x',
            'lat'       => '48.135124',
            'lon'       => '11.581981',
            'startDate' => Carbon::now()->addDays(2),
            'endDate'   => Carbon::now()->addDays(1)
        ];

        $response = $this->json('POST', '/api/event', array_merge($eventData, ['api_token' => $this->getToken()]));
        $this->assertEquals(422, $this->response->status());
        $this->assertStringContainsString('greater than or equal', $this->response->content());
    }

    public function test_create_event()
    {
        $eventData = [
            'name'      => 'Event x',
            'venue'     => 'Location x',
            'lat'       => '48.135124',
            'lon'       => '11.581981',
            'startDate' => Carbon::now()->addDays(1),
            'endDate'   => Carbon::now()->addDays(2)
        ];

        $response = $this->json('POST', '/api/event', array_merge($eventData, ['api_token' => $this->getToken()]));
        $this->assertEquals(200, $this->response->status());
        $this->assertObjectHasAttribute('id', json_decode($this->response->content()));
    }

    public function test_update_event_incorrect_date()
    {
        $event = Event::find(3);

        $eventData = [
            'startDate' => Carbon::create($event->endDate)->addDays(1)
        ];


        $response = $this->json('PUT', '/api/event/3', array_merge($eventData, ['api_token' => $this->getToken()]));
        $this->assertEquals(422, $this->response->status());
        $this->assertStringContainsString('greater than or equal', $this->response->content());


        $event = Event::find(3);

        $eventData = [
            'endDate' => Carbon::create($event->startDate)->subDays(1)
        ];
        
        $response = $this->json('PUT', '/api/event/3', array_merge($eventData, ['api_token' => $this->getToken()]));
        $this->assertEquals(422, $this->response->status());
        $this->assertStringContainsString('greater than or equal', $this->response->content());
    }


    public function test_update_event_not_owner()
    {
        $eventData = [
            'name'      => 'Event y',
            'startDate' => Carbon::now()->subDays(1),
            'endDate'   => Carbon::now()->addDays(1)
        ];

        $response = $this->json('PUT', '/api/event/1', array_merge($eventData, ['api_token' => $this->getToken()]));
        $this->assertEquals(401, $this->response->status());
    }

    public function test_update_event()
    {
        $eventData = [
            'name'      => 'Event y',
            'startDate' => Carbon::now()->subDays(1),
            'endDate'   => Carbon::now()->addDays(1)
        ];

        $response = $this->json('PUT', '/api/event/3', array_merge($eventData, ['api_token' => $this->getToken()]));
        $this->assertEquals(200, $this->response->status());
        $content = json_decode($this->response->content());
        $this->assertObjectHasAttribute('id', $content);
        $this->assertEquals('3', $content->id);
    }


    public function test_get_events()
    {
        $response = $this->json('GET', '/api/event', ['api_token' => $this->getToken()]);
        $this->assertEquals(200, $this->response->status());
        $this->assertIsArray(json_decode($this->response->content()));
    }

    public function test_get_event()
    {
        $response = $this->json('GET', '/api/event/3', ['api_token' => $this->getToken()]);
        $this->assertEquals(200, $this->response->status());
        $this->assertIsObject(json_decode($this->response->content()));
    }

    public function test_delete_active_event()
    {

        $response = $this->json('DELETE', '/api/event/3', ['api_token' => $this->getToken()]);
        $this->assertEquals(401, $this->response->status());
    }

    public function test_delete_inactive_event()
    {

        $response = $this->json('DELETE', '/api/event/4', ['api_token' => $this->getToken()]);
        $this->assertEquals(200, $this->response->status());
        $this->assertEquals(1, $this->response->content());
    }

    public function test_delete_already_deleted_event()
    {

        $response = $this->json('DELETE', '/api/event/4', ['api_token' => $this->getToken()]);
        $this->assertEquals(404, $this->response->status());
    }


    private function getToken()
    {
        $user = User::where('email', 'test@events.com')->first();
        return $user->api_token;
    }
}
