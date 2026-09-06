<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    // ১. হোমপেজ ভিউ
    public function index()
    {
        return view('home');
    }

    // ২. সার্বজনীন ট্র্যাকিং ও যাচাই সার্চ (মোবাইল, এনআইডি, নাম বা ট্র্যাকিং আইডি দিয়ে)
    public function trackApplication(Request $request)
    {
        $query = trim($request->input('query', ''));
        if (empty($query)) {
            return response()->json(['found' => false, 'list' => [], 'total' => 0]);
        }

        // বাংলা সংখ্যা থাকলে ইংরেজিতে রূপান্তর
        $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        $en = ['0','1','2','3','4','5','6','7','8','9'];
        $cleanQuery = str_replace($bn, $en, $query);

        $results = [];

        // ক. নাগরিকত্ব সনদ থেকে অনুসন্ধান
        $cit = DB::table('citizenships')
            ->where('app_id', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('cert_no', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('mobile', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('nid', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->get();
        foreach ($cit as $row) {
            $results[] = [
                'appId' => $row->app_id,
                'certNo' => $row->cert_no,
                'type' => 'নাগরিকত্ব সনদ',
                'applicantName' => $row->name,
                'fatherName' => $row->father_name,
                'mobile' => $row->mobile,
                'nid' => $row->nid,
                'status' => $row->status,
                'applyDate' => $row->apply_date
            ];
        }

        // খ. ট্রেড লাইসেন্স থেকে অনুসন্ধান
        $trade = DB::table('trade_licenses')
            ->where('app_id', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('license_no', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('mobile', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('nid', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('owner_name', 'LIKE', "%{$query}%")
            ->orWhere('org_name', 'LIKE', "%{$query}%")
            ->get();
        foreach ($trade as $row) {
            $results[] = [
                'appId' => $row->app_id,
                'licNo' => $row->license_no,
                'type' => $row->is_renewal ? 'ট্রেড লাইসেন্স নবায়ন' : 'ট্রেড লাইসেন্স',
                'applicantName' => $row->owner_name . ' (' . $row->org_name . ')',
                'fatherName' => $row->father_name,
                'mobile' => $row->mobile,
                'nid' => $row->nid,
                'status' => $row->status,
                'applyDate' => $row->apply_date
            ];
        }

        // গ. পারিবারিক ও উত্তরাধিকারী সনদ থেকে অনুসন্ধান
        $fam = DB::table('family_certificates')
            ->where('app_id', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('mobile', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('nid', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->orWhere('deceased_name', 'LIKE', "%{$query}%")
            ->get();
        foreach ($fam as $row) {
            $results[] = [
                'appId' => $row->app_id,
                'type' => $row->certificate_type,
                'applicantName' => $row->name,
                'fatherName' => $row->father_name,
                'mobile' => $row->mobile,
                'nid' => $row->nid,
                'status' => $row->status,
                'applyDate' => $row->apply_date
            ];
        }

        // ঘ. ওয়ারিশান সনদ থেকে অনুসন্ধান
        $war = DB::table('warishans')
            ->where('app_id', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('mobile', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('nid', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('applicant_name', 'LIKE', "%{$query}%")
            ->orWhere('deceased_name', 'LIKE', "%{$query}%")
            ->get();
        foreach ($war as $row) {
            $results[] = [
                'appId' => $row->app_id,
                'type' => 'ওয়ারিশান সনদ',
                'applicantName' => $row->applicant_name,
                'fatherName' => $row->father_spouse,
                'mobile' => $row->mobile,
                'nid' => $row->nid,
                'status' => $row->status,
                'applyDate' => $row->date
            ];
        }

        // ঙ. সাধারণ প্রত্যয়নপত্র থেকে অনুসন্ধান
        $gen = DB::table('general_applications')
            ->where('app_id', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('mobile', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('nid', 'LIKE', "%{$cleanQuery}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->get();
        foreach ($gen as $row) {
            $results[] = [
                'appId' => $row->app_id,
                'type' => $row->type,
                'applicantName' => $row->name,
                'fatherName' => $row->father_name,
                'mobile' => $row->mobile,
                'nid' => $row->nid,
                'status' => $row->status,
                'applyDate' => $row->apply_date
            ];
        }

        if (count($results) === 0) {
            return response()->json(['found' => false, 'list' => [], 'total' => 0]);
        }

        return response()->json([
            'found' => true,
            'total' => count($results),
            'list' => $results
        ]);
    }

    // ৩. নাগরিকত্ব সনদ সাবমিশন
    public function submitCitizenship(Request $request)
    {
        try {
            $rand = rand(100000, 999999);
            $appId = 'AUL-' . $rand;
            $dobYear = '2000';
            if ($request->dob) {
                preg_match('/\d{4}/', $request->dob, $matches);
                if (!empty($matches)) $dobYear = $matches[0];
            }
            $certNo = $dobYear . '7819510' . $rand;
            $date = date('d/m/Y');

            DB::table('citizenships')->insert([
                'app_id' => $appId,
                'cert_no' => $certNo,
                'name' => $request->name,
                'nid' => $request->nid,
                'father_name' => $request->fatherName,
                'mother_name' => $request->motherName,
                'dob' => $request->dob,
                'marital_status' => $request->maritalStatus ?? 'অবিবাহিত',
                'spouse_name' => $request->spouseName,
                'mobile' => $request->mobile,
                'ward_no' => $request->wardNo,
                'village' => $request->village,
                'post_office' => $request->postOffice ?? 'আউলিয়াপুর ময়দান',
                'division' => 'বরিশাল',
                'language' => $request->language ?? 'bn',
                'status' => 'Pending',
                'apply_date' => $date,
                'signatory_role' => 'চেয়ারম্যান',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'appId' => $appId, 'certNo' => $certNo]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // ৪. ট্রেড লাইসেন্স সাবমিশন
    public function submitTradeLicense(Request $request)
    {
        try {
            $rand = rand(100000, 999999);
            $appId = 'AUL-TR-' . $rand;
            $licNo = '199278195100' . substr($rand, 0, 4);
            $count = DB::table('trade_licenses')->count() + 1;
            $receiptNo = str_pad($count, 2, '0', STR_PAD_LEFT);
            $date = date('d/m/Y');

            DB::table('trade_licenses')->insert([
                'app_id' => $appId,
                'license_no' => $licNo,
                'receipt_no' => $receiptNo,
                'org_name' => $request->orgName,
                'owner_name' => $request->ownerName,
                'father_name' => $request->fatherName,
                'mother_name' => $request->motherName,
                'nid' => $request->nid,
                'dob' => $request->dob,
                'gender' => $request->gender ?? 'পুরুষ',
                'spouse_name' => $request->spouseName,
                'mobile' => $request->mobile,
                'owner_address' => $request->ownerAddress,
                'category' => $request->category,
                'biz_details' => $request->bizDetails,
                'biz_address' => $request->bizAddress,
                'biz_start_date' => $request->bizStartDate,
                'fiscal_year' => $request->fiscalYear ?? '২০২৬-২০২৭',
                'capital' => $request->capital,
                'license_fee' => 200,
                'vat_fee' => 30,
                'comm_tax' => $request->commTax ?? 0,
                'sign_tax' => $request->signTax ?? 0,
                'total_fee' => $request->totalFee ?? 230,
                'photo' => $request->photo,
                'status' => 'Pending',
                'apply_date' => $date,
                'signatory_role' => 'চেয়ারম্যান',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'appId' => $appId, 'licNo' => $licNo]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // ৫. ট্রেড লাইসেন্স নবায়ন সাবমিশন
    public function submitTradeRenewal(Request $request)
    {
        try {
            $rand = rand(100000, 999999);
            $renewalAppId = 'AUL-RN-' . $rand;
            $count = DB::table('trade_licenses')->count() + 1;
            $receiptNo = str_pad($count, 2, '0', STR_PAD_LEFT);
            $date = date('d/m/Y');

            DB::table('trade_licenses')->insert([
                'app_id' => $renewalAppId,
                'license_no' => $request->originalLicNo,
                'receipt_no' => $receiptNo,
                'org_name' => $request->orgName,
                'owner_name' => $request->ownerName,
                'father_name' => $request->fatherName,
                'mother_name' => $request->motherName,
                'nid' => $request->nid,
                'dob' => $request->dob,
                'mobile' => $request->mobile,
                'owner_address' => $request->ownerAddress,
                'category' => $request->category,
                'biz_details' => $request->bizDetails,
                'biz_address' => $request->bizAddress,
                'biz_start_date' => $request->bizStartDate,
                'fiscal_year' => $request->fiscalYear,
                'capital' => $request->capital,
                'license_fee' => 200,
                'vat_fee' => 30,
                'comm_tax' => $request->commTax ?? 0,
                'sign_tax' => $request->signTax ?? 0,
                'total_fee' => $request->totalFee ?? 230,
                'is_renewal' => true,
                'original_license_no' => $request->originalLicNo,
                'photo' => $request->photo,
                'status' => 'Pending',
                'apply_date' => $date,
                'signatory_role' => 'চেয়ারম্যান',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'appId' => $renewalAppId, 'licNo' => $request->originalLicNo]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // ৬. পারিবারিক ও উত্তরাধিকারী সনদ সাবমিশন
    public function submitFamily(Request $request)
    {
        try {
            $isSuccession = ($request->type === 'উত্তরাধিকারী সনদ');
            $prefix = $isSuccession ? 'AUL-UW-' : 'AUL-FW-';
            $appId = $prefix . rand(100000, 999999);
            $date = date('d/m/Y');

            DB::table('family_certificates')->insert([
                'app_id' => $appId,
                'certificate_type' => $request->type ?? 'পারিবারিক সনদ',
                'name' => $request->name,
                'nid' => $request->nid,
                'father_name' => $request->fatherName,
                'mother_name' => $request->motherName,
                'mobile' => $request->mobile,
                'ward_no' => $request->wardNo,
                'village' => $request->village,
                'post_office' => $request->postOffice ?? 'বাদুরা হাট',
                'deceased_name' => $request->deceasedName,
                'deceased_father' => $request->deceasedFather,
                'deceased_mother' => $request->deceasedMother,
                'deceased_date' => $request->deceasedDate,
                'applicant_relation' => $request->applicantRelation,
                'deceased_ward' => $request->deceasedWard,
                'deceased_village' => $request->deceasedVillage,
                'deceased_post_office' => $request->deceasedPostOffice,
                'deceased_union' => $request->deceasedUnion ?? '১১নং আউলিয়াপুর ইউনিয়ন',
                'deceased_upazila' => $request->deceasedUpazila ?? 'পটুয়াখালী সদর',
                'deceased_district' => $request->deceasedDistrict ?? 'পটুয়াখালী',
                'members_json' => json_encode($request->members ?? []),
                'status' => 'Pending',
                'apply_date' => $date,
                'signatory_role' => 'চেয়ারম্যান',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'appId' => $appId, 'type' => $request->type]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // ৭. ওয়ারিশান সনদ সাবমিশন
    public function submitWarishan(Request $request)
    {
        try {
            $appId = 'AUL-WAR-' . rand(1000, 9999);
            $date = date('d/m/Y');
            $count = DB::table('warishans')->count() + 1;
            $smarakNo = 'আ/ইউ/পটুয়া/সদর/' . date('Y') . '/' . str_pad($count, 3, '0', STR_PAD_LEFT);

            DB::table('warishans')->insert([
                'app_id' => $appId,
                'applicant_name' => $request->applicantName,
                'father_spouse' => $request->fatherSpouseName,
                'deceased_name' => $request->deceasedName,
                'deceased_father' => $request->deceasedFather,
                'deceased_relation' => $request->deceasedRelation,
                'nid' => $request->nid,
                'mobile' => $request->mobile,
                'ward_no' => $request->wardNo,
                'village' => $request->village,
                'post_office' => $request->postOffice ?? 'আউলিয়াপুর ময়দান',
                'deceased_ward_no' => $request->deceasedWardNo,
                'deceased_village' => $request->deceasedVillage,
                'deceased_post_office' => $request->deceasedPostOffice,
                'deceased_union' => $request->deceasedUnion ?? '১১নং আউলিয়াপুর ইউনিয়ন',
                'deceased_upazila' => $request->deceasedUpazila ?? 'পটুয়াখালী সদর',
                'deceased_district' => $request->deceasedDistrict ?? 'পটুয়াখালী',
                'smarak_no' => $smarakNo,
                'warishan_tree_json' => json_encode($request->warishanTree ?? []),
                'status' => 'Pending',
                'date' => $date,
                'signatory_role' => 'চেয়ারম্যান',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'appId' => $appId]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // ৮. সাধারণ প্রত্যয়নপত্র সাবমিশন
    public function submitGeneral(Request $request)
    {
        try {
            $appId = 'AUL-' . rand(100000, 999999);
            $date = date('d/m/Y');

            DB::table('general_applications')->insert([
                'app_id' => $appId,
                'type' => $request->type ?? 'সাধারণ প্রত্যয়নপত্র',
                'name' => $request->name,
                'nid' => $request->nid,
                'father_name' => $request->fatherName,
                'mother_name' => $request->motherName,
                'dob' => $request->dob,
                'mobile' => $request->mobile,
                'ward_no' => $request->wardNo,
                'village' => $request->village,
                'post_office' => $request->postOffice ?? 'আউলিয়াপুর ময়দান',
                'division' => 'বরিশাল',
                'status' => 'Pending',
                'apply_date' => $date,
                'signatory_role' => 'চেয়ারম্যান',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'appId' => $appId]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
