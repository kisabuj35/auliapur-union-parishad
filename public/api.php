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

// 🟢 টেবিলসমূহ নিশ্চিতকরণ
$pdo->exec("CREATE TABLE IF NOT EXISTS citizenships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    app_id VARCHAR(50) UNIQUE, cert_no VARCHAR(100), name VARCHAR(255), nid VARCHAR(50),
    father_name VARCHAR(255), mother_name VARCHAR(255), dob VARCHAR(50), marital_status VARCHAR(50),
    spouse_name VARCHAR(255), mobile VARCHAR(50), ward_no VARCHAR(20), village VARCHAR(255),
    post_office VARCHAR(255), division VARCHAR(100), language VARCHAR(10) DEFAULT 'bn',
    status VARCHAR(50) DEFAULT 'Pending', apply_date VARCHAR(50), signatory_role VARCHAR(100) DEFAULT 'চেয়ারম্যান',
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
    status VARCHAR(50) DEFAULT 'Pending', apply_date VARCHAR(50), signatory_role VARCHAR(100) DEFAULT 'চেয়ারম্যান',
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
    status VARCHAR(50) DEFAULT 'Pending', apply_date VARCHAR(50), signatory_role VARCHAR(100) DEFAULT 'চেয়ারম্যান',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS warishans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    app_id VARCHAR(50) UNIQUE, applicant_name VARCHAR(255), father_spouse VARCHAR(255),
    deceased_name VARCHAR(255), deceased_father VARCHAR(255), deceased_relation VARCHAR(100),
    deceased_ward_no VARCHAR(20), deceased_village VARCHAR(255), deceased_post_office VARCHAR(255),
    nid VARCHAR(50), mobile VARCHAR(50), ward_no VARCHAR(20), village VARCHAR(255),
    post_office VARCHAR(255), smarak_no VARCHAR(100), warishan_tree_json LONGTEXT,
    status VARCHAR(50) DEFAULT 'Pending', date VARCHAR(50), signatory_role VARCHAR(100) DEFAULT 'চেয়ারম্যান',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS general_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    app_id VARCHAR(50) UNIQUE, type VARCHAR(100), name VARCHAR(255), nid VARCHAR(50),
    father_name VARCHAR(255), mother_name VARCHAR(255), dob VARCHAR(50), mobile VARCHAR(50),
    ward_no VARCHAR(20), village VARCHAR(255), post_office VARCHAR(255), division VARCHAR(100),
    status VARCHAR(50) DEFAULT 'Pending', apply_date VARCHAR(50), signatory_role VARCHAR(100) DEFAULT 'চেয়ারম্যান',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// 🟢 ওয়ারিশান বাতিলের টেবিল (আবেদনকারীর ঠিকানাসহ)
$pdo->exec("CREATE TABLE IF NOT EXISTS warishan_cancellations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cancel_app_id VARCHAR(50) UNIQUE,
    target_app_id VARCHAR(50),
    target_smarak_no VARCHAR(100),
    applicant_name VARCHAR(255),
    applicant_father VARCHAR(255),
    applicant_nid VARCHAR(50),
    applicant_mobile VARCHAR(50),
    applicant_ward VARCHAR(50),
    applicant_village VARCHAR(255),
    applicant_post VARCHAR(255),
    deceased_name VARCHAR(255),
    deceased_father VARCHAR(255),
    deceased_address TEXT,
    cancellation_reason TEXT,
    status VARCHAR(50) DEFAULT 'Pending',
    apply_date VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// কলাম নিরাপদে সংযোজন (যদি আগে তৈরি হয়ে থাকে)
try { $pdo->exec("ALTER TABLE warishan_cancellations ADD COLUMN applicant_ward VARCHAR(50)"); } catch (Exception $e) {}
try { $pdo->exec("ALTER TABLE warishan_cancellations ADD COLUMN applicant_village VARCHAR(255)"); } catch (Exception $e) {}
try { $pdo->exec("ALTER TABLE warishan_cancellations ADD COLUMN applicant_post VARCHAR(255)"); } catch (Exception $e) {}

function extractDobYear($dob) {
    if (empty($dob)) return date('Y');
    $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    $en = ['0','1','2','3','4','5','6','7','8','9'];
    $clean = str_replace($bn, $en, (string)$dob);
    if (preg_match('/(19\d{2}|20\d{2})/', $clean, $matches)) {
        return $matches[1];
    }
    return date('Y');
}

$rawInput = file_get_contents('php://input');
$request = json_decode($rawInput, true) ?: [];
$action = $request['action'] ?? $_GET['action'] ?? '';
$data = $request['data'] ?? [];

if ($action === 'getUPSettings' || $action === 'saveUPSettings') {
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS up_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            `key` VARCHAR(100) UNIQUE,
            `value` TEXT NULL,
            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        try { $pdo->exec("ALTER TABLE up_settings ADD COLUMN created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP"); } catch (Throwable $ignored) {}
        try { $pdo->exec("ALTER TABLE up_settings ADD COLUMN updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"); } catch (Throwable $ignored) {}

        if ($action === 'getUPSettings') {
            $settings = [];
            $rows = $pdo->query("SELECT `key`, `value` FROM up_settings")->fetchAll();
            foreach ($rows as $row) {
                $settings[$row['key']] = $row['value'];
            }
            echo json_encode($settings, JSON_UNESCAPED_UNICODE);
            exit;
        }

        $settings = is_array($data) ? $data : [];
        $statement = $pdo->prepare("INSERT INTO up_settings (`key`, `value`, created_at, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE `value` = ?, updated_at = CURRENT_TIMESTAMP");
        foreach ($settings as $key => $value) {
            if ($key === '' || $key === '_token' || is_array($value)) continue;
            $stringValue = (string)$value;
            $statement->execute([$key, $stringValue, $stringValue]);
        }
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
    } catch (Throwable $exception) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Settings database error'], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// 🟢 ১. দ্বিমুখী বাংলা ও ইংরেজি স্মার্ট সার্চ ইঞ্জিন
if ($action === 'trackApplication') {
    $q = trim(is_array($data) ? ($data['query'] ?? $data['appId'] ?? '') : $data);
    if (empty($q)) {
        echo json_encode(['found' => false, 'list' => [], 'total' => 0]);
        exit;
    }

    $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    $en = ['0','1','2','3','4','5','6','7','8','9'];
    $enQ = str_replace($bn, $en, $q);
    $bnQ = str_replace($en, $bn, $q);

    $results = [];

    // ১. নাগরিকত্ব সনদ
    try {
        $stmt = $pdo->prepare("SELECT app_id as appId, cert_no as certNo, '' as smarakNo, 'নাগরিকত্ব সনদ' as type, 'নাগরিকত্ব সনদ' as serviceType, name as applicantName, name, father_name as fatherName, mother_name as motherName, '' as deceasedName, mobile, nid, status, apply_date as applyDate, ward_no as wardNo, village, post_office as postOffice 
            FROM citizenships 
            WHERE app_id LIKE ? OR app_id LIKE ? 
               OR cert_no LIKE ? OR cert_no LIKE ? 
               OR mobile LIKE ? OR mobile LIKE ? 
               OR nid LIKE ? OR nid LIKE ? 
               OR name LIKE ?");
        $stmt->execute(["%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$q%"]);
        $results = array_merge($results, $stmt->fetchAll());
    } catch (Exception $e) {}

    // ২. ট্রেড লাইসেন্স
    try {
        $stmt = $pdo->prepare("SELECT app_id as appId, license_no as licNo, license_no as certNo, '' as smarakNo, IF(is_renewal=1, 'ট্রেড লাইসেন্স নবায়ন', 'ট্রেড লাইসেন্স') as type, IF(is_renewal=1, 'ট্রেড লাইসেন্স নবায়ন', 'ট্রেড লাইসেন্স') as serviceType, CONCAT(owner_name, ' (', org_name, ')') as applicantName, owner_name as name, father_name as fatherName, mother_name as motherName, '' as deceasedName, mobile, nid, status, apply_date as applyDate, '' as wardNo, biz_address as village, '' as postOffice 
            FROM trade_licenses 
            WHERE app_id LIKE ? OR app_id LIKE ? 
               OR license_no LIKE ? OR license_no LIKE ? 
               OR mobile LIKE ? OR mobile LIKE ? 
               OR nid LIKE ? OR nid LIKE ? 
               OR owner_name LIKE ? OR org_name LIKE ?");
        $stmt->execute(["%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$q%", "%$q%"]);
        $results = array_merge($results, $stmt->fetchAll());
    } catch (Exception $e) {}

    // ৩. পারিবারিক ও উত্তরাধিকারী সনদ
    try {
        $stmt = $pdo->prepare("SELECT app_id as appId, '' as certNo, '' as smarakNo, certificate_type as type, certificate_type as serviceType, name as applicantName, name, father_name as fatherName, mother_name as motherName, deceased_name as deceasedName, mobile, nid, status, apply_date as applyDate, ward_no as wardNo, village, post_office as postOffice 
            FROM family_certificates 
            WHERE app_id LIKE ? OR app_id LIKE ? 
               OR mobile LIKE ? OR mobile LIKE ? 
               OR nid LIKE ? OR nid LIKE ? 
               OR name LIKE ? OR deceased_name LIKE ?");
        $stmt->execute(["%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$q%", "%$q%"]);
        $results = array_merge($results, $stmt->fetchAll());
    } catch (Exception $e) {}

    // ৪. ওয়ারিশান সনদ (বাতিল স্ট্যাটাস স্বয়ংক্রিয় ভেরিফিকেশন সহ)
    try {
        $stmt = $pdo->prepare("SELECT app_id as appId, smarak_no as certNo, smarak_no as smarakNo, 'ওয়ারিশান সনদ' as type, 'ওয়ারিশান সনদ' as serviceType, applicant_name as applicantName, applicant_name as name, father_spouse as fatherName, '' as motherName, deceased_name as deceasedName, mobile, nid, status, date as applyDate, deceased_ward_no as wardNo, deceased_village as village, deceased_post_office as postOffice 
            FROM warishans 
            WHERE app_id LIKE ? OR app_id LIKE ? 
               OR smarak_no LIKE ? OR smarak_no LIKE ? 
               OR mobile LIKE ? OR mobile LIKE ? 
               OR nid LIKE ? OR nid LIKE ? 
               OR applicant_name LIKE ? OR deceased_name LIKE ?");
        $stmt->execute(["%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$q%", "%$q%"]);
        $warRows = $stmt->fetchAll();

        foreach ($warRows as &$wRow) {
            try {
                $chkCancel = $pdo->prepare("SELECT cancel_app_id FROM warishan_cancellations WHERE status='Approved' AND (target_app_id = ? OR target_smarak_no = ? OR (target_smarak_no != '' AND target_smarak_no = ?))");
                $chkCancel->execute([$wRow['appId'], $wRow['appId'], $wRow['smarakNo']]);
                if ($chkCancel->fetch() || $wRow['status'] === 'Cancelled' || $wRow['status'] === 'বাতিলকৃত') {
                    $wRow['status'] = 'Cancelled';
                    $wRow['isCancelled'] = true;
                    $wRow['cancellationNotice'] = "সতর্কবার্তা: স্মারক নং- " . ($wRow['smarakNo'] ?: $wRow['appId']) . " এর ওয়ারিশান সনদটি ইউনিয়ন পরিষদ কর্তৃক বাতিল করা হইল!";
                }
            } catch (Exception $e) {}
        }
        $results = array_merge($results, $warRows);
    } catch (Exception $e) {}

    // ৫. সাধারণ প্রত্যয়ন
    try {
        $stmt = $pdo->prepare("SELECT app_id as appId, '' as certNo, '' as smarakNo, type, type as serviceType, name as applicantName, name, father_name as fatherName, mother_name as motherName, '' as deceasedName, mobile, nid, status, apply_date as applyDate, ward_no as wardNo, village, post_office as postOffice 
            FROM general_applications 
            WHERE app_id LIKE ? OR app_id LIKE ? 
               OR mobile LIKE ? OR mobile LIKE ? 
               OR nid LIKE ? OR nid LIKE ? 
               OR name LIKE ?");
        $stmt->execute(["%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$q%"]);
        $results = array_merge($results, $stmt->fetchAll());
    } catch (Exception $e) {}

    // ৬. ওয়ারিশ সনদ বাতিলের আবেদনসমূহ
    try {
        $stmt = $pdo->prepare("SELECT cancel_app_id as appId, target_smarak_no as certNo, target_smarak_no as smarakNo, 'ওয়ারিশ সনদ বাতিলের আবেদন' as type, 'ওয়ারিশ সনদ বাতিলের আবেদন' as serviceType, applicant_name as applicantName, applicant_name as name, applicant_father as fatherName, '' as motherName, deceased_name as deceasedName, applicant_mobile as mobile, applicant_nid as nid, status, apply_date as applyDate, applicant_ward as wardNo, applicant_village as village, applicant_post as postOffice 
            FROM warishan_cancellations 
            WHERE cancel_app_id LIKE ? OR cancel_app_id LIKE ? 
               OR target_smarak_no LIKE ? OR target_smarak_no LIKE ? 
               OR applicant_mobile LIKE ? OR applicant_mobile LIKE ? 
               OR applicant_nid LIKE ? OR applicant_nid LIKE ? 
               OR applicant_name LIKE ?");
        $stmt->execute(["%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$enQ%", "%$bnQ%", "%$q%"]);
        $results = array_merge($results, $stmt->fetchAll());
    } catch (Exception $e) {}

    if (empty($results)) {
        echo json_encode(['found' => false, 'list' => [], 'total' => 0]);
    } else {
        echo json_encode([
            'found' => true,
            'total' => count($results),
            'list' => $results
        ]);
    }
    exit;
}

// 🟢 ২. ওয়ারিশান সনদ বাতিল অনুমোদন (শতভাগ নিশ্চিত বাতিলকরণ)
if ($action === 'approveWarishanCancellation') {
    $cancelAppId = trim($data['cancelAppId'] ?? '');
    $targetSmarak = trim($data['targetSmarakNo'] ?? '');
    $targetAppId = trim($data['targetAppId'] ?? '');

    $pdo->prepare("UPDATE warishan_cancellations SET status='Approved' WHERE cancel_app_id=?")->execute([$cancelAppId]);

    $stmtCancel = $pdo->prepare("UPDATE warishans SET status='Cancelled' WHERE app_id = ? OR app_id = ? OR smarak_no = ? OR smarak_no = ? OR smarak_no LIKE ?");
    $stmtCancel->execute([$targetAppId, $targetSmarak, $targetAppId, $targetSmarak, "%$targetSmarak%"]);

    echo json_encode(['success' => true]);
    exit;
}

// 🟢 ৩. ওয়ারিশান বাতিলের আবেদন সাবমিট (আবেদনকারীর পূর্ণাঙ্গ ঠিকানাসহ)
if ($action === 'submitWarishanCancellation') {
    $rand6 = rand(100000, 999999);
    $cancelAppId = 'AUL-WCN-' . $rand6;
    $date = date('d/m/Y');

    $stmt = $pdo->prepare("INSERT INTO warishan_cancellations (cancel_app_id, target_app_id, target_smarak_no, applicant_name, applicant_father, applicant_nid, applicant_mobile, applicant_ward, applicant_village, applicant_post, deceased_name, deceased_father, deceased_address, cancellation_reason, status, apply_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $cancelAppId,
        $data['targetAppId'] ?? '',
        $data['targetSmarakNo'] ?? '',
        $data['applicantName'] ?? '',
        $data['applicantFather'] ?? '',
        $data['applicantNid'] ?? '',
        $data['applicantMobile'] ?? '',
        $data['applicantWard'] ?? '০১',
        $data['applicantVillage'] ?? '',
        $data['applicantPost'] ?? 'আউলিয়াপুর ময়দান',
        $data['deceasedName'] ?? '',
        $data['deceasedFather'] ?? '',
        $data['deceasedAddress'] ?? '',
        $data['cancellationReason'] ?? '',
        'Pending',
        $date
    ]);

    echo json_encode(['success' => true, 'appId' => $cancelAppId]);
    exit;
}

// 🟢 ৪. ওয়ারিশান বাতিলের আবেদন তালিকা লোড
if ($action === 'getWarishanCancellationApps') {
    $stmt = $pdo->query("SELECT * FROM warishan_cancellations ORDER BY id DESC");
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($action === 'rejectWarishanCancellation') {
    $cancelAppId = trim($data['cancelAppId'] ?? '');
    $pdo->prepare("UPDATE warishan_cancellations SET status='Rejected' WHERE cancel_app_id=?")->execute([$cancelAppId]);
    echo json_encode(['success' => true]);
    exit;
}

// 🟢 ৫. বাতিল আবেদন ও বাতিল আদেশপত্রের বিস্তারিত তথ্য
if ($action === 'getWarishanCancellationDetails') {
    $q = trim(is_array($data) ? ($data['appId'] ?? '') : $data);
    $stmt = $pdo->prepare("SELECT * FROM warishan_cancellations WHERE cancel_app_id = ?");
    $stmt->execute([$q]);
    $row = $stmt->fetch();
    if ($row) {
        $row['found'] = true;
        // মূল ওয়ারিশ সনদের ইস্যু তারিখ নিয়ে আসা
        $targetSm = $row['target_smarak_no'];
        $origStmt = $pdo->prepare("SELECT date as original_issue_date FROM warishans WHERE smarak_no = ? OR app_id = ?");
        $origStmt->execute([$targetSm, $row['target_app_id']]);
        $origRow = $origStmt->fetch();
        $row['original_issue_date'] = $origRow['original_issue_date'] ?? $row['apply_date'];

        echo json_encode($row);
    } else {
        echo json_encode(['found' => false]);
    }
    exit;
}

// 🟢 ৬. নাগরিকত্ব আবেদন সাবমিট
if ($action === 'submitCitizenshipDirect') {
    $rand6 = rand(100000, 999999);
    $appId = 'AUL-' . $rand6;
    $dobYear = extractDobYear($data['dob'] ?? '');
    $certNo = $dobYear . '7819510' . substr($rand6, 0, 4);
    $date = date('d/m/Y');

    $stmt = $pdo->prepare("INSERT INTO citizenships (app_id, cert_no, name, nid, father_name, mother_name, dob, marital_status, spouse_name, mobile, ward_no, village, post_office, division, language, status, apply_date, signatory_role) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $appId, $certNo, $data['name'], $data['nid'], $data['fatherName'], $data['motherName'],
        $data['dob'], $data['maritalStatus'] ?? 'অবিবাহিত', $data['spouseName'] ?? '', $data['mobile'],
        $data['wardNo'], $data['village'], $data['postOffice'] ?? 'আউলিয়াপুর ময়দান', 'বরিশাল',
        $data['language'] ?? 'bn', 'Pending', $date, 'চেয়ারম্যান'
    ]);

    echo json_encode(['success' => true, 'appId' => $appId, 'certNo' => $certNo]);
    exit;
}

// 🟢 ৭. নাগরিকত্ব বিবরণ (Print)
if ($action === 'getCitizenshipDetails') {
    $q = trim(is_array($data) ? ($data['appId'] ?? '') : $data);
    $stmt = $pdo->prepare("SELECT * FROM citizenships WHERE app_id = ? OR cert_no = ?");
    $stmt->execute([$q, $q]);
    $row = $stmt->fetch();

    if ($row) {
        $dobYear = extractDobYear($row['dob']);
        $certNo = $row['cert_no'];
        if (!empty($dobYear) && substr($certNo, 0, 4) !== $dobYear) {
            $certNo = $dobYear . substr($certNo, 4);
            $pdo->prepare("UPDATE citizenships SET cert_no = ? WHERE id = ?")->execute([$certNo, $row['id']]);
        }

        echo json_encode([
            'found' => true,
            'appId' => $row['app_id'],
            'certNo' => $certNo,
            'name' => $row['name'],
            'fatherName' => $row['father_name'],
            'motherName' => $row['mother_name'],
            'dob' => $row['dob'],
            'nid' => $row['nid'],
            'maritalStatus' => $row['marital_status'],
            'spouseName' => $row['spouse_name'],
            'mobile' => $row['mobile'],
            'wardNo' => $row['ward_no'],
            'village' => $row['village'],
            'postOffice' => $row['post_office'],
            'division' => $row['division'],
            'language' => $row['language'],
            'status' => $row['status'],
            'applyDate' => $row['apply_date'],
            'signatoryRole' => $row['signatory_role'] ?? 'চেয়ারম্যান'
        ]);
    } else {
        echo json_encode(['found' => false]);
    }
    exit;
}

// 🟢 ৮. পারিবারিক বিবরণ
if ($action === 'getFamilyDetails') {
    $q = trim(is_array($data) ? ($data['appId'] ?? '') : $data);
    $stmt = $pdo->prepare("SELECT * FROM family_certificates WHERE app_id = ?");
    $stmt->execute([$q]);
    $row = $stmt->fetch();

    if ($row) {
        $members = json_decode($row['members_json'] ?? '[]', true);
        echo json_encode([
            'found' => true,
            'appId' => $row['app_id'],
            'type' => $row['certificate_type'],
            'name' => $row['name'],
            'nid' => $row['nid'],
            'fatherName' => $row['father_name'],
            'motherName' => $row['mother_name'],
            'mobile' => $row['mobile'],
            'wardNo' => $row['ward_no'],
            'village' => $row['village'],
            'postOffice' => $row['post_office'],
            'deceasedName' => $row['deceased_name'],
            'deceasedFather' => $row['deceased_father'],
            'deceasedMother' => $row['deceased_mother'],
            'deceasedDate' => $row['deceased_date'],
            'applicantRelation' => $row['applicant_relation'],
            'deceasedWard' => $row['deceased_ward'],
            'deceasedVillage' => $row['deceased_village'],
            'deceasedPostOffice' => $row['deceased_post_office'],
            'deceasedUnion' => $row['deceased_union'],
            'deceasedUpazila' => $row['deceased_upazila'],
            'deceasedDistrict' => $row['deceased_district'],
            'members' => $members,
            'status' => $row['status'],
            'applyDate' => $row['apply_date'],
            'signatoryRole' => $row['signatory_role'] ?? 'চেয়ারম্যান'
        ]);
    } else {
        echo json_encode(['found' => false]);
    }
    exit;
}

// 🟢 ৯. ট্রেড লাইসেন্স বিবরণ
if ($action === 'getTradeLicenseDetails') {
    $q = trim(is_array($data) ? ($data['appId'] ?? $data['licNo'] ?? '') : $data);
    $stmt = $pdo->prepare("SELECT * FROM trade_licenses WHERE app_id = ? OR license_no = ?");
    $stmt->execute([$q, $q]);
    $row = $stmt->fetch();

    if ($row) {
        echo json_encode([
            'found' => true,
            'appId' => $row['app_id'],
            'licNo' => $row['license_no'],
            'receiptNo' => $row['receipt_no'],
            'orgName' => $row['org_name'],
            'ownerName' => $row['owner_name'],
            'fatherName' => $row['father_name'],
            'motherName' => $row['mother_name'],
            'nid' => $row['nid'],
            'dob' => $row['dob'],
            'mobile' => $row['mobile'],
            'ownerAddress' => $row['owner_address'],
            'category' => $row['category'],
            'bizDetails' => $row['biz_details'],
            'bizAddress' => $row['biz_address'],
            'bizStartDate' => $row['biz_start_date'],
            'fiscalYear' => $row['fiscal_year'],
            'capital' => $row['capital'],
            'licenseFee' => $row['license_fee'],
            'vatFee' => $row['vat_fee'],
            'commTax' => $row['comm_tax'],
            'signTax' => $row['sign_tax'],
            'totalFee' => $row['total_fee'],
            'photo' => $row['photo'],
            'isRenewal' => (bool)$row['is_renewal'],
            'status' => $row['status'],
            'applyDate' => $row['apply_date'],
            'signatoryRole' => $row['signatory_role'] ?? 'চেয়ারম্যান'
        ]);
    } else {
        echo json_encode(['found' => false]);
    }
    exit;
}

// 🟢 ১০. ওয়ারিশান বিবরণ
if ($action === 'getWarishanDetails') {
    $q = trim(is_array($data) ? ($data['appId'] ?? '') : $data);
    $stmt = $pdo->prepare("SELECT * FROM warishans WHERE app_id = ? OR smarak_no = ?");
    $stmt->execute([$q, $q]);
    $row = $stmt->fetch();

    if ($row) {
        $tree = json_decode($row['warishan_tree_json'] ?? '[]', true);
        echo json_encode([
            'found' => true,
            'appId' => $row['app_id'],
            'applicantName' => $row['applicant_name'],
            'fatherSpouseName' => $row['father_spouse'],
            'deceasedName' => $row['deceased_name'],
            'deceasedFather' => $row['deceased_father'],
            'deceasedRelation' => $row['deceased_relation'],
            'nid' => $row['nid'],
            'mobile' => $row['mobile'],
            'wardNo' => $row['ward_no'],
            'village' => $row['village'],
            'postOffice' => $row['post_office'],
            'smarakNo' => $row['smarak_no'],
            'warishanTree' => $tree,
            'deceasedWardNo' => $row['deceased_ward_no'],
            'deceasedVillage' => $row['deceased_village'],
            'deceasedPostOffice' => $row['deceased_post_office'],
            'deceasedUnion' => $row['deceased_union'],
            'deceasedUpazila' => $row['deceased_upazila'],
            'deceasedDistrict' => $row['deceased_district'],
            'status' => $row['status'],
            'date' => $row['date'],
            'applyDate' => $row['date'],
            'signatoryRole' => $row['signatory_role'] ?? 'চেয়ারম্যান'
        ]);
    } else {
        echo json_encode(['found' => false]);
    }
    exit;
}

// 🟢 ১১. সংশোধিত সকল তথ্য ডাটাবেসে সেভ করা
if ($action === 'saveEditedApplication' || $action === 'updateApplicationData') {
    $appId = trim($data['appId'] ?? '');

    $stmtGen = $pdo->prepare("UPDATE general_applications SET name=?, father_name=?, mother_name=?, nid=?, mobile=?, ward_no=?, village=?, post_office=? WHERE app_id=?");
    $stmtGen->execute([
        $data['name'] ?? '', $data['fatherName'] ?? '', $data['motherName'] ?? '',
        $data['nid'] ?? '', $data['mobile'] ?? '', $data['wardNo'] ?? '',
        $data['village'] ?? '', $data['postOffice'] ?? '', $appId
    ]);

    $membersJson = isset($data['members']) ? json_encode($data['members']) : null;
    $stmtFam = $pdo->prepare("UPDATE family_certificates SET name=?, nid=?, father_name=?, mother_name=?, mobile=?, ward_no=?, village=?, post_office=?, deceased_name=?, deceased_father=?, deceased_mother=?, deceased_date=?, applicant_relation=?, deceased_ward=?, deceased_village=?, deceased_post_office=?, members_json=COALESCE(?, members_json) WHERE app_id=?");
    $stmtFam->execute([
        $data['name'] ?? '', $data['nid'] ?? '', $data['fatherName'] ?? '', $data['motherName'] ?? '',
        $data['mobile'] ?? '', $data['wardNo'] ?? '', $data['village'] ?? '', $data['postOffice'] ?? '',
        $data['deceasedName'] ?? '', $data['deceasedFather'] ?? '', $data['deceasedMother'] ?? '',
        $data['deceasedDate'] ?? '', $data['applicantRelation'] ?? '', $data['deceasedWard'] ?? '',
        $data['deceasedVillage'] ?? '', $data['deceasedPostOffice'] ?? '', $membersJson, $appId
    ]);

    $stmtCit = $pdo->prepare("UPDATE citizenships SET name=?, nid=?, father_name=?, mother_name=?, dob=?, mobile=?, ward_no=?, village=?, post_office=?, marital_status=?, spouse_name=? WHERE app_id=?");
    $stmtCit->execute([
        $data['name'] ?? '', $data['nid'] ?? '', $data['fatherName'] ?? '', $data['motherName'] ?? '',
        $data['dob'] ?? '', $data['mobile'] ?? '', $data['wardNo'] ?? '', $data['village'] ?? '',
        $data['postOffice'] ?? '', $data['maritalStatus'] ?? 'অবিবাহিত', $data['spouseName'] ?? '', $appId
    ]);

    echo json_encode(['success' => true]);
    exit;
}

// 🟢 ১২. ট্রেড লাইসেন্স সাবমিট ও আপডেট
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
        'Pending', $date, 'চেয়ারম্যান'
    ]);

    echo json_encode(['success' => true, 'appId' => $appId, 'licNo' => $licNo]);
    exit;
}

if ($action === 'updateTradeLicenseData') {
    $appId = trim($data['appId'] ?? '');
    $stmt = $pdo->prepare("UPDATE trade_licenses SET org_name=?, owner_name=?, father_name=?, mother_name=?, nid=?, mobile=?, category=?, fiscal_year=?, owner_address=?, biz_address=?, comm_tax=?, sign_tax=?, total_fee=? WHERE app_id=?");
    $stmt->execute([
        $data['orgName'] ?? '', $data['ownerName'] ?? '', $data['fatherName'] ?? '', $data['motherName'] ?? '',
        $data['nid'] ?? '', $data['mobile'] ?? '', $data['category'] ?? '', $data['fiscalYear'] ?? '',
        $data['ownerAddress'] ?? '', $data['bizAddress'] ?? '', $data['commTax'] ?? 0, $data['signTax'] ?? 0,
        $data['totalFee'] ?? 0, $appId
    ]);
    echo json_encode(['success' => true]);
    exit;
}

// 🟢 ১৩. পারিবারিক সাবমিট
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
        json_encode($data['members'] ?? []), 'Pending', $date, 'চেয়ারম্যান'
    ]);

    echo json_encode(['success' => true, 'appId' => $appId]);
    exit;
}

// 🟢 ১৪. ওয়ারিশান সাবমিট ও আপডেট
if ($action === 'submitWarishanApplication') {
    $rand = rand(1000, 9999);
    $appId = 'AUL-WAR-' . $rand;
    $date = date('d/m/Y');
    
    $count = $pdo->query("SELECT COUNT(*) FROM warishans")->fetchColumn() + 1;
    $smarakNo = 'আ/ইউ/পটুয়া/সদর/' . date('Y') . '/' . str_pad($count, 3, '0', STR_PAD_LEFT);

    $stmt = $pdo->prepare("INSERT INTO warishans (app_id, applicant_name, father_spouse, deceased_name, deceased_father, deceased_relation, deceased_ward_no, deceased_village, deceased_post_office, nid, mobile, ward_no, village, post_office, smarak_no, warishan_tree_json, status, date, signatory_role) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $appId, $data['applicantName'], $data['fatherSpouseName'], $data['deceasedName'], $data['deceasedFather'],
        $data['deceasedRelation'] ?? 'পুত্র', $data['deceasedWardNo'] ?? '০১', $data['deceasedVillage'] ?? '',
        $data['deceasedPostOffice'] ?? 'বাদুরা হাট-৮৬০০', $data['nid'], $data['mobile'], $data['wardNo'], $data['village'],
        $data['postOffice'] ?? 'আউলিয়াপুর ময়দান', $smarakNo, json_encode($data['warishanTree'] ?? []),
        'Pending', $date, 'চেয়ারম্যান'
    ]);

    echo json_encode(['success' => true, 'appId' => $appId, 'smarakNo' => $smarakNo]);
    exit;
}

