    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateSimpananSukarelaTable extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('simpanan_sukarela', function (Blueprint $table) {
                $table->id();
                $table->string('no_simpanan')->unique(); // Nomor simpanan unik
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
                $table->foreignId('rekening_simpanan_sukarela_id')->constrained('rekening_simpanan_sukarela')->onDelete('cascade'); // Foreign key ke rekening_simpanan_sukarela
                $table->string('bank'); // Nama bank
                $table->bigInteger('nominal'); // Nominal simpanan
                $table->string('virtual_account')->nullable(); // Virtual account
                $table->timestamp('expired_at')->nullable(); // Expired payment
                $table->string('status_payment')->default('Menunggu Approve Manager'); // Status payment sebagai string
                $table->timestamps(); // created_at, updated_at
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('simpanan_sukarela');
        }
    }
