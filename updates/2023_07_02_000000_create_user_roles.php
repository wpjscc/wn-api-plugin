<?php

use Illuminate\Support\Facades\Schema;

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Wpjscc\Api\Models\UserRole;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique('role_unique');
            $table->string('code')->nullable()->index('role_code_index');
            $table->text('description')->nullable();
            $table->text('permissions')->nullable();
            $table->boolean('is_system')->default(0);
            $table->timestamps();
        });

        // This detects older builds and performs a migration to include
        // the new role system. This column will exist for new installs
        // so this heavy migration process does not need to execute.
        $this->migratePreviousBuild();
    }

    public function down()
    {
        Schema::dropIfExists('user_roles');
    }

    protected function migratePreviousBuild()
    {
        // Role not found in the users table, perform a complete migration.
        // Merging group permissions with the user and assigning the user
        // with the first available role.

        $this->createSystemUserRoles();

    }

    protected function createSystemUserRoles()
    {
        Db::table('user_roles')->insert([
            'name' => 'Publisher',
            'code' => UserRole::CODE_PUBLISHER,
            'description' => 'Site editor with access to publishing tools.',
        ]);

        Db::table('user_roles')->insert([
            'name' => 'Developer',
            'code' => UserRole::CODE_DEVELOPER,
            'description' => 'Site administrator with access to developer tools.',
        ]);
    }

};