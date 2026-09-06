<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>ওয়ারিশান সনদ - {{ $data->app_id }}</title>
  <link href="https://fonts.maateen.me/nikosh/font.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kalpurush&family=Tiro+Bangla&display=swap" rel="stylesheet">
  <style>
    @page {
      size: A4 portrait;
      margin: 0mm !important;
    }
    * {
      box-sizing: border-box;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }
    html, body {
      margin: 0 !important;
      padding: 0 !important;
      background: #ffffff;
      font-family: 'Nikosh', 'Kalpurush', sans-serif;
    }
    .war-page-container {
      width: 210mm !important;
      height: 295mm !important;
      max-height: 295mm !important;
      padding: 10mm 15mm 12mm 15mm !important;
      margin: 0 auto 10px auto !important;
      background: #ffffff !important;
      border: 3.5px double #006837 !important;
      outline: 1.5px solid #006837 !important;
      outline-offset: 3px !important;
      box-sizing: border-box !important;
      position: relative !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: space-between !important;
      page-break-after: always !important;
      page-break-inside: avoid !important;
      overflow: hidden !important;
    }
    .war-watermark-bg {
      position: absolute !important;
      top: 50% !important;
      left: 50% !important;
      transform: translate(-50%, -50%) !important;
      width: 450px !important;
      opacity: 0.11 !important;
      pointer-events: none !important;
      z-index: 1 !important;
    }
    .war-content-body {
      position: relative !important;
      z-index: 2 !important;
      height: 100% !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: space-between !important;
    }
    /* ৩ডি (3D) রয়্যাল সবুজ রিবন */
    .royal-ribbon-wrapper {
      text-align: center !important;
      margin: 6px 0 10px 0 !important;
      position: relative !important;
    }
    .royal-ribbon-3d {
      position: relative !important;
      display: inline-block !important;
      background: linear-gradient(180deg, #007a3d 0%, #005a2d 60%, #004622 100%) !important;
      color: #ffffff !important;
      font-size: 22px !important;
      font-weight: 800 !important;
      padding: 5px 45px !important;
      letter-spacing: 1px !important;
      box-shadow: 0 4px 8px rgba(0,0,0,0.28) !important;
      z-index: 2 !important;
      border-radius: 2px !important;
      border-top: 1.5px solid #6ee7b7 !important;
      border-bottom: 2px solid #002813 !important;
      text-shadow: 0 1.5px 3px rgba(0,0,0,0.4) !important;
    }
    .royal-ribbon-3d::before, .royal-ribbon-3d::after {
      content: "" !important;
      position: absolute !important;
      top: 6px !important;
      border: 17px solid #00361a !important;
      z-index: -1 !important;
    }
    .royal-ribbon-3d::before { left: -24px !important; border-right-width: 14px !important; border-left-color: transparent !important; }
    .royal-ribbon-3d::after { right: -24px !important; border-left-width: 14px !important; border-right-color: transparent !important; }
    .ribbon-fold-left, .ribbon-fold-right { position: absolute !important; bottom: -6px !important; width: 0 !important; height: 0 !important; border: 3px solid transparent !important; z-index: 1 !important; }
    .ribbon-fold-left { left: 0 !important; border-top-color: #001a0d !important; border-left-color: #001a0d !important; }
    .ribbon-fold-right { right: 0 !important; border-top-color: #001a0d !important; border-right-color: #001a0d !important; }

    /* টেবিল কাঠামো */
    .war-main-table {
      width: 100% !important;
      border-collapse: collapse !important;
      margin-top: 5px !important;
      font-size: 14px !important;
    }
    .war-main-table th, .war-main-table td {
      border: 1.2px solid #000000 !important;
      padding: 4px 6px !important;
      text-align: center !important;
      vertical-align: middle !important;
    }
    .war-main-table th { font-weight: bold !important; background-color: #f1f5f9 !important; }
    .war-subgroup-title-row { font-weight: bold !important; text-align: left !important; padding: 4px 8px !important; font-size: 13.5px !important; border: 1.2px solid #000000 !important; background-color: #fff !important; }
    .war-bottom-banner {
      border-top: 1.5px solid #006837 !important;
      padding-top: 4px !important;
      display: flex !important;
      justify-content: space-between !important;
      align-items: center !important;
      font-size: 12.5px !important;
      font-weight: bold !important;
      color: #006837 !important;
    }
  </style>
</head>
<body onload="window.print();">

  @php
    function toBn($num) {
      $en = ['0','1','2','3','4','5','6','7','8','9'];
      $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
      return str_replace($en, $bn, $num);
    }
    function formatMrito($name) {
      $name = trim($name ?? '');
      return str_starts_with($name, 'মৃত') ? $name : 'মৃত ' . $name;
    }

    $totalRows = 0;
    foreach($tree as $grp) {
      $totalRows += count($grp['members'] ?? []);
    }
    $isSinglePage = ($totalRows <= 15 && count($tree) <= 2);

    $deceasedAddress = trim(($data->deceased_ward_no ? $data->deceased_ward_no . ' নং ওয়ার্ড, ' : '') .
      ($data->deceased_village ? $data->deceased_village . ' গ্রাম, ' : '') .
      ($data->deceased_post_office ? 'ডাকঘর ' . $data->deceased_post_office . ', ' : '') .
      ($data->deceased_union ? 'ইউনিয়ন: ' . $data->deceased_union . ', ' : '') .
      ($data->deceased_upazila ? $data->deceased_upazila . ' উপজেলা, ' : '') .
      ($data->deceased_district ? $data->deceased_district . ' জেলা' : ''), ', ');
  @endphp

  <!-- ==================== পেজ ১ ==================== -->
  <div class="war-page-container">
    <img src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" class="war-watermark-bg" alt="Watermark">

    <div class="war-content-body">
      <div>
        <!-- হেডার -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2.5px solid #006837; padding-bottom: 4px;">
          <div style="width: 75px; text-align: left;"><img src="https://upload.wikimedia.org/wikipedia/commons/8/84/Government_Seal_of_Bangladesh.svg" width="60" height="60" alt="Govt"></div>
          <div style="text-align: center; flex-grow: 1;">
            <h2 style="font-weight: 800; margin: 0; font-size: 24px; color: #000;">১১নং আউলিয়াপুর ইউনিয়ন পরিষদ কার্যালয়</h2>
            <p style="margin: 2px 0 0 0; font-weight: bold; font-size: 14.5px; color: #222;">ডাকঘরঃ আউলিয়াপুর ময়দান, উপজেলা ও জেলাঃ পটুয়াখালী ।</p>
            <p style="margin: 0; font-size: 12px; font-weight: bold; color: #006837;">web site: www.aup.auliapur.com</p>
          </div>
          <div style="width: 75px; text-align: right;"><img src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" width="60" height="60" alt="UP"></div>
        </div>
        <div style="border-bottom: 1px solid #006837; margin-top: 2px; margin-bottom: 6px;"></div>

        <!-- মেটা বার -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 14.5px; font-weight: bold;">
          <div>স্মারক নং- {{ $data->smarak_no ?? 'আ/ইউ/পটুয়া/সদর/' . date('Y') . '/০১' }}</div>
          <div>তারিখঃ {{ $data->date }} ইং</div>
        </div>

        <!-- ৩ডি রিবন -->
        <div class="royal-ribbon-wrapper">
          <div class="royal-ribbon-3d">
            <span>ওয়ারিশ সার্টিফিকেট</span>
            <div class="ribbon-fold-left"></div>
            <div class="ribbon-fold-right"></div>
          </div>
        </div>

        <!-- বিবরণী -->
        <div style="margin-bottom: 8px; text-align: justify; font-size: 15px; line-height: 1.8; text-indent: 40px;">
          এই মর্মে ওয়ারিশ সার্টিফিকেট প্রদান করা যাইতেছে যে, {{ $deceasedAddress }}-এর {{ !empty($data->deceased_father) ? formatMrito($data->deceased_father) . ' এর ' . ($data->deceased_relation ?? 'পুত্র') . ' ' : '' }}<strong>{{ formatMrito($data->deceased_name) }}</strong> এর লোকান্তরে নিম্নলিখিত ব্যক্তিগণ তাহার স্থাবর-অস্থাবর সকল সম্পত্তির ওয়ারিশ বিদ্যমান থাকেন।
        </div>

        <!-- ওয়ারিশ তালিকা টেবিল -->
        <table class="war-main-table">
          <thead>
            <tr>
              <th style="width: 8%;">ক্রমিক নং</th>
              <th style="width: 32%;">ওয়ারিশগণের নাম</th>
              <th style="width: 30%;">পিতা/স্বামী নাম</th>
              <th style="width: 18%;">গ্রাম</th>
              <th style="width: 12%;">সম্পর্ক</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tree as $gIdx => $grp)
              @if(!$isSinglePage && $gIdx > 0) @break @endif
              @if($gIdx > 0)
                <tr><td colspan="5" class="war-subgroup-title-row">{{ $grp['groupTitle'] ?? '' }}</td></tr>
              @endif
              @foreach($grp['members'] ?? [] as $m)
                <tr>
                  <td>{{ toBn($m['sl'] ?? '') }}</td>
                  <td style="text-align: left; padding-left: 8px;"><strong>{{ $m['name'] ?? '' }}</strong></td>
                  <td style="text-align: left; padding-left: 8px;">{{ $m['relName'] ?? '-' }}</td>
                  <td>{{ $m['address'] ?? '-' }}</td>
                  <td>{{ $m['relation'] ?? '-' }}</td>
                </tr>
              @endforeach
            @endforeach
          </tbody>
        </table>

        @if(!$isSinglePage)
          <p style="margin: 8px 0 0; text-align: center; font-weight: bold; font-size: 14px;">(বাকি ওয়ারিশগণের নাম পরের পাতায় দেওয়া হলো)</p>
        @else
          <div style="margin-top: 8px; font-size: 13px; line-height: 1.4;">
            <p style="margin: 0; font-weight: bold;">সংশ্লিষ্ট গ্রাম পুলিশ ও ওয়ার্ড মেম্বার এর প্রত্যয়ন মতে ইহা ছাড়া আর কোন ওয়ারিশ নাই ।</p>
            <p style="margin: 2px 0 0 0;">ভবিষ্যতে বর্ণিত তথ্যের কোনরূপ গড়মিল পরিলক্ষিত হইলে উক্ত ওয়ারিশ সনদপত্র বাতিল বলিয়া গণ্য হইবে ।</p>
          </div>
        @endif
      </div>

      <!-- স্বাক্ষর ব্লক -->
      <div>
        <div style="display: flex; justify-content: space-between; align-items: flex-end; text-align: center; margin-top: 25px; padding-bottom: 8px; font-size: 14px;">
          <div style="width: 30%;">
            <span style="border-top: 1.5px solid #000; display: block; width: 80%; margin: 0 auto 3px auto;"></span>
            <strong>গ্রাম পুলিশ</strong><br>১১নং আউলিয়াপুর ইউনিয়ন
          </div>
          <div style="width: 30%;">
            <span style="border-top: 1.5px solid #000; display: block; width: 80%; margin: 0 auto 3px auto;"></span>
            <strong>ইউপি সদস্য</strong><br>১১নং আউলিয়াপুর ইউনিয়ন
          </div>
          <div style="width: 38%;">
            <span style="border-top: 1.5px solid #000; display: block; width: 80%; margin: 0 auto 3px auto;"></span>
            <strong>({{ $settings['chairman'] ?? 'অ্যাড. মোঃ হুমায়ুন কবির' }})</strong><br>
            {{ $data->signatory_role ?? 'চেয়ারম্যান' }}<br>১১নং আউলিয়াপুর ইউনিয়ন
          </div>
        </div>

        <div class="war-bottom-banner">
          <div><i class="fa fa-check-circle"></i> নিয়মিত ইউনিয়ন পরিষদের কর প্রদান করুন</div>
          <div>{{ $isSinglePage ? 'তথ্য যাচাই: www.aup.auliapur.com' : 'পাতা নং-০১ মোট পাতা-০২টি' }}</div>
          <div><i class="fa fa-shield-halved"></i> বাল্যবিবাহ রোধ করুন</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ==================== পেজ ২ (যদি ওয়ারিশ বেশি থাকে) ==================== -->
  @if(!$isSinglePage)
  <div class="war-page-container">
    <img src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" class="war-watermark-bg" alt="Watermark">

    <div class="war-content-body">
      <div>
        <!-- হেডার -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2.5px solid #006837; padding-bottom: 4px;">
          <div style="width: 75px; text-align: left;"><img src="https://upload.wikimedia.org/wikipedia/commons/8/84/Government_Seal_of_Bangladesh.svg" width="60" height="60" alt="Govt"></div>
          <div style="text-align: center; flex-grow: 1;">
            <h2 style="font-weight: 800; margin: 0; font-size: 24px; color: #000;">১১নং আউলিয়াপুর ইউনিয়ন পরিষদ কার্যালয়</h2>
            <p style="margin: 2px 0 0 0; font-weight: bold; font-size: 14.5px; color: #222;">ডাকঘরঃ আউলিয়াপুর ময়দান, উপজেলা ও জেলাঃ পটুয়াখালী ।</p>
          </div>
          <div style="width: 75px; text-align: right;"><img src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" width="60" height="60" alt="UP"></div>
        </div>
        <div style="border-bottom: 1px solid #006837; margin-top: 2px; margin-bottom: 6px;"></div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; font-size: 14.5px; font-weight: bold;">
          <div>স্মারক নং- {{ $data->smarak_no ?? '' }}</div>
          <div>তারিখঃ {{ $data->date }} ইং</div>
        </div>

        <!-- বাকি ওয়ারিশদের টেবিল -->
        <table class="war-main-table">
          <thead>
            <tr>
              <th style="width: 8%;">ক্রমিক নং</th>
              <th style="width: 32%;">ওয়ারিশগণের নাম</th>
              <th style="width: 30%;">পিতা/স্বামী নাম</th>
              <th style="width: 18%;">গ্রাম</th>
              <th style="width: 12%;">সম্পর্ক</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tree as $gIdx => $grp)
              @if($gIdx === 0) @continue @endif
              <tr><td colspan="5" class="war-subgroup-title-row">{{ $grp['groupTitle'] ?? '' }}</td></tr>
              @foreach($grp['members'] ?? [] as $m)
                <tr>
                  <td>{{ toBn($m['sl'] ?? '') }}</td>
                  <td style="text-align: left; padding-left: 8px;"><strong>{{ $m['name'] ?? '' }}</strong></td>
                  <td style="text-align: left; padding-left: 8px;">{{ $m['relName'] ?? '-' }}</td>
                  <td>{{ $m['address'] ?? '-' }}</td>
                  <td>{{ $m['relation'] ?? '-' }}</td>
                </tr>
              @endforeach
            @endforeach
          </tbody>
        </table>

        <div style="margin-top: 10px; font-size: 13.5px; line-height: 1.5;">
          <p style="margin: 0; font-weight: bold;">সংশ্লিষ্ট গ্রাম পুলিশ ও ওয়ার্ড মেম্বার এর প্রত্যয়ন মতে ইহা ছাড়া আর কোন ওয়ারিশ নাই ।</p>
          <p style="margin: 3px 0 0 0;">ভবিষ্যতে বর্ণিত তথ্যের কোনরূপ গড়মিল পরিলক্ষিত হইলে উক্ত ওয়ারিশ সনদপত্র বাতিল বলিয়া গণ্য হইবে । এ সনদপত্র দ্বারা সংশ্লিষ্ট আইনে অনুমোদিত অন্য কোন বৈধ ওয়ারিশকে বঞ্চিত করা যাইবে না ।</p>
        </div>
      </div>

      <!-- পেজ ২ এর স্বাক্ষর ব্লক -->
      <div>
        <div style="display: flex; justify-content: space-between; align-items: flex-end; text-align: center; margin-top: 25px; padding-bottom: 8px; font-size: 14px;">
          <div style="width: 30%;">
            <span style="border-top: 1.5px solid #000; display: block; width: 80%; margin: 0 auto 3px auto;"></span>
            <strong>গ্রাম পুলিশ</strong><br>১১নং আউলিয়াপুর ইউনিয়ন
          </div>
          <div style="width: 30%;">
            <span style="border-top: 1.5px solid #000; display: block; width: 80%; margin: 0 auto 3px auto;"></span>
            <strong>ইউপি সদস্য</strong><br>১১নং আউলিয়াপুর ইউনিয়ন
          </div>
          <div style="width: 38%;">
            <span style="border-top: 1.5px solid #000; display: block; width: 80%; margin: 0 auto 3px auto;"></span>
            <strong>({{ $settings['chairman'] ?? 'অ্যাড. মোঃ হুমায়ুন কবির' }})</strong><br>
            {{ $data->signatory_role ?? 'চেয়ারম্যান' }}<br>১১নং আউলিয়াপুর ইউনিয়ন
          </div>
        </div>

        <div class="war-bottom-banner">
          <div><i class="fa fa-check-circle"></i> নিয়মিত ইউনিয়ন পরিষদের কর প্রদান করুন</div>
          <div>পাতা নং-০২ মোট পাতা-০২টি</div>
          <div><i class="fa fa-shield-halved"></i> বাল্যবিবাহ রোধ করুন</div>
        </div>
      </div>
    </div>
  </div>
  @endif

</body>
</html>
