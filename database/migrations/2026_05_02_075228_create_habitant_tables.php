<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habitant_themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('habitant_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id')->constrained('habitant_themes')->cascadeOnDelete();

            /*
             * Types:
             * background
             * avatar
             * food
             * toy
             * decoration
             */
            $table->string('type', 50);

            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();

            /*
             * For normal assets, image_path is the main image.
             * For avatar, image_path is idle image.
             */
            $table->string('image_path')->nullable();

            /*
             * Only used for avatar type.
             */
            $table->string('walking_image_path')->nullable();
            $table->string('eating_image_path')->nullable();
            $table->string('sad_image_path')->nullable();

            $table->unsignedInteger('price_coins')->default(0);

            /*
             * Default layout values.
             * x and y are percentage based.
             */
            $table->decimal('default_x', 6, 2)->default(50);
            $table->decimal('default_y', 6, 2)->default(70);
            $table->decimal('default_scale', 6, 2)->default(1);
            $table->decimal('default_rotation', 6, 2)->default(0);
            $table->string('default_direction', 20)->default('right');
            $table->integer('default_z_index')->default(10);

            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['theme_id', 'slug']);
            $table->index(['theme_id', 'type', 'is_active']);
        });

        Schema::create('child_habitats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('theme_id')->constrained('habitant_themes')->cascadeOnDelete();

            $table->foreignId('active_background_asset_id')
                ->nullable()
                ->constrained('habitant_assets')
                ->nullOnDelete();

            $table->foreignId('active_avatar_asset_id')
                ->nullable()
                ->constrained('habitant_assets')
                ->nullOnDelete();

            $table->unsignedTinyInteger('hunger')->default(40);
            $table->unsignedTinyInteger('happiness')->default(70);

            $table->timestamp('last_fed_at')->nullable();
            $table->timestamp('last_played_at')->nullable();
            $table->timestamp('guide_completed_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'theme_id']);
        });

        Schema::create('child_habitat_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('theme_id')->constrained('habitant_themes')->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained('habitant_assets')->cascadeOnDelete();
            $table->unsignedInteger('price_paid')->default(0);
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'asset_id']);
        });

        Schema::create('child_habitat_layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_habitat_id')->constrained('child_habitats')->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained('habitant_assets')->cascadeOnDelete();

            $table->decimal('x', 6, 2)->default(50);
            $table->decimal('y', 6, 2)->default(70);
            $table->decimal('scale', 6, 2)->default(1);
            $table->decimal('rotation', 6, 2)->default(0);
            $table->string('direction', 20)->default('right');
            $table->integer('z_index')->default(10);
            $table->boolean('is_visible')->default(true);

            $table->timestamps();

            $table->unique(['child_habitat_id', 'asset_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_habitat_layouts');
        Schema::dropIfExists('child_habitat_purchases');
        Schema::dropIfExists('child_habitats');
        Schema::dropIfExists('habitant_assets');
        Schema::dropIfExists('habitant_themes');
    }
};