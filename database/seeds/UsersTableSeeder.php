<?php

use App\User;
use App\Media;
use App\Couple;
use App\Vendor;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$vendor1 = factory(User::class)->states('vendor')->create([
			'email' => 'vendor@gmail.com',
			'password' => 'password',
			'fname' => 'Vendor',
			'lname' => 'Vendor',
		]);

		$v1 = Vendor::where('user_id', $vendor1->id)->first();
		$tempv = factory(Vendor::class)->make(['user_id' => $vendor1->id]);
		$v1->update([
			'business_name' => $tempv->business_name,
			'trading_name' => $tempv->trading_name,
			'profile_avatar' => 'https://placeimg.com/300/300/people',
			'profile_cover' => 'https://placeimg.com/900/900/nature',
		]);

		$couple1 = factory(User::class)->states('couple')->create([
			'email' => 'couple@gmail.com',
			'password' => 'password',
			'fname' => 'Couple',
			'lname' => 'Couple',
		]);

		$c1 = Couple::where('userA_id', $couple1->id)->first();
		$tempv = factory(Couple::class)->make(['user_id' => $couple1->id]);
		$c1->update([
			'title' => $tempv->title,
			'profile_avatar' => 'https://placeimg.com/300/300/people',
			'profile_cover' => 'https://placeimg.com/900/900/nature',
		]);

		$vendor2 = factory(User::class)->states('vendor')->create([
			'email' => 'vendor2@gmail.com',
			'password' => 'password',
			'fname' => 'Vendor2',
			'lname' => 'Vendor2',
		]);

		$v2 = Vendor::where('user_id', $vendor2->id)->first();
		$tempv = factory(Vendor::class)->make(['user_id' => $vendor2->id]);
		$v2->update([
			'business_name' => $tempv->business_name,
			'trading_name' => $tempv->trading_name,
			'profile_avatar' => 'https://placeimg.com/300/300/people',
			'profile_cover' => 'https://placeimg.com/900/900/nature',
		]);

		$couple2 = factory(User::class)->states('couple')->create([
			'email' => 'couple2@gmail.com',
			'password' => 'password',
			'fname' => 'Couple2',
			'lname' => 'Couple2',
		]);

		$c2 = Couple::where('userA_id', $couple2->id)->first();
		$tempv = factory(Couple::class)->make(['user_id' => $couple2->id]);
		$c2->update([
			'title' => $tempv->title,
			'profile_avatar' => 'https://placeimg.com/300/300/people',
			'profile_cover' => 'https://placeimg.com/900/900/nature',
		]);

		$couple3 = factory(User::class)->states('couple')->create([
			'email' => 'couple@email.com',
			'password' => 'password',
			'fname' => 'couple3',
			'lname' => 'couple3',
		]);

		$c3 = Couple::where('userA_id', $couple3->id)->first();
		$tempv = factory(Couple::class)->make(['user_id' => $couple3->id]);
		$c3->update([
			'title' => $tempv->title,
			'profile_avatar' => 'https://placeimg.com/300/300/people',
			'profile_cover' => 'https://placeimg.com/900/900/nature',
		]);

		$vendor3 = factory(User::class)->states('vendor')->create([
			'email' => 'vendor@email.com',
			'password' => 'password',
			'fname' => 'vendor3',
			'lname' => 'vendor3',
		]);

		$v3 = Vendor::where('user_id', $vendor3->id)->first();
		$tempv = factory(Vendor::class)->make(['user_id' => $vendor3->id]);
		$v3->update([
			'business_name' => $tempv->business_name,
			'trading_name' => $tempv->trading_name,
			'profile_avatar' => 'https://placeimg.com/300/300/people',
			'profile_cover' => 'https://placeimg.com/900/900/nature',
		]);
	}
}
