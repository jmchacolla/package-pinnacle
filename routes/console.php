<?php
use Illuminate\Support\Facades\Schema;
Artisan::command('package-pinnacle:install', function () {
    // Create pinnacle_payment_record table

    if (!Schema::hasTable('pinnacle_payment_record')) {
        Schema::create('pinnacle_payment_record', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->integer('case_id');
            $table->integer('user_id');
            $table->integer('respondent_id')->nullable();
            $table->string('permission')->nullable();
            $table->dateTime('sent_date')->nullable();
            $table->dateTime('response_date')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('medical_conditions')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('amount_paid')->nullable();
            $table->string('transaction_date_time')->nullable();
            $table->string('credit_card_number')->nullable();
            $table->string('customer_number')->nullable();
            $table->string('customer_name')->nullable();
            $table->enum('response_status', ['PENDING', 'DONE'])->nullable();
            $table->enum('attended', ['NO', 'YES'])->nullable();
            $table->timestamps();
        });
    }

    // Create pinnacle_excursion_slip table
    if (!Schema::hasTable('pinnacle_excursion_slip')) {
        Schema::create('pinnacle_excursion_slip', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->integer('case_id');
            $table->primary('case_id');
            $table->string('who_response')->nullable();
            $table->decimal('amount', $precision = 8, $scale = 2)->nullable();
            $table->string('status')->default('DRAFT')->comment('DRAFT, QUEUED,SENT, PENDING_APPROVAL, APPROVED');
            $table->dateTime('date_added')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('activity_date')->nullable();
            $table->string('slip_due_date')->nullable();
            $table->string('reminder')->nullable();
            $table->string('repeat_reminder')->nullable();
            $table->string('reminder_for')->nullable();
            $table->string('slip_date_to_send')->nullable();
            $table->string('time_to_send')->nullable();
            $table->string('close_response')->nullable();
            $table->string('closing_date')->nullable();
            $table->string('limit_responses')->nullable();
            $table->integer('limit_first')->nullable();
            $table->string('allow_wait_list')->nullable();
            $table->timestamps();
        });
    }

    // Add fields to table pinnacle_excursion_slip

    if (!Schema::hasColumn('pinnacle_excursion_slip','campus')) {
        Schema::table('pinnacle_excursion_slip', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('campus')->after('case_id')->nullable();
        });
    }
    if (!Schema::hasColumn('pinnacle_excursion_slip','school_level')) {
        Schema::table('pinnacle_excursion_slip', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('school_level')->after('case_id')->nullable();
        });
    }
    if (!Schema::hasColumn('pinnacle_excursion_slip','slip_type')) {
        Schema::table('pinnacle_excursion_slip', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('slip_type')->after('case_id')->nullable();
        });
    }
    if (!Schema::hasColumn('pinnacle_excursion_slip','requestor')) {
        Schema::table('pinnacle_excursion_slip', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->integer('requestor')->after('case_id')->nullable();
        });
    }
    if (!Schema::hasColumn('pinnacle_excursion_slip','slip_title')) {
        Schema::table('pinnacle_excursion_slip', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->integer('slip_title')->after('case_id')->nullable();
        });
    }

    if (!Schema::hasTable('uvProcessMakerStaff')) {
        Schema::create('uvProcessMakerStaff', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('employeeNo');
            $table->string('firstName', 20);
            $table->string('lastName', 20);
            $table->string('fullName', 62)->nullable();
            $table->char('gender', 1)->nullable();
            $table->string('username', 20)->nullable();
            $table->string('workEmail', 70)->nullable();
            $table->string('phone', 15)->nullable();
            $table->integer('locationId');
            $table->string('locationName', 50)->nullable();
            $table->char('status', 3)->nullable();
            $table->dateTime('createdDate', 0);
            $table->dateTime('updatedDate', 0);
        });
    }

    if (!Schema::hasTable('uvProcessMakerParents')) {
        Schema::create('uvProcessMakerParents', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parentNo');
            $table->string('firstName', 30);
            $table->string('lastName', 50);
            $table->string('fullName', 102)->nullable();
            $table->char('gender', 1)->nullable();
            $table->string('username', 20)->nullable();
            $table->string('email', 70)->nullable();
            $table->string('mobilePhone', 15)->nullable();
            $table->integer('locationId');
            $table->string('locationName', 21);
            $table->char('status', 3)->nullable();
            $table->dateTime('createdDate', 0);
            $table->dateTime('updatedDate', 0);
        });
    }

    // Create the CoolSis integration tables

    if (!Schema::hasTable('uvProcessMakerStudents')) {
        Schema::create('uvProcessMakerStudents', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('studentNo');
            $table->string('firstName', 30);
            $table->string('lastName', 50);
            $table->string('fullName', 112)->nullable();
            $table->string('email', 70)->nullable();
            $table->string('username', 20)->nullable();
            $table->timestamp('dateOfBirth')->nullable();
            $table->char('gender', 1);
            $table->mediumText('picture')->nullable();
            $table->string('gradeGroup', 7)->nullable();
            $table->char('gradeLevel', 2);
            $table->integer('locationId');
            $table->string('locationName', 21);
            $table->char('status', 3);
            $table->dateTime('createdDate', 0);
            $table->dateTime('updatedDate', 0);
        });
    }

    if (!Schema::hasTable('uvProcessMakerStudentParents')) {
        Schema::create('uvProcessMakerStudentParents', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('studentNo')->nullable();
            $table->string('parentNo')->nullable();
            $table->char('parentRelation', 3)->nullable();
        });
    }




    Artisan::call('vendor:publish', [
        '--tag' => 'package-pinnacle',
        '--force' => true
    ]);

    $this->info('Package Pinnacle has been installed');
})->describe('Installs the required js files and table in DB');


