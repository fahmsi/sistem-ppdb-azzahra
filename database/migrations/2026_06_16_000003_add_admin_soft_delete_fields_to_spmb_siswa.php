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
        if ($this->supersedesLegacyMigration()) {
            return;
        }

        Schema::table('spmb_siswa', function (Blueprint $table) {
            if (! Schema::hasColumn('spmb_siswa', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }

            if (! Schema::hasColumn('spmb_siswa', 'created_by_admin_id')) {
                $table->foreignId('created_by_admin_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('spmb_siswa', 'input_source')) {
                $table->string('input_source')->default('online')->after('created_by_admin_id');
            }

            if (! Schema::hasColumn('spmb_siswa', 'deleted_by')) {
                $table->foreignId('deleted_by')
                    ->nullable()
                    ->after('input_source')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('spmb_siswa', 'deleted_reason')) {
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
        if ($this->supersedesLegacyMigration()) {
            return;
        }

        $this->restoreUserIdCascadeConstraint();

        Schema::table('spmb_siswa', function (Blueprint $table) {
            if (Schema::hasColumn('spmb_siswa', 'created_by_admin_id')) {
                $table->dropConstrainedForeignId('created_by_admin_id');
            }

            if (Schema::hasColumn('spmb_siswa', 'deleted_by')) {
                $table->dropConstrainedForeignId('deleted_by');
            }

            if (Schema::hasColumn('spmb_siswa', 'input_source')) {
                $table->dropColumn('input_source');
            }

            if (Schema::hasColumn('spmb_siswa', 'deleted_reason')) {
                $table->dropColumn('deleted_reason');
            }

            if (Schema::hasColumn('spmb_siswa', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }

    private function makeUserIdNullable(): void
    {
        if (! Schema::hasColumn('spmb_siswa', 'user_id')) {
            return;
        }

        $this->dropUserForeignKeyIfPossible();

        Schema::table('spmb_siswa', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });

        Schema::table('spmb_siswa', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    private function restoreUserIdCascadeConstraint(): void
    {
        if (! Schema::hasColumn('spmb_siswa', 'user_id')) {
            return;
        }

        $this->dropUserForeignKeyIfPossible();

        DB::table('spmb_siswa')->whereNull('user_id')->delete();

        Schema::table('spmb_siswa', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });

        Schema::table('spmb_siswa', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    private function dropUserForeignKeyIfPossible(): void
    {
        try {
            Schema::table('spmb_siswa', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (Throwable) {
            //
        }
    }

    private function supersedesLegacyMigration(): bool
    {
        $current = pathinfo(__FILE__, PATHINFO_FILENAME);
        $legacy = str_replace('spmb', implode('', ['p', 's', 'b']), $current);

        return DB::table('migrations')->where('migration', $legacy)->exists();
    }
};
