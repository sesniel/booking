<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$locations = collect([
			['city' => 'Canberra & Surrounds ACT', 'state' => 'Australian Capital Territory'],
			['city' => 'Brisbane QLD', 'state' => 'Queensland'],
			['city' => 'Gold Coast QLD', 'state' => 'Queensland'],
			['city' => 'Sunshine Coast QLD', 'state' => 'Queensland'],
			['city' => 'Bundaberg QLD', 'state' => 'Queensland'],
			['city' => 'Capricorn QLD', 'state' => 'Queensland'],
			['city' => 'Fraser Coast QLD', 'state' => 'Queensland'],
			['city' => 'Gladstone QLD', 'state' => 'Queensland'],
			['city' => 'The Whitsundays QLD', 'state' => 'Queensland'],
			['city' => 'Great Barrier Reef QLD', 'state' => 'Queensland'],
			['city' => 'Mackay QLD', 'state' => 'Queensland'],
			['city' => 'Outback Queensland QLD', 'state' => 'Queensland'],
			['city' => 'Southern Queensland Country QLD', 'state' => 'Queensland'],
			['city' => 'Townsville QLD', 'state' => 'Queensland'],
			['city' => 'Blue Mountains NSW', 'state' => 'New South Wales'],
			['city' => 'Central Coast NSW', 'state' => 'New South Wales'],
			['city' => 'Country NSW', 'state' => 'New South Wales'],
			['city' => 'Hunter Valley NSW', 'state' => 'New South Wales'],
			['city' => 'Lord Howe Island NSW', 'state' => 'New South Wales'],
			['city' => 'North Coast NSW', 'state' => 'New South Wales'],
			['city' => 'Outback NSW', 'state' => 'New South Wales'],
			['city' => 'Snowy Mountains NSW', 'state' => 'New South Wales'],
			['city' => 'South Coast NSW', 'state' => 'New South Wales'],
			['city' => 'Sydney City NSW', 'state' => 'New South Wales'],
			['city' => 'Darwin & Surrounds NT', 'state' => 'Northern Territory'],
			['city' => 'Alice Springs & Surrounds NT', 'state' => 'Northern Territory'],
			['city' => 'Uluru & Surrounds NT', 'state' => 'Northern Territory'],
			['city' => 'Kakadu NT', 'state' => 'Northern Territory'],
			['city' => 'Katherine & Surrounds NT', 'state' => 'Northern Territory'],
			['city' => 'Arnhem Land NT', 'state' => 'Northern Territory'],
			['city' => 'Tennant Creek & Barkly Region NT', 'state' => 'Northern Territory'],
			['city' => 'Adelaide SA', 'state' => 'South Australia'],
			['city' => 'Adelaide Hills SA', 'state' => 'South Australia'],
			['city' => 'Barossa SA', 'state' => 'South Australia'],
			['city' => 'Clare Valley SA', 'state' => 'South Australia'],
			['city' => 'Eyre Peninsula SA', 'state' => 'South Australia'],
			['city' => 'Fleurieu Peninsula SA', 'state' => 'South Australia'],
			['city' => 'Flinders Ranges and Outback SA', 'state' => 'South Australia'],
			['city' => 'Kangaroo Island SA', 'state' => 'South Australia'],
			['city' => 'Limestone Coast SA', 'state' => 'South Australia'],
			['city' => 'Murray River, Lakes and Coorong SA', 'state' => 'South Australia'],
			['city' => 'Riverland SA', 'state' => 'South Australia'],
			['city' => 'Yorke Peninsula SA', 'state' => 'South Australia'],
			['city' => 'Launceston & North TAS', 'state' => 'Tasmania'],
			['city' => 'East Coast TAS', 'state' => 'Tasmania'],
			['city' => 'Hobart and South TAS', 'state' => 'Tasmania'],
			['city' => 'West Coast TAS', 'state' => 'Tasmania'],
			['city' => 'North West TAS', 'state' => 'Tasmania'],
			['city' => 'Daylesford & the Macedon Ranges VIC', 'state' => 'Victoria'],
			['city' => 'Geelong & the Bellarine VIC', 'state' => 'Victoria'],
			['city' => 'Gippsland VIC', 'state' => 'Victoria'],
			['city' => 'Grampians VIC', 'state' => 'Victoria'],
			['city' => 'Goldfields VIC', 'state' => 'Victoria'],
			['city' => 'High Country VIC', 'state' => 'Victoria'],
			['city' => 'Melbourne VIC', 'state' => 'Victoria'],
			['city' => 'Morning Peninsula VIC', 'state' => 'Victoria'],
			['city' => 'The Murray VIC', 'state' => 'Victoria'],
			['city' => 'Phillip Island VIC', 'state' => 'Victoria'],
			['city' => 'Yarra Valley & Dandenong Ranges VIC', 'state' => 'Victoria'],
			['city' => 'Perth and Surrounds WA', 'state' => 'Western Australia'],
			['city' => 'Margaret River & South West WA', 'state' => 'Western Australia'],
			['city' => 'Exmouth & the Coral Coast WA', 'state' => 'Western Australia'],
			['city' => 'Broome & the North West WA', 'state' => 'Western Australia'],
			['city' => 'Esperance & the Golden Outback WA', 'state' => 'Western Australia'],
			['city' => 'All Around Australia', 'state' => 'Australia Wide'],
		])->reduce(function ($locations, $location) {
			$locations[] = [
				'name' => $location['city'],
				'state' => $location['state'],
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			];

			return $locations;
		});

		DB::table('locations')->insert($locations);
	}
}
