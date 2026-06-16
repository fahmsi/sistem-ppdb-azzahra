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
        Schema::table('psb_siswa', function (Blueprint $table) {
            if (! Schema::hasColumn('psb_siswa', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }

            if (! Schema::hasColumn('psb_siswa', 'created_by_admin_id')) {
                $table->foreignId('created_by_admin_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('psb_siswa', 'input_source')) {
                $table->string('input_source')->default('online')->after('created_by_admin_id');
            }

            if (! Schema::hasColumn('psb_siswa', 'deleted_by')) {
                $table->foreignId('deleted_by')
                    ->nullable()
                    ->after('input_source')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('psb_siswa', 'deleted_reason')) {
                $table->text('deleted_reason')->nullable()->after('deleted_by');
            }
        });

        $this->makeUserIdNullable();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->restoreUserIdCascadeConstraint();

        Schema::table('psb_siswa', function (Blueprint $table) {
            if (Schema::hasColumn('psb_siswa', 'created_by_admin_id')) {
                $table->dropConstrainedForeignId('created_by_admin_id');
            }

            if (Schema::hasColumn('psb_siswa', 'deleted_by')) {
                $table->dropConstrainedForeignId('deleted_by');
            }

            if (Schema::hasColumn('psb_siswa', 'input_source')) {
                $table->dropColumn('input_source');
            }

            if (Schema::hasColumn('psb_siswa', 'deleted_reason')) {
                $table->dropColumn('deleted_reason');
            }

            if (Schema::hasColumn('psb_siswa', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }

    private function makeUserIdNullable(): void
    {
        if (! Schema::hasColumn('psb_siswa', 'user_id')) {
            return;
        }

        $this->dropUserForeignKeyIfPossible();

        Schema::table('psb_siswa', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });

        Schema::table('psb_siswa', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    private function restoreUserIdCascadeConstraint(): void
    {
        if (! Schema::hasColumn('psb_siswa', 'user_id')) {
            return;
        }

        $this->dropUserForeignKeyIfPossible();

        DB::table('psb_siswa')->whereNull('user_id')->delete();

        Schema::table('psb_siswa', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });

        Schema::table('psb_siswa', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    private function dropUserForeignKeyIfPossible(): void
    {
        try {
            Schema::table('psb_siswa', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (\Throwable) {
            //
        }
    }
};