if ($action === 'updateWarishanData') {
    $appId = trim($data['appId'] ?? '');
    $stmt = $pdo->prepare("UPDATE warishans SET applicant_name=?, father_spouse=?, deceased_name=?, deceased_father=?, deceased_relation=?, deceased_ward_no=?, deceased_village=?, deceased_post_office=?, nid=?, mobile=?, ward_no=?, village=?, post_office=?, warishan_tree_json=? WHERE app_id=?");
    $stmt->execute([
        $data['applicantName'] ?? '', $data['fatherSpouseName'] ?? '', $data['deceasedName'] ?? '',
        $data['deceasedFather'] ?? '', $data['deceasedRelation'] ?? 'পুত্র', $data['deceasedWardNo'] ?? '০১',
        $data['deceasedVillage'] ?? '', $data['deceasedPostOffice'] ?? '', $data['nid'] ?? '',
        $data['mobile'] ?? '', $data['wardNo'] ?? '', $data['village'] ?? '',
        $data['postOffice'] ?? '', json_encode($data['warishanTree'] ?? []),
        $appId
    ]);
    echo json_encode(['success' => true]);
    exit;
}

// 🟢 ১৫. সাধারণ প্রত্যয়ন সাবমিট
if ($action === 'submitApplication') {
    $appId = 'AUL-' . rand(100000, 999999);
    $date = date('d/m/Y');

    $stmt = $pdo->prepare("INSERT INTO general_applications (app_id, type, name, nid, father_name, mother_name, mobile, ward_no, village, post_office, status, apply_date, signatory_role) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $appId, $data['type'] ?? 'বিবিধ প্রত্যয়নপত্র', $data['name'], $data['nid'], $data['fatherName'],
        $data['motherName'] ?? '', $data['mobile'], $data['wardNo'], $data['village'],
        $data['postOffice'] ?? 'আউলিয়াপুর ময়দান', 'Pending', $date, 'চেয়ারম্যান'
    ]);

    echo json_encode(['success' => true, 'appId' => $appId]);
    exit;
}

