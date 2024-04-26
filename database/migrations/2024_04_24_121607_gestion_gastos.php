<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGestionGastosTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('cuentas', function (Blueprint $table) {
            $table->bigIncrements('id_cuenta');
            $table->unsignedBigInteger('id');
            $table->string('nombre_cuenta');
            $table->decimal('objetivo_ahorro', 10, 2)->nullable();
            $table->timestamps();
            $table->foreign('id')->references('id')->on('users');
        });

        Schema::create('objetivos_ahorro', function (Blueprint $table) {
            $table->bigIncrements('id_objetivo');
            $table->unsignedBigInteger('id_cuenta');
            $table->decimal('objetivo', 10, 2);
            $table->timestamps();
            $table->foreign('id_cuenta')->references('id_cuenta')->on('cuentas');
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->bigIncrements('id_categoria');
            $table->unsignedBigInteger('id');
            $table->string('nombre');
            $table->string('color')->nullable();
            $table->timestamps();
            $table->foreign('id')->references('id')->on('users');
        });

        Schema::create('gastos', function (Blueprint $table) {
            $table->bigIncrements('id_gasto');
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('id_categoria');
            $table->unsignedBigInteger('id_cuenta');
            $table->date('fecha');
            $table->string('descripcion')->nullable();
            $table->decimal('importe', 10, 2);
            $table->timestamps();
            $table->foreign('id')->references('id')->on('users');
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias');
            $table->foreign('id_cuenta')->references('id_cuenta')->on('cuentas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gastos');
        Schema::dropIfExists('objetivos_ahorro');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('cuentas');
        Schema::dropIfExists('users');
    }
}
