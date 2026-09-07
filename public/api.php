<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// 🟢 TiDB Cloud ডাটাবেস কানেকশন
$host = 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = '4000';
$db   = 'test';
$user = 'GPq6ziukdiBtnU1.root'; 
$pass = 'GvhCfDZtnVJL3noh';
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_CA => true,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// টেবিলগুলো না থাকলে অটোমেটিক তৈরি করার ফাংশন
function ensureTablesExist($pdo) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS citizenships (
        id INT AUTO_INCREMENT PRIMARY KEY,
        app_id VARCHAR(50) UNIQUE, cert_no VARCHAR(100), name VARCHAR(255), nid VARCHAR(50),
        father_name VARCHAR(255), mother_name VARCHAR(255), dob VARCHAR(50), marital_status VARCHAR(50),
        spouse_name VARCHAR(255), mobile VARCHAR(50), ward_no VARCHAR(20), village VARCHAR(255),
        post_office VARCHAR(255), division VARCHAR(100), language VARCHAR(10) DEFAULT 'bn',
        status VARCHAR(50) DEFAULT 'Pending', apply_date VARCHAR(50), signatory_role VARCHAR(100) DEFAULT 'চেয়ারম্যান',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS trade_licenses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        app_id VARCHAR(50) UNIQUE, license_no VARCHAR(100), receipt_no VARCHAR(50), org_name VARCHAR(255),
        owner_name VARCHAR(255), father_name VARCHAR(255), mother_name VARCHAR(255), nid VARCHAR(50),
        dob VARCHAR(50), mobile VARCHAR(50), owner_address TEXT, category VARCHAR(255),
        biz_details TEXT, biz_address TEXT, biz_start_date VARCHAR(50), fiscal_year VARCHAR(50),
        capital VARCHAR(100), license_fee DECIMAL(10,2) DEFAULT 200, vat_fee DECIMAL(10,2) DEFAULT 30,
        comm_tax DECIMAL(10,2) DEFAULT 0, sign_tax DECIMAL(10,2) DEFAULT 0, total_fee DECIMAL(10,2) DEFAULT 230,
        is_renewal TINYINT(1) DEFAULT 0, original_license_no VARCHAR(100), photo LONGTEXT,
        status VARCHAR(50) DEFAULT 'Pending', apply_date VARCHAR(50), signatory_role VARCHAR(100) DEFAULT 'চেয়ারম্যান',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS family_certificates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        app_id VARCHAR(50) UNIQUE, certificate_type VARCHAR(100) DEFAULT 'পারিবারিক সনদ',
        name VARCHAR(255), nid VARCHAR(50), father_name VARCHAR(255), mother_name VARCHAR(255),
        mobile VARCHAR(50), ward_no VARCHAR(20), village VARCHAR(255), post_office VARCHAR(255),
        deceased_name VARCHAR(255), deceased_father VARCHAR(255), deceased_mother VARCHAR(255),
        deceased_date VARCHAR(50), applicant_relation VARCHAR(100), deceased_ward VARCHAR(20),
        deceased_village VARCHAR(255), deceased_post_office VARCHAR(255), deceased_union VARCHAR(100),
        deceased_upazila VARCHAR(100), deceased_district VARCHAR(100), members_json LONGTEXT,
        status VARCHAR(50) DEFAULT 'Pending', apply_date VARCHAR(50), signatory_role VARCHAR(100) DEFAULT 'চেয়ারম্যান',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS warishans (
        id INT AUTO_INCREMENT PRIMARY KEY,
        app_id VARCHAR(50) UNIQUE, applicant_name VARCHAR(255), father_spouse VARCHAR(255),
        deceased_name VARCHAR(255), deceased_father VARCHAR(255), deceased_relation VARCHAR(100),
        nid VARCHAR(50), mobile VARCHAR(50), ward_no VARCHAR(20), village VARCHAR(255),
        post_office VARCHAR(255), deceased_ward_no VARCHAR(20), deceased_village VARCHAR(255),
        deceased_post_office VARCHAR(255), deceased_union VARCHAR(100), deceased_upazila VARCHAR(100),
        deceased_district VARCHAR(100), smarak_no VARCHAR(100), warishan_tree_json LONGTEXT,
        status VARCHAR(50) DEFAULT 'Pending', date VARCHAR(50), signatory_role VARCHAR(100) DEFAULT 'চেয়ারম্যান',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS general_applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        app_id VARCHAR(50) UNIQUE, type VARCHAR(100), name VARCHAR(255), nid VARCHAR(50),
        father_name VARCHAR(255), mother_name VARCHAR(255), dob VARCHAR(50), mobile VARCHAR(50),
        ward_no VARCHAR(20), village VARCHAR(255), post_office VARCHAR(255), division VARCHAR(100),
        status VARCHAR(50) DEFAULT 'Pending', apply_date VARCHAR(50), signatory_role VARCHAR(100) DEFAULT 'চেয়ারম্যান',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS up_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        `key` VARCHAR(100) UNIQUE, `value` TEXT
    )");
}