// 🟢 ১৬. ড্যাশবোর্ড পরিসংখ্যান
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

// 🟢 ১৭. তালিকার API সমূহ
if ($action === 'getCitizenshipApps') {
    echo json_encode($pdo->query("SELECT app_id as appId, cert_no as certNo, name, father_name as fatherName, mobile, status FROM citizenships ORDER BY id DESC")->fetchAll());
    exit;
}
if ($action === 'getTradeLicenses' || $action === 'getTradeRenewals') {
    echo json_encode($pdo->query("SELECT app_id as appId, license_no as licNo, org_name as orgName, owner_name as ownerName, father_name as fatherName, mobile, total_fee as totalFee, status, is_renewal as isRenewal FROM trade_licenses ORDER BY id DESC")->fetchAll());
    exit;
}
if ($action === 'getFamilyApps') {
    echo json_encode($pdo->query("SELECT app_id as appId, certificate_type as type, name, nid, father_name as fatherName, mobile, ward_no as wardNo, village, status FROM family_certificates ORDER BY id DESC")->fetchAll());
    exit;
}
if ($action === 'getWarishanApps') {
    echo json_encode($pdo->query("SELECT app_id as appId, smarak_no as smarakNo, applicant_name as applicantName, deceased_name as deceasedName, deceased_father as deceasedFather, mobile, village, status FROM warishans ORDER BY id DESC")->fetchAll());
    exit;
}
if ($action === 'getAllApplications') {
    echo json_encode($pdo->query("SELECT app_id as appId, type, name, nid, mobile, ward_no as wardNo, status FROM general_applications ORDER BY id DESC")->fetchAll());
    exit;
}

