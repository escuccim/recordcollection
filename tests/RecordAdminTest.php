<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Escuccim\RecordCollection\Models\Record;

class RecordAdminTest extends BrowserKitTest
{
	use DatabaseTransactions;

	public function testPageStatus(){
        $user = $this->createTestUser(0);

        $this->actingAs($user)
            ->visit('/records')
            ->assertResponseOk()
            ->see(html_entity_decode(trans('record-lang::records.search')))
            ->dontSee(html_entity_decode(trans('record-lang::records.addrecord')));

        $admin = $this->createTestUser(1);

        $this->actingAs($admin)
            ->visit('/records')
            ->assertResponseOk()
            ->see(html_entity_decode(trans('record-lang::records.addrecord')));

        $this->actingAs($admin)
            ->visit('/records/create')
            ->assertResponseOk()
            ->see(html_entity_decode(trans('record-lang::records.addrecord')));
    }

    public function testModel(){
	    // add a record
        $data = $this->addSampleData(1);
        $record = $data[0];

        // make sure it is in the DB
        $this->seeInDatabase(config('records.table_name'), [
            'artist'    => $record->artist,
            'title'     => $record->title,
            'label'     => $record->label,
        ]);

        // update a record
        $record->update([
           'artist'     => 'The Artist Formerly Known as Rick',
            'title'     => 'Everyone Get Schwifty In Here',
            'label'     => 'Squanching Records',
        ]);
        $this->seeInDatabase(config('records.table_name'), [
            'artist'    => 'The Artist Formerly Known as Rick',
            'title'     => 'Everyone Get Schwifty In Here',
            'label'     => 'Squanching Records',
        ]);

        // delete a record
        $record->destroy($record->id);

        $this->notSeeInDatabase(config('records.table_name'), [
            'artist'    => 'The Artist Formerly Known as Rick',
            'title'     => 'Everyone Get Schwifty In Here',
            'label'     => 'Squanching Records',
        ]);
    }

    public function testAddRecord(){
        $admin = $this->createTestUser(1);

        $record = $this->generateTestData();
        // get a random label to select
        $label = $this->addSampleData(1)[0]->label;

        $this->actingAs($admin)
            ->visit('/records/create')
            ->type($record['artist'], 'artist')
            ->type($record['title'], 'title')
            ->type($record['catalog_no'], 'catalog_no')
            ->select($label, 'label')
            ->press(trans('record-lang::records.save'))
            ->assertResponseOk()
            ->see('The record has been created');

        // make sure it is in the database
        $this->seeInDatabase(config('records.table_name'), [
            'artist'    => $record['artist'],
            'title'     => $record['title'],
            'catalog_no'    => $record['catalog_no'],
            'label'     => $label,
        ]);
    }

    public function testEditRecord(){
        // create a user and a record to test with
        $admin = $this->createTestUser(1);
        $record = $this->addSampleData(1)[0];

        // go to show page as non-admin
        $this->visit('/records/' . $record->id)
            ->dontSee(trans('record-lang::records.editrecord'));

        // go to the show page as admin
        $this->actingAs($admin)
            ->visit('/records/' . $record->id)
            ->assertResponseOk()
            ->see($record->artist)
            ->see($record->label)
            ->see(trans('record-lang::records.editrecord'));

        // get some fake data
        $data = $this->generateTestData();

        // go to edit page
        $this->actingAs($admin)
            ->visit('/records/' . $record->id . '/edit')
            ->type($data['title'], 'title')
            ->type($data['artist'], 'artist')
            ->type($data['catalog_no'], 'catalog_no')
            ->press(trans('record-lang::records.updaterecord'))
            ->assertResponseOk()
            ->see('Your record has been updated');

        // make sure it is in the database
        $this->seeInDatabase(config('records.table_name'), [
            'artist'    => $data['artist'],
            'title'     => $data['title'],
            'catalog_no'    => $data['catalog_no'],
        ]);
    }

    private function createTestUser($admin = 0){
        $user = factory(App\User::class)->create();
        $user->type = $admin;
        $user->api_token = 'TEST_TOKEN_123';
        $user->save();

        return $user;
    }

    private function generateTestData(){
        $faker = Faker\Factory::create();
        $data = [
            'artist'    => $artist = $faker->name,
            'title'     => $faker->streetName,
            'label'     => $faker->company,
            'catalog_no'    => $faker->isbn10,
            ];

        return $data;
    }

    private function addSampleData($count = 5){
        for($i = 1; $i <= $count; $i++) {
            $data = $this->generateTestData();

            $record = new Record();
            $record->artist = $data['artist'];
            $record->title = $data['title'];
            $record->label = $data['label'];
            $record->catalog_no = $data['catalog_no'];
            $record->save();

            $returnArray[] = $record;
        }

        return $returnArray;
    }
}
