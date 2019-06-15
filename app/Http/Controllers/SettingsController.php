<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{
	public function index() {
		return view('settings.index');
	}

	public function profile_couple() {
		return view('settings.couple.profile');
	}

	public function card_account_couple() {
		return view('settings.couple.card-account');
	}

	public function notification_couple() {
		return view('settings.couple.notification');
	}

	public function profile_vendor() {
		return view('settings.vendor.profile');
	}

	public function card_account_vendor() {
		return view('settings.vendor.card-account');
	}

	public function notification_vendor() {
		return view('settings.vendor.notification');
	}

	public function terms_vendor() {
		return view('settings.vendor.terms-conditions');
	}
}
