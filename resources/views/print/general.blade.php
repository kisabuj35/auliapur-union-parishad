<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>{{ $data->type ?? 'প্রত্যয়নপত্র' }} - {{ $data->app_id }}</title>
  <link href="https://fonts.maateen.me/nikosh/font.css" rel="stylesheet">
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
      width: 210mm;
      height: 297mm;
      margin: 0 !important;
      padding: 0 !important;
      background: #ffffff;
      font-family: 'Nikosh', sans-serif !important;
      overflow: hidden !important;
    }
    .official-certificate {
      position: relative;
      width: 210mm !important;
      height: 295mm !important;
      max-height: 295mm !important;
      margin: auto;
      padding: 10mm 15mm 8mm 15mm !important;
      background: #ffffff !important;
      color: #18241e;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      overflow: hidden;
      border: 3.5px double #126341 !important;
      outline: 1.5px solid #b89446 !important;
      outline-offset: -7px;
      page-break-after: avoid !important;
      page-break-inside: avoid !important;
    }
    .official-certificate:before {
      content: "";
      position: absolute;
      inset: 4mm;
      border: 1px solid rgba(184,148,70,.65);
      pointer-events: none;
      z-index: 3;
    }
    .cert-watermark {
      position: absolute;
      top: 50%;
      left: 50%;
      width: 500px !important;
      height: 500px !important;
      transform: translate(-50%, -50%);
      opacity: .09 !important;
      z-index: 0;
      pointer-events: none;
    }
    .cert-content {
      position: relative;
      z-index: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100%;
    }
    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2mm 3mm;
      border-bottom: 2px solid #126341;
    }
    header img { width: 22mm; height: 22mm; object-fit: contain; }
    .header-copy { flex: 1; text-align: center; }
    .header-copy .govt-title { font-size: 13.5px; font-weight: bold; color: #222; }
    h1 { margin: 0; color: #073d2a; font-size: 27px; font-weight: 800; letter-spacing: 0.5px; }
    .header-copy .address-text { font-size: 14.5px; font-weight: bold; color: #000; margin-top: 1px; }
    .seal-box { width: 22mm; height: 19mm; border: 1.5px dashed #666; color: #555; font-size: 12px; display: flex; align-items: center; justify-content: center; text-align: center; }

    .rule { height: 4px; margin: 2mm 0 3mm; border-top: 1px solid #b89446; border-bottom: 1.5px solid #126341; }
    .meta { display: flex; justify-content: space-between; font-size: 16.5px !important; font-weight: bold !important; color: #000; margin: 2px 2mm 5mm; }

    /* ৩ডি (3D) সবুজ রিবন */
    h2.cert-title-ribbon {
      position: relative;
      width: fit-content;
      margin: 0 auto 6mm;
      padding: 4px 48px;
      color: #fff !important;
      background: linear-gradient(180deg, #007a3d 0%, #005a2d 60%, #004622 100%) !important;
      font-size: 24px !important;
      font-weight: bold !important;
      border-radius: 2px;
      border-top: 1.5px solid #6ee7b7;
      border-bottom: 2px solid #002813;
      box-shadow: 0 4px 8px rgba(0,0,0,.25);
      letter-spacing: 1px;
    }
    h2.cert-title-ribbon:before, h2.cert-title-ribbon:after {
      content: "";
      position: absolute;
      top: 5px;
      width: 17mm;
      height: 12mm;
      background: #00361a !important;
      z-index: -1;
    }
    h2.cert-title-ribbon:before { left: -11mm; clip-path: polygon(0 0,100% 0,72% 50%,100% 100%,0 100%,28% 50%); }
    h2.cert-title-ribbon:after { right: -11mm; clip-path: polygon(0 0,100% 0,72% 50%,100% 100%,0 100%,28% 50%); transform: scaleX(-1); }

    .certificate-body {
      font-size: 19px !important;
      line-height: 2.15 !important;
      text-align: justify;
      text-indent: 45px;
      margin: 8px 2mm 0;
      color: #000000;
      font-weight: 500;
    }
    .closing { margin: 14px 2mm 0; text-align: center; font-size: 20px !important; font-weight: bold !important; color: #000; }

    .certificate-signatures {
      display: flex;
      justify-content: flex-end;
      align-items: flex-end;
      margin: 0 2mm 4mm;
      margin-top: auto !important;
      padding-top: 12mm;
      text-align: center;
    }
    .chairman-sign-block {
      width: 44%;
      font-size: 17.5px !important;
      font-weight: bold !important;
      color: #000;
      line-height: 1.35;
    }
    .signature-line { display: block; border-top: 1.5px solid #000; width: 85%; margin: 0 auto 4px auto; }
    footer {
      display: flex;
      justify-content: space-between;
      margin-top: auto;
      padding: 3mm 2mm 0;
      border-top: 1.5px solid #126341;
      font-size: 12px;
      font-weight: bold;
      color: #126341;
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

    $templates = [
      'চারিত্রিক সনদ' => 'তিনি জন্মসূত্রে বাংলাদেশের স্থায়ী নাগরিক ও অত্র ইউনিয়নের একজন সৎ, চরিত্রবান, শান্তিপ্রিয় ও সমাজহিতৈষী ব্যক্তি। আমার জানামতে তিনি কোনো প্রকার রাষ্ট্রবিরোধী, আইনবিরোধী বা সমাজবিধ্বংসী অনৈতিক কর্মকাণ্ডের সহিত জড়িত নন এবং তাঁর বিরুদ্ধে কোনো প্রকার নৈতিক স্খলনজনিত অভিযোগ নেই। সমাজে তাঁহার নৈতিক চরিত্র অত্যন্ত প্রশংসনীয়।',
      'ভূমিহীন সনদ' => 'আমার জানামতে তাঁহার কিংবা তাঁহার পরিবারভুক্ত কোনো সদস্যের নিজস্ব কোনো স্থাবর সম্পত্তি, বসতভিটা বা চাষযোগ্য কৃষি জমি নাই। তিনি প্রকৃত অর্থেই একজন নিঃস্ব ও ভূমিহীন নাগরিক এবং পরিবারের সদস্যদের লইয়া অতি কষ্টে জীবনযাপন করিতেছেন। তিনি সরকারি যেকোনো খাসজমি বন্দোবস্ত ও পুনর্বাসন সহায়তা পাওয়ার সম্পূর্ণ উপযুক্ত।',
      'অবিবাহিত সনদ' => 'আমার জানামতে অদ্যাবধি তিনি কোনো প্রকার বিবাহ বন্ধনে আবদ্ধ হন নাই। তিনি বর্তমানে সম্পূর্ণ অবিবাহিত অবস্থায় আছেন এবং সামাজিক, ধর্মীয় ও আইনগতভাবে তিনি একজন অবিবাহিত নাগরিক হিসেবে গণ্য।',
      'বিবাহিত সনদ' => 'তিনি সামাজিক ও ধর্মীয় বিধান মোতাবেক বিবাহ বন্ধনে আবদ্ধ হইয়া স্বামী/স্ত্রী সহকারে সুখে-শান্তিতে দাম্পত্য জীবন অতিবাহিত করিতেছেন। তিনি অত্র ইউনিয়নের একজন দায়িত্বশীল বিবাহিত নাগরিক।',
      'পুনঃ বিবাহ না হওয়া সনদ' => 'তাঁহার পূর্বের স্বামী/স্ত্রীর মৃত্যুর পর অদ্যাবধি তিনি দ্বিতীয় কোনো বিবাহ বন্ধনে আবদ্ধ হন নাই। তিনি বর্তমানে সম্পূর্ণ নিঃসঙ্গ জীবন যাপন করিতেছেন এবং অদ্যাবধি দ্বিতীয় কোনো বিবাহ করেন নাই মর্মে প্রত্যয়ন করা হইল।',
      'বার্ষিক আয়ের প্রত্যয়ন' => 'আমার জানামতে তাঁহার পরিবারে কৃষি, ব্যবসা, মজুরি ও অন্যান্য যাবতীয় বৈধ উৎস হইতে বার্ষিক মোট আনুমানিক আয় প্রায় ১,২০,০০০/- (এক লক্ষ বিশ হাজার) টাকা মাত্র। উক্ত সীমিত আয়ের ওপরই তাঁহার সমগ্র পরিবারের যাবতীয় ব্যয়ভার নির্ভরশীল।',
      'প্রতিবন্ধী সনদপত্র' => 'তিনি শারীরিক/মানসিক প্রতিবন্ধকতায় ভুগিতেছেন এবং স্বাভাবিক কাজকর্ম পরিচালনায় অক্ষম। তিনি সমাজসেবা অধিদপ্তর এবং সরকারের সামাজিক নিরাপত্তা কর্মসূচির আওতায় প্রতিবন্ধী ভাতা ও অন্যান্য সুবিধা পাওয়ার পূর্ণ যোগ্য।',
      'আর্থিক অসচ্ছলতার সনদপত্র' => 'তাঁহার পরিবারের সার্বিক আর্থিক অবস্থা অত্যন্ত নাজুক, অসচ্ছল ও দারিদ্র্যসীমার নিচে। তিনি যেকোনো সরকারি ও বেসরকারি আর্থিক সহায়তা, অনুদান এবং বিশেষ সাহায্য পাওয়ার সম্পূর্ণ উপযুক্ত ও হকদার।',
      'নতুন ভোটারের প্রত্যয়ন পত্র' => 'তাঁহার বয়স ১৮ (আঠারো) বছর পূর্ণ হইয়াছে এবং তিনি অত্র এলাকার একজন স্থায়ী অধিবাসী। তিনি বাংলাদেশ নির্বাচন কমিশনের ভোটার তালিকায় নতুন ভোটার হিসেবে নাম অন্তর্ভুক্তির জন্য সর্বতোভাবে উপযুক্ত।',
      'জাতীয়তা সনদ' => 'তিনি জন্মসূত্রে ও বংশানুক্রমিকভাবে বাংলাদেশের একজন গর্বিত ও স্থায়ী নাগরিক। তিনি অদ্যাবধি কোনো বিদেশি রাষ্ট্রের নাগরিকত্ব গ্রহণ করেন নাই এবং তিনি বাংলাদেশ রাষ্ট্রের প্রতি সম্পূর্ণ অনুগত।',
      'স্থায়ী বাসিন্দা সনদ' => 'তিনি এবং তাঁহার পূর্বপুরুষগণ বংশানুক্রমিকভাবে অত্র ইউনিয়ন পরিষদের উক্ত ওয়ার্ডের স্থায়ী অধিবাসী এবং তিনি এলাকার যাবতীয় নাগরিক অধিকার ও সাংবিধানিক সুযোগ-সুবিধা ভোগ করিয়া আসিতেছেন।',
      'বিবিধ প্রত্যয়নপত্র' => 'তিনি অত্র এলাকার একজন সুপরিচিত, সৎ, শান্তিপ্রিয় ও আইনানুগ নাগরিক। আমার জানামতে তিনি কোনো প্রকার সমাজবিরোধী, রাষ্ট্রদ্রোহী বা অনৈতিক কর্মকাণ্ডে জড়িত নন।'
    ];

    $certType = $data->type ?? 'বিবিধ প্রত্যয়নপত্র';
    $specificText = $templates[$certType] ?? $templates['বিবিধ প্রত্যয়নপত্র'];
    $shortAppId = substr($data->app_id, -3);
  @endphp

  <article class="official-certificate">
    <!-- ওয়াটারমার্ক -->
    <img class="cert-watermark" src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" alt="Watermark">

    <div class="cert-content">
      <div>
        <header>
          <img src="https://lh3.googleusercontent.com/d/1_LMOoAtirVZeGxE4sz_CVQtQpInPPQTs" alt="UP Logo">
          <div class="header-copy">
            <div class="govt-title">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার (স্থানীয় সরকার বিভাগ)</div>
            <h1>১১নং আউলিয়াপুর ইউনিয়ন পরিষদ কার্যালয়</h1>
            <div class="address-text">ডাকঘর: আউলিয়াপুর ময়দান, পটুয়াখালী সদর, পটুয়াখালী</div>
          </div>
          <div class="seal-box">সিল /<br>ছবি</div>
        </header>

        <div class="rule"></div>

        <div class="meta">
          <span>স্মারক নং- আ/ইউ/পটুয়া/সদর/{{ toBn(date('Y')) }}/{{ toBn($shortAppId) }}</span>
          <span>তারিখ: {{ $data->apply_date }} ইং</span>
        </div>

        <h2 class="cert-title-ribbon">{{ $certType }}</h2>

        <div class="certificate-body">
          এই মর্মে প্রত্যয়ন পত্র প্রদান করা যাইতেছে যে, <strong>{{ $data->name }}</strong>, পিতা: <strong>{{ $data->father_name }}</strong>, মাতা: <strong>{{ $data->mother_name ?? '-' }}</strong>, জন্ম নিবন্ধন/ভোটার আইডি নম্বর: <strong>{{ toBn($data->nid) }}</strong>, গ্রাম: <strong>{{ $data->village }}</strong>, ডাকঘর: <strong>{{ $data->post_office }}</strong>, ওয়ার্ড নং: <strong>{{ toBn($data->ward_no) }}</strong>, ১১নং আউলিয়াপুর ইউনিয়ন, পটুয়াখালী সদর, পটুয়াখালী-এর একজন স্থায়ী বাসিন্দা। {{ $specificText }}
        </div>

        <div class="closing">আমি তাঁর সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।</div>
      </div>

      <div>
        <div class="certificate-signatures">
          <div class="chairman-sign-block">
            <span class="signature-line"></span>
            ({{ $settings['chairman'] ?? 'অ্যাড. মোঃ হুমায়ুন কবির' }})<br>
            <span style="font-size: 16px; font-weight: bold;">{{ $data->signatory_role ?? 'চেয়ারম্যান' }}</span><br>
            <small>১১নং আউলিয়াপুর ইউনিয়ন পরিষদ<br>পটুয়াখালী সদর, পটুয়াখালী ।</small>
          </div>
        </div>

        <footer>
          <span>তথ্য যাচাই করুন: www.aup.auliapur.com</span>
          <strong>সত্যের জয় হোক</strong>
          <span>অফিসিয়াল প্রত্যয়নপত্র</span>
        </footer>
      </div>
    </div>
  </article>

</body>
</html>