ensureTablesExist($pdo);

// ইনপুট গ্রহণ
$rawInput = file_get_contents('php://input');
$request = json_decode($rawInput, true) ?: [];
$action = $request['action'] ?? $_GET['action'] ?? '';
$data = $request['data'] ?? [];

// 🟢 ১. সার্বজনীন ট্র্যাকিং (মোবাইল / এনআইডি / নাম দিয়ে)
if ($action === 'trackApplication') {
    $q = trim(is_array($data) ? ($data['query'] ?? '') : $data);
    $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    $en = ['0','1','2','3','4','5','6','7','8','9'];
    $cleanQ = str_replace($bn, $en, $q);

    $results = [];

    // নাগরিকত্ব
    $stmt = $pdo->prepare("SELECT app_id as appId, cert_no as certNo, 'নাগরিকত্ব সনদ' as type, name as applicantName, father_name as fatherName, mobile, nid, status, apply_date as applyDate FROM citizenships WHERE app_id LIKE ? OR mobile LIKE ? OR nid LIKE ? OR name LIKE ?");
    $stmt->execute(["%$cleanQ%", "%$cleanQ%", "%$cleanQ%", "%$q%"]);
    $results = array_merge($results, $stmt->fetchAll());

    // ট্রেড লাইসেন্স
    $stmt = $pdo->prepare("SELECT app_id as appId, license_no as licNo, IF(is_renewal=1, 'ট্রেড লাইসেন্স নবায়ন', 'ট্রেড লাইসেন্স') as type, CONCAT(owner_name, ' (', org_name, ')') as applicantName, father_name as fatherName, mobile, nid, status, apply_date as applyDate FROM trade_licenses WHERE app_id LIKE ? OR license_no LIKE ? OR mobile LIKE ? OR nid LIKE ? OR owner_name LIKE ? OR org_name LIKE ?");
    $stmt->execute(["%$cleanQ%", "%$cleanQ%", "%$cleanQ%", "%$cleanQ%", "%$q%", "%$q%"]);
    $results = array_merge($results, $stmt->fetchAll());

    // পারিবারিক
    $stmt = $pdo->prepare("SELECT app_id as appId, certificate_type as type, name as applicantName, father_name as fatherName, mobile, nid, status, apply_date as applyDate FROM family_certificates WHERE app_id LIKE ? OR mobile LIKE ? OR nid LIKE ? OR name LIKE ?");
    $stmt->execute(["%$cleanQ%", "%$cleanQ%", "%$cleanQ%", "%$q%"]);
    $results = array_merge($results, $stmt->fetchAll());

    // ওয়ারিশান
    $stmt = $pdo->prepare("SELECT app_id as appId, 'ওয়ারিশান সনদ' as type, applicant_name as applicantName, father_spouse as fatherName, mobile, nid, status, date as applyDate FROM warishans WHERE app_id LIKE ? OR mobile LIKE ? OR nid LIKE ? OR applicant_name LIKE ?");
    $stmt->execute(["%$cleanQ%", "%$cleanQ%", "%$cleanQ%", "%$q%"]);
    $results = array_merge($results, $stmt->fetchAll());

    // সাধারণ প্রত্যয়ন
    $stmt = $pdo->prepare("SELECT app_id as appId, type, name as applicantName, father_name as fatherName, mobile, nid, status, apply_date as applyDate FROM general_applications WHERE app_id LIKE ? OR mobile LIKE ? OR nid LIKE ? OR name LIKE ?");
    $stmt->execute(["%$cleanQ%", "%$cleanQ%", "%$cleanQ%", "%$q%"]);
    $results = array_merge($results, $stmt->fetchAll());

    if (empty($results)) {
        echo json_encode(['found' => false, 'list' => [], 'total' => 0]);
    } else {
        echo json_encode(['found' => true, 'total' => count($results), 'list' => $results]);
    }
    exit;
}

