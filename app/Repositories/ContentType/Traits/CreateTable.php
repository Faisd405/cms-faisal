<?php

namespace App\Repositories\ContentType\Traits;

use App\Models\ContentType\ContentType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait CreateTable
{
    private function createTable(ContentType $contentType, string $tableName, \Closure $callback)
    {
        Schema::create('content_value_' . $contentType->id . '_' . $tableName, $callback);
    }

    public function createPageTable(ContentType $contentType)
    {
        if (Schema::hasTable('content_value_' . $contentType->id . '_page')) {
            throw new \Exception('Table already exists');
        }

        $this->createTable($contentType, 'page_value', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->unsignedBigInteger('content_type_id');

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->foreign('content_type_id')->references('id')->on('content_types');
        });
    }

    public function addFieldToPageTable(ContentType $contentType, string $fieldName, string $fieldType, int $relationContentTypeId = null)
    {
        if (!Schema::hasTable('content_value_' . $contentType->id . '_page')) {
            throw new \Exception('Table does not exist');
        }

        Schema::table('content_value_' . $contentType->id . '_page', function (Blueprint $table) use ($fieldName, $fieldType, $relationContentTypeId) {
            if ($fieldType === 'text') {
                $table->text($fieldName)->nullable();
            } elseif ($fieldType === 'number') {
                $table->integer($fieldName)->nullable();
            } elseif ($fieldType === 'date') {
                $table->date($fieldName)->nullable();
            } elseif ($fieldType === 'datetime') {
                $table->dateTime($fieldName)->nullable();
            } elseif ($fieldType === 'boolean') {
                $table->boolean($fieldName)->nullable();
            } elseif ($fieldType === 'image') {
                $table->string($fieldName)->nullable();
            } elseif ($fieldType === 'file') {
                $table->string($fieldName)->nullable();
            } elseif ($fieldType === 'relation') { // WIP
                $table->unsignedBigInteger($fieldName)->nullable();
                $table->foreign($fieldName)->references('id')->on('content_value_' . $relationContentTypeId . '_page');
            }
        });
    }

    public function removeFieldFromPageTable(ContentType $contentType, string $fieldName)
    {
        if (!Schema::hasTable('content_value_' . $contentType->id . '_page')) {
            throw new \Exception('Table does not exist');
        }

        Schema::table('content_value_' . $contentType->id . '_page', function (Blueprint $table) use ($fieldName) {
            $table->dropColumn($fieldName);
        });
    }

    public function dropPageTable(ContentType $contentType)
    {
        if (!Schema::hasTable('content_value_' . $contentType->id . '_page')) {
            throw new \Exception('Table does not exist');
        }

        Schema::dropIfExists('content_value_' . $contentType->id . '_page');
    }
}
