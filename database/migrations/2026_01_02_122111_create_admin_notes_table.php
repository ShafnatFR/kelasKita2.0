<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_notes', function (Blueprint $table) {
            $table->id();
            $table->morphs('notable'); // notable_id, notable_type
            $table->text('content');
            $table->timestamps();
        });

        // Migrate existing data from Users
        if (Schema::hasColumn('users', 'catatan_admin')) {
            $users = DB::table('users')->whereNotNull('catatan_admin')->get();
            foreach ($users as $user) {
                DB::table('admin_notes')->insert([
                    'notable_id' => $user->id_user,
                    'notable_type' => 'App\Models\User',
                    'content' => $user->catatan_admin,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Migrate existing data from Reports
        if (Schema::hasColumn('reports', 'catatan_admin')) {
            $reports = DB::table('reports')->whereNotNull('catatan_admin')->get();
            foreach ($reports as $report) {
                DB::table('admin_notes')->insert([
                    'notable_id' => $report->id_report,
                    'notable_type' => 'App\Models\Report',
                    'content' => $report->catatan_admin,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Migrate existing data from Kelas
        if (Schema::hasColumn('kelas', 'catatan_admin')) {
            $classes = DB::table('kelas')->whereNotNull('catatan_admin')->get();
            foreach ($classes as $class) {
                DB::table('admin_notes')->insert([
                    'notable_id' => $class->id_kelas,
                    'notable_type' => 'App\Models\Kelas',
                    'content' => $class->catatan_admin,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            Schema::table('kelas', function (Blueprint $table) {
                $table->dropColumn('catatan_admin');
            });
        }

        // Drop old columns
        if (Schema::hasColumn('users', 'catatan_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('catatan_admin');
            });
        }

        if (Schema::hasColumn('reports', 'catatan_admin')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->dropColumn('catatan_admin');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notes');

        Schema::table('users', function (Blueprint $table) {
            $table->text('catatan_admin')->nullable()->after('status');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->text('catatan_admin')->nullable()->after('status');
        });

    }
};
