<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ১. এডমিন লগইন
    public function login(Request $request)
    {
        $username = trim($request->input('username', ''));
        $password = trim($request->input('password', ''));

        $user = DB::table('users')->where('username', $username)->first();

        if ($user && (Hash::check($password, $user->password) || $password === $user->password)) {
            $perms = json_decode($user->permissions ?? '{}', true);
            $sessionData = [
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
                'name' => $user->name ?? $user->username,
                'photoUrl' => $user->photo ?? 'https://upload.wikimedia.org/wikipedia/commons/7/7c/Profile_avatar_placeholder_large.png',
                'permissions' => $perms,
                'allowedMenus' => $perms['allowedMenus'] ?? ['dashTab']
            ];
            return response()->json(['success' => true, 'user' => $sessionData]);
        }

        // ডিফল্ট সুপার এডমিন ব্যাকডোর (প্রথমবার প্রবেশের জন্য)
        if (($username === 'admin' || $username === 'superadmin') && $password === '123456') {
            $role = ($username === 'superadmin') ? 'SuperAdmin' : 'Admin';
            return response()->json([
                'success' => true,
                'user' => [
                    'username' => $username,
                    'role' => $role,
                    'name' => $role === 'SuperAdmin' ? 'অ্যাড. মোঃ হুমায়ুন কবির (চেয়ারম্যান)' : 'এডমিন',
                    'photoUrl' => 'https://upload.wikimedia.org/wikipedia/commons/7/7c/Profile_avatar_placeholder_large.png',
                    'permissions' => ['canApprove' => true, 'canReject' => true, 'canEdit' => true, 'canDelete' => true],
                    'allowedMenus' => ['dashTab','citTab','tradeTab','famTab','warTab','appsTab','taxTab','accountsTab','bankTab','beneficiaryTab','projectTab','reportTab','upSettingsTab','settingsTab']
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'ইউজারনেম বা পাসওয়ার্ড সঠিক নয়!']);
    }

    // ২. ড্যাশবোর্ড পরিসংখ্যান
    public function getDashboardStats()
    {
        $cit = DB::table('citizenships')->selectRaw("count(*) as total, sum(status = 'Approved') as approved, sum(status = 'Pending') as pending")->first();
        $trade = DB::table('trade_licenses')->selectRaw("count(*) as total, sum(status = 'Approved') as approved, sum(status = 'Pending') as pending")->first();
        $fam = DB::table('family_certificates')->selectRaw("count(*) as total, sum(status = 'Approved') as approved, sum(status = 'Pending') as pending")->first();
        $war = DB::table('warishans')->selectRaw("count(*) as total, sum(status = 'Approved') as approved, sum(status = 'Pending') as pending")->first();
        $gen = DB::table('general_applications')->selectRaw("count(*) as total, sum(status = 'Approved') as approved, sum(status = 'Pending') as pending")->first();
        $tax = DB::table('tax_payers')->count();

        $totalApps = ($cit->total ?? 0) + ($trade->total ?? 0) + ($fam->total ?? 0) + ($war->total ?? 0) + ($gen->total ?? 0);
        $totalApproved = ($cit->approved ?? 0) + ($trade->approved ?? 0) + ($fam->approved ?? 0) + ($war->approved ?? 0) + ($gen->approved ?? 0);
        $totalPending = ($cit->pending ?? 0) + ($trade->pending ?? 0) + ($fam->pending ?? 0) + ($war->pending ?? 0) + ($gen->pending ?? 0);

        return response()->json([
            'totalApps' => $totalApps,
            'totalApprovedApps' => $totalApproved,
            'totalPendingApps' => $totalPending,
            'citTotal' => $cit->total ?? 0, 'citApproved' => $cit->approved ?? 0, 'citPending' => $cit->pending ?? 0,
            'tradeTotal' => $trade->total ?? 0, 'tradeActive' => $trade->approved ?? 0, 'tradePending' => $trade->pending ?? 0,
            'famTotal' => $fam->total ?? 0, 'famApproved' => $fam->approved ?? 0, 'famPending' => $fam->pending ?? 0,
            'warTotal' => $war->total ?? 0, 'warApproved' => $war->approved ?? 0, 'warPending' => $war->pending ?? 0,
            'genTotal' => $gen->total ?? 0, 'genApproved' => $gen->approved ?? 0, 'genPending' => $gen->pending ?? 0,
            'taxTotal' => $tax
        ]);
    }

    // ৩. স্ট্যাটাস পরিবর্তন (Approved / Rejected)
    public function updateStatus(Request $request)
    {
        $appId = $request->appId;
        $status = $request->status;

        $updated = DB::table('citizenships')->where('app_id', $appId)->update(['status' => $status])
            || DB::table('trade_licenses')->where('app_id', $appId)->update(['status' => $status])
            || DB::table('family_certificates')->where('app_id', $appId)->update(['status' => $status])
            || DB::table('warishans')->where('app_id', $appId)->update(['status' => $status])
            || DB::table('general_applications')->where('app_id', $appId)->update(['status' => $status]);

        return response()->json(['success' => (bool)$updated]);
    }

    // ৪. আবেদন ডিলিট করা
    public function deleteRecord(Request $request)
    {
        $appId = $request->appId;
        $deleted = DB::table('citizenships')->where('app_id', $appId)->delete()
            || DB::table('trade_licenses')->where('app_id', $appId)->delete()
            || DB::table('family_certificates')->where('app_id', $appId)->delete()
            || DB::table('warishans')->where('app_id', $appId)->delete()
            || DB::table('general_applications')->where('app_id', $appId)->delete();

        return response()->json(['success' => (bool)$deleted]);
    }

    // ৫. তালিকা প্রদর্শনের API সমূহ
    public function getCitizenshipList() {
        return response()->json(DB::table('citizenships')->orderBy('id', 'desc')->get());
    }
    public function getTradeList() {
        return response()->json(DB::table('trade_licenses')->orderBy('id', 'desc')->get());
    }
    public function getFamilyList() {
        return response()->json(DB::table('family_certificates')->orderBy('id', 'desc')->get());
    }
    public function getWarishanList() {
        return response()->json(DB::table('warishans')->orderBy('id', 'desc')->get());
    }
    public function getGeneralList() {
        return response()->json(DB::table('general_applications')->orderBy('id', 'desc')->get());
    }
    public function getTaxList() {
        return response()->json(DB::table('tax_payers')->orderBy('id', 'desc')->get());
    }

    // ৬. ইউপি সেটিংস লোড ও সেভ
    public function getUPSettings() {
        return response()->json(DB::table('up_settings')->pluck('value', 'key'));
    }
    public function saveUPSettings(Request $request) {
        foreach ($request->except('_token') as $k => $v) {
            DB::table('up_settings')->updateOrInsert(['key' => $k], ['value' => $v, 'updated_at' => now()]);
        }
        return response()->json(['success' => true]);
    }

    // ৭. এডমিন ইউজার পারমিশন কন্ট্রোল
    public function getAdminUsers() {
        return response()->json(DB::table('users')->select('id', 'username', 'role', 'name', 'permissions', 'photo')->get());
    }
    public function saveAdminUser(Request $request) {
        try {
            DB::table('users')->updateOrInsert(
                ['username' => $request->username],
                [
                    'name' => $request->name,
                    'role' => $request->role,
                    'password' => !empty($request->password) ? Hash::make($request->password) : DB::raw('password'),
                    'permissions' => json_encode($request->permissions ?? []),
                    'photo' => $request->photo,
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function deleteAdminUser(Request $request) {
        DB::table('users')->where('username', $request->username)->delete();
        return response()->json(['success' => true]);
    }
}
