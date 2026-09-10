<div id="adminPanelWrapper" class="container-fluid my-3">
  <div class="row g-3">
    
    <!-- 🟢 বাম সাইডবার মেনু -->
    <div class="col-md-3 col-lg-2">
      <div class="card bg-dark text-white p-2 rounded-4 shadow-sm border-0" style="min-height: 85vh;">
        <div class="text-center py-3 border-bottom border-secondary">
          <img src="https://upload.wikimedia.org/wikipedia/commons/7/7c/Profile_avatar_placeholder_large.png" id="admActivePhoto" class="rounded-circle border border-2 border-success mb-2" width="70" height="70" style="object-fit: cover;">
          <h6 class="fw-bold m-0" id="admActiveName">এডমিন</h6>
          <span class="badge bg-success mt-1" id="admActiveRole">Admin</span>
        </div>

        <div class="d-grid gap-1 mt-3" id="admSidebarMenu">
          <button class="btn btn-outline-light text-start border-0 active" onclick="switchTab('dashTab')"><i class="fa fa-tachometer-alt me-2 text-success"></i> ড্যাশবোর্ড</button>
          <button class="btn btn-outline-light text-start border-0" onclick="switchTab('citTab')"><i class="fa fa-id-card me-2 text-danger"></i> নাগরিকত্ব সনদ</button>
          <button class="btn btn-outline-light text-start border-0" onclick="switchTab('tradeTab')"><i class="fa fa-briefcase me-2 text-success"></i> ট্রেড লাইসেন্স</button>
          <button class="btn btn-outline-light text-start border-0" onclick="switchTab('famTab')"><i class="fa fa-people-roof me-2 text-primary"></i> পারিবারিক সনদ</button>
          <button class="btn btn-outline-light text-start border-0" onclick="switchTab('warTab')"><i class="fa fa-sitemap me-2 text-warning"></i> ওয়ারিশান সনদ</button>
          <button class="btn btn-outline-light text-start border-0" onclick="switchTab('genTab')"><i class="fa fa-file-invoice me-2 text-info"></i> সাধারণ প্রত্যয়ন</button>
          <button class="btn btn-outline-light text-start border-0" onclick="switchTab('taxTab')"><i class="fa fa-hand-holding-dollar me-2 text-success"></i> কর আদায় ও সদস্য</button>
          <button class="btn btn-outline-light text-start border-0 text-info border-top border-secondary mt-2 pt-2" onclick="switchTab('upSettingsTab')"><i class="fa fa-sliders me-2"></i> ইউপি সেটিংস</button>
          <button class="btn btn-outline-light text-start border-0 text-warning" id="superAdminBtn" onclick="switchTab('usersTab')"><i class="fa fa-user-gear me-2"></i> এডমিন পারমিশন</button>
        </div>

        <div class="mt-auto pt-3 border-top border-secondary text-center">
          <button class="btn btn-danger btn-sm w-100 fw-bold rounded-pill" onclick="logoutAdmin()"><i class="fa fa-sign-out-alt me-1"></i> লগআউট</button>
        </div>
      </div>
    </div>

    <!-- 🟢 ডান পাশ: মূল এডমিন কন্টেন্ট এরিয়া -->
    <div class="col-md-9 col-lg-10">
      
      <!-- ১. ড্যাশবোর্ড ট্যাব -->
      <div id="dashTab" class="adm-tab">
        <div class="card p-3 shadow-sm border-0 mb-3 bg-success text-white d-flex flex-row justify-content-between align-items-center rounded-3">
          <h5 class="fw-bold m-0"><i class="fa fa-tachometer-alt me-2"></i> এডমিন ড্যাশবোর্ড ওভারভিউ</h5>
          <button class="btn btn-light btn-sm fw-bold text-success" onclick="loadDashboardStats()"><i class="fa fa-sync me-1"></i> রিফ্রেশ</button>
        </div>
        <div class="row g-3">
          <div class="col-md-4"><div class="card p-3 bg-primary text-white border-0 rounded-3 shadow-sm"><div class="d-flex justify-content-between"><h6>মোট সকল আবেদন</h6><h4 class="fw-bold" id="dTotalApps">০</h4></div><div class="mt-2 pt-2 border-top border-white-50 d-flex justify-content-between"><span>অনুমোদিত: <b id="dApproved">০</b></span><span>পেন্ডিং: <b id="dPending">০</b></span></div></div></div>
          <div class="col-md-4"><div class="card p-3 bg-danger text-white border-0 rounded-3 shadow-sm"><div class="d-flex justify-content-between"><h6>নাগরিকত্ব সনদ</h6><h4 class="fw-bold" id="dCitTotal">০</h4></div><div class="mt-2 pt-2 border-top border-white-50 d-flex justify-content-between"><span>প্রদানকৃত: <b id="dCitApproved">০</b></span><span>পেন্ডিং: <b id="dCitPending">০</b></span></div></div></div>
          <div class="col-md-4"><div class="card p-3 bg-success text-white border-0 rounded-3 shadow-sm"><div class="d-flex justify-content-between"><h6>ট্রেড লাইসেন্স</h6><h4 class="fw-bold" id="dTradeTotal">০</h4></div><div class="mt-2 pt-2 border-top border-white-50 d-flex justify-content-between"><span>অনুমোদিত: <b id="dTradeApproved">০</b></span><span>পেন্ডিং: <b id="dTradePending">০</b></span></div></div></div>
          <div class="col-md-4"><div class="card p-3 bg-warning text-dark border-0 rounded-3 shadow-sm"><div class="d-flex justify-content-between"><h6>ওয়ারিশান সনদ</h6><h4 class="fw-bold" id="dWarTotal">০</h4></div><div class="mt-2 pt-2 border-dark-50 d-flex justify-content-between"><span>অনুমোদিত: <b id="dWarApproved">০</b></span><span>পেন্ডিং: <b id="dWarPending">০</b></span></div></div></div>
          <div class="col-md-4"><div class="card p-3 bg-info text-dark border-0 rounded-3 shadow-sm"><div class="d-flex justify-content-between"><h6>পারিবারিক সনদ</h6><h4 class="fw-bold" id="dFamTotal">০</h4></div><div class="mt-2 pt-2 border-dark-50 d-flex justify-content-between"><span>অনুমোদিত: <b id="dFamApproved">০</b></span><span>পেন্ডিং: <b id="dFamPending">০</b></span></div></div></div>
          <div class="col-md-4"><div class="card p-3 bg-secondary text-white border-0 rounded-3 shadow-sm"><div class="d-flex justify-content-between"><h6>সাধারণ প্রত্যয়ন</h6><h4 class="fw-bold" id="dGenTotal">০</h4></div><div class="mt-2 pt-2 border-top border-white-50 d-flex justify-content-between"><span>অনুমোদিত: <b id="dGenApproved">০</b></span><span>পেন্ডিং: <b id="dGenPending">০</b></span></div></div></div>
        </div>
      </div>

      <!-- ২. নাগরিকত্ব সনদ তালিকা ট্যাব -->
      <div id="citTab" class="adm-tab d-none">
        <div class="card p-3 shadow-sm border-0 mb-3 bg-danger text-white d-flex flex-row justify-content-between align-items-center rounded-3">
          <h5 class="fw-bold m-0"><i class="fa fa-id-card me-2"></i> নাগরিকত্ব সনদের আবেদন তালিকা</h5>
          <button class="btn btn-light btn-sm fw-bold text-danger" onclick="loadCitizenshipList()"><i class="fa fa-sync me-1"></i> রিফ্রেশ</button>
        </div>
        <div class="table-responsive bg-white p-3 shadow-sm rounded-3 border">
          <table class="table table-hover align-middle">
            <thead class="table-dark"><tr><th>আবেদন নং</th><th>সনদ নং</th><th>নাম</th><th>পিতার নাম</th><th>মোবাইল</th><th>স্ট্যাটাস</th><th width="140">অ্যাকশন</th></tr></thead>
            <tbody id="citTableBody"></tbody>
          </table>
        </div>
      </div>

      <!-- ৩. ট্রেড লাইসেন্স তালিকা ট্যাব -->
      <div id="tradeTab" class="adm-tab d-none">
        <div class="card p-3 shadow-sm border-0 mb-3 bg-success text-white d-flex flex-row justify-content-between align-items-center rounded-3">
          <h5 class="fw-bold m-0"><i class="fa fa-briefcase me-2"></i> ট্রেড লাইসেন্স আবেদন ও তালিকা</h5>
          <button class="btn btn-light btn-sm fw-bold text-success" onclick="loadTradeList()"><i class="fa fa-sync me-1"></i> রিফ্রেশ</button>
        </div>
        <div class="table-responsive bg-white p-3 shadow-sm rounded-3 border">
          <table class="table table-hover align-middle">
            <thead class="table-dark"><tr><th>আবেদন নং</th><th>প্রতিষ্ঠানের নাম</th><th>মালিকের নাম</th><th>মোবাইল</th><th>ফি</th><th>স্ট্যাটাস</th><th width="140">অ্যাকশন</th></tr></thead>
            <tbody id="tradeTableBody"></tbody>
          </table>
        </div>
      </div>

      <!-- ৪. পারিবারিক ও উত্তরাধিকারী সনদ ট্যাব -->
      <div id="famTab" class="adm-tab d-none">
        <div class="card p-3 shadow-sm border-0 mb-3 bg-primary text-white d-flex flex-row justify-content-between align-items-center rounded-3">
          <h5 class="fw-bold m-0"><i class="fa fa-people-roof me-2"></i> পারিবারিক ও উত্তরাধিকারী আবেদন তালিকা</h5>
          <button class="btn btn-light btn-sm fw-bold text-primary" onclick="loadFamilyList()"><i class="fa fa-sync me-1"></i> রিফ্রেশ</button>
        </div>
        <div class="table-responsive bg-white p-3 shadow-sm rounded-3 border">
          <table class="table table-hover align-middle">
            <thead class="table-dark"><tr><th>আবেদন নং</th><th>ধরন</th><th>নাম</th><th>পিতার নাম</th><th>মোবাইল</th><th>স্ট্যাটাস</th><th width="140">অ্যাকশন</th></tr></thead>
            <tbody id="famTableBody"></tbody>
          </table>
        </div>
      </div>

      <!-- ৫. ওয়ারিশান সনদ তালিকা ট্যাব -->
      <div id="warTab" class="adm-tab d-none">
        <div class="card p-3 shadow-sm border-0 mb-3 bg-warning text-dark d-flex flex-row justify-content-between align-items-center rounded-3">
          <h5 class="fw-bold m-0"><i class="fa fa-sitemap me-2"></i> ওয়ারিশান সনদের আবেদন তালিকা</h5>
          <button class="btn btn-dark btn-sm fw-bold" onclick="loadWarishanList()"><i class="fa fa-sync me-1"></i> রিফ্রেশ</button>
        </div>
        <div class="table-responsive bg-white p-3 shadow-sm rounded-3 border">
          <table class="table table-hover align-middle">
            <thead class="table-dark"><tr><th>আবেদন নং</th><th>আবেদনকারী</th><th>মৃত ব্যক্তি</th><th>মোবাইল</th><th>স্মারক নং</th><th>স্ট্যাটাস</th><th width="140">অ্যাকশন</th></tr></thead>
            <tbody id="warTableBody"></tbody>
          </table>
        </div>
      </div>

      <!-- ৬. সাধারণ প্রত্যয়নপত্র তালিকা ট্যাব -->
      <div id="genTab" class="adm-tab d-none">
        <div class="card p-3 shadow-sm border-0 mb-3 bg-info text-dark d-flex flex-row justify-content-between align-items-center rounded-3">
          <h5 class="fw-bold m-0"><i class="fa fa-file-invoice me-2"></i> সাধারণ প্রত্যয়নপত্রের আবেদন তালিকা</h5>
          <button class="btn btn-dark btn-sm fw-bold" onclick="loadGeneralList()"><i class="fa fa-sync me-1"></i> রিফ্রেশ</button>
        </div>
        <div class="table-responsive bg-white p-3 shadow-sm rounded-3 border">
          <table class="table table-hover align-middle">
            <thead class="table-dark"><tr><th>আবেদন নং</th><th>প্রত্যয়নের নাম</th><th>নাম</th><th>পিতার নাম</th><th>মোবাইল</th><th>স্ট্যাটাস</th><th width="140">অ্যাকশন</th></tr></thead>
            <tbody id="genTableBody"></tbody>
          </table>
        </div>
      </div>

      <!-- ৭. কর আদায় ও সদস্য তালিকা -->
      <div id="taxTab" class="adm-tab d-none">
        <div class="card p-3 shadow-sm border-0 mb-3 bg-success text-white d-flex flex-row justify-content-between align-items-center rounded-3">
          <h5 class="fw-bold m-0"><i class="fa fa-hand-holding-dollar me-2"></i> কর আদায় ও করদাতা সদস্য তালিকা</h5>
          <button class="btn btn-light btn-sm fw-bold text-success" onclick="loadTaxList()"><i class="fa fa-sync me-1"></i> রিফ্রেশ</button>
        </div>
        <div class="table-responsive bg-white p-3 shadow-sm rounded-3 border">
          <table class="table table-hover align-middle">
            <thead class="table-dark"><tr><th>হোল্ডিং নং</th><th>নাম</th><th>পিতার নাম</th><th>ওয়ার্ড</th><th>গ্রাম</th><th>বাড়ির ধরন</th><th>বার্ষিক কর</th></tr></thead>
            <tbody id="taxTableBody"></tbody>
          </table>
        </div>
      </div>

      <!-- ৮. ইউপি সেটিংস (চেয়ারম্যান ও সচিব সেটিংস) -->
      <div id="upSettingsTab" class="adm-tab d-none">
        <div class="card p-3 shadow-sm border-0 mb-3 bg-dark text-white rounded-3">
          <h5 class="fw-bold m-0 text-info"><i class="fa fa-sliders me-2"></i> চেয়ারম্যান ও সচিব সেটিংস</h5>
        </div>
        <div class="card p-4 shadow-sm border-0 bg-white rounded-3">
          <form onsubmit="event.preventDefault(); saveUPSettings();">
            <div class="row g-3">
              <div class="col-md-6"><label class="form-label fw-bold">চেয়ারম্যানের নাম (বাংলা) *</label><input type="text" id="setChairman" class="form-control" required></div>
              <div class="col-md-6"><label class="form-label fw-bold">চেয়ারম্যানের ইংরেজি নাম</label><input type="text" id="setChairmanEn" class="form-control"></div>
              <div class="col-md-6"><label class="form-label fw-bold">প্যানেল চেয়ারম্যানের নাম (বাংলা)</label><input type="text" id="setPanelChairman" class="form-control"></div>
              <div class="col-md-6"><label class="form-label fw-bold">প্যানেল চেয়ারম্যানের ইংরেজি নাম</label><input type="text" id="setPanelChairmanEn" class="form-control"></div>
              <div class="col-md-6"><label class="form-label fw-bold">ইউপি সচিবের নাম (বাংলা) *</label><input type="text" id="setSecretary" class="form-control" required></div>
              <div class="col-md-6"><label class="form-label fw-bold">ইউপি সচিবের ইংরেজি নাম</label><input type="text" id="setSecretaryEn" class="form-control"></div>
              <div class="col-md-6"><label class="form-label fw-bold">চেয়ারম্যান হেল্পলাইন মোবাইল</label><input type="tel" id="setChairmanHelpline" class="form-control"></div>
            </div>
            <div class="text-end mt-4">
              <button type="submit" class="btn btn-success fw-bold px-4"><i class="fa fa-save me-1"></i> সেটিংস সংরক্ষণ করুন</button>
            </div>
          </form>
        </div>
      </div>

      <!-- ৯. এডমিন ইউজার পারমিশন কন্ট্রোল -->
      <div id="usersTab" class="adm-tab d-none">
        <div class="card p-3 shadow-sm border-0 mb-3 bg-dark text-white d-flex flex-row justify-content-between align-items-center rounded-3">
          <h5 class="fw-bold m-0 text-warning"><i class="fa fa-user-gear me-2"></i> এডমিন ইউজার ও এক্সেস পারমিশন</h5>
          <button class="btn btn-warning btn-sm fw-bold" onclick="openAddUserModal()"><i class="fa fa-plus me-1"></i> নতুন এডমিন যোগ করুন</button>
        </div>
        <div class="table-responsive bg-white p-3 shadow-sm rounded-3 border">
          <table class="table table-hover align-middle">
            <thead class="table-dark"><tr><th>ইউজারনেম</th><th>নাম</th><th>রোল</th><th>পারমিশন</th><th width="100">অ্যাকশন</th></tr></thead>
            <tbody id="usersTableBody"></tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  // ট্যাব সুইচার
  function switchTab(tabId) {
    document.querySelectorAll('.adm-tab').forEach(el => el.classList.add('d-none'));
    document.getElementById(tabId).classList.remove('d-none');
    
    if (tabId === 'dashTab') loadDashboardStats();
    else if (tabId === 'citTab') loadCitizenshipList();
    else if (tabId === 'tradeTab') loadTradeList();
    else if (tabId === 'famTab') loadFamilyList();
    else if (tabId === 'warTab') loadWarishanList();
    else if (tabId === 'genTab') loadGeneralList();
    else if (tabId === 'taxTab') loadTaxList();
    else if (tabId === 'upSettingsTab') loadUPSettings();
    else if (tabId === 'usersTab') loadAdminUsers();
  }

  // ড্যাশবোর্ড পরিসংখ্যান লোড
  async function loadDashboardStats() {
    const res = await fetch('/admin-api/dashboard-stats').then(r => r.json());
    if(!res) return;
    document.getElementById('dTotalApps').innerText = res.totalApps;
    document.getElementById('dApproved').innerText = res.totalApprovedApps;
    document.getElementById('dPending').innerText = res.totalPendingApps;
    document.getElementById('dCitTotal').innerText = res.citTotal;
    document.getElementById('dCitApproved').innerText = res.citApproved;
    document.getElementById('dCitPending').innerText = res.citPending;
    document.getElementById('dTradeTotal').innerText = res.tradeTotal;
    document.getElementById('dTradeApproved').innerText = res.tradeActive;
    document.getElementById('dTradePending').innerText = res.tradePending;
    document.getElementById('dWarTotal').innerText = res.warTotal;
    document.getElementById('dWarApproved').innerText = res.warApproved;
    document.getElementById('dWarPending').innerText = res.warPending;
    document.getElementById('dFamTotal').innerText = res.famTotal;
    document.getElementById('dFamApproved').innerText = res.famApproved;
    document.getElementById('dFamPending').innerText = res.famPending;
    document.getElementById('dGenTotal').innerText = res.genTotal;
    document.getElementById('dGenApproved').innerText = res.genApproved;
    document.getElementById('dGenPending').innerText = res.genPending;
  }

  // স্ট্যাটাস পরিবর্তন
  async function changeStatus(appId, status) {
    const res = await apiRequest('/admin-api/update-status', { appId: appId, status: status });
    if(res && res.success) {
      Swal.fire({ icon: 'success', title: 'স্ট্যাটাস আপডেট!', timer: 1200, showConfirmButton: false });
      loadDashboardStats();
    }
  }

  // ডিলিট
  async function deleteApp(appId) {
    Swal.fire({
      title: 'আপনি কি নিশ্চিত?',
      text: "আবেদনটি স্থায়ীভাবে মুছে ফেলা হবে!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'হ্যাঁ, ডিলিট করুন'
    }).then(async (result) => {
      if (result.isConfirmed) {
        const res = await apiRequest('/admin-api/delete-record', { appId: appId });
        if(res && res.success) {
          Swal.fire({ icon: 'success', title: 'ডিলিট সম্পন্ন!', timer: 1200, showConfirmButton: false });
          loadDashboardStats();
        }
      }
    });
  }

  // নাগরিকত্ব তালিকা
  async function loadCitizenshipList() {
    const list = await fetch('/admin-api/citizenship-list').then(r => r.json());
    var tbody = document.getElementById('citTableBody');
    tbody.innerHTML = '';
    list.forEach(row => {
      var badge = row.status === 'Approved' ? '<span class="badge bg-success">Approved</span>' : '<span class="badge bg-warning text-dark">Pending</span>';
      tbody.innerHTML += `
        <tr>
          <td><strong>${row.app_id}</strong></td>
          <td>${row.cert_no || '-'}</td>
          <td>${row.name}</td>
          <td>${row.father_name}</td>
          <td>${row.mobile}</td>
          <td>${badge}</td>
          <td>
            <button class="btn btn-sm btn-success" onclick="changeStatus('${row.app_id}', 'Approved')" title="অনুমোদন"><i class="fa fa-check"></i></button>
            <button class="btn btn-sm btn-danger" onclick="changeStatus('${row.app_id}', 'Rejected')" title="বাতিল"><i class="fa fa-times"></i></button>
            <a href="/print/citizenship/${row.app_id}" target="_blank" class="btn btn-sm btn-danger fw-bold" title="প্রিন্ট"><i class="fa fa-print"></i></a>
            <button class="btn btn-sm btn-outline-danger" onclick="deleteApp('${row.app_id}')" title="মুছুন"><i class="fa fa-trash"></i></button>
          </td>
        </tr>
      `;
    });
  }

  // ট্রেড লাইসেন্স তালিকা
  async function loadTradeList() {
    const list = await fetch('/admin-api/trade-list').then(r => r.json());
    var tbody = document.getElementById('tradeTableBody');
    tbody.innerHTML = '';
    list.forEach(row => {
      var badge = row.status === 'Approved' ? '<span class="badge bg-success">Approved</span>' : '<span class="badge bg-warning text-dark">Pending</span>';
      tbody.innerHTML += `
        <tr>
          <td><strong>${row.app_id}</strong></td>
          <td>${row.org_name}</td>
          <td>${row.owner_name}</td>
          <td>${row.mobile}</td>
          <td>৳${row.total_fee}</td>
          <td>${badge}</td>
          <td>
            <button class="btn btn-sm btn-success" onclick="changeStatus('${row.app_id}', 'Approved')"><i class="fa fa-check"></i></button>
            <button class="btn btn-sm btn-danger" onclick="changeStatus('${row.app_id}', 'Rejected')"><i class="fa fa-times"></i></button>
            <a href="/print/trade/${row.app_id}" target="_blank" class="btn btn-sm btn-success fw-bold"><i class="fa fa-print"></i></a>
            <button class="btn btn-sm btn-outline-danger" onclick="deleteApp('${row.app_id}')"><i class="fa fa-trash"></i></button>
          </td>
        </tr>
      `;
    });
  }

  // ইউপি সেটিংস লোড ও সেভ
  async function loadUPSettings() {
    const s = await fetch('/admin-api/up-settings').then(r => r.json());
    if(s) {
      document.getElementById('setChairman').value = s.chairman || 'অ্যাড. মোঃ হুমায়ুন কবির';
      document.getElementById('setChairmanEn').value = s.chairmanEn || 'Adv. Md. Humayun Kabir';
      document.getElementById('setPanelChairman').value = s.panelChairman || 'মোঃ আবদুস সালাম মৃধা';
      document.getElementById('setPanelChairmanEn').value = s.panelChairmanEn || 'Md. Abdus Salam Mridha';
      document.getElementById('setSecretary').value = s.secretary || 'মোঃ মোতাহার উদ্দিন';
      document.getElementById('setSecretaryEn').value = s.secretaryEn || 'Md. Motahar Uddin';
      document.getElementById('setChairmanHelpline').value = s.chairmanHelpline || '০১৭১০-১৮১০৫৯';
    }
  }

  async function saveUPSettings() {
    const data = {
      chairman: document.getElementById('setChairman').value,
      chairmanEn: document.getElementById('setChairmanEn').value,
      panelChairman: document.getElementById('setPanelChairman').value,
      panelChairmanEn: document.getElementById('setPanelChairmanEn').value,
      secretary: document.getElementById('setSecretary').value,
      secretaryEn: document.getElementById('setSecretaryEn').value,
      chairmanHelpline: document.getElementById('setChairmanHelpline').value
    };
    const res = await apiRequest('/admin-api/save-up-settings', data);
    if(res && res.success) Swal.fire({ icon: 'success', title: 'সংরক্ষিত হয়েছে!', timer: 1200, showConfirmButton: false });
  }

  function logoutAdmin() {
    sessionStorage.removeItem('upAdminSession');
    window.location.reload();
  }
</script>
