<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ১. এডমিন ও ইউজার টেবিল
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('role')->default('Admin');
            $table->string('name')->nullable();
            $table->json('permissions')->nullable();
            $table->longText('photo')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // ২. ইউপি সেটিংস (চেয়ারম্যান, সচিব নাম ইত্যাদি)
        Schema::create('up_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // ৩. নাগরিকত্ব সনদ টেবিল
        Schema::create('citizenships', function (Blueprint $table) {
            $table->id();
            $table->string('app_id')->unique();
            $table->string('cert_no')->nullable();
            $table->string('name');
            $table->string('nid');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('dob');
            $table->string('marital_status')->default('অবিবাহিত');
            $table->string('spouse_name')->nullable();
            $table->string('mobile');
            $table->string('ward_no');
            $table->string('village');
            $table->string('post_office')->default('আউলিয়াপুর ময়দান');
            $table->string('division')->default('বরিশাল');
            $table->string('language')->default('bn');
            $table->string('status')->default('Pending');
            $table->string('apply_date');
            $table->string('signatory_role')->default('চেয়ারম্যান');
            $table->timestamps();
        });

        // ৪. পারিবারিক ও উত্তরাধিকারী সনদ টেবিল
        Schema::create('family_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('app_id')->unique();
            $table->string('certificate_type')->default('পারিবারিক সনদ');
            $table->string('name');
            $table->string('nid')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('ward_no')->nullable();
            $table->string('village')->nullable();
            $table->string('post_office')->default('বাদুরা হাট');
            $table->string('deceased_name')->nullable();
            $table->string('deceased_father')->nullable();
            $table->string('deceased_mother')->nullable();
            $table->string('deceased_date')->nullable();
            $table->string('applicant_relation')->nullable();
            $table->string('deceased_ward')->nullable();
            $table->string('deceased_village')->nullable();
            $table->string('deceased_post_office')->nullable();
            $table->string('deceased_union')->default('১১নং আউলিয়াপুর ইউনিয়ন');
            $table->string('deceased_upazila')->default('পটুয়াখালী সদর');
            $table->string('deceased_district')->default('পটুয়াখালী');
            $table->json('members_json')->nullable();
            $table->string('status')->default('Pending');
            $table->string('apply_date');
            $table->string('signatory_role')->default('চেয়ারম্যান');
            $table->timestamps();
        });

        // ৫. ওয়ারিশান সনদ টেবিল
        Schema::create('warishans', function (Blueprint $table) {
            $table->id();
            $table->string('app_id')->unique();
            $table->string('applicant_name');
            $table->string('father_spouse')->nullable();
            $table->string('deceased_name');
            $table->string('deceased_father')->nullable();
            $table->string('deceased_relation')->nullable();
            $table->string('nid')->nullable();
            $table->string('mobile')->nullable();
            $table->string('ward_no')->nullable();
            $table->string('village')->nullable();
            $table->string('post_office')->default('আউলিয়াপুর ময়দান');
            $table->string('deceased_ward_no')->nullable();
            $table->string('deceased_village')->nullable();
            $table->string('deceased_post_office')->nullable();
            $table->string('deceased_union')->default('১১নং আউলিয়াপুর ইউনিয়ন');
            $table->string('deceased_upazila')->default('পটুয়াখালী সদর');
            $table->string('deceased_district')->default('পটুয়াখালী');
            $table->string('smarak_no')->nullable();
            $table->json('warishan_tree_json')->nullable();
            $table->string('status')->default('Pending');
            $table->string('date');
            $table->string('signatory_role')->default('চেয়ারম্যান');
            $table->timestamps();
        });

        // ৬. ট্রেড লাইসেন্স টেবিল (নতুন ও নবায়ন)
        Schema::create('trade_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('app_id')->unique();
            $table->string('license_no')->nullable();
            $table->string('receipt_no')->nullable();
            $table->string('org_name');
            $table->string('owner_name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('nid')->nullable();
            $table->string('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('mobile');
            $table->text('owner_address')->nullable();
            $table->string('category')->nullable();
            $table->text('biz_details')->nullable();
            $table->text('biz_address');
            $table->string('biz_start_date')->nullable();
            $table->string('fiscal_year')->default('২০২৬-২০২৭');
            $table->string('capital')->nullable();
            $table->decimal('license_fee', 10, 2)->default(200.00);
            $table->decimal('vat_fee', 10, 2)->default(30.00);
            $table->decimal('comm_tax', 10, 2)->default(0.00);
            $table->decimal('sign_tax', 10, 2)->default(0.00);
            $table->decimal('total_fee', 10, 2)->default(230.00);
            $table->boolean('is_renewal')->default(false);
            $table->string('original_license_no')->nullable();
            $table->longText('photo')->nullable();
            $table->string('status')->default('Pending');
            $table->string('apply_date');
            $table->string('signatory_role')->default('চেয়ারম্যান');
            $table->timestamps();
        });

        // ৭. সাধারণ প্রত্যয়নপত্র টেবিল (২১ প্রকার প্রত্যয়ন)
        Schema::create('general_applications', function (Blueprint $table) {
            $table->id();
            $table->string('app_id')->unique();
            $table->string('type');
            $table->string('name');
            $table->string('nid');
            $table->string('father_name');
            $table->string('mother_name')->nullable();
            $table->string('dob')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('ward_no');
            $table->string('village');
            $table->string('post_office')->default('আউলিয়াপুর ময়দান');
            $table->string('division')->default('বরিশাল');
            $table->string('status')->default('Pending');
            $table->string('apply_date');
            $table->string('signatory_role')->default('চেয়ারম্যান');
            $table->timestamps();
        });

        // ৮. করদাতা সদস্য টেবিল
        Schema::create('tax_payers', function (Blueprint $table) {
            $table->id();
            $table->string('holding_no')->unique();
            $table->string('name');
            $table->string('nid')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('ward_no');
            $table->string('village');
            $table->string('house_type')->nullable();
            $table->decimal('annual_tax', 10, 2)->default(0.00);
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_payers');
        Schema::dropIfExists('general_applications');
        Schema::dropIfExists('trade_licenses');
        Schema::dropIfExists('warishans');
        Schema::dropIfExists('family_certificates');
        Schema::dropIfExists('citizenships');
        Schema::dropIfExists('up_settings');
        Schema::dropIfExists('users');
    }
};
