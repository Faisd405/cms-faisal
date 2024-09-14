<?php

namespace Database\Seeders;

use App\Enums\ContentType as EnumsContentType;
use Illuminate\Database\Seeder;
use App\Models\ContentType\{
    ContentType,
    ContentTypeField
};
use App\Models\Page\{
    Page,
    PageContent
};
use App\Models\Collection\{
    CollectionSection,
    CollectionPost,
    CollectionPostContent
};
use App\Models\Datamaster\Language;

class DumpSeeder extends Seeder
{
    public function run()
    {
        // Language
        $languages = [
            [
                'name' => 'English',
                'iso_code' => 'en',
                'native_name' => 'English',
                'is_rtl' => false,
                'is_default' => true,
            ],
            [
                'name' => 'Indonesian',
                'iso_code' => 'id',
                'native_name' => 'Bahasa Indonesia',
                'is_rtl' => false,
                'is_default' => false,
            ],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }

        // Content Types
        $contentTypes = [
            [
                'name' => 'Page',
                'type' => EnumsContentType::PAGE,
            ],
            [
                'name' => 'Collection',
                'type' => EnumsContentType::COLLECTION,
            ],
        ];

        foreach ($contentTypes as $contentType) {
            ContentType::create($contentType);
        }

        // Content Type Fields
        $contentTypeFields = [
            [
                'content_type_id' => 1,
                'name' => 'Title',
                'label' => 'Title',
                'type' => 'text',
                'order' => 1,
            ],
            [
                'content_type_id' => 1,
                'name' => 'Content',
                'label' => 'Content',
                'type' => 'textarea',
                'order' => 2,
            ],
            [
                'content_type_id' => 2,
                'name' => 'Title',
                'label' => 'Title',
                'type' => 'text',
                'order' => 1,
            ],
            [
                'content_type_id' => 2,
                'name' => 'Description',
                'label' => 'Description',
                'type' => 'textarea',
                'order' => 2,
            ],
        ];

        foreach ($contentTypeFields as $contentTypeField) {
            ContentTypeField::create($contentTypeField);
        }

        // Pages
        $pages = [
            [
                'title' => 'Home',
                'content_type_id' => 1,
                'slug' => 'home',
            ],
            [
                'title' => 'About',
                'content_type_id' => 1,
                'slug' => 'about',
            ],
        ];

        foreach ($pages as $page) {
            $page = Page::create($page);
        }

        // Collections
        $collections = [
            [
                'title' => 'News',
                'slug' => 'news',
                'description' => 'Latest news',
                'post_content_type_id' => 2,
            ],
            [
                'title' => 'Blog',
                'slug' => 'blog',
                'description' => 'Blog posts',
                'post_content_type_id' => 2,
            ],
        ];

        foreach ($collections as $collection) {
            $collection = CollectionSection::create($collection);

            CollectionPost::create([
                'section_id' => $collection->id,
                'title' => $collection->title,
                'slug' => $collection->slug,
            ]);
        }

        // Page Content
        $pageContents = [
            [
                'page_id' => 1,
                'content_type_field_id' => 1,
                'value' => 'Waduh',
                'localization_id' => 1,
            ],
            [
                'page_id' => 2,
                'content_type_field_id' => 2,
                'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus',
                'localization_id' => 1,
            ],
        ];

        foreach ($pageContents as $pageContent) {
            PageContent::create([
                'page_id' => $pageContent['page_id'],
                'content_type_field_id' => $pageContent['content_type_field_id'],
                'value' => $pageContent['value'],
                'localization_id' => $pageContent['localization_id'],
            ]);
        }

        // Collection Post Content
        $collectionPostContents = [
            [
                'post_id' => 1,
                'content_type_field_id' => 3,
                'value' => 'Judul',
                'localization_id' => 1,
            ],
            [
                'post_id' => 1,
                'content_type_field_id' => 4,
                'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus',
                'localization_id' => 1,
            ],
        ];

        foreach ($collectionPostContents as $collectionPostContent) {
            CollectionPostContent::create([
                'post_id' => $collectionPostContent['post_id'],
                'content_type_field_id' => $collectionPostContent['content_type_field_id'],
                'value' => $collectionPostContent['value'],
                'localization_id' => $collectionPostContent['localization_id'],
            ]);
        }
    }
}
