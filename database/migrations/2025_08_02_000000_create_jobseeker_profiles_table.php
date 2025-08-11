<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('jobseeker_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('birthday')->nullable();
            $table->string('sex')->nullable();
            $table->string('photo')->nullable();
            $table->string('civilstatus')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('religion')->nullable();
            $table->string('contactnumber')->nullable();
            $table->string('email')->nullable();
            $table->json('disability')->nullable();
            $table->boolean('is_4ps')->default(false);
            $table->string('employmentstatus')->nullable();
            $table->json('education')->nullable();
            $table->json('work_experience')->nullable();
            $table->json('skills')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobseeker_profiles');
    }
};