// 🟢 ২. নাগরিকত্ব আবেদন সাবমিট
if ($action === 'submitCitizenshipDirect') {
    $rand = rand(100000, 999999);
    $appId = 'AUL-' . $rand;
    $certNo = '20007819510' . $rand;
    $date = date('d/m/Y');

    $stmt = $pdo->prepare("INSERT INTO citizenships (app_id, cert_no, name, nid, father_name, mother_name, dob, marital_status, spouse_name, mobile, ward_no, village, post_office, division, language, status, apply_date, signatory_role) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $appId, $certNo, $data['name'], $data['nid'], $data['fatherName'], $data['motherName'],
        $data['dob'], $data['maritalStatus'] ?? 'অবিবাহিত', $data['spouseName'] ?? '', $data['mobile'],
        $data['wardNo'], $data['village'], $data['postOffice'] ?? 'আউলিয়াপুর ময়দান', 'বরিশাল',
        $data['language'] ?? 'bn', 'Pending', $date, 'চেয়ারম্যান'
    ]);

    echo json_encode(['success' => true, 'appId' => $appId, 'certNo' => $certNo]);
    exit;
}

// 🟢 ৩. ট্রেড লাইসেন্স সাবমিট
if ($action === 'submitTradeLicenseApplication') {
    $rand = rand(100000, 999999);
    $appId = 'AUL-TR-' . $rand;
    $licNo = '199278195100' . substr($rand, 0, 4);
    $date = date('d/m/Y');

    $stmt = $pdo->prepare("INSERT INTO trade_licenses (app_id, license_no, receipt_no, org_name, owner_name, father_name, mother_name, nid, dob, mobile, owner_address, category, biz_details, biz_address, biz_start_date, fiscal_year, capital, license_fee, vat_fee, comm_tax, sign_tax, total_fee, photo, status, apply_date, signatory_role) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $appId, $licNo, '01', $data['orgName'], $data['ownerName'], $data['fatherName'], $data['motherName'],
        $data['nid'], $data['dob'], $data['mobile'], $data['ownerAddress'], $data['category'], $data['bizDetails'] ?? '',
        $data['bizAddress'], $data['bizStartDate'] ?? '', $data['fiscalYear'] ?? '২০২৬-২০২৭', $data['capital'] ?? '',
        200, 30, $data['commTax'] ?? 0, $data['signTax'] ?? 0, $data['totalFee'] ?? 230, $data['photo'] ?? '',
        'Pending', $date, 'চেয়ারম্যান'
    ]);

    echo json_encode(['success' => true, 'appId' => $appId, 'licNo' => $licNo]);
    exit;
}

// 🟢 ৪. পারিবারিক ও উত্তরাধিকারী সাবমিট
if ($action === 'submitFamilyDirect') {
    $isSuccession = ($data['type'] === 'উত্তরাধিকারী সনদ');
    $prefix = $isSuccession ? 'AUL-UW-' : 'AUL-FW-';
    $appId = $prefix . rand(100000, 999999);
    $date = date('d/m/Y');

    $stmt = $pdo->prepare("INSERT INTO family_certificates (app_id, certificate_type, name, nid, father_name, mother_name, mobile, ward_no, village, post_office, deceased_name, deceased_father, deceased_mother, deceased_date, applicant_relation, deceased_ward, deceased_village, deceased_post_office, members_json, status, apply_date, signatory_role) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $appId, $data['type'] ?? 'পারিবারিক সনদ', $data['name'], $data['nid'], $data['fatherName'], $data['motherName'],
        $data['mobile'], $data['wardNo'], $data['village'], $data['postOffice'] ?? 'বাদুরা হাট',
        $data['deceasedName'] ?? '', $data['deceasedFather'] ?? '', $data['deceasedMother'] ?? '',
        $data['deceasedDate'] ?? '', $data['applicantRelation'] ?? '', $data['deceasedWard'] ?? '',
        $data['deceasedVillage'] ?? '', $data['deceasedPostOffice'] ?? '',
        json_encode($data['members'] ?? []), 'Pending', $date, 'চেয়ারম্যান'
    ]);

    echo json_encode(['success' => true, 'appId' => $appId]);
    exit;
}

