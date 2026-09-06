<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    // সহায়িকা: ইউপি অফিশিয়াল সেটিংস লোড করা (চেয়ারম্যান, সচিব ইত্যাদি)
    private function getSettings()
    {
        $rows = DB::table('up_settings')->get()->pluck('value', 'key')->toArray();
        return array_merge([
            'chairman' => 'অ্যাড. মোঃ হুমায়ুন কবির',
            'chairmanEn' => 'Adv. Md. Humayun Kabir',
            'panelChairman' => 'মোঃ আবদুস সালাম মৃধা',
            'panelChairmanEn' => 'Md. Abdus Salam Mridha',
            'secretary' => 'মোঃ মোতাহার উদ্দিন',
            'secretaryEn' => 'Md. Motahar Uddin',
        ], $rows);
    }

    // ১. নাগরিকত্ব সনদ প্রিন্ট ভিউ
    public function printCitizenship($appId)
    {
        $data = DB::table('citizenships')->where('app_id', $appId)->first();
        if (!$data) abort(404, 'আবেদনটি পাওয়া যায়নি!');
        $settings = $this->getSettings();
        return view('print.citizenship', compact('data', 'settings'));
    }

    // ২. ট্রেড লাইসেন্স প্রিন্ট ভিউ
    public function printTradeLicense($appId)
    {
        $data = DB::table('trade_licenses')->where('app_id', $appId)->first();
        if (!$data) abort(404, 'ট্রেড লাইসেন্স ডাটা পাওয়া যায়নি!');
        $settings = $this->getSettings();
        return view('print.trade_license', compact('data', 'settings'));
    }

    // ৩. পারিবারিক ও উত্তরাধিকারী সনদ প্রিন্ট ভিউ
    public function printFamily($appId)
    {
        $data = DB::table('family_certificates')->where('app_id', $appId)->first();
        if (!$data) abort(404, 'পারিবারিক আবেদন পাওয়া যায়নি!');
        $members = json_decode($data->members_json ?? '[]', true);
        $settings = $this->getSettings();
        return view('print.family', compact('data', 'members', 'settings'));
    }

    // ৪. ওয়ারিশান সনদ প্রিন্ট ভিউ (১ পেজ ও ২ পেজ অটোমেটিক হ্যান্ডেল করবে)
    public function printWarishan($appId)
    {
        $data = DB::table('warishans')->where('app_id', $appId)->first();
        if (!$data) abort(404, 'ওয়ারিশান সনদ পাওয়া যায়নি!');
        $tree = json_decode($data->warishan_tree_json ?? '[]', true);
        $settings = $this->getSettings();
        return view('print.warishan', compact('data', 'tree', 'settings'));
    }

    // ৫. সাধারণ প্রত্যয়নপত্র প্রিন্ট ভিউ (২১ প্রকার প্রত্যয়ন)
    public function printGeneral($appId)
    {
        $data = DB::table('general_applications')->where('app_id', $appId)->first();
        if (!$data) abort(404, 'প্রত্যয়নপত্র পাওয়া যায়নি!');
        $settings = $this->getSettings();
        return view('print.general', compact('data', 'settings'));
    }

    // ৬. দাখিলকৃত আবেদন কপি প্রিন্ট ভিউ (পেন্ডিং অবস্থায় যাচাইয়ের জন্য)
    public function printAppCopy($appId)
    {
        // পাঁচটি টেবিল থেকে যেকোনো একটিতে ডাটাটি আছে কি না খুঁজে নেওয়া
        $record = DB::table('citizenships')->where('app_id', $appId)->first()
            ?? DB::table('trade_licenses')->where('app_id', $appId)->first()
            ?? DB::table('family_certificates')->where('app_id', $appId)->first()
            ?? DB::table('warishans')->where('app_id', $appId)->first()
            ?? DB::table('general_applications')->where('app_id', $appId)->first();

        if (!$record) abort(404, 'আবেদন কপি পাওয়া যায়নি!');
        return view('print.app_copy', compact('record'));
    }
}
