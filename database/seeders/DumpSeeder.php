<?php

namespace Database\Seeders;

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

class DumpSeeder extends Seeder
{
    public function run()
    {
        // Content Types
        $contentTypes = [
            [
                'name' => 'Page',
                'description' => 'Page content type',
                'type' => Page::class,
            ],
            [
                'name' => 'Collection',
                'description' => 'Collection content type',
                'type' => CollectionSection::class,
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
            ],
            [
                'title' => 'About',
            ],
        ];

        foreach ($pages as $page) {
            $page = Page::create($page);
        }

        // Collections
        $collections = [
            [
                'title' => 'News',
                'description' => 'Latest news',
            ],
            [
                'title' => 'Blog',
                'description' => 'Blog posts',
            ],
        ];

        foreach ($collections as $collection) {
            $collection = CollectionSection::create($collection);

            CollectionPost::create([
                'collection_section_id' => $collection->id,
                'title' => $collection->title,
                'description' => $collection->description,
            ]);
        }

        // Page Content
        $pageContents = [
            [
                'page_id' => 1,
                'content_type_id' => 1,
                'content' => [
                    'Title' => 'Home',
                    'Content' => 'Welcome to our website!',
                ],
            ],
            [
                'page_id' => 2,
                'content_type_id' => 1,
                'content' => [
                    'Title' => 'About',
                    'Content' => 'About us',
                ],
            ],
        ];

        foreach ($pageContents as $pageContent) {
            PageContent::create([
                'page_id' => $pageContent['page_id'],
                'content_type_id' => $pageContent['content_type_id'],
                'content' => $pageContent['content'],
            ]);
        }

        // Collection Post Content
        $collectionPostContents = [
            [
                'collection_post_id' => 1,
                'content_type_id' => 2,
                'content' => [
                    'Title' => 'News',
                    'Description' => 'Latest news',
                ],
            ],
            [
                'collection_post_id' => 2,
                'content_type_id' => 2,
                'content' => [
                    'Title' => 'Blog',
                    'Description' => 'Blog posts',
                ],
            ],
        ];

        foreach ($collectionPostContents as $collectionPostContent) {
            CollectionPostContent::create([
                'collection_post_id' => $collectionPostContent['collection_post_id'],
                'content_type_id' => $collectionPostContent['content_type_id'],
                'content' => $collectionPostContent['content'],
            ]);
        }
    }
}