// 🟢 ৫. ওয়ারিশান সাবমিট
if ($action === 'submitWarishanApplication') {
    $appId = 'AUL-WAR-' . rand(1000, 9999);
    $date = date('d/m/Y');
    $smarakNo = 'আ/ইউ/পটুয়া/সদর/' . date('Y') . '/' . rand(10, 99);

    $stmt = $pdo->prepare("INSERT INTO warishans (app_id, applicant_name, father_spouse, deceased_name, deceased_father, deceased_relation, nid, mobile, ward_no, village, post_office, smarak_no, warishan_tree_json, status, date, signatory_role) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $appId, $data['applicantName'], $data['fatherSpouseName'], $data['deceasedName'], $data['deceasedFather'],
        $data['deceasedRelation'] ?? 'পুত্র', $data['nid'], $data['mobile'], $data['wardNo'], $data['village'],
        $data['postOffice'] ?? 'আউলিয়াপুর ময়দান', $smarakNo, json_encode($data['warishanTree'] ?? []),
        'Pending', $date, 'চেয়ারম্যান'
    ]);

    echo json_encode(['success' => true, 'appId' => $appId]);
    exit;
}

// 🟢 ৬. সাধারণ প্রত্যয়ন সাবমিট
if ($action === 'submitApplication') {
    $appId = 'AUL-' . rand(100000, 999999);
    $date = date('d/m/Y');

    $stmt = $pdo->prepare("INSERT INTO general_applications (app_id, type, name, nid, father_name, mother_name, mobile, ward_no, village, post_office, status, apply_date, signatory_role) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $appId, $data['type'] ?? 'বিবিধ প্রত্যয়নপত্র', $data['name'], $data['nid'], $data['fatherName'],
        $data['motherName'] ?? '', $data['mobile'], $data['wardNo'], $data['village'],
        $data['postOffice'] ?? 'আউলিয়াপুর ময়দান', 'Pending', $date, 'চেয়ারম্যান'
    ]);

    echo json_encode(['success' => true, 'appId' => $appId]);
    exit;
}

// 🟢 ৭. ড্যাশবোর্ড পরিসংখ্যান (Dashboard Stats)
if ($action === 'getMasterDashboardStats') {
    $citTotal = $pdo->query("SELECT COUNT(*) FROM citizenships")->fetchColumn();
    $citApproved = $pdo->query("SELECT COUNT(*) FROM citizenships WHERE status='Approved'")->fetchColumn();
    $citPending = $citTotal - $citApproved;

    $tradeTotal = $pdo->query("SELECT COUNT(*) FROM trade_licenses")->fetchColumn();
    $tradeApproved = $pdo->query("SELECT COUNT(*) FROM trade_licenses WHERE status='Approved'")->fetchColumn();
    $tradePending = $tradeTotal - $tradeApproved;

    $famTotal = $pdo->query("SELECT COUNT(*) FROM family_certificates")->fetchColumn();
    $famApproved = $pdo->query("SELECT COUNT(*) FROM family_certificates WHERE status='Approved'")->fetchColumn();
    $famPending = $famTotal - $famApproved;

    $warTotal = $pdo->query("SELECT COUNT(*) FROM warishans")->fetchColumn();
    $warApproved = $pdo->query("SELECT COUNT(*) FROM warishans WHERE status='Approved'")->fetchColumn();
    $warPending = $warTotal - $warApproved;

    $genTotal = $pdo->query("SELECT COUNT(*) FROM general_applications")->fetchColumn();
    $genApproved = $pdo->query("SELECT COUNT(*) FROM general_applications WHERE status='Approved'")->fetchColumn();
    $genPending = $genTotal - $genApproved;

    $totalAll = $citTotal + $tradeTotal + $famTotal + $warTotal + $genTotal;
    $totalApproved = $citApproved + $tradeApproved + $famApproved + $warApproved + $genApproved;
    $totalPending = $totalAll - $totalApproved;

    echo json_encode([
        'totalApps' => $totalAll,
        'totalApprovedApps' => $totalApproved,
        'totalPendingApps' => $totalPending,
        'citTotal' => $citTotal, 'citApproved' => $citApproved, 'citPending' => $citPending,
        'tradeTotal' => $tradeTotal, 'tradeActive' => $tradeApproved, 'tradePending' => $tradePending,
        'famTotal' => $famTotal, 'famApproved' => $famApproved, 'famPending' => $famPending,
        'warTotal' => $warTotal, 'warApproved' => $warApproved, 'warPending' => $warPending,
        'genTotal' => $genTotal, 'genApproved' => $genApproved, 'genPending' => $genPending
    ]);
    exit;
}

