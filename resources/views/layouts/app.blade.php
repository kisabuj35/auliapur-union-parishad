<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>১১নং আউলিয়াপুর ইউনিয়ন পরিষদ - স্মার্ট ক্যাশলেস সেবা বাতায়ন</title>

  <!-- Google & Bangla Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Kalpurush&family=Tiro+Bangla&display=swap" rel="stylesheet">
  <link href="https://fonts.maateen.me/nikosh/font.css" rel="stylesheet">

  <!-- Bootstrap 5 CSS & FontAwesome Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    :root {
      --primary-green: #006837;
      --primary-dark: #004d28;
      --primary-light: #008748;
      --accent-gold: #ffc107;
      --accent-orange: #f97316;
      --text-dark: #1e293b;
      --bg-light: #f1f5f9;
      --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }

    body {
      font-family: 'Hind Siliguri', 'Kalpurush', sans-serif;
      background-color: var(--bg-light);
      color: var(--text-dark);
      overflow-x: hidden;
    }

    /* টপ স্ট্রিপ */
    .top-smart-bar {
      background: linear-gradient(90deg, #4a148c 0%, #7b1fa2 50%, #ad1457 100%);
      color: #ffffff;
      font-size: 13px;
      padding: 6px 0;
      font-weight: 500;
    }

    /* মূল হেডার */
    .union-main-header {
      background: linear-gradient(135deg, #00582f 0%, #006837 50%, #004625 100%);
      color: #ffffff;
      padding: 14px 0;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }
    .header-badge-chip {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(5px);
      border: 1px solid rgba(255, 255, 255, 0.25);
      border-radius: 30px;
      padding: 4px 14px;
      font-size: 12.5px;
      color: #fff;
    }

    /* ন্যাভবার */
    .smart-navbar {
      background: #0f172a !important;
      border-bottom: 3px solid var(--accent-gold);
      box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    .nav-link-custom {
      color: #e2e8f0 !important;
      padding: 10px 16px !important;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14.5px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.25s ease;
    }
    .nav-link-custom:hover {
      background: rgba(0, 104, 55, 0.8) !important;
      color: var(--accent-gold) !important;
    }
    .active-nav-item {
      background: linear-gradient(90deg, #006837, #008748) !important;
      color: #ffffff !important;
    }

    /* নোটিশ বার */
    .smart-notice-strip {
      background: linear-gradient(90deg, #0f172a 0%, #006837 100%);
      color: #fff;
      padding: 6px 0;
      font-size: 14px;
    }
    .notice-tag {
      background: #dc2626;
      color: #fff;
      font-size: 13px;
      font-weight: 700;
      padding: 4px 12px;
      border-radius: 6px;
      white-space: nowrap;
    }

    /* ফুটার */
    .smart-footer {
      background: #09131f;
      color: #cbd5e1;
      border-top: 4px solid #006837;
      padding-top: 45px;
      padding-bottom: 25px;
    }
    .footer-title {
      color: #ffffff;
      font-weight: 700;
      font-size: 16px;
      margin-bottom: 18px;
      position: relative;
      padding-bottom: 8px;
    }
    .footer-title::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      width: 40px;
      height: 3px;
      background: var(--accent-gold);
    }
    .footer-links { list-style: none; padding: 0; margin: 0; }
    .footer-links li { margin-bottom: 10px; font-size: 13.5px; }
    .footer-links a { color: #94a3b8; text-decoration: none; }
    .footer-links a:hover { color: var(--accent-gold); }

    /* গ্লোবাল লোডার */
    .global-loader-overlay {
      position: fixed; inset: 0;
      background: rgba(15, 23, 42, 0.45);
      backdrop-filter: blur(5px);
      display: none; align-items: center; justify-content: center; z-index: 99999;
    }
    .global-loader-box {
      background: #ffffff;
      border-radius: 20px;
      padding: 24px 30px;
      text-align: center;
      box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    }

    @media print { .no-print { display: none !important; } }
  </style>
</head>
<body>

  <!-- ১. টপ স্মার্ট বার -->
  <div class="top-smart-bar no-print">
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div><i class="fa-solid fa-bolt text-warning me-1"></i>১১নং আউলিয়াপুর ইউনিয়ন পরিষদ • ক্যাশলেস স্মার্ট ডিজিটাল সিটিজেন পোর্টাল</div>
      <div><i class="fa-solid fa-phone me-1"></i> হেল্পলাইন: <strong>০১৭১০-১৮১০৫৯</strong> | <i class="fa-solid fa-envelope me-1"></i> kisabuj35@gmail.com</div>
    </div>
  </div>

  <!-- ২. মূল ব্যানার ও লোগো -->
  <div class="union-main-header no-print">
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div class="d-flex align-items-center">
        <img src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" alt="Logo" width="65" height="65" class="bg-white rounded-circle p-1 me-3">
        <div>
          <h3 class="fw-bold m-0 text-white" style="font-size: 24px;">১১নং আউলিয়াপুর ইউনিয়ন পরিষদ</h3>
          <p class="m-0 text-white-50 small">পটুয়াখালী সদর, পটুয়াখালী | স্থানীয় সরকার বিভাগ (LGD)</p>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <div class="header-badge-chip"><i class="fa-solid fa-location-dot me-1 text-warning"></i> বরিশাল বিভাগ</div>
        <div class="header-badge-chip bg-warning text-dark fw-bold border-0"><i class="fa-solid fa-circle-check me-1"></i> ভেরিফাইড পোর্টাল</div>
      </div>
    </div>
  </div>

  <!-- ৩. ন্যাভবার -->
  <nav class="navbar navbar-expand-lg smart-navbar py-2 sticky-top no-print">
    <div class="container">
      <button class="navbar-toggler border-secondary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="mainMenu">
        <div class="navbar-nav me-auto align-items-center">
          <a class="nav-link-custom active-nav-item" href="javascript:void(0)" onclick="navToSection('publicHome', this)"><i class="fa fa-home"></i> হোম</a>
          
          <div class="nav-item dropdown">
            <a class="nav-link-custom dropdown-toggle text-warning" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fa fa-certificate me-1"></i> সনদের আবেদনসমূহ
            </a>
            <ul class="dropdown-menu shadow border-0 rounded-3 py-2">
              <li><a class="dropdown-item fw-bold text-danger py-2" href="javascript:void(0)" onclick="openMasterService('নাগরিকত্ব সনদ')"><i class="fa fa-id-card me-2"></i>নাগরিকত্ব সনদ</a></li>
              <li><a class="dropdown-item fw-bold text-success py-2" href="javascript:void(0)" onclick="openMasterService('ট্রেড লাইসেন্স')"><i class="fa fa-briefcase me-2"></i>ট্রেড লাইসেন্স</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item fw-bold text-primary py-2" href="javascript:void(0)" onclick="openMasterService('পারিবারিক সনদ')"><i class="fa fa-people-roof me-2"></i>পারিবারিক সনদ</a></li>
              <li><a class="dropdown-item fw-bold text-dark py-2" href="javascript:void(0)" onclick="openMasterService('উত্তরাধিকারী সনদ')"><i class="fa fa-users me-2"></i>উত্তরাধিকারী সনদ</a></li>
              <li><a class="dropdown-item fw-bold text-warning text-dark py-2" href="javascript:void(0)" onclick="openMasterService('ওয়ারিশান সনদ')"><i class="fa fa-sitemap me-2"></i>ওয়ারিশান সনদ</a></li>
            </ul>
          </div>

          <a class="nav-link-custom" href="javascript:void(0)" onclick="navToSection('trackSection', this)"><i class="fa fa-magnifying-glass"></i> আবেদন ট্র্যাকিং</a>
          <a class="nav-link-custom" href="javascript:void(0)" onclick="navToSection('serviceInfoSection', this)"><i class="fa fa-circle-info"></i> সেবা পরিচিতি</a>
          <a class="nav-link-custom" href="javascript:void(0)" onclick="navToSection('noticeSection', this)"><i class="fa fa-bullhorn"></i> নোটিশ বাতায়ন</a>
          <a class="nav-link-custom text-info" href="javascript:void(0)" onclick="navToSection('contactSection', this)"><i class="fa-solid fa-headset"></i> যোগাযোগ ও ম্যাপ</a>
        </div>
        
        <button class="btn btn-sm btn-warning fw-bold px-3 py-2 rounded-pill shadow-sm" id="adminAuthBtn" onclick="handleNavAuthBtn()">
          <i class="fa fa-lock me-1"></i> এডমিন লগইন
        </button>
      </div>
    </div>
  </nav>

  <!-- ৪. লাইভ স্ক্রল নোটিশ -->
  <div class="smart-notice-strip no-print">
    <div class="container d-flex align-items-center">
      <span class="notice-tag me-3"><i class="fa-solid fa-bell me-1"></i>জরুরি নোটিশ</span>
      <marquee behavior="scroll" direction="left" scrollamount="6">
        ১১নং আউলিয়াপুর ইউনিয়ন পরিষদের সকল সম্মানিত নাগরিককে জানানো যাচ্ছে যে— ঘরে বসেই সকল প্রকার প্রত্যয়ন, নাগরিকত্ব, পারিবারিক, উত্তরাধিকারী ও ট্রেড লাইসেন্সের ডিজিটাল আবেদন করুন ও কিউআর কোডযুক্ত আসল সনদ গ্রহণ করুন।
      </marquee>
    </div>
  </div>

  <!-- ৫. মূল কন্টেন্ট সেকশন -->
  <main>
    @yield('content')
  </main>

  <!-- ৬. আধুনিক ফুটার -->
  <footer class="smart-footer no-print">
    <div class="container">
      <div class="row g-4 mb-4">
        <div class="col-lg-4 col-md-6">
          <h5 class="footer-title">১১নং আউলিয়াপুর ইউনিয়ন পরিষদ</h5>
          <p class="text-white-50 small" style="line-height: 1.8;">
            ডিজিটাল বাংলাদেশের ধারাবাহিকতায় স্মার্ট আউলিয়াপুর ইউনিয়ন গড়ার প্রত্যয়ে নাগরিকদের হাতের মুঠোয় সম্পূর্ণ ক্যাশলেস ই-সেবা নিশ্চিত করা হচ্ছে।
          </p>
        </div>
        <div class="col-lg-3 col-md-6">
          <h5 class="footer-title">গুরুত্বপূর্ণ লিংক</h5>
          <ul class="footer-links">
            <li><a href="https://bangladesh.gov.bd" target="_blank"><i class="fa-solid fa-angle-right text-success"></i> জাতীয় তথ্য বাতায়ন</a></li>
            <li><a href="https://lgd.gov.bd" target="_blank"><i class="fa-solid fa-angle-right text-success"></i> স্থানীয় সরকার বিভাগ</a></li>
            <li><a href="https://forms.mygov.bd" target="_blank"><i class="fa-solid fa-angle-right text-success"></i> বাংলাদেশ ফরম</a></li>
          </ul>
        </div>
        <div class="col-lg-2 col-md-6">
          <h5 class="footer-title">জরুরি হেল্পলাইন</h5>
          <ul class="footer-links">
            <li><a href="tel:333"><span class="badge bg-primary me-2">৩৩৩</span> তথ্য সেবা</a></li>
            <li><a href="tel:999"><span class="badge bg-danger me-2">৯৯৯</span> জরুরি সেবা</a></li>
            <li><a href="tel:109"><span class="badge bg-warning text-dark me-2">১০৯</span> নারী সহায়তা</a></li>
          </ul>
        </div>
        <div class="col-lg-3 col-md-6">
          <h5 class="footer-title">কারিগরি সহযোগিতায়</h5>
          <div class="p-3 bg-white bg-opacity-10 rounded-3">
            <strong class="text-white d-block">কে আই আইটি সলিউশন</strong>
            <small class="text-warning d-block">মোঃ কামরুল ইসলাম সবুজ</small>
            <small class="text-white-50 d-block mt-1">মোবাইল: ০১৭১০-১৮১০৫৯</small>
          </div>
        </div>
      </div>
      <div class="border-top border-secondary pt-3 text-center text-white-50 small">
        &copy; {{ date('Y') }} ১১নং আউলিয়াপুর ইউনিয়ন পরিষদ। সর্বস্বত্ব সংরক্ষিত।
      </div>
    </div>
  </footer>

  <!-- লোডার ও স্ক্রিপ্ট -->
  <div id="globalLoader" class="global-loader-overlay">
    <div class="global-loader-box">
      <div class="spinner-border text-success mb-2" role="status"></div>
      <h6 class="fw-bold m-0 text-dark">তথ্য প্রক্রিয়াকরণ হচ্ছে...</h6>
    </div>
  </div>

  <script>
    function showLoader() { document.getElementById('globalLoader').style.display = 'flex'; }
    function hideLoader() { document.getElementById('globalLoader').style.display = 'none'; }

    // সেন্ট্রাল AJAX রিকোয়েস্ট ব্রিজ
    async function apiRequest(url, data = {}) {
      showLoader();
      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(data)
        });
        const res = await response.json();
        hideLoader();
        return res;
      } catch (err) {
        hideLoader();
        console.error(err);
        return { success: false, error: 'সার্ভার সংযোগ বিচ্ছিন্ন হয়েছে।' };
      }
    }
  </script>
</body>
</html>