// 🟢 ১৮. স্ট্যাটাস পরিবর্তন ও ডিলিট
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

// 🟢 ১৯. এডমিন লগইন ও পারমিশন
if ($action === 'adminLogin') {
    $u = trim($data['username'] ?? '');
    $p = trim($data['password'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$u]);
    $user = $stmt->fetch();

    if ($user && ($user['password'] === $p || password_verify($p, $user['password']))) {
        $perms = json_decode($user['permissions'] ?? '{}', true);
        echo json_encode([
            'success' => true,
            'username' => $user['username'],
            'role' => $user['role'] ?? 'Admin',
            'name' => $user['name'] ?? $user['username'],
            'photoUrl' => $user['photo'] ?? 'https://upload.wikimedia.org/wikipedia/commons/7/7c/Profile_avatar_placeholder_large.png',
            'permissions' => $perms,
            'allowedMenus' => $perms['allowedMenus'] ?? ['dashTab']
        ]);
        exit;
    }
    echo json_encode(['success' => false, 'message' => 'ইউজারনেম বা পাসওয়ার্ড সঠিক নয়!']);
    exit;
}

if ($action === 'getAdminUsers') {
    echo json_encode($pdo->query("SELECT username, role, name, permissions, photo as photoUrl FROM users ORDER BY id DESC")->fetchAll());
    exit;
}
if ($action === 'saveAdminUser' || $action === 'updateUser') {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, name, permissions, photo) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE name=VALUES(name), role=VALUES(role), permissions=VALUES(permissions), photo=VALUES(photo)");
    $stmt->execute([$data['username'], $data['password'] ?? '123456', $data['role'] ?? 'Admin', $data['name'] ?? $data['username'], json_encode($data['permissions'] ?? []), $data['photo'] ?? '']);
    echo json_encode(['success' => true]);
    exit;
}
if ($action === 'deleteAdminUser') {
    $pdo->prepare("DELETE FROM users WHERE username=?")->execute([$data['username'] ?? $data]);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => true, 'message' => 'API Active']);