// 🟢 ৮. নাগরিকত্ব তালিকা (Citizenship List)
if ($action === 'getCitizenshipApps') {
    $stmt = $pdo->query("SELECT app_id as appId, cert_no as certNo, name, father_name as fatherName, mobile, status FROM citizenships ORDER BY id DESC");
    echo json_encode($stmt->fetchAll());
    exit;
}

// 🟢 ৯. ট্রেড লাইসেন্স তালিকা (Trade License List)
if ($action === 'getTradeLicenses' || $action === 'getTradeRenewals') {
    $stmt = $pdo->query("SELECT app_id as appId, license_no as licNo, org_name as orgName, owner_name as ownerName, father_name as fatherName, mobile, total_fee as totalFee, status, is_renewal as isRenewal FROM trade_licenses ORDER BY id DESC");
    echo json_encode($stmt->fetchAll());
    exit;
}

// 🟢 ১০. পারিবারিক ও উত্তরাধিকারী তালিকা (Family List)
if ($action === 'getFamilyApps') {
    $stmt = $pdo->query("SELECT app_id as appId, certificate_type as type, name, nid, father_name as fatherName, mobile, ward_no as wardNo, village, status FROM family_certificates ORDER BY id DESC");
    echo json_encode($stmt->fetchAll());
    exit;
}

// 🟢 ১১. ওয়ারিশান তালিকা (Warishan List)
if ($action === 'getWarishanApps') {
    $stmt = $pdo->query("SELECT app_id as appId, applicant_name as applicantName, deceased_name as deceasedName, deceased_father as deceasedFather, mobile, village, status FROM warishans ORDER BY id DESC");
    echo json_encode($stmt->fetchAll());
    exit;
}

// 🟢 ১২. সাধারণ প্রত্যয়ন তালিকা (General Apps List)
if ($action === 'getAllApplications') {
    $stmt = $pdo->query("SELECT app_id as appId, type, name, nid, mobile, ward_no as wardNo, status FROM general_applications ORDER BY id DESC");
    echo json_encode($stmt->fetchAll());
    exit;
}

// 🟢 ১৩. স্ট্যাটাস পরিবর্তন (Approve / Reject)
if ($action === 'updateAppStatus') {
    $appId = $data['appId'] ?? '';
    $status = $data['status'] ?? 'Approved';

    $pdo->prepare("UPDATE citizenships SET status=? WHERE app_id=?")->execute([$status, $appId]);
    $pdo->prepare("UPDATE trade_licenses SET status=? WHERE app_id=?")->execute([$status, $appId]);
    $pdo->prepare("UPDATE family_certificates SET status=? WHERE app_id=?")->execute([$status, $appId]);
    $pdo->prepare("UPDATE warishans SET status=? WHERE app_id=?")->execute([$status, $appId]);
    $pdo->prepare("UPDATE general_applications SET status=? WHERE app_id=?")->execute([$status, $appId]);

    echo json_encode(['success' => true]);
    exit;
}

// 🟢 ১৪. আবেদন ডিলিট করা
if ($action === 'deleteApplication') {
    $appId = is_array($data) ? ($data['appId'] ?? '') : $data;

    $pdo->prepare("DELETE FROM citizenships WHERE app_id=?")->execute([$appId]);
    $pdo->prepare("DELETE FROM trade_licenses WHERE app_id=?")->execute([$appId]);
    $pdo->prepare("DELETE FROM family_certificates WHERE app_id=?")->execute([$appId]);
    $pdo->prepare("DELETE FROM warishans WHERE app_id=?")->execute([$appId]);
    $pdo->prepare("DELETE FROM general_applications WHERE app_id=?")->execute([$appId]);

    echo json_encode(['success' => true]);
    exit;
}

// ডিফল্ট রেসপন্স
echo json_encode(['success' => true, 'message' => 'API is active']);
