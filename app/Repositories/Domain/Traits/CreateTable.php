<?php

namespace App\Repositories\Domain;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\Domain;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait CreateTable
{
    private function deleteTable(Domain $domain)
    {
        Schema::dropIfExists('domain_' . $domain->id . '_content_types');
        Schema::dropIfExists('domain_' . $domain->id . '_content_type_fields');
        Schema::dropIfExists('domain_' . $domain->id . '_languages');
        Schema::dropIfExists('domain_' . $domain->id . '_pages');
        Schema::dropIfExists('domain_' . $domain->id . '_page_contents');
        Schema::dropIfExists('domain_' . $domain->id . '_collection_sections');
        Schema::dropIfExists('domain_' . $domain->id . '_collection_categories');
        Schema::dropIfExists('domain_' . $domain->id . '_collection_posts');
        Schema::dropIfExists('domain_' . $domain->id . '_tags');
        Schema::dropIfExists('domain_' . $domain->id . '_tag_contents');
        Schema::dropIfExists('domain_' . $domain->id . '_seo_metas');
    }

    private function createTable(Domain $domain, string $tableName, \Closure $callback)
    {
        Schema::create('domain_' . $domain->id . '_' . $tableName, $callback);
    }

    private function createTableContentType(Domain $domain)
    {
        $this->createTable($domain, 'content_types', function (Blueprint $table) use ($domain) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->string('name');
            $table->string('description')->nullable();
            $table->string('type');

            $table->timestamps();

            // Add foreign key
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        $this->createTable($domain, 'content_type_fields', function (Blueprint $table) use ($domain) {
            $table->id();

            $table->unsignedBigInteger('content_type_id');
            $table->string('name');
            $table->string('label');
            $table->string('type');
            $table->text('options')->nullable();
            $table->text('validation')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('default_value')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_unique')->default(false);
            $table->boolean('is_searchable')->default(false);
            $table->boolean('is_localizable')->default(false);
            $table->timestamps();

            // Add foreign key
            $table->foreign('content_type_id')->references('id')->on('domain_' . $domain->id . '_content_types');
        });
    }

    private function createTableLanguage(Domain $domain)
    {
        $this->createTable($domain, 'languages', function (Blueprint $table) use ($domain) {
            $table->id();

            $table->string('iso_code')->unique();
            $table->string('name');
            $table->string('native_name');
            $table->boolean('is_rtl')->default(false);
            $table->boolean('is_default')->default(false);

            $table->timestamps();
        });
    }

    private function createTablePage(Domain $domain)
    {
        $this->createTable($domain, 'pages', function (Blueprint $table) use ($domain) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('content_type_id');

            $table->string('title');
            $table->string('slug');
            $table->string('template')->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            // Add foreign key
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->foreign('content_type_id')->references('id')->on('domain_' . $domain->id . '_content_types');
        });
    }

    private function createTableCollection(Domain $domain)
    {
        $this->createTable($domain, 'collection_sections', function (Blueprint $table) use ($domain) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->unsignedBigInteger('post_content_type_id');

            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();

            $table->timestamps();

            // Add foreign key
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->foreign('post_content_type_id')->references('id')->on('domain_' . $domain->id . '_content_types');
        });

        $this->createTable($domain, 'collection_categories', function (Blueprint $table) use ($domain) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->unsignedBigInteger('section_id')->nullable();

            $table->string('name');
            $table->string('slug');

            $table->timestamps();

            // Add foreign key
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->foreign('section_id')->references('id')->on('domain_' . $domain->id . '_collection_sections');
        });

        $this->createTable($domain, 'collection_posts', function (Blueprint $table) use ($domain) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();

            $table->string('title');
            $table->string('slug');
            $table->unsignedInteger('order')->default(0);

            $table->timestamps();

            // Add foreign key
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->foreign('section_id')->references('id')->on('domain_' . $domain->id . '_collection_sections');
            $table->foreign('category_id')->references('id')->on('domain_' . $domain->id . '_collection_categories');
        });
    }

    private function createTableTagContent(Domain $domain)
    {
        $this->createTable($domain, 'tags', function (Blueprint $table) use ($domain) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->timestamps();
        });

        $this->createTable($domain, 'tag_contents', function (Blueprint $table) use ($domain) {
            $table->id();

            $table->unsignedBigInteger('tag_id');
            $table->morphs('taggable');

            $table->timestamps();
        });
    }

    private function createTableSeoMeta(Domain $domain)
    {
        $this->createTable($domain, 'seo_metas', function (Blueprint $table) use ($domain) {
            $table->id();

            $table->morphs('seoable');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots')->nullable();
        });
    }
}
