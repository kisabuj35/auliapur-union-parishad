@extends('layouts.app')

@section('content')
<style>
  /* হিরো ব্যানার ও সার্চ */
  .hero-smart-banner {
    background: linear-gradient(135deg, #00582f 0%, #0a7a44 52%, #024e2b 100%);
    border-radius: 22px; padding: 28px; color: #fff; margin-bottom: 26px; box-shadow: 0 14px 34px rgba(0, 98, 52, 0.22);
  }
  .verify-live-search {
    background: rgba(255,255,255,0.96); border-radius: 50px; padding: 6px 8px 6px 18px;
    display: flex; align-items: center; width: 100%; border: 2px solid rgba(255,255,255,0.6);
  }
  .verify-live-search input { border: none; outline: none; width: 100%; background: transparent; font-size: 14.5px; }
  .verify-btn-action {
    background: linear-gradient(135deg, #006837, #004d28) !important; color: #fff !important;
    border: 2px solid #ffc107 !important; border-radius: 50px !important; padding: 8px 22px !important; font-weight: bold;
  }
  
  /* সার্ভিস কার্ড */
  .service-card-modern {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px 12px; text-align: center;
    cursor: pointer; min-height: 125px; display: flex; flex-direction: column; align-items: center; justify-content: center;
    transition: all 0.25s ease;
  }
  .service-card-modern:hover { transform: translateY(-4px); border-color: #006837; box-shadow: 0 10px 20px rgba(0,104,55,0.15); }
  .service-icon-wrap { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 8px; }
  .service-card-title { font-size: 13.5px; font-weight: 700; margin: 0; color: #1e293b; }

  /* ভেরিফিকেশন ফলাফল কার্ড */
  .verification-result-card {
    background: #fff; border: 1px solid #dfe7e3; border-left: 5px solid #0b8f52;
    border-radius: 16px; padding: 18px; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.04);
  }
  .verification-result-card.status-pending { border-left-color: #f59e0b; }
  .verification-result-card.status-rejected { border-left-color: #dc2626; }
</style>

<div class="container my-4">

  <!-- ==================== ১. মূল হোম পেজ ভিউ ==================== -->
  <div id="publicHome" class="view-section">
    <div class="hero-smart-banner">
      <div class="row align-items-center g-3">
        <div class="col-lg-6">
          <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2">স্মার্ট সেবা • ক্যাশলেস ইউনিয়ন</span>
          <h2 class="fw-bold mb-2 text-white">নাগরিক সেবায় ডিজিটাল ইউনিয়ন পরিষদ</h2>
          <p class="text-white-50 mb-0" style="font-size: 14px;">ঘরে বসেই নির্ভুল ও দ্রুত সময়ে সকল প্রকার সনদ এবং ট্রেড লাইসেন্স গ্রহণ করুন।</p>
        </div>
        <div class="col-lg-6">
          <div class="text-end mb-1"><span class="badge bg-light text-success fw-bold px-3 py-1 rounded-pill"><i class="fa-solid fa-shield-halved me-1"></i>আপনার তথ্য যাচাই করুন</span></div>
          <form onsubmit="event.preventDefault(); executeSearch();" class="verify-live-search">
            <i class="fa-solid fa-search text-success me-2 fs-5"></i>
            <input type="text" id="heroSearchInput" placeholder="মোবাইল নম্বর / এনআইডি নম্বর / আপনার নাম লিখুন">
            <button type="button" class="verify-btn-action" onclick="executeSearch()"><i class="fa-solid fa-search me-1"></i> যাচাই</button>
          </form>
        </div>
      </div>
    </div>

    <!-- সার্ভিস কার্ড গ্রিড ও সার্চ রেজাল্ট কন্টেইনার -->
    <div class="row g-4">
      <div class="col-lg-9">
        <!-- কন্টেইনার ১: ২৫টি সেবার গ্রিড -->
        <div id="servicesGridContainer" class="card shadow-sm border-0 rounded-4 p-3 bg-white">
          <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
            <h5 class="fw-bold text-success m-0"><i class="fa-solid fa-layer-group me-2"></i>সকল নাগরিক ও প্রশাসনিক সেবাসমূহ</h5>
            <span class="badge bg-warning text-dark">২৫টি ডিজিটাল সেবা</span>
          </div>
          <div class="row g-2">
            <div class="col-6 col-md-3"><div class="service-card-modern border-danger" onclick="openServiceView('citizenView')"><div class="service-icon-wrap bg-danger-subtle text-danger"><i class="fa-solid fa-id-card"></i></div><p class="service-card-title text-danger">নাগরিকত্ব সনদ</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern border-success" onclick="openServiceView('tradeView')"><div class="service-icon-wrap bg-success-subtle text-success"><i class="fa-solid fa-briefcase"></i></div><p class="service-card-title text-success">ট্রেড লাইসেন্স</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern border-warning" onclick="openServiceView('warishanView')"><div class="service-icon-wrap bg-warning-subtle text-dark"><i class="fa-solid fa-sitemap"></i></div><p class="service-card-title text-dark">ওয়ারিশান সনদ</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern border-primary" onclick="openFamilyPage('উত্তরাধিকারী সনদ')"><div class="service-icon-wrap bg-primary-subtle text-primary"><i class="fa-solid fa-users"></i></div><p class="service-card-title text-primary">উত্তরাধিকারী সনদ</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern border-info" onclick="openFamilyPage('পারিবারিক সনদ')"><div class="service-icon-wrap bg-info-subtle text-info-emphasis"><i class="fa-solid fa-people-roof"></i></div><p class="service-card-title text-dark">পারিবারিক সনদ</p></div></div>
            
            <!-- সাধারণ প্রত্যয়নপত্রসমূহ -->
            <div class="col-6 col-md-3"><div class="service-card-modern" onclick="openGeneralPage('চারিত্রিক সনদ')"><div class="service-icon-wrap bg-light text-success"><i class="fa-solid fa-user-check"></i></div><p class="service-card-title">চারিত্রিক সনদ</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern" onclick="openGeneralPage('ভূমিহীন সনদ')"><div class="service-icon-wrap bg-light text-success"><i class="fa-solid fa-house-chimney-crack"></i></div><p class="service-card-title">ভূমিহীন সনদ</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern" onclick="openGeneralPage('অবিবাহিত সনদ')"><div class="service-icon-wrap bg-light text-success"><i class="fa-solid fa-user"></i></div><p class="service-card-title">অবিবাহিত সনদ</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern" onclick="openGeneralPage('বার্ষিক আয়ের প্রত্যয়ন')"><div class="service-icon-wrap bg-light text-success"><i class="fa-solid fa-money-bill-wave"></i></div><p class="service-card-title">বার্ষিক আয়ের প্রত্যয়ন</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern" onclick="openGeneralPage('একই নামের প্রত্যয়ন')"><div class="service-icon-wrap bg-light text-success"><i class="fa-solid fa-equals"></i></div><p class="service-card-title">একই নামের প্রত্যয়ন</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern" onclick="openGeneralPage('প্রতিবন্ধী সনদপত্র')"><div class="service-icon-wrap bg-light text-success"><i class="fa-solid fa-wheelchair"></i></div><p class="service-card-title">প্রতিবন্ধী সনদপত্র</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern" onclick="openGeneralPage('আর্থিক অসচ্ছলতার সনদপত্র')"><div class="service-icon-wrap bg-light text-success"><i class="fa-solid fa-hand-holding-dollar"></i></div><p class="service-card-title">আর্থিক অসচ্ছলতার সনদ</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern" onclick="openGeneralPage('নতুন ভোটারের প্রত্যয়ন পত্র')"><div class="service-icon-wrap bg-light text-success"><i class="fa-solid fa-check-to-slot"></i></div><p class="service-card-title">নতুন ভোটারের প্রত্যয়ন</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern" onclick="openGeneralPage('জাতীয়তা সনদ')"><div class="service-icon-wrap bg-light text-success"><i class="fa-solid fa-flag"></i></div><p class="service-card-title">জাতীয়তা সনদ</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern" onclick="openGeneralPage('স্থায়ী বাসিন্দা সনদ')"><div class="service-icon-wrap bg-light text-success"><i class="fa-solid fa-location-dot"></i></div><p class="service-card-title">স্থায়ী বাসিন্দা সনদ</p></div></div>
            <div class="col-6 col-md-3"><div class="service-card-modern" onclick="openGeneralPage('বিবিধ প্রত্যয়নপত্র')"><div class="service-icon-wrap bg-light text-success"><i class="fa-solid fa-file-signature"></i></div><p class="service-card-title">বিবিধ প্রত্যয়নপত্র</p></div></div>
          </div>
        </div>

        <!-- কন্টেইনার ২: সার্চ ফলাফল ভিউ -->
        <div id="searchResultContainer" class="card shadow-sm border-0 rounded-4 p-3 bg-light d-none">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-success m-0"><i class="fa-solid fa-list-check me-2"></i>নাগরিক সেবা ও সনদ তথ্য যাচাই ফলাফল</h5>
            <button class="btn btn-sm btn-warning fw-bold rounded-pill" onclick="closeSearch()"><i class="fa-solid fa-arrow-left me-1"></i> সেবাসমূহ ফিরে যান</button>
          </div>
          <div id="searchOutput"></div>
        </div>
      </div>

      <!-- ডান পাশ: পরিচিতি ও জরুরি হটলাইন -->
      <div class="col-lg-3">
        <div class="card shadow-sm border-0 rounded-4 p-3 mb-3 text-center bg-white">
          <h6 class="fw-bold text-success border-bottom pb-2 mb-2 text-start"><i class="fa-solid fa-user-tie me-2"></i>পরিকল্পনা ও পরিচালনায়</h6>
          <img src="https://lh3.googleusercontent.com/d/1vZUuzu8m0kk5V3rc5x9Meb8nnh-bL660" class="rounded-4 border mb-2 img-fluid shadow-sm" style="max-height: 160px; width: 100%; object-fit: cover;">
          <h6 class="fw-bold text-dark m-0">মোঃ কামরুল ইসলাম সবুজ</h6>
          <small class="text-muted d-block">১১নং আউলিযাপুর ইউনিয়ন পরিষদ</small>
          <div class="mt-2"><a href="tel:01710181059" class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3"><i class="fa-solid fa-phone me-1"></i> ০১৭১০-১৮১০৫৯</a></div>
        </div>
        <div class="card shadow-sm border-0 rounded-4 p-3 bg-white">
          <h6 class="fw-bold text-dark border-bottom pb-2 mb-2"><i class="fa-solid fa-phone-volume me-2"></i>জরুরি সরকারি সেবা</h6>
          <a href="tel:333" class="btn btn-sm btn-primary w-100 fw-bold mb-2 text-start d-flex justify-content-between"><span>জাতীয় তথ্য বাতায়ন</span> <span>৩৩৩</span></a>
          <a href="tel:999" class="btn btn-sm btn-danger w-100 fw-bold mb-2 text-start d-flex justify-content-between"><span>জরুরি পুলিশ ও ফায়ার</span> <span>৯৯৯</span></a>
          <a href="tel:109" class="btn btn-sm btn-warning w-100 fw-bold text-start d-flex justify-content-between text-dark"><span>নারী ও শিশু সহায়তা</span> <span>১০৯</span></a>
        </div>
      </div>
    </div>
  </div>

  <!-- ==================== ২. নাগরিকত্ব সনদ ফরম ==================== -->
  <div id="citizenView" class="view-section d-none">
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-danger m-0"><i class="fa fa-id-card me-2"></i>নাগরিকত্ব সনদের অনলাইন আবেদন ফরম</h4>
        <button class="btn btn-sm btn-outline-secondary" onclick="navToSection('publicHome')"><i class="fa fa-arrow-left me-1"></i> হোম পেজ</button>
      </div>
      <form onsubmit="event.preventDefault(); submitCitizenForm();">
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label fw-bold">সনদধারীর নাম *</label><input type="text" id="citName" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">জাতীয় পরিচয়পত্র (NID) নম্বর *</label><input type="text" id="citNid" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">পিতার নাম *</label><input type="text" id="citFather" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">মাতার নাম *</label><input type="text" id="citMother" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">জন্ম তারিখ *</label><input type="date" id="citDob" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">মোবাইল নম্বর *</label><input type="tel" id="citMobile" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">ওয়ার্ড নম্বর *</label><select id="citWard" class="form-select"><option>০১</option><option>০২</option><option>০৩</option><option>০৪</option><option>০৫</option><option>০৬</option><option>০৭</option><option>০৮</option><option>০৯</option></select></div>
          <div class="col-md-4"><label class="form-label fw-bold">গ্রাম/মহল্লা *</label><input type="text" id="citVillage" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">ডাকঘর *</label><input type="text" id="citPost" class="form-control" value="আউলিয়াপুর ময়দান" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">সনদের ভাষা *</label><select id="citLang" class="form-select"><option value="bn">বাংলা</option><option value="en">English</option></select></div>
        </div>
        <div class="text-end mt-4">
          <button type="submit" class="btn btn-danger fw-bold px-4"><i class="fa fa-paper-plane me-1"></i> আবেদন জমা দিন</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ==================== ৩. ট্রেড লাইসেন্স ফরম ==================== -->
  <div id="tradeView" class="view-section d-none">
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-success m-0"><i class="fa fa-briefcase me-2"></i>ট্রেড লাইসেন্স আবেদন ফরম</h4>
        <button class="btn btn-sm btn-outline-secondary" onclick="navToSection('publicHome')"><i class="fa fa-arrow-left me-1"></i> হোম পেজ</button>
      </div>
      <form onsubmit="event.preventDefault(); submitTradeForm();">
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label fw-bold">প্রতিষ্ঠানের নাম *</label><input type="text" id="trOrg" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">মালিকের নাম *</label><input type="text" id="trOwner" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">পিতার নাম *</label><input type="text" id="trFather" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">মাতার নাম *</label><input type="text" id="trMother" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">এনআইডি নম্বর *</label><input type="text" id="trNid" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">মোবাইল নম্বর *</label><input type="tel" id="trMobile" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">ব্যবসার ধরন *</label><input type="text" id="trCategory" class="form-control" placeholder="যেমন: মুদি দোকান" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">ব্যবসা প্রতিষ্ঠানের প্রধান ঠিকানা *</label><input type="text" id="trBizAddress" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">মালিকের স্থায়ী ঠিকানা *</label><input type="text" id="trOwnerAddress" class="form-control" required></div>
        </div>
        <div class="text-end mt-4">
          <button type="submit" class="btn btn-success fw-bold px-4"><i class="fa fa-paper-plane me-1"></i> ট্রেড লাইসেন্স আবেদন জমা দিন</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ==================== ৪. ওয়ারিশান সনদ ফরম ==================== -->
  <div id="warishanView" class="view-section d-none">
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-warning text-dark m-0"><i class="fa fa-sitemap me-2"></i>ওয়ারিশান সনদের আবেদন ফরম</h4>
        <button class="btn btn-sm btn-outline-secondary" onclick="navToSection('publicHome')"><i class="fa fa-arrow-left me-1"></i> হোম পেজ</button>
      </div>
      <form onsubmit="event.preventDefault(); submitWarishanForm();">
        <h6 class="fw-bold text-success border-bottom pb-2 mb-3">১. আবেদনকারীর তথ্য</h6>
        <div class="row g-3 mb-4">
          <div class="col-md-4"><label class="form-label fw-bold">আবেদনকারীর নাম *</label><input type="text" id="warAppName" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">পিতা/স্বামীর নাম *</label><input type="text" id="warAppFather" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">মোবাইল নম্বর *</label><input type="tel" id="warAppMobile" class="form-control" required></div>
        </div>
        <h6 class="fw-bold text-danger border-bottom pb-2 mb-3">২. মূল মৃত ব্যক্তির তথ্য</h6>
        <div class="row g-3 mb-4">
          <div class="col-md-4"><label class="form-label fw-bold">মৃত ব্যক্তির নাম *</label><input type="text" id="warDecName" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">মৃত ব্যক্তির পিতা/স্বামী *</label><input type="text" id="warDecFather" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">সম্পর্ক *</label><select id="warDecRel" class="form-select"><option>পুত্র</option><option>কন্যা</option><option>স্ত্রী</option><option>স্বামী</option></select></div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="fw-bold text-primary m-0">৩. ওয়ারিশগণের তালিকা</h6>
          <button type="button" class="btn btn-sm btn-success" onclick="addWarRow()"><i class="fa fa-plus me-1"></i> সদস্য যোগ করুন</button>
        </div>
        <table class="table table-bordered text-center align-middle" id="warTable">
          <thead class="table-light"><tr><th>ক্র.নং</th><th>ওয়ারিশের নাম</th><th>পিতা/স্বামী</th><th>গ্রাম</th><th>সম্পর্ক</th><th>মুছুন</th></tr></thead>
          <tbody id="warTbody">
            <tr>
              <td>১</td><td><input class="form-control form-control-sm war-name" required></td><td><input class="form-control form-control-sm war-rel-name"></td><td><input class="form-control form-control-sm war-addr"></td><td><input class="form-control form-control-sm war-rel"></td>
              <td><button type="button" class="btn btn-sm text-danger" onclick="this.closest('tr').remove()"><i class="fa fa-trash"></i></button></td>
            </tr>
          </tbody>
        </table>
        <div class="text-end mt-4">
          <button type="submit" class="btn btn-success fw-bold px-4"><i class="fa fa-paper-plane me-1"></i> ওয়ারিশ আবেদন দাখিল করুন</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ==================== ৫. পারিবারিক ও উত্তরাধিকারী সনদ ফরম ==================== -->
  <div id="familyView" class="view-section d-none">
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-primary m-0" id="famHeading">পারিবারিক সনদের আবেদন ফরম</h4>
        <button class="btn btn-sm btn-outline-secondary" onclick="navToSection('publicHome')"><i class="fa fa-arrow-left me-1"></i> হোম পেজ</button>
      </div>
      <form onsubmit="event.preventDefault(); submitFamilyForm();">
        <input type="hidden" id="famType" value="পারিবারিক সনদ">
        <div class="row g-3 mb-4">
          <div class="col-md-4"><label class="form-label fw-bold">আবেদনকারীর নাম *</label><input type="text" id="famName" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">পিতার নাম *</label><input type="text" id="famFather" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">মোবাইল নম্বর *</label><input type="tel" id="famMobile" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">ওয়ার্ড নম্বর *</label><select id="famWard" class="form-select"><option>০১</option><option>০২</option><option>০৩</option><option>০৪</option><option>০৫</option><option>০৬</option><option>০৭</option><option>০৮</option><option>০৯</option></select></div>
          <div class="col-md-4"><label class="form-label fw-bold">গ্রাম *</label><input type="text" id="famVillage" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">ডাকঘর *</label><input type="text" id="famPost" class="form-control" value="বাদুরা হাট" required></div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="fw-bold text-success m-0">পরিবারের সদস্যদের তালিকা</h6>
          <button type="button" class="btn btn-sm btn-success" onclick="addFamMemberRow()"><i class="fa fa-plus me-1"></i> সদস্য যোগ করুন</button>
        </div>
        <table class="table table-bordered text-center align-middle" id="famTable">
          <thead class="table-light"><tr><th>ক্র.নং</th><th>সদস্যের নাম</th><th>এনআইডি / জন্ম নিবন্ধন</th><th>সম্পর্ক</th><th>মুছুন</th></tr></thead>
          <tbody id="famTbody">
            <tr>
              <td>১</td><td><input class="form-control form-control-sm fam-m-name" required></td><td><input class="form-control form-control-sm fam-m-nid"></td><td><input class="form-control form-control-sm fam-m-rel"></td>
              <td><button type="button" class="btn btn-sm text-danger" onclick="this.closest('tr').remove()"><i class="fa fa-trash"></i></button></td>
            </tr>
          </tbody>
        </table>
        <div class="text-end mt-4">
          <button type="submit" class="btn btn-primary fw-bold px-4"><i class="fa fa-paper-plane me-1"></i> আবেদন সাবমিট করুন</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ==================== ৬. সাধারণ প্রত্যয়নপত্র ফরম ==================== -->
  <div id="generalView" class="view-section d-none">
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-success m-0"><i class="fa fa-file-signature me-2"></i><span id="genTitle">প্রত্যয়নপত্র</span> আবেদন ফরম</h4>
        <button class="btn btn-sm btn-outline-secondary" onclick="navToSection('publicHome')"><i class="fa fa-arrow-left me-1"></i> হোম পেজ</button>
      </div>
      <form onsubmit="event.preventDefault(); submitGeneralForm();">
        <input type="hidden" id="genType" value="বিবিধ প্রত্যয়নপত্র">
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label fw-bold">আবেদনকারীর পূর্ণ নাম *</label><input type="text" id="gName" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">পিতার নাম *</label><input type="text" id="gFather" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">মাতার নাম *</label><input type="text" id="gMother" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">এনআইডি নম্বর *</label><input type="text" id="gNid" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">মোবাইল নম্বর *</label><input type="tel" id="gMobile" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label fw-bold">ওয়ার্ড নম্বর *</label><select id="gWard" class="form-select"><option>০১</option><option>০২</option><option>০৩</option><option>০৪</option><option>০৫</option><option>০৬</option><option>০৭</option><option>০৮</option><option>০৯</option></select></div>
          <div class="col-md-4"><label class="form-label fw-bold">গ্রাম *</label><input type="text" id="gVillage" class="form-control" required></div>
        </div>
        <div class="text-end mt-4">
          <button type="submit" class="btn btn-success fw-bold px-4"><i class="fa fa-paper-plane me-1"></i> প্রত্যয়ন আবেদন দাখিল করুন</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ==================== ৭. এডমিন লগইন ভিউ ==================== -->
  <div id="loginView" class="view-section d-none">
    <div class="row justify-content-center my-5">
      <div class="col-md-4">
        <div class="card p-4 shadow-lg border-0 rounded-4 bg-white text-center">
          <div class="service-icon-wrap bg-success-subtle text-success mx-auto mb-3" style="width:64px; height:64px; font-size:26px;">
            <i class="fa-solid fa-user-shield"></i>
          </div>
          <h4 class="fw-bold text-dark mb-1">প্রশাসনিক সাইন ইন</h4>
          <p class="text-muted small mb-4">ইউপি কর্মকর্তা ও অনুমোদিত এডমিন প্যানেল</p>
          <form onsubmit="event.preventDefault(); doLogin();">
            <input type="text" id="admUser" class="form-control rounded-pill mb-3 px-3 py-2" placeholder="ইউজারনেম" required>
            <input type="password" id="admPass" class="form-control rounded-pill mb-4 px-3 py-2" placeholder="গোপন পাসওয়ার্ড" required>
            <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill py-2">লগইন করুন</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- সাকসেস ট্র্যাকিং মোডাল -->
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 text-center p-4">
      <div class="service-icon-wrap bg-success-subtle text-success mx-auto mb-2" style="width:70px; height:70px; font-size:32px;"><i class="fa-solid fa-check"></i></div>
      <h4 class="fw-bold text-success mb-2">আবেদন সফলভাবে জমা হয়েছে!</h4>
      <p class="text-muted mb-3">আপনার ট্র্যাকিং নম্বরটি সংরক্ষণ করুন:</p>
      <div class="mb-3"><span class="badge bg-success-subtle text-success border border-success px-4 py-2 fs-5 fw-bold" id="successAppId">AUL-000000</span></div>
      <div class="d-flex justify-content-center gap-2">
        <button class="btn btn-outline-dark fw-bold px-3 btn-sm rounded-pill" onclick="navigator.clipboard.writeText(document.getElementById('successAppId').innerText); Swal.fire({icon:'success', title:'কপি হয়েছে!', timer:1200, showConfirmButton:false});"><i class="fa fa-copy me-1"></i> কপি</button>
        <button class="btn btn-success fw-bold px-4 btn-sm rounded-pill" data-bs-dismiss="modal" onclick="navToSection('publicHome')">ঠিক আছে</button>
      </div>
    </div>
  </div>
</div>

<script>
  // সেকশন নেভিগেশন
  function navToSection(secId) {
    document.querySelectorAll('.view-section').forEach(el => el.classList.add('d-none'));
    document.getElementById(secId).classList.remove('d-none');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function openServiceView(secId) { navToSection(secId); }
  function openFamilyPage(type) {
    document.getElementById('famType').value = type;
    document.getElementById('famHeading').innerText = type + ' আবেদন ফরম';
    navToSection('familyView');
  }
  function openGeneralPage(type) {
    document.getElementById('genType').value = type;
    document.getElementById('genTitle').innerText = type;
    navToSection('generalView');
  }
  function handleNavAuthBtn() { navToSection('loginView'); }

  // সার্বজনীন সার্চ (এক ক্লিকে সকল কার্ড প্রদর্শন)
  async function executeSearch() {
    var query = document.getElementById('heroSearchInput').value.trim();
    if (!query) return Swal.fire({ icon: 'warning', title: 'তথ্য দিন', text: 'মোবাইল, এনআইডি অথবা নাম লিখুন।' });

    document.getElementById('servicesGridContainer').classList.add('d-none');
    document.getElementById('searchResultContainer').classList.remove('d-none');
    var output = document.getElementById('searchOutput');
    output.innerHTML = '<p class="text-center py-4 text-success fw-bold"><i class="fa fa-spinner fa-spin fa-2x me-2"></i>অনুসন্ধান করা হচ্ছে...</p>';

    const res = await apiRequest("{{ route('track.application') }}", { query: query });
    if (!res || !res.found || res.list.length === 0) {
      output.innerHTML = '<div class="alert alert-danger text-center rounded-4 p-4"><i class="fa-solid fa-triangle-exclamation fa-2x d-block mb-2"></i>কোনো রেকর্ড পাওয়া যায়নি!</div>';
      return;
    }

    var html = `<div class="alert alert-success fw-bold mb-3">মোট ${res.total} টি রেকর্ড পাওয়া গেছে</div>`;
    res.list.forEach((item, idx) => {
      var isApproved = (item.status === 'Approved');
      var badge = isApproved ? '<span class="badge bg-success">অনুমোদিত (Approved)</span>' : '<span class="badge bg-warning text-dark">অপেক্ষমাণ (Pending)</span>';
      
      var printBtn = '';
      if (isApproved) {
        if (item.type === 'নাগরিকত্ব সনদ') printBtn = `<a href="/print/citizenship/${item.appId}" target="_blank" class="btn btn-sm btn-danger fw-bold"><i class="fa fa-print me-1"></i> সনদ প্রিন্ট</a>`;
        else if (item.type.includes('ট্রেড')) printBtn = `<a href="/print/trade/${item.appId}" target="_blank" class="btn btn-sm btn-success fw-bold"><i class="fa fa-print me-1"></i> লাইসেন্স প্রিন্ট</a>`;
        else if (item.type === 'ওয়ারিশান সনদ') printBtn = `<a href="/print/warishan/${item.appId}" target="_blank" class="btn btn-sm btn-warning fw-bold text-dark"><i class="fa fa-print me-1"></i> ওয়ারিশ সনদ প্রিন্ট</a>`;
        else if (item.type.includes('পারিবারিক') || item.type.includes('উত্তরাধিকারী')) printBtn = `<a href="/print/family/${item.appId}" target="_blank" class="btn btn-sm btn-primary fw-bold"><i class="fa fa-print me-1"></i> সনদ প্রিন্ট</a>`;
        else printBtn = `<a href="/print/general/${item.appId}" target="_blank" class="btn btn-sm btn-success fw-bold"><i class="fa fa-print me-1"></i> প্রত্যয়ন প্রিন্ট</a>`;
      } else {
        printBtn = `<a href="/print/application-copy/${item.appId}" target="_blank" class="btn btn-sm btn-outline-secondary fw-bold"><i class="fa fa-file-lines me-1"></i> আবেদন কপি প্রিন্ট</a>`;
      }

      html += `
        <div class="verification-result-card">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold text-dark m-0">${item.applicantName} <small class="text-muted fs-6">#${idx+1}</small></h5>
            ${badge}
          </div>
          <div class="row g-2 mb-2 text-muted small">
            <div class="col-md-4"><strong>সেবা:</strong> <span class="text-dark">${item.type}</span></div>
            <div class="col-md-4"><strong>ট্র্যাকিং আইডি:</strong> <span class="text-primary fw-bold">${item.appId}</span></div>
            <div class="col-md-4"><strong>মোবাইল:</strong> <span class="text-dark">${item.mobile || '-'}</span></div>
          </div>
          <div class="text-end pt-2 border-top">${printBtn}</div>
        </div>
      `;
    });
    output.innerHTML = html;
  }

  function closeSearch() {
    document.getElementById('searchResultContainer').classList.add('d-none');
    document.getElementById('servicesGridContainer').classList.remove('d-none');
  }

  // ফর্ম সাবমিশন লজিক
  async function submitCitizenForm() {
    const data = {
      name: document.getElementById('citName').value, nid: document.getElementById('citNid').value,
      fatherName: document.getElementById('citFather').value, motherName: document.getElementById('citMother').value,
      dob: document.getElementById('citDob').value, mobile: document.getElementById('citMobile').value,
      wardNo: document.getElementById('citWard').value, village: document.getElementById('citVillage').value,
      postOffice: document.getElementById('citPost').value, language: document.getElementById('citLang').value
    };
    const res = await apiRequest("{{ route('apply.citizenship') }}", data);
    if(res && res.success) { document.getElementById('successAppId').innerText = res.appId; bootstrap.Modal.getOrCreateInstance(document.getElementById('successModal')).show(); }
    else Swal.fire('ত্রুটি', res.error || 'আবেদন জমা দেওয়া যায়নি', 'error');
  }

  async function submitTradeForm() {
    const data = {
      orgName: document.getElementById('trOrg').value, ownerName: document.getElementById('trOwner').value,
      fatherName: document.getElementById('trFather').value, motherName: document.getElementById('trMother').value,
      nid: document.getElementById('trNid').value, mobile: document.getElementById('trMobile').value,
      category: document.getElementById('trCategory').value, bizAddress: document.getElementById('trBizAddress').value,
      ownerAddress: document.getElementById('trOwnerAddress').value
    };
    const res = await apiRequest("{{ route('apply.trade') }}", data);
    if(res && res.success) { document.getElementById('successAppId').innerText = res.appId; bootstrap.Modal.getOrCreateInstance(document.getElementById('successModal')).show(); }
    else Swal.fire('ত্রুটি', res.error || 'আবেদন জমা দেওয়া যায়নি', 'error');
  }

  async function submitGeneralForm() {
    const data = {
      type: document.getElementById('genType').value, name: document.getElementById('gName').value,
      fatherName: document.getElementById('gFather').value, motherName: document.getElementById('gMother').value,
      nid: document.getElementById('gNid').value, mobile: document.getElementById('gMobile').value,
      wardNo: document.getElementById('gWard').value, village: document.getElementById('gVillage').value
    };
    const res = await apiRequest("{{ route('apply.general') }}", data);
    if(res && res.success) { document.getElementById('successAppId').innerText = res.appId; bootstrap.Modal.getOrCreateInstance(document.getElementById('successModal')).show(); }
    else Swal.fire('ত্রুটি', res.error || 'আবেদন জমা দেওয়া যায়নি', 'error');
  }

  // এডমিন লগইন
  async function doLogin() {
    const data = { username: document.getElementById('admUser').value, password: document.getElementById('admPass').value };
    const res = await apiRequest("{{ route('admin.login') }}", data);
    if (res && res.success) {
      sessionStorage.setItem('upAdminSession', JSON.stringify(res.user));
      Swal.fire({ icon: 'success', title: 'লগইন সফল!', timer: 1200, showConfirmButton: false }).then(() => {
        window.location.reload();
      });
    } else Swal.fire('লগইন ব্যর্থ', res.message || 'ইউজারনেম বা পাসওয়ার্ড সঠিক নয়!', 'error');
  }
</script>
@endsection
